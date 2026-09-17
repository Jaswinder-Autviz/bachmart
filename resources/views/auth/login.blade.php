<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BachatMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f7f8fa; min-height: 100vh; display: flex; align-items: center; }
        .auth-card { background: #fff; border-radius: 18px; box-shadow: 0 8px 40px rgba(0,0,0,0.1); padding: 2.5rem; width: 100%; max-width: 420px; }
        .brand-text { font-size: 1.8rem; font-weight: 800; color: #FF6B35; }
        .brand-text span { color: #2D3748; }
        .btn-primary-bm { background: #FF6B35; color: #fff; border: none; border-radius: 10px; font-weight: 600; padding: 0.7rem; transition: all .2s; }
        .btn-primary-bm:hover { background: #E85C26; color: #fff; }
        .form-control { border-radius: 10px; padding: 0.65rem 1rem; border: 1.5px solid #e2e8f0; }
        .form-control:focus { border-color: #FF6B35; box-shadow: 0 0 0 3px rgba(255,107,53,.12); }
        .divider { display: flex; align-items: center; gap: .75rem; color: #aaa; font-size: .85rem; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="auth-card">
        <div class="text-center mb-4">
            <a href="{{ route('home') }}" class="text-decoration-none">
                <div class="brand-text">Bachat<span>Mart</span></div>
            </a>
            <p class="text-muted mt-1 mb-0" style="font-size:.9rem">Welcome back! Please sign in.</p>
        </div>

        @if($errors->any())
        <div class="alert alert-danger py-2 px-3" style="font-size:.875rem;border-radius:10px">
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-600 small">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       placeholder="you@example.com" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label fw-600 small">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="passwordField"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Enter your password" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePwd()"
                            style="border-radius:0 10px 10px 0;border:1.5px solid #e2e8f0">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small" for="remember">Remember me</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary-bm w-100">Sign In</button>
        </form>

        <div class="divider my-4">or</div>

        <div class="text-center">
            <p class="small mb-2">New customer? <a href="{{ route('register') }}" style="color:#FF6B35;font-weight:600">Create Account</a></p>
            <p class="small mb-0">Want to sell? <a href="{{ route('register.seller') }}" style="color:#FF6B35;font-weight:600">Register Your Shop</a></p>
        </div>

        {{-- Demo Credentials --}}
        <div class="mt-4 p-3 rounded-3" style="background:#f7f8fa;font-size:.8rem">
            <div class="fw-600 mb-2 text-muted">Demo Credentials</div>
            <div class="row g-2">
                <div class="col-6">
                    <div class="p-2 bg-white rounded-2 border">
                        <div class="fw-600 text-primary" style="font-size:.75rem">ADMIN</div>
                        <div class="text-muted">admin@bachatmart.com</div>
                        <div class="text-muted">admin@123</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 bg-white rounded-2 border">
                        <div class="fw-600" style="color:#FF6B35;font-size:.75rem">SELLER</div>
                        <div class="text-muted">rajesh@example.com</div>
                        <div class="text-muted">seller@123</div>
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
