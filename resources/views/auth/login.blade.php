<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - BachatMart</title>
    
    {{-- Bootstrap 5 & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    {{-- Google Fonts - Nunito --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,600;1,700&display=swap" rel="stylesheet">

    <style>
        body { 
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
            background: radial-gradient(100% 100% at 50% 0%, #FFF5F0 0%, #F8FAFC 100%); 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            color: #0F172A;
            font-size: 13.5px;
        }
        .auth-card { 
            background: #FFFFFF; 
            border-radius: 18px; 
            box-shadow: 0 10px 35px rgba(15, 23, 42, 0.07); 
            border: 1px solid #E2E8F0;
            padding: 2.25rem 2rem; 
            width: 100%; 
            max-width: 420px; 
        }
        .brand-text { 
            font-size: 1.65rem; 
            font-weight: 800; 
            color: #FF5722; 
            letter-spacing: -0.03em;
        }
        .brand-text span { color: #0F172A; }
        .btn-primary-bm { 
            background: linear-gradient(135deg, #FF6B35 0%, #FF4500 100%); 
            color: #fff !important; 
            border: none; 
            border-radius: 9999px; 
            font-weight: 700; 
            padding: 0.75rem; 
            box-shadow: 0 4px 14px rgba(255, 87, 34, 0.3);
            transition: all .2s; 
        }
        .btn-primary-bm:hover { 
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(255, 87, 34, 0.4);
        }
        .form-control { 
            border-radius: 12px; 
            padding: 0.72rem 1.1rem; 
            border: 1.5px solid #E2E8F0; 
            font-size: 0.92rem;
            font-weight: 500;
        }
        .form-control:focus { 
            border-color: #FF5722; 
            box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.12); 
        }
        .divider { display: flex; align-items: center; gap: .75rem; color: #94A3B8; font-size: .85rem; font-weight: 600; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #E2E8F0; }
        .auth-tab-group {
            display: flex;
            background: #F1F5F9;
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 1.5rem;
        }
        .auth-tab {
            flex: 1;
            text-align: center;
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
            font-weight: 700;
            border-radius: 9px;
            text-decoration: none;
            color: #64748B;
            transition: all 0.2s;
        }
        .auth-tab.active {
            background: #FFFFFF;
            color: #FF5722;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
        }
        .auth-tab:hover:not(.active) {
            color: #0F172A;
        }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center min-vh-100 py-4">
    <div class="auth-card">
        <div class="text-center mb-4">
            <a href="{{ route('home') }}" class="text-decoration-none">
                <div class="brand-text">Bachat<span>Mart</span></div>
            </a>
            <p class="text-muted mt-1 mb-0" style="font-size: 0.88rem; font-weight: 500;">Welcome back! Please sign in to your account.</p>
        </div>

        {{-- Switcher Tabs --}}
        <div class="auth-tab-group">
            <a href="{{ route('login') }}" class="auth-tab active">
                <i class="bi bi-key me-1"></i> Password
            </a>
            <a href="{{ route('login.otp') }}" class="auth-tab">
                <i class="bi bi-phone me-1"></i> Mobile OTP
            </a>
        </div>

        @if($errors->any())
        <div class="alert alert-danger py-2 px-3 rounded-3 mb-3" style="font-size: 0.88rem;">
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-700 small text-dark">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       placeholder="you@example.com" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label fw-700 small text-dark">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="passwordField"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Enter your password" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePwd()"
                             style="border-radius: 0 12px 12px 0; border: 1.5px solid #E2E8F0; border-left: none;">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small fw-600 text-muted" for="remember">Remember me</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary-bm w-100">Sign In</button>
        </form>

        <div class="divider my-4">OR</div>

        <a href="{{ route('login.otp') }}" class="btn btn-outline-secondary w-100 mb-3 fw-700 rounded-pill py-2" style="font-size: 0.88rem; border-color: #E2E8F0; color: #334155;">
            <i class="bi bi-phone text-warning me-2"></i>Sign In with Mobile OTP (MSG91)
        </a>

        <div class="text-center">
            <p class="small mb-2 fw-500">New customer? <a href="{{ route('register') }}" style="color: #FF5722; font-weight: 700;">Create Free Account</a></p>
            <p class="small mb-0 fw-500">Want to sell surplus stock? <a href="{{ route('register.seller') }}" style="color: #FF5722; font-weight: 700;">Register Your Shop</a></p>
        </div>

        {{-- Demo Credentials --}}
        <div class="mt-4 p-3 rounded-4" style="background: #F8FAFC; border: 1px solid #E2E8F0; font-size: 0.8rem;">
            <div class="fw-700 mb-2 text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">Demo Logins (Password or OTP)</div>
            <div class="row g-2">
                <div class="col-6">
                    <div class="p-2 bg-white rounded-3 border">
                        <div class="fw-800 text-primary" style="font-size: 0.72rem;">ADMIN</div>
                        <div class="text-dark fw-600 text-truncate" style="font-size: 0.73rem;">admin@bachatmart.com</div>
                        <div class="text-muted" style="font-size: 0.7rem;"><i class="bi bi-phone"></i> 9800000000</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 bg-white rounded-3 border">
                        <div class="fw-800 text-primary-bm" style="font-size: 0.72rem;">SELLER</div>
                        <div class="text-dark fw-600 text-truncate" style="font-size: 0.73rem;">rajesh@example.com</div>
                        <div class="text-muted" style="font-size: 0.7rem;"><i class="bi bi-phone"></i> 9811111111</div>
                    </div>
                </div>
            </div>
            <div class="mt-2 text-center text-muted" style="font-size: 0.72rem;">
                <i class="bi bi-info-circle me-1"></i> Demo OTP: <strong>1234</strong> | Password: <strong>seller@123</strong>
            </div>
        </div>
    </div>
</div>
<script>
function togglePwd() {
    const f = document.getElementById('passwordField');
    const i = document.getElementById('eyeIcon');
    if (f.type === 'password') { f.type = 'text'; i.className = 'bi bi-eye-slash'; }
    else { f.type = 'password'; i.className = 'bi bi-eye'; }
}
</script>
</body>
</html>
