<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify OTP - BachatMart</title>
    
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
        .icon-circle {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #FFF5F0;
            color: #FF5722;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 1.25rem;
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
        .otp-inputs {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin: 1.5rem 0;
        }
        .otp-digit {
            width: 58px;
            height: 62px;
            text-align: center;
            font-size: 1.6rem;
            font-weight: 800;
            border-radius: 14px;
            border: 2px solid #E2E8F0;
            background: #F8FAFC;
            color: #0F172A;
            transition: all 0.2s;
        }
        .otp-digit:focus {
            border-color: #FF5722;
            background: #FFFFFF;
            outline: none;
            box-shadow: 0 0 0 4px rgba(255, 87, 34, 0.15);
        }
        .badge-demo {
            background: #FFF5F0;
            color: #FF5722;
            border: 1px solid #FFD3C4;
            font-weight: 700;
            font-size: 0.78rem;
            border-radius: 8px;
            padding: 6px 12px;
        }
        .resend-btn {
            background: none;
            border: none;
            color: #FF5722;
            font-weight: 700;
            font-size: 0.88rem;
            padding: 0;
            cursor: pointer;
            text-decoration: underline;
        }
        .resend-btn:disabled {
            color: #94A3B8;
            cursor: not-allowed;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center min-vh-100 py-4">
    <div class="auth-card text-center">
        <a href="{{ route('home') }}" class="text-decoration-none d-block mb-3">
            <div class="brand-text">Bachat<span>Mart</span></div>
        </a>

        <div class="icon-circle">
            <i class="bi bi-shield-check"></i>
        </div>

        <h4 class="fw-800 text-dark mb-1">Verify Mobile Number</h4>
        <p class="text-muted small mb-1">
            We sent an OTP code via MSG91 to
        </p>
        <div class="d-inline-flex align-items-center gap-2 mb-3">
            <span class="fw-800 text-dark" style="font-size: 0.95rem;">{{ $maskedPhone }}</span>
            <a href="{{ route('login.otp') }}" class="text-decoration-none small fw-700" style="color: #FF5722;">
                <i class="bi bi-pencil-square me-1"></i>Edit
            </a>
        </div>

        @if($isMock)
        <div class="mb-3 p-2 rounded-3 bg-light border text-center">
            <span class="badge-demo"><i class="bi bi-info-circle-fill me-1"></i>Demo Mode</span>
            <div class="small fw-700 text-dark mt-1">Use Test OTP: <span class="badge bg-dark text-white px-2 py-1 fs-6">{{ $testOtp }}</span></div>
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success py-2 px-3 rounded-3 mb-3 small fw-600 text-start">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger py-2 px-3 rounded-3 mb-3 small fw-600 text-start">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('login.otp.verify') }}" method="POST" id="verifyForm">
            @csrf
            
            {{-- Hidden input holding the concatenated OTP --}}
            <input type="hidden" name="otp" id="combinedOtp">

            <div class="otp-inputs">
                @for($i = 0; $i < $otpLength; $i++)
                <input type="text"
                       class="otp-digit"
                       maxlength="1"
                       inputmode="numeric"
                       pattern="[0-9]*"
                       data-index="{{ $i }}"
                       autocomplete="off"
                       required>
                @endfor
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check text-start mb-0">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                    <label class="form-check-label small fw-600 text-muted" for="remember">Keep me signed in</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary-bm w-100 mb-3" id="verifyBtn">
                <i class="bi bi-check2-circle me-1"></i> Verify & Sign In
            </button>
        </form>

        {{-- Resend OTP Section --}}
        <div class="mt-3 pt-3 border-top">
            <div class="small text-muted mb-2 fw-500">Didn't receive the code?</div>
            <form action="{{ route('login.otp.resend') }}" method="POST" id="resendForm" class="d-inline">
                @csrf
                <button type="submit" class="resend-btn" id="resendBtn" disabled>
                    Resend OTP
                </button>
            </form>
            <span class="small text-muted ms-1" id="timerContainer">in <span id="timerText" class="fw-700 text-dark">25</span>s</span>
        </div>

        <div class="mt-4">
            <a href="{{ route('login') }}" class="small fw-600 text-muted text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Back to Password Login
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const digits = Array.from(document.querySelectorAll('.otp-digit'));
    const combinedInput = document.getElementById('combinedOtp');
    const form = document.getElementById('verifyForm');
    const verifyBtn = document.getElementById('verifyBtn');

    if (digits.length > 0) {
        digits[0].focus();
    }

    digits.forEach((digit, idx) => {
        digit.addEventListener('input', (e) => {
            const val = e.target.value.replace(/[^0-9]/g, '');
            e.target.value = val;

            if (val && idx < digits.length - 1) {
                digits[idx + 1].focus();
            }

            updateCombinedOtp();

            // Auto-submit if all digits are entered
            if (combinedInput.value.length === digits.length) {
                form.submit();
            }
        });

        digit.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !digit.value && idx > 0) {
                digits[idx - 1].focus();
            }
        });

        digit.addEventListener('paste', (e) => {
            e.preventDefault();
            const pasteData = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
            if (pasteData) {
                pasteData.split('').slice(0, digits.length).forEach((char, pIdx) => {
                    digits[pIdx].value = char;
                });
                updateCombinedOtp();
                const nextFocus = Math.min(pasteData.length, digits.length - 1);
                digits[nextFocus].focus();

                if (combinedInput.value.length === digits.length) {
                    form.submit();
                }
            }
        });
    });

    function updateCombinedOtp() {
        const val = digits.map(d => d.value).join('');
        combinedInput.value = val;
    }

    form.addEventListener('submit', (e) => {
        updateCombinedOtp();
        if (combinedInput.value.length !== digits.length) {
            e.preventDefault();
            alert('Please enter all ' + digits.length + ' digits of the OTP.');
        } else {
            verifyBtn.disabled = true;
            verifyBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Verifying...';
        }
    });

    // Resend countdown timer
    let seconds = 25;
    const resendBtn = document.getElementById('resendBtn');
    const timerText = document.getElementById('timerText');
    const timerContainer = document.getElementById('timerContainer');

    const countdown = setInterval(() => {
        seconds--;
        if (seconds <= 0) {
            clearInterval(countdown);
            resendBtn.disabled = false;
            timerContainer.style.display = 'none';
        } else {
            timerText.textContent = seconds;
        }
    }, 1000);
});
</script>
</body>
</html>
