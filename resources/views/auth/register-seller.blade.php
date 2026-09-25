<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Registration - BachatMart</title>
    
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
            color: #0F172A;
        }
        .auth-card { 
            background: #FFFFFF; 
            border-radius: 20px; 
            box-shadow: 0 10px 40px rgba(15, 23, 42, 0.08); 
            border: 1px solid #E2E8F0;
            padding: 2.75rem 2.25rem; 
            width: 100%; 
            max-width: 480px; 
        }
        .brand-text { 
            font-size: 2.2rem; 
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
        .benefit-item { display: flex; align-items: center; gap: .75rem; padding: .45rem 0; font-size: .92rem; font-weight: 600; color: #334155; }
        .benefit-item i { color: #10B981; font-size: 1.2rem; }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center min-vh-100 py-5">
    <div class="row g-5 w-100 justify-content-center align-items-center" style="max-width: 960px;">

        {{-- Benefits panel --}}
        <div class="col-md-5 d-none d-md-block">
            <div class="pe-3">
                <div class="brand-text mb-3">Bachat<span>Mart</span></div>
                <h3 class="fw-900" style="color: #0F172A; line-height: 1.25; letter-spacing: -0.03em;">
                    Reach more local buyers.<br>Turn surplus into cash.
                </h3>
                <p class="text-muted mt-2" style="font-size: 0.95rem; line-height: 1.6;">Join hundreds of neighborhood store owners liquidating unsold inventory directly to customers.</p>
                
                <div class="mt-4">
                    <div class="benefit-item"><i class="bi bi-check-circle-fill"></i> Just ₹12 per product listing</div>
                    <div class="benefit-item"><i class="bi bi-check-circle-fill"></i> Direct WhatsApp chats & phone calls</div>
                    <div class="benefit-item"><i class="bi bi-check-circle-fill"></i> Boost in-store walk-in customers</div>
                    <div class="benefit-item"><i class="bi bi-check-circle-fill"></i> Real-time analytics & lead tracking</div>
                    <div class="benefit-item"><i class="bi bi-check-circle-fill"></i> 0% commission on your sales</div>
                </div>

                <div class="mt-4 p-3 rounded-4" style="background: #FFF5F0; border: 1px solid #FFD3C4;">
                    <div class="small fw-800 text-uppercase" style="color: #FF5722; font-size: 0.72rem; letter-spacing: 0.05em;">PAY-PER-LISTING FREEDOM</div>
                    <div class="small text-muted mt-1 fw-500">No monthly lock-ins. Pay only ₹12 when you want to list an item, with 2 free edits included.</div>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div class="col-md-7 col-12">
            <div class="auth-card">
                <h4 class="fw-900 mb-1" style="color: #0F172A; letter-spacing: -0.02em;">Create Seller Account</h4>
                <p class="text-muted mb-4" style="font-size: 0.88rem; font-weight: 500;">Register your shop and start listing inventory in minutes.</p>

                @if($errors->any())
                <div class="alert alert-danger py-2 px-3 mb-3 rounded-3" style="font-size: 0.88rem;">
                    <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
                @endif

                <form action="{{ route('register.seller.submit') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-700 small text-dark">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Shopkeeper / Owner Name"
                               value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-700 small text-dark">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="business@example.com"
                               value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-700 small text-dark">Mobile Number <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" class="form-control" placeholder="+91 98765 43210"
                               value="{{ old('phone') }}" required>
                        <div class="form-text small" style="font-size: 0.78rem;">Customers will call and WhatsApp you on this number.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-700 small text-dark">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimum 8 characters" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-700 small text-dark">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                    </div>
                    <button type="submit" class="btn btn-primary-bm w-100">
                        <i class="bi bi-shop me-2"></i>Create Seller Account
                    </button>
                </form>

                <div class="text-center mt-4">
                    <p class="small mb-0 fw-500">Already registered? <a href="{{ route('login') }}" style="color: #FF5722; font-weight: 700;">Sign In</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
