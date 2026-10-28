<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.partials.head')
    <style>
        /* --- Style tambahan untuk panduan login --- */
        .login-guide {
            background: linear-gradient(135deg, #e3f2fd, #f8fbff);
            border: 1px solid #90caf9;
            border-radius: 10px;
            padding: 15px 18px;
            font-size: 14px;
            color: #0d47a1;
            box-shadow: 0 3px 6px rgba(13, 71, 161, 0.1);
            position: relative;
            display: none;
            /* default tersembunyi */
            animation: fadeIn 0.3s ease-in-out;
        }

        .login-guide::before {
            content: "💡";
            position: absolute;
            top: 12px;
            right: 15px;
            font-size: 20px;
            opacity: 0.4;
        }

        .login-guide b {
            color: #0d47a1;
        }

        .login-guide ul {
            margin: 0;
            padding-left: 18px;
        }

        .login-guide li {
            margin-bottom: 6px;
        }

        .login-guide .example {
            background: #fff;
            border-left: 4px solid #42a5f5;
            padding: 8px 10px;
            margin-top: 10px;
            font-family: monospace;
            color: #1565c0;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .toggle-guide {
            cursor: pointer;
            color: #1976d2;
            font-weight: 600;
            text-decoration: underline;
            font-size: 14px;
            display: inline-block;
        }

        .toggle-guide:hover {
            color: #0d47a1;
        }
    </style>
</head>

<body class="d-flex flex-column">
    <div class="page">
        <div class="container container-tight py-4">
            <!-- Logo -->
            {{-- <div class="text-center">
                <a href="{{ url('/') }}" class="navbar-brand navbar-brand-autodark">
                    <img src="{{ asset('assets/images/logo_hr.png') }}" alt="Logo BHC" class="mb-3 logo-size">
                </a>
            </div> --}}

            <!-- Login Card -->
            <div class="card card-md shadow-sm border-0 mb-4">
                @if (session('message'))
                    <div class="alert alert-warning mb-3">{{ session('message') }}</div>
                @endif

                <div class="card-body">
                    <h2 class="h2 text-center mb-4 text-primary fw-bold">Login to Sistem</h2>

                    <!-- FORM LOGIN -->
                    <form method="POST" action="{{ route('login') }}" autocomplete="off" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Email" required
                                autofocus>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-semibold">Password</label>
                            <div class="input-group input-group-flat">
                                <input type="password" class="form-control" name="password"
                                    placeholder="Masukkan password" id="password" required>
                                <span class="input-group-text">
                                    <a href="#" class="link-secondary"
                                        onclick="event.preventDefault(); togglePassword();">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                            <path
                                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                        </svg>
                                    </a>
                                </span>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-check">
                                <input type="checkbox" class="form-check-input" name="remember">
                                <span class="form-check-label">Remember me</span>
                            </label>
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary w-100 fw-semibold" id="login-button">
                                Sign in
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <span class="text-muted">Belum punya akun?</span><br>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 mt-2 fw-semibold">
                                Daftar Akun
                            </a>
                        </div>


                        <div id="error-message" class="alert alert-danger mt-3 d-none"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    @include('layouts.partials.js')

    <script>
        window.addEventListener('load', function() {
            const modalEl = document.getElementById('authInfoModal');

            if (!modalEl || typeof bootstrap === 'undefined') return;

            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        });
    </script>

    <script>
        function togglePassword() {
            let password = document.getElementById("password");
            let eyeIcon = document.getElementById("eye-icon");

            if (password.type === "password") {
                password.type = "text";
                eyeIcon.innerHTML =
                    '<path d="M4 4l16 16"></path><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path>';
            } else {
                password.type = "password";
                eyeIcon.innerHTML =
                    '<path d="M10 12a 2 2 0 1 0 4 0 a 2 2 0 0 0 -4 0"></path><path d="M21 12 c -2.4 4 -5.4 6 -9 6 c -3.6 0 -6.6 -2 -9 -6 c 2.4 -4 5.4 -6 9 -6 c 3.6 0 6.6 2 9 6"></path>';
            }
        }
    </script>
</body>

</html>
