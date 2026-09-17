<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Registration - BachatMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg,#fff5f0,#fff); }
        .auth-card { background: #fff; border-radius: 18px; box-shadow: 0 8px 40px rgba(0,0,0,0.1); padding: 2.5rem; width: 100%; max-width: 460px; }
        .brand-text { font-size: 1.8rem; font-weight: 800; color: #FF6B35; }
        .brand-text span { color: #2D3748; }
        .btn-primary-bm { background: #FF6B35; color: #fff; border: none; border-radius: 10px; font-weight: 600; padding: 0.7rem; }
        .btn-primary-bm:hover { background: #E85C26; color: #fff; }
        .form-control { border-radius: 10px; padding: 0.65rem 1rem; border: 1.5px solid #e2e8f0; }
        .form-control:focus { border-color: #FF6B35; box-shadow: 0 0 0 3px rgba(255,107,53,.12); }
        .benefit-item { display: flex; align-items: center; gap: .6rem; padding: .5rem 0; font-size: .9rem; }
        .benefit-item i { color: #48BB78; font-size: 1.1rem; }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center min-vh-100 py-4">
    <div class="row g-4 w-100 justify-content-center align-items-center" style="max-width:900px">

        {{-- Benefits panel --}}
        <div class="col-md-5 d-none d-md-block">
            <div class="ps-2">
                <div class="brand-text mb-3">Bachat<span>Mart</span></div>
                <h4 class="fw-800" style="color:#2D3748">Reach more customers.<br>Sell your stock faster.</h4>
                <p class="text-muted mt-2" style="font-size:.9rem">Join thousands of local shopkeepers who list their deals on BachatMart.</p>
                <div class="mt-4">
                    <div class="benefit-item"><i class="bi bi-check-circle-fill"></i> List products for free</div>
                    <div class="benefit-item"><i class="bi bi-check-circle-fill"></i> Get discovered by local customers</div>
                    <div class="benefit-item"><i class="bi bi-check-circle-fill"></i> Customers call & WhatsApp directly</div>
                    <div class="benefit-item"><i class="bi bi-check-circle-fill"></i> Real-time leads & analytics</div>
                    <div class="benefit-item"><i class="bi bi-check-circle-fill"></i> No commission on sales</div>
                </div>
                <div class="mt-4 p-3 rounded-3" style="background:#fff5f0">
                    <div class="small fw-600" style="color:#FF6B35">FREE PLAN INCLUDES</div>
                    <div class="small text-muted mt-1">5 active product listings • Basic shop profile • Customer lead tracking</div>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div class="col-md-7 col-12">
            <div class="auth-card">
                <h5 class="fw-800 mb-1" style="color:#2D3748">Create Seller Account</h5>
                <p class="text-muted mb-4" style="font-size:.85rem">Sign up and start listing your products today.</p>

                @if($errors->any())
                <div class="alert alert-danger py-2 px-3 mb-3" style="font-size:.875rem;border-radius:10px">
                    <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
                @endif

                <form action="{{ route('register.seller.submit') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Your Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="As per business documents"
                               value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="business@example.com"
                               value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Mobile Number <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" class="form-control" placeholder="+91 98765 43210"
                               value="{{ old('phone') }}" required>
                        <div class="form-text">Customers may contact you on this number.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimum 8 characters" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="agree" required>
                        <label class="form-check-label small" for="agree">
                            I agree to the <a href="#" style="color:#FF6B35">Terms & Conditions</a>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary-bm w-100">
                        <i class="bi bi-shop me-2"></i>Create Seller Account
                    </button>
                </form>

                <div class="text-center mt-3">
                    <p class="small">Already have an account? <a href="{{ route('login') }}" style="color:#FF6B35;font-weight:600">Sign In</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
