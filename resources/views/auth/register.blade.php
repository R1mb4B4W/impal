<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Daftar Deltizen Corner</title>
    <!-- Yummy CSS dan Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet">
    <link href="{{ asset('template_front/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template_front/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('template_front/assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('template_front/assets/css/main.css') }}" rel="stylesheet">
    <!-- CSS Kustom untuk Register Elegan -->
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f1faee 0%, #e5e5e5 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .register-container {
            max-width: 450px;
            width: 100%;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            margin: 20px;
        }
        .register-container:hover {
            transform: translateY(-5px);
        }
        .register-title {
            font-family: 'Amatic SC', cursive;
            font-size: 48px;
            color: #1d3557;
            text-align: center;
            margin-bottom: 20px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            position: relative;
            margin-bottom: 25px;
        }
        .form-group label {
            font-size: 14px;
            color: #1d3557;
            margin-bottom: 5px;
            display: block;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .form-group input:focus {
            border-color: #e63946;
            box-shadow: 0 0 5px rgba(230, 57, 70, 0.2);
            outline: none;
        }
        .btn-show-pass {
            position: absolute;
            right: 15px;
            top: 65%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            font-size: 18px;
        }
        .btn-register {
            background: #e63946;
            color: white;
            padding: 12px 0;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 500;
            text-transform: uppercase;
            width: 100%;
            transition: background 0.3s, transform 0.3s;
        }
        .btn-register:hover {
            background: #d00000;
            transform: scale(1.02);
        }
        .text-link {
            color: #e63946;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        .text-link:hover {
            color: #d00000;
            text-decoration: underline;
        }
        .alert-danger {
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
        }
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
            color: #6c757d;
        }
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #ddd;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="register-container" data-aos="fade-up">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <h2 class="register-title">Daftar Sekarang!</h2>

            <!-- Nama -->
            <div class="form-group">
                <label for="name">Nama</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
            </div>
            @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
            </div>
            @error('email')
                <div class="alert alert-danger">E-mail sudah dipakai</div>
            @enderror

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <div class="position-relative">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    <span class="btn-show-pass" onclick="togglePasswordVisibility('password', 'togglePasswordIcon')">
                        <i class="bi bi-eye" id="togglePasswordIcon"></i>
                    </span>
                </div>
            </div>

            <!-- Konfirmasi Password -->
            <div class="form-group">
                <label for="password-confirm">Konfirmasi Password</label>
                <div class="position-relative">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    <span class="btn-show-pass" onclick="togglePasswordVisibility('password-confirm', 'togglePasswordConfirmIcon')">
                        <i class="bi bi-eye" id="togglePasswordConfirmIcon"></i>
                    </span>
                </div>
            </div>
            @error('password')
                <div class="alert alert-danger">Password minimal 8 karakter atau konfirmasi tidak cocok.</div>
            @enderror

            <script>
                function togglePasswordVisibility(inputId, iconId) {
                    const passwordInput = document.getElementById(inputId);
                    const toggleIcon = document.getElementById(iconId);
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        toggleIcon.classList.remove('bi-eye');
                        toggleIcon.classList.add('bi-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        toggleIcon.classList.remove('bi-eye-slash');
                        toggleIcon.classList.add('bi-eye');
                    }
                }
            </script>

            <!-- Tombol Daftar -->
            <button type="submit" class="btn-register">{{ __('Daftar') }}</button>

            <div class="divider">atau</div>

            <div class="text-center">
                <span class="text-muted">Sudah Punya Akun?</span>
                <a class="text-link d-block mt-2" href="{{ route('login') }}">Login di sini</a>
            </div>
        </form>
    </div>

    <!-- Vendor JS Files -->
    <script src="{{ asset('template_front/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template_front/assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('template_front/assets/js/main.js') }}"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>