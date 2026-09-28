<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\Msg91Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->isBlocked()) {
            Auth::logout();
            return back()->withErrors(['email' => 'Your account has been blocked. Please contact support.']);
        }

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($user->isSeller()) {
            return redirect()->intended(route('seller.dashboard'));
        }

        return redirect()->intended(route('home'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request, Msg91Service $msg91)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'min:10', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'phone.required' => 'Mobile number is required for OTP verification.',
            'phone.min' => 'Please enter a valid 10-digit mobile number.',
        ]);

        if (User::findByPhone($validated['phone'])) {
            return back()->withInput()->withErrors([
                'phone' => 'This mobile number is already registered. Please sign in or use another number.',
            ]);
        }

        // Send OTP via MSG91
        $result = $msg91->sendOtp($validated['phone']);
        if (!$result['success']) {
            return back()->withInput()->withErrors([
                'phone' => $result['message'],
            ]);
        }

        // Store pending registration data in session
        session([
            'pending_registration' => [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'customer',
            ],
            'reg_otp_phone' => $validated['phone'],
            'reg_otp_mask' => $msg91->maskMobile($validated['phone']),
            'reg_otp_is_mock' => $result['mock'] ?? false,
            'reg_otp_test_code' => $result['test_otp'] ?? null,
            'reg_return_route' => 'register',
        ]);

        return redirect()->route('register.otp.verify.show')->with('success', $result['message']);
    }

    public function showSellerRegister()
    {
        return view('auth.register-seller');
    }

    public function registerSeller(Request $request, Msg91Service $msg91)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'min:10', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'phone.required' => 'Mobile number is required for shopkeeper verification.',
            'phone.min' => 'Please enter a valid 10-digit mobile number.',
        ]);

        if (User::findByPhone($validated['phone'])) {
            return back()->withInput()->withErrors([
                'phone' => 'This mobile number is already registered. Please sign in or use another number.',
            ]);
        }

        // Send OTP via MSG91
        $result = $msg91->sendOtp($validated['phone']);
        if (!$result['success']) {
            return back()->withInput()->withErrors([
                'phone' => $result['message'],
            ]);
        }

        // Store pending registration data in session
        session([
            'pending_registration' => [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'seller',
            ],
            'reg_otp_phone' => $validated['phone'],
            'reg_otp_mask' => $msg91->maskMobile($validated['phone']),
            'reg_otp_is_mock' => $result['mock'] ?? false,
            'reg_otp_test_code' => $result['test_otp'] ?? null,
            'reg_return_route' => 'register.seller',
        ]);

        return redirect()->route('register.otp.verify.show')->with('success', $result['message']);
    }

    /**
     * Show registration OTP verification screen.
     */
    public function showRegisterVerifyOtp(Msg91Service $msg91)
    {
        if (!session()->has('pending_registration') || !session()->has('reg_otp_phone')) {
            return redirect()->route('register')->withErrors([
                'phone' => 'Registration session expired. Please fill out the registration form again.',
            ]);
        }

        $pending = session('pending_registration');

        return view('auth.register-otp-verify', [
            'role' => $pending['role'] ?? 'customer',
            'phone' => session('reg_otp_phone'),
            'maskedPhone' => session('reg_otp_mask', $msg91->maskMobile(session('reg_otp_phone'))),
            'isMock' => session('reg_otp_is_mock', $msg91->isMockMode()),
            'testOtp' => session('reg_otp_test_code', config('services.msg91.test_otp', '1234')),
            'otpLength' => (int) config('services.msg91.otp_length', 4),
            'returnRoute' => session('reg_return_route', 'register'),
        ]);
    }

    /**
     * Verify OTP and complete user account creation.
     */
    public function verifyRegisterOtp(Request $request, Msg91Service $msg91)
    {
        $request->validate([
            'otp' => ['required', 'string', 'min:4', 'max:6'],
        ], [
            'otp.required' => 'Please enter the verification code sent to your mobile number.',
        ]);

        if (!session()->has('pending_registration') || !session()->has('reg_otp_phone')) {
            return redirect()->route('register')->withErrors([
                'phone' => 'Registration session expired. Please fill out the registration form again.',
            ]);
        }

        $pending = session('pending_registration');
        $phone = session('reg_otp_phone');

        // Verify with MSG91
        $result = $msg91->verifyOtp($phone, $request->input('otp'));
        if (!$result['success']) {
            return back()->withErrors(['otp' => $result['message']]);
        }

        // Safety check: ensure email or phone wasn't registered in the interim
        if (User::where('email', $pending['email'])->exists()) {
            return redirect()->route($pending['role'] === 'seller' ? 'register.seller' : 'register')->withErrors([
                'email' => 'This email address is already registered. Please sign in.',
            ]);
        }
        if (User::findByPhone($pending['phone'])) {
            return redirect()->route($pending['role'] === 'seller' ? 'register.seller' : 'register')->withErrors([
                'phone' => 'This mobile number is already registered. Please sign in.',
            ]);
        }

        // Create the user with verified status
        $user = User::create([
            'name' => $pending['name'],
            'email' => $pending['email'],
            'phone' => $pending['phone'],
            'password' => $pending['password'],
            'role' => $pending['role'],
            'status' => 'active',
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
        ]);

        // Clean up registration session
        session()->forget([
            'pending_registration',
            'reg_otp_phone',
            'reg_otp_mask',
            'reg_otp_is_mock',
            'reg_otp_test_code',
            'reg_return_route',
        ]);

        Auth::login($user);

        if ($user->isSeller()) {
            return redirect()->route('seller.shop.create')
                ->with('success', 'Mobile number verified and seller account created! Now set up your shop profile.');
        }

        return redirect()->route('home')->with('success', 'Welcome to BachatMart! Your account and phone number are verified.');
    }

    /**
     * Resend registration OTP.
     */
    public function resendRegisterOtp(Request $request, Msg91Service $msg91)
    {
        $phone = session('reg_otp_phone');
        if (!$phone) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Session expired.'], 400);
            }
            return redirect()->route('register')->withErrors(['phone' => 'Session expired. Please register again.']);
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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
