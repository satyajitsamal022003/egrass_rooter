<!DOCTYPE html>
<html lang="en">
<head>
	<title>Quantum Able Bootstrap 4 Admin Dashboard Template</title>


	<!-- Meta -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta name="description" content="codedthemes">
	<meta name="keywords"
		  content=", Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
	<meta name="author" content="codedthemes">

	<!-- Favicon icon -->
	<link rel="shortcut icon" href="{{url('/')}}/assets/images/favicon.png" type="image/x-icon">
	<link rel="icon" href="{{url('/')}}/assets/images/favicon.ico" type="image/x-icon">

	<!-- Google font-->
   <link href="https://fonts.googleapis.com/css?family=Ubuntu:400,500,700" rel="stylesheet">

	<!-- Font Awesome -->
	<link href="{{url('/')}}/assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	<!--ico Fonts-->
	<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/icon/icofont/css/icofont.css">

    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/plugins/bootstrap/css/bootstrap.min.css">

	<!-- waves css -->
	<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/plugins/Waves/waves.min.css">

	<!-- Style.css -->
	<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/main.css">

	<!-- Responsive.css-->
	<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/responsive.css">

	<!--color css-->
	<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/color/color-1.min.css" id="color"/>

</head>
<body>
<section class="login p-fixed d-flex text-center bg-primary common-img-bg">
	<!-- Container-fluid starts -->
	<div class="container-fluid">
		<div class="row">

			<div class="col-sm-12">
				<div class="login-card card-block">
					<form method="POST" action="{{ route('login') }}" class="md-float-material">
					   @csrf
						<div class="text-center">
						<a href="#" class="logo" style="font-size: 20px;"><i class="fa fa-globe" aria-hidden="true"></i>&nbsp;&nbsp;Egrassrooter</a>
						</div>
						<h3 class="text-center" style="color: chocolate;">
							Sign In
						</h3>
						<div class="row">
							<div class="col-md-12">
								<div class="md-input-wrapper">
									<input type="email" id="email" name="email" class="md-form-control" required="required"/>
									<x-input-error :messages="$errors->get('email')" class="mt-2" />
									<label>Email</label> 
								</div>
							</div>
							<div class="col-md-12">
								<div class="md-input-wrapper">
									<input type="password" id="password" name="password" class="md-form-control" required="required"/>
									<x-input-error :messages="$errors->get('password')" class="mt-2" />
									<label>Password</label>
								</div>
							</div>
							<div class="col-sm-6 col-xs-12">
							<div class="rkmd-checkbox checkbox-rotate checkbox-ripple m-b-25">
								<label class="input-checkbox checkbox-primary">
									<input name="remember" type="checkbox" id="remember_me">
									<span class="checkbox"></span>
								</label>
								<div class="captions">Remember Me</div>

							</div>
								</div>
							<div class="col-sm-6 col-xs-12 forgot-phone text-right">
							@if (Route::has('password.request'))
								<a href="{{ route('password.request') }}" class="text-right f-w-600"> Forget Password?</a>
							@endif
							</div>
						</div>
						<div class="row">
							<div class="col-xs-10 offset-xs-1">
								<button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">LOGIN</button>
							</div>
						</div>
						<!-- <div class="card-footer"> -->
						<!-- <div class="col-sm-12 col-xs-12 text-center">
							<span class="text-muted">Don't have an account?</span>
							<a href="{{url('/')}}/register2.html" class="f-w-600 p-l-5">Sign up Now</a>
						</div> -->

						<!-- </div> -->
					</form>
					<!-- end of form -->
				</div>
				<!-- end of login-card -->
			</div>
			<!-- end of col-sm-12 -->
		</div>
		<!-- end of row -->
	</div>
	<!-- end of container-fluid -->
</section>

<!-- Warning Section Starts -->
<!-- Older IE warning message -->
<!--[if lt IE 9]>
<div class="ie-warning">
	<h1>Warning!!</h1>
	<p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers to access this website.</p>
	<div class="iew-container">
		<ul class="iew-download">
			<li>
				<a href="http://www.google.com/chrome/">
					<img src="assets/images/browser/chrome.png" alt="Chrome">
					<div>Chrome</div>
				</a>
			</li>
			<li>
				<a href="https://www.mozilla.org/en-US/firefox/new/">
					<img src="assets/images/browser/firefox.png" alt="Firefox">
					<div>Firefox</div>
				</a>
			</li>
			<li>
				<a href="http://www.opera.com">
					<img src="assets/images/browser/opera.png" alt="Opera">
					<div>Opera</div>
				</a>
			</li>
			<li>
				<a href="https://www.apple.com/safari/">
					<img src="assets/images/browser/safari.png" alt="Safari">
					<div>Safari</div>
				</a>
			</li>
			<li>
				<a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
					<img src="assets/images/browser/ie.png" alt="">
					<div>IE (9 & above)</div>
				</a>
			</li>
		</ul>
	</div>
	<p>Sorry for the inconvenience!</p>
</div>
<![endif]-->
<!-- Warning Section Ends -->
<!-- Required Jqurey -->
<script src="{{url('/')}}/assets/plugins/jquery/dist/jquery.min.js"></script>
<script src="{{url('/')}}/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="{{url('/')}}/assets/plugins/tether/dist/js/tether.min.js"></script>

<!-- Required Fremwork -->
<script src="{{url('/')}}/assets/plugins/bootstrap/js/bootstrap.min.js"></script>

<!-- waves effects.js -->
<script src="{{url('/')}}/assets/plugins/Waves/waves.min.js"></script>
<!-- Custom js -->
<script type="text/javascript" src="{{url('/')}}/assets/pages/elements.js"></script>



</body>
</html>
