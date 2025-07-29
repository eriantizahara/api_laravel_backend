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
            <div class="auth-logo mb-3">
                <img src="{{ asset('assets/images/logo/Lawang_Adventure_Park1.png') }}" alt="Logo"
                    style="width: 180px; height: auto;" srcset="">
            </div>
            <h2 class="auth-title mb-4">Login</h2>
            {{-- <p class="text-muted mb-4">Enter your credentials below</p> --}}

            <form action="{{ route('login.cek') }}" method="POST">
                @csrf

                {{-- Tampilkan pesan error login jika ada --}}
                @if (session('msg'))
                    <div class="alert alert-danger alert-dismissible fade show text-start mb-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('msg') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Validasi Error untuk input --}}
                @error('name')
                    <div class="alert alert-warning text-start py-2 px-3 small">
                        <i class="bi bi-info-circle me-1"></i> {{ $message }}
                    </div>
                @enderror

                @error('password')
                    <div class="alert alert-warning text-start py-2 px-3 small">
                        <i class="bi bi-info-circle me-1"></i> {{ $message }}
                    </div>
                @enderror


                <!-- Username -->
                <div class="form-group mb-3">
                    {{-- Label di kiri atas --}}
                    <label for="name" class="form-label d-block text-start">Username</label>

                    {{-- Wrapper ikon dan input --}}
                    <div class="position-relative">
                        {{-- Ikon di dalam input --}}
                        <div class="position-absolute top-50 start-0 translate-middle-y ps-3">
                            <i class="bi bi-person text-secondary"></i>
                        </div>

                        {{-- Input field dengan border lengkung dan padding kiri --}}
                        <input type="text" id="name" name="name" class="form-control rounded-pill ps-5"
                            placeholder="Masukkan username" required>
                    </div>
                </div>


                <!-- Password -->
                <div class="form-group mb-3">
                    {{-- Label dan Link "Lupa Password" sejajar --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <label for="password-field" class="form-label mb-1 text-start">Password</label>
                        <a href="#" class="text-primary small">Lupa password?</a>
                    </div>

                    {{-- Wrapper input dan icon --}}
                    <div class="position-relative">
                        {{-- Ikon di dalam input --}}
                        <div class="position-absolute top-50 start-0 translate-middle-y ps-3">
                            <i class="bi bi-shield-lock text-secondary"></i>
                        </div>

                        {{-- Input password --}}
                        <input type="password" name="password" class="form-control rounded-pill ps-5"
                            id="password-field" placeholder="Masukkan password" required>

                        {{-- Optional toggle show/hide password --}}
                        {{-- <span toggle="#password-field" class="bi bi-eye toggle-password position-absolute top-50 end-0 translate-middle-y pe-3" onclick="togglePassword()"></span>  --}}

                    </div>
                </div>


                <!-- Forgot password & Keep me -->
                <div class="form-footer mb-4">
                    <div class="form-check d-flex align-items-center">
                        <input class="form-check-input me-2" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label text-muted" for="remember">Ingat saya</label>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center rounded-pill">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    <span>Login</span>
                </button>


            </form>

            {{-- <div class="mt-4">
                <p class="text-muted">Don't have an account?
                    <a href="#" class="fw-bold text-primary">Sign up</a>
                </p>
            </div> --}}
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper (untuk dismiss alert, modal, dll) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


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
