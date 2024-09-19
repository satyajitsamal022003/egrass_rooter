<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Egrassrooter - Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="{{url('/')}}/new_assets/assets/css/style.css" />
    <link rel="stylesheet" href="{{url('/')}}/new_assets/assets/css/animate.min.css" />
    <link rel="stylesheet" href="{{url('/')}}/new_assets/assets/css/menu.css" />
    <style>
        .form-control {
            border-radius: 5px;
        }

        .btn-one {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .btn-one:hover {
            background-color: #0056b3;
        }

        .confirm-box-page {
            background: #fff;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>

    <section class="ptb-100">
        <div class="container">
            <div class="row">
                <div class=" col-lg-9 mx-auto">
                    <div class="confirm-box-page">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <div class="confirm-img">
                                    <img src="{{url('/')}}/new_assets/assets/images/forgot-pswd.jpg" alt="Reset Password">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="confirm-form">
                                    <h1>Reset Your Password</h1>
                                    <p>Please enter and confirm your new password to secure your account.</p>
                                    <form id="resetPasswordForm" method="POST" action="{{ url('/api/reset-password/' . $userid) }}">
                                        @csrf
                                        <div class="form-group">
                                            <label for="newPassword">New Password</label>
                                            <div class="input-group">
                                                <input type="password" id="npwd" name="npwd" placeholder="Enter your new password" class="form-control" required minlength="6">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('npwd', this)">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="confirmPassword">Confirm Password</label>
                                            <div class="input-group">
                                                <input type="password" id="cpwd" name="cpwd" placeholder="Confirm your new password" class="form-control" required>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('cpwd', this)">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn-one btn-block">Confirm</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="copyright">
        <p>© Copyright <a href="#">Egrassrooter</a> 2024. All rights Reserved.</p>
    </div>

    <a href="#" class="back-to-top"><i class="fa fa-arrow-up"></i></a>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{url('/')}}/new_assets/assets/js/menu.js"></script>
    <script src="{{url('/')}}/new_assets/assets/js/wow.js"></script>
    <script>
        new WOW().init();
    </script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('resetPasswordForm').addEventListener('submit', function(event) {
            var password = document.getElementById('npwd').value;
            var confirmPassword = document.getElementById('cpwd').value;

            if (password !== confirmPassword) {
                event.preventDefault(); // Prevent form submission

                // Show SweetAlert2 toast for error
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Password and Confirm Password does not match!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true
                });

                return false; // Prevent further actions
            }
        });
    </script>

    <script>
        function togglePassword(fieldId, button) {
            var field = document.getElementById(fieldId);
            var icon = button.querySelector('i');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>

</body>

</html>