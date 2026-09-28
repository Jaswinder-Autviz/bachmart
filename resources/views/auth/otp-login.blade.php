<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In with Mobile OTP - BachatMart</title>
    
    {{-- Bootstrap 5 & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    {{-- Google Fonts - Montserrat --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,600;1,700&display=swap" rel="stylesheet">

    <style>
        body { 
            font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
            background: radial-gradient(100% 100% at 50% 0%, #FFF5F0 0%, #F8FAFC 100%); 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            color: #0F172A;
        }
        .auth-card { 
            background: #FFFFFF; 
            border-radius: 20px; 
            box-shadow: 0 10px 40px rgba(15, 23, 42, 0.08); 
            border: 1px solid #E2E8F0;
            padding: 2.75rem 2.25rem; 
            width: 100%; 
            max-width: 440px; 
        }
        .brand-text { 
            font-size: 2rem; 
            font-weight: 900; 
            color: #FF5722; 
            letter-spacing: -0.04em;
        }
        .brand-text span { color: #0F172A; }
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
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 0.02em;
        }
        .form-control:focus { 
            border-color: #FF5722; 
            box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.12); 
        }
        .input-group-text {
            border-radius: 12px 0 0 12px;
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            border-right: none;
            font-weight: 700;
            color: #475569;
            font-size: 0.92rem;
        }
        .input-group .form-control {
            border-radius: 0 12px 12px 0;
            border-left: none;
        }
        .badge-demo {
            background: #FFF5F0;
            color: #FF5722;
            border: 1px solid #FFD3C4;
            font-weight: 700;
            font-size: 0.75rem;
            border-radius: 6px;
            padding: 4px 10px;
        }
        .divider { display: flex; align-items: center; gap: .75rem; color: #94A3B8; font-size: .85rem; font-weight: 600; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #E2E8F0; }
        .demo-chip {
            background: #FFFFFF;
            border: 1px dashed #CBD5E1;
            border-radius: 8px;
            padding: 6px 10px;
            cursor: pointer;
            transition: all 0.15s;
        }
        .demo-chip:hover {
            border-color: #FF5722;
            background: #FFF5F0;
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
            <p class="text-muted mt-1 mb-0" style="font-size: 0.88rem; font-weight: 500;">Passwordless sign in via secure OTP</p>
        </div>

        {{-- Switcher Tabs --}}
        <div class="auth-tab-group">
            <a href="{{ route('login') }}" class="auth-tab">
                <i class="bi bi-key me-1"></i> Password
            </a>
            <a href="{{ route('login.otp') }}" class="auth-tab active">
                <i class="bi bi-phone me-1"></i> Mobile OTP
            </a>
        </div>

        @if($isMock)
        <div class="d-flex align-items-center justify-content-between mb-3 p-2 rounded-3 bg-light border">
            <span class="badge-demo"><i class="bi bi-gear-fill me-1"></i>MSG91 Test Mode</span>
            <small class="text-muted fw-600">Test OTP: <strong class="text-dark">1234</strong></small>
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success py-2 px-3 rounded-3 mb-3 small fw-600">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger py-2 px-3 rounded-3 mb-3" style="font-size: 0.88rem;">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('login.otp.send') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-700 small text-dark">Mobile Number</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-telephone-fill text-muted me-1"></i> +91</span>
                    <input type="tel" name="phone" id="phoneInput"
                           class="form-control @error('phone') is-invalid @enderror"
                           placeholder="9876543210"
                           value="{{ old('phone') }}"
                           maxlength="15"
                           autofocus required>
                </div>
                <div class="form-text text-muted small mt-1">We will send a 4-digit verification code via MSG91 SMS.</div>
            </div>

            <button type="submit" class="btn btn-primary-bm w-100 mt-2">
                <i class="bi bi-send-fill me-2"></i>Send Verification Code
            </button>
        </form>

        <div class="divider my-4">OR</div>

        <div class="text-center">
            <p class="small mb-2 fw-500">Prefer password? <a href="{{ route('login') }}" style="color: #FF5722; font-weight: 700;">Sign In with Password</a></p>
            <p class="small mb-0 fw-500">Don't have an account? <a href="{{ route('register') }}" style="color: #FF5722; font-weight: 700;">Create Free Account</a></p>
        </div>

        {{-- Quick Demo Numbers --}}
        <div class="mt-4 p-3 rounded-4" style="background: #F8FAFC; border: 1px solid #E2E8F0; font-size: 0.8rem;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-700 text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">Click Demo Mobile to Fill</div>
                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.65rem;">Tap to test</span>
            </div>
            <div class="row g-2">
                <div class="col-6">
                    <div class="demo-chip" onclick="fillNumber('9811111111')">
                        <div class="fw-800 text-primary-bm" style="font-size: 0.72rem;">SELLER (Rajesh)</div>
                        <div class="text-dark fw-700" style="font-size: 0.78rem;">98111 11111</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="demo-chip" onclick="fillNumber('9800000000')">
                        <div class="fw-800 text-primary" style="font-size: 0.72rem;">ADMIN (BachatMart)</div>
                        <div class="text-dark fw-700" style="font-size: 0.78rem;">98000 00000</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="demo-chip" onclick="fillNumber('9000000001')">
                        <div class="fw-800 text-success" style="font-size: 0.72rem;">CUSTOMER (Arun Kumar)</div>
                        <div class="text-dark fw-700" style="font-size: 0.78rem;">90000 00001</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fillNumber(number) {
    const input = document.getElementById('phoneInput');
    input.value = number;
    input.focus();
}
</script>
</body>
</html>
