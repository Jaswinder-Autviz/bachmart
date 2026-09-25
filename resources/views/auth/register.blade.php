<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration - BachatMart</title>
    
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
            max-width: 460px; 
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
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center min-vh-100 py-4">
    <div class="auth-card">
        <div class="text-center mb-4">
            <a href="{{ route('home') }}" class="text-decoration-none">
                <div class="brand-text">Bachat<span>Mart</span></div>
            </a>
            <p class="text-muted mt-1 mb-0" style="font-size: 0.88rem; font-weight: 500;">Create a free customer account</p>
        </div>

        @if($errors->any())
        <div class="alert alert-danger py-2 px-3 rounded-3 mb-3" style="font-size: 0.88rem;">
            <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-700 small text-dark">Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="Your full name"
                       value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-700 small text-dark">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="you@example.com"
                       value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-700 small text-dark">Phone Number <span class="text-muted fw-400">(optional)</span></label>
                <input type="tel" name="phone" class="form-control" placeholder="+91 98765 43210"
                       value="{{ old('phone') }}">
            </div>
            <div class="mb-3">
                <label class="form-label fw-700 small text-dark">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Min 8 characters" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-700 small text-dark">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
            </div>
            <button type="submit" class="btn btn-primary-bm w-100">Create Free Account</button>
        </form>

        <div class="text-center mt-4">
            <p class="small mb-1 fw-500">Already have an account? <a href="{{ route('login') }}" style="color: #FF5722; font-weight: 700;">Sign In</a></p>
            <p class="small mb-0 fw-500">Want to sell surplus products? <a href="{{ route('register.seller') }}" style="color: #FF5722; font-weight: 700;">Register as Seller</a></p>
        </div>
    </div>
</div>
</body>
</html>
