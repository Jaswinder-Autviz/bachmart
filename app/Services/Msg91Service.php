<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Msg91Service
{
    protected ?string $authKey;
    protected ?string $templateId;
    protected int $otpLength;
    protected int $otpExpiry;
    protected bool $mock;
    protected string $testOtp;

    public function __construct()
    {
        $this->authKey = config('services.msg91.auth_key');
        $this->templateId = config('services.msg91.template_id');
        $this->otpLength = (int) config('services.msg91.otp_length', 4);
        $this->otpExpiry = (int) config('services.msg91.otp_expiry', 5);
        $this->mock = (bool) config('services.msg91.mock', true);
        $this->testOtp = (string) config('services.msg91.test_otp', '1234');
    }

    /**
     * Determine if service is operating in mock / test mode.
     */
    public function isMockMode(): bool
    {
        return $this->mock || empty($this->authKey) || empty($this->templateId);
    }

    /**
     * Format mobile number to E.164 without plus sign (e.g., 919876543210 for India).
     */
    public function formatMobile(string $phone, string $defaultCountryCode = '91'): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone);

        // If standard 10 digit Indian number
        if (strlen($digits) === 10) {
            return $defaultCountryCode . $digits;
        }

        // If 11 digits starting with 0 (e.g., 09876543210)
        if (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            return $defaultCountryCode . substr($digits, 1);
        }

        return $digits;
    }

    /**
     * Mask mobile number for display (e.g. +91 ******1234).
     */
    public function maskMobile(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($digits) >= 10) {
            $last4 = substr($digits, -4);
            $prefix = strlen($digits) > 10 ? '+' . substr($digits, 0, strlen($digits) - 10) . ' ' : '+91 ';
            return $prefix . '******' . $last4;
        }
        return $phone;
    }

    /**
     * Send OTP to the specified phone number.
     *
     * @param string $phone
     * @param string|null $customOtp
     * @return array{success: bool, message: string, mock: bool, test_otp?: string}
     */
    public function sendOtp(string $phone, ?string $customOtp = null): array
    {
        $formattedMobile = $this->formatMobile($phone);

        // MOCK / TESTING MODE
        if ($this->isMockMode()) {
            $otp = $customOtp ?: $this->testOtp;

            session([
                'msg91_mock_otp' => $otp,
                'msg91_mock_mobile' => $formattedMobile,
                'msg91_otp_expires_at' => now()->addMinutes($this->otpExpiry)->timestamp,
                'msg91_last_sent_at' => now()->timestamp,
            ]);

            Log::info("MSG91 [MOCK MODE] OTP for {$formattedMobile}: {$otp}");

            return [
                'success' => true,
                'message' => 'OTP sent successfully (Demo Mode: use ' . $otp . ')',
                'mock' => true,
                'test_otp' => $otp,
            ];
        }

        // LIVE MSG91 API MODE
        try {
            $payload = [
                'template_id' => $this->templateId,
                'mobile' => $formattedMobile,
                'otp_length' => $this->otpLength,
                'otp_expiry' => $this->otpExpiry,
            ];

            if ($customOtp) {
                $payload['otp'] = $customOtp;
            }

            $response = Http::withHeaders([
                'authkey' => $this->authKey,
                'Content-Type' => 'application/json',
            ])->timeout(10)->post('https://control.msg91.com/api/v5/otp', $payload);

            $data = $response->json();

            if ($response->successful() && (!isset($data['type']) || strtolower($data['type']) !== 'error')) {
                session([
                    'msg91_mobile' => $formattedMobile,
                    'msg91_last_sent_at' => now()->timestamp,
                ]);

                return [
                    'success' => true,
                    'message' => $data['message'] ?? 'OTP sent successfully to your mobile number.',
                    'mock' => false,
                ];
            }

            $errorMessage = $data['message'] ?? 'Failed to send OTP via MSG91. Please check your configuration.';
            Log::error('MSG91 Send OTP Error', ['response' => $data, 'mobile' => $formattedMobile]);

            return [
                'success' => false,
                'message' => $errorMessage,
                'mock' => false,
            ];
        } catch (\Throwable $e) {
            Log::error('MSG91 Send OTP Exception: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return [
                'success' => false,
                'message' => 'Network error while contacting SMS gateway. Please try again later.',
                'mock' => false,
            ];
        }
    }

    /**
     * Verify the entered OTP for the phone number.
     *
     * @param string $phone
     * @param string $enteredOtp
     * @return array{success: bool, message: string}
     */
    public function verifyOtp(string $phone, string $enteredOtp): array
    {
        $formattedMobile = $this->formatMobile($phone);
        $cleanOtp = trim($enteredOtp);

        // MOCK / TESTING MODE
        if ($this->isMockMode()) {
            $savedOtp = session('msg91_mock_otp', $this->testOtp);
            $expiresAt = session('msg91_otp_expires_at');

            if ($expiresAt && now()->timestamp > $expiresAt) {
                return [
                    'success' => false,
                    'message' => 'OTP has expired. Please request a new one.',
                ];
            }

            // Accept configured test OTP or generated session mock OTP
            if ($cleanOtp === (string) $savedOtp || $cleanOtp === (string) $this->testOtp) {
                // Clear mock OTP session
                session()->forget(['msg91_mock_otp', 'msg91_mock_mobile', 'msg91_otp_expires_at']);

                return [
                    'success' => true,
                    'message' => 'OTP verified successfully.',
                ];
            }

            return [
                'success' => false,
                'message' => 'Invalid OTP. Please check the code and try again.',
            ];
        }

        // LIVE MSG91 API MODE
        try {
            $response = Http::withHeaders([
                'authkey' => $this->authKey,
            ])->timeout(10)->get('https://control.msg91.com/api/v5/otp/verify', [
                'otp' => $cleanOtp,
                'mobile' => $formattedMobile,
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['type']) && strtolower($data['type']) === 'success') {
                return [
                    'success' => true,
                    'message' => $data['message'] ?? 'OTP verified successfully.',
                ];
            }

            $msg = $data['message'] ?? 'Invalid OTP or OTP expired.';
            Log::warning('MSG91 Verify OTP Failed', ['response' => $data, 'mobile' => $formattedMobile]);

            return [
                'success' => false,
                'message' => $msg,
            ];
        } catch (\Throwable $e) {
            Log::error('MSG91 Verify OTP Exception: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Error verifying OTP with SMS gateway. Please try again.',
            ];
        }
    }

    /**
     * Resend / Retry OTP.
     *
     * @param string $phone
     * @param string $retryType 'text' or 'voice'
     * @return array{success: bool, message: string}
     */
    public function resendOtp(string $phone, string $retryType = 'text'): array
    {
        $formattedMobile = $this->formatMobile($phone);

        // Throttle check: allow resend only after 20 seconds
        $lastSent = session('msg91_last_sent_at', 0);
        $cooldown = 20;
        $now = now()->timestamp;
        if (($now - $lastSent) < $cooldown) {
            $remaining = $cooldown - ($now - $lastSent);
            return [
                'success' => false,
                'message' => "Please wait {$remaining} seconds before requesting a new OTP.",
            ];
        }

        // MOCK MODE
        if ($this->isMockMode()) {
            return $this->sendOtp($phone);
        }

        // LIVE MSG91 RETRY API
        try {
            $response = Http::withHeaders([
                'authkey' => $this->authKey,
            ])->timeout(10)->get('https://control.msg91.com/api/v5/otp/retry', [
                'authkey' => $this->authKey,
                'mobile' => $formattedMobile,
                'retrytype' => $retryType,
            ]);

            $data = $response->json();

            if ($response->successful() && (!isset($data['type']) || strtolower($data['type']) !== 'error')) {
                session(['msg91_last_sent_at' => now()->timestamp]);
                return [
                    'success' => true,
                    'message' => $data['message'] ?? 'OTP resent successfully.',
                ];
            }

            return [
                'success' => false,
                'message' => $data['message'] ?? 'Unable to resend OTP. Please try again later.',
            ];
        } catch (\Throwable $e) {
            Log::error('MSG91 Resend OTP Exception: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Network error while requesting OTP resend.',
            ];
        }
    }
}
