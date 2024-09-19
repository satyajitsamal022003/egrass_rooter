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
                <div class="col-lg-9 mx-auto">
                    <div class="confirm-box-page">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <div class="confirm-img">
                                    <img src="{{url('/')}}/new_assets/assets/images/forgot-pswd.jpg" alt="Reset Password">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="confirm-form">
                                    <h1>Forgot Password</h1>
                                    <p>{{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}</p>
                                    
                                    <!-- Session Status -->
                                    @if (session('status'))
                                        <div class="alert alert-success mb-4">
                                            {{ session('status') }}
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('password.email') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label for="email">{{ __('Email') }}</label>
                                            <div class="input-group">
                                                <input type="email" id="email" name="email" placeholder="{{ __('Enter your Mail ID') }}" class="form-control" required autofocus>
                                            </div>
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group mt-3">
                                            <button type="submit" class="btn-one">
                                                {{ __('Email Password Reset Link') }}
                                            </button>
                                        </div>
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

</body>

</html>
