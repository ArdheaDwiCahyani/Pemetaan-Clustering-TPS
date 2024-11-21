<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/logoDLH2.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logoDLH2.png') }}">
    <title>
        DLH KOTA MALANG
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- CSS Files -->
    <!-- <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.0.4" rel="stylesheet" /> -->
    <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css?v=2.0.4') }}" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"
        integrity="sha512-dPXYcDub/aeb08c63jRq/k6GaKccl256JQy/AnOq7CAnEZ9FzSL9wSbcZkMp4R26vBsMLFYH4kQ67/bbV8XaCQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <style>
        #subMenu {
            display: none;
        }
    </style>
</head>

<body class="g-sidenav-show">
    <img class="position-absolute top-0 w-100 h-100"
        style="background-size: cover; background-position: center; filter: blur(2px);"
        src="{{ asset('assets/img/bg-dlh.jpg') }}" alt="dlh-image.jpg">
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh">
        <div class="card h-auto p-4 shadow" style="width: 450px;">
            <h4 class="text-center mb-4">Forgot Password</h4>
            <form method="POST" action="{{ route('reset-pw') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label text-dark text-sm font-weight-bold">Email</label>
                    <input type="text" name="email" id="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label text-dark text-sm font-weight-bold">New Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" required>
                        <span class="input-group-text" id="toggle-password" style="cursor: pointer;">
                            <i class="bi bi-eye-slash" id="password-icon"></i>
                        </span>
                    </div>
                </div>
                <div class="mb-5">
                    <label for="password_confirmation" class="form-label text-dark text-sm font-weight-bold">Confirm
                        Password</label>
                    <div class="input-group">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control" required>
                        <span class="input-group-text" id="toggle-password-conformation" style="cursor: pointer;">
                            <i class="bi bi-eye-slash" id="password-confirmation-icon"></i>
                        </span>
                    </div>
                </div>
                <div class="text-center mt-3 mb-3">
                    <button type="submit" class="btn btn-primary w-100 btn-block">Confirm Password</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('toggle-password').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');

            // Toggle antara password dan text
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordIcon.classList.remove('bi-eye-slash');
                passwordIcon.classList.add('bi-eye');
            } else {
                passwordField.type = 'password';
                passwordIcon.classList.remove('bi-eye');
                passwordIcon.classList.add('bi-eye-slash');
            }
        });

        document.getElementById('toggle-password-confirmation').addEventListener('click', function() {
            const passwordConfirmationField = document.getElementById('password_confirmation');
            const passwordConfirmationIcon = document.getElementById('password-confirmation-icon');

            // Toggle antara password dan text
            if (passwordConfirmationField.type === 'password') {
                passwordConfirmationField.type = 'text';
                passwordConfirmationIcon.classList.remove('bi-eye-slash');
                passwordConfirmationIcon.classList.add('bi-eye');
            } else {
                passwordConfirmationField.type = 'password';
                passwordConfirmationIcon.classList.remove('bi-eye');
                passwordConfirmationIcon.classList.add('bi-eye-slash');
            }
        });
    </script>
</body>

</html>
