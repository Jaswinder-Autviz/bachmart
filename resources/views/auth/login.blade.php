<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - BachatMart</title>
    
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

        <div class="text-center">
            <p class="small mb-2 fw-500">New customer? <a href="{{ route('register') }}" style="color: #FF5722; font-weight: 700;">Create Free Account</a></p>
            <p class="small mb-0 fw-500">Want to sell surplus stock? <a href="{{ route('register.seller') }}" style="color: #FF5722; font-weight: 700;">Register Your Shop</a></p>
        </div>

        {{-- Demo Credentials --}}
        <div class="mt-4 p-3 rounded-4" style="background: #F8FAFC; border: 1px solid #E2E8F0; font-size: 0.8rem;">
            <div class="fw-700 mb-2 text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">Demo Logins</div>
            <div class="row g-2">
                <div class="col-6">
                    <div class="p-2 bg-white rounded-3 border">
                        <div class="fw-800 text-primary" style="font-size: 0.72rem;">ADMIN</div>
                        <div class="text-dark fw-600" style="font-size: 0.75rem;">admin@bachatmart.com</div>
                        <div class="text-muted" style="font-size: 0.72rem;">admin@123</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 bg-white rounded-3 border">
                        <div class="fw-800 text-primary-bm" style="font-size: 0.72rem;">SELLER</div>
                        <div class="text-dark fw-600" style="font-size: 0.75rem;">rajesh@example.com</div>
                        <div class="text-muted" style="font-size: 0.72rem;">seller@123</div>
                    </div>
                </div>
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
