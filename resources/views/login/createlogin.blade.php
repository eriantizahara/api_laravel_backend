<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login')</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap & Icons -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    
    <!-- App Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">

    <!-- Auth Custom Styles (Optional, akan kita override jika perlu) -->
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/pages/auth.css') }}"> --}}


    <style>

        /* Override styling untuk hapus warna ungu di auth-right (jika masih muncul) */
        #auth-right {
            background: none !important;
        }

        /* Pastikan tinggi layar penuh & form di tengah */
        #auth {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
        }

        .auth-box {
            width: 100%;
            max-width: 400px;
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .auth-title {
            font-size: 1.8rem;
            font-weight: 700;
        }

        .form-control-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }

        .position-relative input {
            padding-left: 40px;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
        }

        .form-footer a {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div id="auth">
        <div class="auth-box text-center">
            {{-- <div class="auth-logo mb-4">
                <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo" style="height: 40px;">
            </div> --}}
            <h2 class="auth-title mb-4">Login</h2>
            {{-- <p class="text-muted mb-4">Enter your credentials below</p> --}}

            <form action="{{ route('login.cek') }}" method="POST">
                @csrf

                <!-- Username -->
                <div class="form-group position-relative mb-3">
                    <input type="text" name="name" class="form-control" placeholder="Username" required>
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group position-relative mb-2">
                    <input type="password" name="password" class="form-control" placeholder="Password" id="password-field" required>
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    {{-- <span toggle="#password-field" class="bi bi-eye toggle-password" onclick="togglePassword()"></span> --}}
                </div>

                <!-- Forgot password & Keep me -->
                <div class="form-footer mb-4">
                    <div class="form-check d-flex align-items-center">
                        <input class="form-check-input me-2" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label text-muted" for="remember">Keep me logged in</label>
                    </div>
                    <a href="#" class="text-primary">Forgot password?</a>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary w-100">
                    Log in
                </button>
            </form>

            <div class="mt-4">
                <p class="text-muted">Don't have an account?
                    <a href="#" class="fw-bold text-primary">Sign up</a>
                </p>
            </div>
        </div>
    </div>

    <!-- JS for show/hide password -->
    {{-- <script>
        function togglePassword() {
            const password = document.getElementById("password-field");
            const toggle = document.querySelector(".toggle-password");
            if (password.type === "password") {
                password.type = "text";
                toggle.classList.remove("bi-eye");
                toggle.classList.add("bi-eye-slash");
            } else {
                password.type = "password";
                toggle.classList.remove("bi-eye-slash");
                toggle.classList.add("bi-eye");
            }
        }
    </script> --}}
</body>

</html>
