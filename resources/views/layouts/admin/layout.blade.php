<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin || Panel</title>
    <!-- HTML5 Shim and Respond.js IE9 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
     <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
     <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
     <![endif]-->

    <style>
        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            /* Reduced width */
            height: 20px;
            /* Reduced height */
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            /* Reduced height */
            width: 16px;
            /* Reduced width */
            left: 2px;
            /* Adjusted position */
            bottom: 2px;
            /* Adjusted position */
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(20px);
            /* Adjusted translation */
            -ms-transform: translateX(20px);
            /* Adjusted translation */
            transform: translateX(20px);
            /* Adjusted translation */
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 20px;
            /* Adjusted to match new height */
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>

<style>
    .dropdown-menu {
    max-height: 300px; /* Adjust the height as needed */
    overflow-y: auto; /* Add vertical scrolling */
    overflow-x: hidden; /* Hide horizontal scrolling, if any */
}

</style>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <!-- Favicon icon -->
    <link rel="shortcut icon" href="{{ url('/') }}/assets/images/favicon.png" type="image/x-icon">
    <link rel="icon" href="{{ url('/') }}/assets/images/favicon.ico" type="image/x-icon">

    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:400,500,700" rel="stylesheet">

    <!-- themify -->
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/icon/themify-icons/themify-icons.css">

    <!-- iconfont -->
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/icon/icofont/css/icofont.css">

    <!-- simple line icon -->
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/icon/simple-line-icons/css/simple-line-icons.css">

    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/plugins/bootstrap/css/bootstrap.min.css">
    <!-- Chartlist chart css -->
    <link rel="stylesheet" href="{{ url('/') }}/assets/plugins/chartist/dist/chartist.css" type="text/css" media="all">

    <!-- Weather css -->
    <link href="{{ url('/') }}/assets/css/svg-weather.css" rel="stylesheet">

    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/css/main.css">

    <!-- Responsive.css-->
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/css/responsive.css">

    <!-- datatables -->
    <!-- Theme style -->
    <!-- <link rel="stylesheet" href="{{ url('/') }}/datatables/dist/css/adminlte.min.css"> -->

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('/') }}/datatables/plugins/fontawesome-free/css/all.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ url('/') }}/datatables/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/datatables/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">


    <!-- toastr.css -->
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">


</head>

