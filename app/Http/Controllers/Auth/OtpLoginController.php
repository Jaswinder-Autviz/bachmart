<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Msg91Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpLoginController extends Controller
{
    /**
     * Show the mobile number entry form for OTP login.
     */
    public function showSendForm(Msg91Service $msg91)
    {
        return view('auth.otp-login', [
            'isMock' => $msg91->isMockMode(),
        ]);
    }

    /**
     * Validate phone, locate user, and dispatch OTP via MSG91.
     */
    public function sendOtp(Request $request, Msg91Service $msg91)
    {
        $request->validate([
            'phone' => ['required', 'string', 'min:10', 'max:20'],
        ], [
            'phone.required' => 'Please enter your mobile number.',
            'phone.min' => 'Please enter a valid 10-digit mobile number.',
        ]);

        $rawPhone = $request->input('phone');
        $user = User::findByPhone($rawPhone);

        if (!$user) {
            return back()->withInput()->withErrors([
                'phone' => 'No account found with mobile number ' . htmlspecialchars($rawPhone) . '. Please check the number or register for an account.',
            ]);
        }

        if ($user->isBlocked()) {
            return back()->withInput()->withErrors([
                'phone' => 'Your account has been blocked. Please contact support.',
            ]);
        }

        $result = $msg91->sendOtp($rawPhone);

        if (!$result['success']) {
            return back()->withInput()->withErrors([
                'phone' => $result['message'],
            ]);
        }

        // Store OTP login context in session
        session([
            'otp_login_phone' => $rawPhone,
            'otp_login_user_id' => $user->id,
            'otp_login_mask' => $msg91->maskMobile($rawPhone),
            'otp_login_is_mock' => $result['mock'] ?? false,
            'otp_login_test_code' => $result['test_otp'] ?? null,
        ]);

        return redirect()->route('login.otp.verify.show')->with('success', $result['message']);
    }

    /**
     * Show the OTP verification form.
     */
    public function showVerifyForm(Msg91Service $msg91)
    {
        if (!session()->has('otp_login_phone') || !session()->has('otp_login_user_id')) {
            return redirect()->route('login.otp')->withErrors([
                'phone' => 'Please enter your mobile number first.',
            ]);
        }

        return view('auth.otp-verify', [
            'phone' => session('otp_login_phone'),
            'maskedPhone' => session('otp_login_mask', $msg91->maskMobile(session('otp_login_phone'))),
            'isMock' => session('otp_login_is_mock', $msg91->isMockMode()),
            'testOtp' => session('otp_login_test_code', config('services.msg91.test_otp', '1234')),
            'otpLength' => (int) config('services.msg91.otp_length', 4),
        ]);
    }

    /**
     * Verify the entered OTP and authenticate the user.
     */
    public function verifyOtp(Request $request, Msg91Service $msg91)
    {
        $request->validate([
            'otp' => ['required', 'string', 'min:4', 'max:6'],
        ], [
            'otp.required' => 'Please enter the verification code sent to your phone.',
        ]);

        $phone = session('otp_login_phone');
        $userId = session('otp_login_user_id');

        if (!$phone || !$userId) {
            return redirect()->route('login.otp')->withErrors([
                'phone' => 'Your session has expired. Please enter your mobile number again.',
            ]);
        }

        $result = $msg91->verifyOtp($phone, $request->input('otp'));

        if (!$result['success']) {
            return back()->withErrors([
                'otp' => $result['message'],
            ]);
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'User account not found.',
            ]);
        }

        if ($user->isBlocked()) {
            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been blocked. Please contact support.',
            ]);
        }

        // Clean up OTP session data
        session()->forget([
            'otp_login_phone',
            'otp_login_user_id',
            'otp_login_mask',
            'otp_login_is_mock',
            'otp_login_test_code',
        ]);

        // Login user
        Auth::login($user, $request->boolean('remember', true));
        $request->session()->regenerate();

        // Redirect based on role
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($user->isSeller()) {
            return redirect()->intended(route('seller.dashboard'));
        }

        return redirect()->intended(route('home'));
    }

    /**
     * Resend the OTP to the mobile number in session.
     */
    public function resendOtp(Request $request, Msg91Service $msg91)
    {
        $phone = session('otp_login_phone');

        if (!$phone) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Session expired.'], 400);
            }
            return redirect()->route('login.otp')->withErrors(['phone' => 'Session expired. Please enter your number again.']);
        }

        $result = $msg91->resendOtp($phone);

        if ($request->expectsJson()) {
            return response()->json($result, $result['success'] ? 200 : 422);
        }

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->withErrors(['otp' => $result['message']]);
    }
}
