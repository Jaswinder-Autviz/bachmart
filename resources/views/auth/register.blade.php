<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - BachatMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f7f8fa; }
        .auth-card { background: #fff; border-radius: 18px; box-shadow: 0 8px 40px rgba(0,0,0,0.1); padding: 2.5rem; width: 100%; max-width: 440px; }
        .brand-text { font-size: 1.8rem; font-weight: 800; color: #FF6B35; }
        .brand-text span { color: #2D3748; }
        .btn-primary-bm { background: #FF6B35; color: #fff; border: none; border-radius: 10px; font-weight: 600; padding: 0.7rem; }
        .btn-primary-bm:hover { background: #E85C26; color: #fff; }
        .form-control { border-radius: 10px; padding: 0.65rem 1rem; border: 1.5px solid #e2e8f0; }
        .form-control:focus { border-color: #FF6B35; box-shadow: 0 0 0 3px rgba(255,107,53,.12); }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center min-vh-100 py-4">
    <div class="auth-card">
        <div class="text-center mb-4">
            <a href="{{ route('home') }}" class="text-decoration-none">
                <div class="brand-text">Bachat<span>Mart</span></div>
            </a>
            <p class="text-muted mt-1" style="font-size:.9rem">Create a free customer account</p>
        </div>

        @if($errors->any())
        <div class="alert alert-danger py-2 px-3" style="font-size:.875rem;border-radius:10px">
            <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold small">Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="Your full name"
                       value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold small">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="you@example.com"
                       value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold small">Phone <span class="text-muted">(optional)</span></label>
                <input type="tel" name="phone" class="form-control" placeholder="+91 98765 43210"
                       value="{{ old('phone') }}">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold small">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Min 8 characters" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold small">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
            </div>
            <button type="submit" class="btn btn-primary-bm w-100">Create Account</button>
        </form>

        <div class="text-center mt-3">
            <p class="small">Already have an account? <a href="{{ route('login') }}" style="color:#FF6B35;font-weight:600">Sign In</a></p>
            <p class="small mb-0">Want to sell products? <a href="{{ route('register.seller') }}" style="color:#FF6B35;font-weight:600">Register as Seller</a></p>
        </div>
    </div>
</div>
</body>
</html>