<body>
    </head>

    <body class="sidebar-mini fixed">
        <div class="loader-bg">
            <div class="loader-bar">
            </div>
        </div>
        <div class="wrapper">
            <!-- Navbar-->
            <header class="main-header-top hidden-print">
                <a href="{{ route('admin.dashboard') }}" class="logo"><i class="fa fa-globe" aria-hidden="true"></i>&nbsp;&nbsp;Egrassrooter</a>
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <a href="#notifications" data-toggle="offcanvas" class="sidebar-toggle"></a>
                    <!--Navbar Right Menu-->
                    <div class="navbar-custom-menu f-right">
                        <ul class="top-nav">
                            <!--Notification Menu-->
                            <li class="dropdown notification-menu">
                                    @php
                                    use App\Models\Notification;
                                    $notifications = Notification::where('admin_status', 2)->orderBy('created', 'desc')->get();
                                    @endphp
                                <a href="#notifications" data-toggle="dropdown" aria-expanded="false" class="dropdown-toggle">
                                    <i class="icon-bell"></i>
                                    <span class="badge badge-danger header-badge">{{ $notifications->count() }}</span>
                                </a>
                                <ul class="dropdown-menu" style="max-height: 300px; overflow-y: auto; overflow-x: hidden;">
                                   

                                    <li class="not-head">You have <b class="text-primary">{{ $notifications->count() }}</b> new notifications.</li>

                                    @foreach ($notifications as $notification)
                                    <li class="bell-notification">
                                        <a href="#" class="media">
                                            <span class="media-left media-icon">
                                                <img class="img-circle" src="{{ $notification->image_url ?? url('/images/blank.jpg') }}" alt="User Image">
                                            </span>
                                            <div class="media-body">
                                                <span class="block">{{ $notification->message }}</span>
                                                @php
                                                $createdAt = \Carbon\Carbon::parse($notification->created);
                                                @endphp
                                                <span class="text-muted block-time">{{ $createdAt->diffForHumans() }}</span>
                                            </div>
                                        </a>
                                    </li>
                                    @endforeach

                                    <li class="not-footer">
                                        <a href="{{route('dashboard.viewnotify')}}">See all notifications.</a>
                                    </li>
                                </ul>



                            </li>
                            <!-- User Menu-->
                            @php($admindata = Auth::user())
                            <li class="dropdown">
                                <a href="#!" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle drop icon-circle drop-image">
                                    <span><img class="img-circle " src="{{url('/')}}/images/blank-user.png" style="width:40px;" alt="User Image"></span>
                                    <span>{{$admindata->name}} <i class=" icofont icofont-simple-down"></i></span>
                                </a>
                                <ul class="dropdown-menu settings-menu">
                                    <li><a href="{{route('manageadmins.edit',$admindata->id)}}"><i class="icon-user"></i> Profile</a></li>
                                    <li class="p-0">
                                        <div class="dropdown-divider m-0"></div>
                                    </li>
                                    @csrf
                                    <form action="{{ route('logout') }}">
                                        <li><a href="{{ route('logout') }}" onclick="event.preventDefault();this.closest('form').submit();"><i class="icon-logout"></i> Logout</a></li>
                                    </form>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            @include('layouts.admin.menu')
            <!-- Sidebar chat end-->
            <div class="content-wrapper">
                <!-- Container-fluid starts -->
                @yield('section')
                <!-- Container-fluid ends -->
            </div>
        </div>
        <!-- Required Jqurey -->
        <script src="{{ url('/') }}/assets/plugins/Jquery/dist/jquery.min.js"></script>

        <!-- <script src="{{ asset('assets/plugins/ckeditor/ckeditor.js') }}"></script>
        <script src="{{ asset('assets/plugins/ckeditor/plugins/colorbutton/plugin.js') }}"></script>
        <script src="{{ asset('assets/plugins/ckeditor/plugins/justify/plugin.js') }}"></script>
        <script src="{{ asset('assets/plugins/ckeditor/plugins/button/plugin.js') }}"></script>
        <script src="{{ asset('assets/plugins/ckeditor/plugins/panelbutton/plugin.js') }}"></script>
        <script src="{{ asset('assets/plugins/ckeditor/plugins/imageuploader/plugin.js') }}"></script> -->

        <script src="{{ url('/') }}/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
        <script src="{{ url('/') }}/assets/plugins/tether/dist/js/tether.min.js"></script>

        <!-- Required Fremwork -->
        <script src="{{ url('/') }}/assets/plugins/bootstrap/js/bootstrap.min.js"></script>

        <!-- custom js -->
        <script type="text/javascript" src="{{ url('/') }}/assets/js/main.min.js"></script>
        <script type="text/javascript" src="{{ url('/') }}/assets/pages/dashboard.js"></script>
        <script type="text/javascript" src="{{ url('/') }}/assets/pages/elements.js"></script>
        <script src="{{ url('/') }}/assets/js/menu.min.js"></script>

        <!-- Scrollbar JS-->
        <script src="{{ url('/') }}/assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>
        <script src="{{ url('/') }}/assets/plugins/jquery.nicescroll/jquery.nicescroll.min.js"></script>

        <!--classic JS-->
        <script src="{{ url('/') }}/assets/plugins/classie/classie.js"></script>

        <!-- notification -->
        <script src="{{ url('/') }}/assets/plugins/notification/js/bootstrap-growl.min.js"></script>

        <!-- Sparkline charts -->
        <script src="{{ url('/') }}/assets/plugins/jquery-sparkline/dist/jquery.sparkline.js"></script>

        <!-- Counter js  -->
        <script src="{{ url('/') }}/assets/plugins/waypoints/jquery.waypoints.min.js"></script>
        <script src="{{ url('/') }}/assets/plugins/countdown/js/jquery.counterup.js"></script>

        <!-- Echart js -->
        <script src="{{ url('/') }}/assets/plugins/charts/echarts/js/echarts-all.js"></script>

        <!-- <script src="https://code.highcharts.com/highcharts.js"></script>
   <script src="https://code.highcharts.com/modules/exporting.js"></script>
   <script src="https://code.highcharts.com/highcharts-3d.js"></script> -->


        <script>
            var $window = $(window);
            var nav = $('.fixed-button');
            $window.scroll(function() {
                if ($window.scrollTop() >= 200) {
                    nav.addClass('active');
                } else {
                    nav.removeClass('active');
                }
            });
        </script>



        <!-- font awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">



        <!-- datatables -->
        <!-- jQuery -->
        <!-- comment because of not scrolling down -->
        <script src="{{ url('/') }}/datatables/plugins/jquery/jquery.min.js"></script>


        <!-- SweetAlert2 CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

        <!-- SweetAlert2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>


        <!-- Bootstrap 4 -->
        <!-- <script src="{{ url('/') }}/datatables/plugins/bootstrap/js/bootstrap.bundle.min.js"></script> -->
        <!-- DataTables -->
        <script src="{{ url('/') }}/datatables/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="{{ url('/') }}/datatables/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="{{ url('/') }}/datatables/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="{{ url('/') }}/datatables/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
        <script>
            $(function() {
                $('#datatable').DataTable({
                    "paging": true,
                    "lengthChange": false,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                });
            });
        </script>

        <!-- toastr.js -->
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
        <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>


        <!-- toastr script -->
        <script>
            // Check if there's an error message
            @if(Session::has('error'))
            toastr.error("{{ session('error') }}");
            @endif

            // Check if there's a success message
            @if(Session::has('message'))
            toastr.success("{{ session('message') }}");
            @endif

            @if(Session::has('info'))
            toastr.info("{{ session('info') }}");
            @endif

            @if($errors -> any())
            @foreach($errors -> all() as $error)
            toastr.error("{{ $error }}");
            @endforeach
            @endif
        </script>

    </body>

</html>