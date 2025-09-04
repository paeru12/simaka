<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Login - SIMAKA</title>
    <link href="{{ asset('assets/img/logo.png')}}" rel="icon">
    <link href="{{ asset('assets/img/logo.png')}}" rel="apple-touch-icon">
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans|Nunito|Poppins" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css')}}" rel="stylesheet">
    <style>
        #passwordIcon {
            font-size: 1.2rem;
        }

        #showHidePassword {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background-color: transparent;
        }

        .input-group .form-control {
            padding-right: 2.5rem; /* biar tidak ketumpuk icon */
        }
    </style>
</head>
<body>
    <main>
        <div class="container">
            <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-center pt-4">
                                        <a href="" class="logo d-flex align-items-center w-auto">
                                            <img src="{{asset('assets/img/logo.png')}}" class="w-100">
                                        </a>
                                    </div>
                                    <div class="pb-2">
                                        <p class="text-center small">Enter your Email & Password to login</p>
                                        @if (session('errorLogin'))
                                        <p class="text-center small text-danger">Email / Password salah!</p>
                                        @endif
                                    </div>

                                    <form class="row g-3 needs-validation" novalidate action="" method="post">
                                        @csrf
                                        
                                        <!-- Floating Email -->
                                        <div class="col-12">
                                            <div class="form-floating">
                                                <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
                                                <label for="email">Email</label>
                                                <div class="invalid-feedback">Please enter your Email.</div>
                                            </div>
                                        </div>

                                        <!-- Floating Password -->
                                        <div class="col-12">
                                            <div class="form-floating position-relative">
                                                <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                                                <label for="password">Password</label>
                                                <button id="showHidePassword" type="button" class="btn">
                                                    <i id="passwordIcon" class="bi bi-eye-slash"></i>
                                                </button>
                                                <div class="invalid-feedback">Please enter your password!</div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <button class="btn btn-purple w-100" type="submit" id="login">Login</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('assets/js/main.js')}}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const passwordInput = document.getElementById("password");
            const passwordIcon = document.getElementById("passwordIcon");
            document.getElementById("showHidePassword").addEventListener("click", function() {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    passwordIcon.classList.remove("bi-eye-slash");
                    passwordIcon.classList.add("bi-eye");
                } else {
                    passwordInput.type = "password";
                    passwordIcon.classList.remove("bi-eye");
                    passwordIcon.classList.add("bi-eye-slash");
                }
            });
        });
    </script>
</body>
</html>
