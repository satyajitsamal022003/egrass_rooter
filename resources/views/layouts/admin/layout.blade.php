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
                    <a href="#!" data-toggle="offcanvas" class="sidebar-toggle"></a>
                    <!-- <ul class="top-nav lft-nav">
               <li>
                  <a href="#!" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle drop icon-circle drop-image">
                     <i class="ti-files"> </i><span> Files</span>
                  </a>
               </li>
               <li class="dropdown">
                  <a href="#!" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle drop icon-circle drop-image">
                     <span>Dropdown </span><i class=" icofont icofont-simple-down"></i>
                  </a>
                  <ul class="dropdown-menu settings-menu">
                     <li><a href="#">List item 1</a></li>
                     <li><a href="#">List item 2</a></li>
                     <li><a href="#">List item 3</a></li>
                     <li><a href="#">List item 4</a></li>
                     <li><a href="#">List item 5</a></li>
                  </ul>
               </li>
               <li class="dropdown pc-rheader-submenu message-notification search-toggle">
                  <a href="#!" id="morphsearch-search" class="drop icon-circle txt-white">
                     <i class="ti-search"></i>
                  </a>
               </li>
            </ul> -->
                    <!-- Navbar Right Menu-->
                    <div class="navbar-custom-menu f-right">
                        <!-- <div class="upgrade-button">
                <a href="#" class="icon-circle txt-white btn btn-sm btn-primary upgrade-button">
                    <span>Upgrade To Pro</span>
                </a>
              </div> -->

                        <ul class="top-nav">
                            <!--Notification Menu-->

                            <li class="dropdown notification-menu">
                                <a href="#!" data-toggle="dropdown" aria-expanded="false" class="dropdown-toggle">
                                    <i class="icon-bell"></i>
                                    <span class="badge badge-danger header-badge">9</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="not-head">You have <b class="text-primary">4</b> new notifications.
                                    </li>
                                    <li class="bell-notification">
                                        <a href="javascript:;" class="media">
                                            <span class="media-left media-icon">
                                                <img class="img-circle" src="{{ url('/') }}/assets/images/avatar-1.png" alt="User Image">
                                            </span>
                                            <div class="media-body"><span class="block">Lisa sent you a
                                                    mail</span><span class="text-muted block-time">2min ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="bell-notification">
                                        <a href="javascript:;" class="media">
                                            <span class="media-left media-icon">
                                                <img class="img-circle" src="{{ url('/') }}/assets/images/avatar-2.png" alt="User Image">
                                            </span>
                                            <div class="media-body"><span class="block">Server Not
                                                    Working</span><span class="text-muted block-time">20min ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="bell-notification">
                                        <a href="javascript:;" class="media"><span class="media-left media-icon">
                                                <img class="img-circle" src="{{ url('/') }}/assets/images/avatar-3.png" alt="User Image">
                                            </span>
                                            <div class="media-body"><span class="block">Transaction xyz
                                                    complete</span><span class="text-muted block-time">3 hours
                                                    ago</span></div>
                                        </a>
                                    </li>
                                    <li class="not-footer">
                                        <a href="#!">See all notifications.</a>
                                    </li>
                                </ul>
                            </li>
                            <!-- chat dropdown -->
                            <li class="pc-rheader-submenu ">
                                <a href="#!" class="drop icon-circle displayChatbox">
                                    <i class="icon-bubbles"></i>
                                    <span class="badge badge-danger header-badge">5</span>
                                </a>

                            </li>
                            <!-- window screen -->
                            <li class="pc-rheader-submenu">
                                <a href="#!" class="drop icon-circle" onclick="javascript:toggleFullScreen()">
                                    <i class="icon-size-fullscreen"></i>
                                </a>

                            </li>
                            <!-- User Menu-->
                            @php($admindata = Auth::user())
                            <li class="dropdown">
                                <a href="#!" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle drop icon-circle drop-image">
                                    <span><img class="img-circle " src="{{url('/')}}/images/blank-user.png" style="width:40px;" alt="User Image"></span>
                                    <span>{{$admindata->name}} <i class=" icofont icofont-simple-down"></i></span>
                                </a>
                                <ul class="dropdown-menu settings-menu">
                                    <!-- <li><a href="#!"><i class="icon-settings"></i> Settings</a></li> -->
                                    <li><a href="{{route('manageadmins.edit',$admindata->id)}}"><i class="icon-user"></i> Profile</a></li>
                                    <!-- <li><a href="#"><i class="icon-envelope-open"></i> My Messages</a></li>
                        <li class="p-0">
                           <div class="dropdown-divider m-0"></div>
                        </li>
                        <li><a href="#"><i class="icon-lock"></i> Lock Screen</a></li> -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <li><a href="{{ route('logout') }}" onclick="event.preventDefault();this.closest('form').submit();"><i class="icon-logout"></i> Logout</a></li>
                                    </form>
                                </ul>
                            </li>
                        </ul>

                        <!-- search -->
                        <div id="morphsearch" class="morphsearch">
                            <form class="morphsearch-form">

                                <input class="morphsearch-input" type="search" placeholder="Search..." />

                                <button class="morphsearch-submit" type="submit">Search</button>

                            </form>
                            <div class="morphsearch-content">
                                <div class="dummy-column">
                                    <h2>People</h2>
                                    <a class="dummy-media-object" href="#!">
                                        <img class="round" src="http://0.gravatar.com/avatar/81b58502541f9445253f30497e53c280?s=50&d=identicon&r=G" alt="Sara Soueidan" />
                                        <h3>Sara Soueidan</h3>
                                    </a>

                                    <a class="dummy-media-object" href="#!">
                                        <img class="round" src="http://1.gravatar.com/avatar/9bc7250110c667cd35c0826059b81b75?s=50&d=identicon&r=G" alt="Shaun Dona" />
                                        <h3>Shaun Dona</h3>
                                    </a>
                                </div>
                                <div class="dummy-column">
                                    <h2>Popular</h2>
                                    <a class="dummy-media-object" href="#!">
                                        <img src="{{ url('/') }}/assets/images/avatar-1.png" alt="PagePreloadingEffect" />
                                        <h3>Page Preloading Effect</h3>
                                    </a>

                                    <a class="dummy-media-object" href="#!">
                                        <img src="{{ url('/') }}/assets/images/avatar-1.png" alt="DraggableDualViewSlideshow" />
                                        <h3>Draggable Dual-View Slideshow</h3>
                                    </a>
                                </div>
                                <div class="dummy-column">
                                    <h2>Recent</h2>
                                    <a class="dummy-media-object" href="#!">
                                        <img src="{{ url('/') }}/assets/images/avatar-1.png" alt="TooltipStylesInspiration" />
                                        <h3>Tooltip Styles Inspiration</h3>
                                    </a>
                                    <a class="dummy-media-object" href="#!">
                                        <img src="{{ url('/') }}/assets/images/avatar-1.png" alt="NotificationStyles" />
                                        <h3>Notification Styles Inspiration</h3>
                                    </a>
                                </div>
                            </div>
                            <!-- /morphsearch-content -->
                            <span class="morphsearch-close"><i class="icofont icofont-search-alt-1"></i></span>
                        </div>
                        <!-- search end -->
                    </div>
                </nav>
            </header>
            @include('layouts.admin.menu')
            <div id="sidebar" class="p-fixed header-users showChat">
                <div class="had-container">
                    <div class="card card_main header-users-main">
                        <div class="card-content user-box">
                            <div class="md-group-add-on p-20">
                                <span class="md-add-on">
                                    <i class="icofont icofont-search-alt-2 chat-search"></i>
                                </span>
                                <div class="md-input-wrapper">
                                    <input type="text" class="md-form-control" name="username" id="search-friends">
                                    <label>Search</label>
                                </div>

                            </div>
                            <div class="media friendlist-main">

                                <h6>Friend List</h6>

                            </div>
                            <div class="main-friend-list">
                                <div class="media friendlist-box" data-id="1" data-status="online" data-username="Josephin Doe" data-toggle="tooltip" data-placement="left" title="Josephin Doe">

                                    <a class="media-left" href="#!">
                                        <img class="media-object img-circle" src="{{ url('/') }}/assets/images/avatar-1.png" alt="Generic placeholder image">
                                        <div class="live-status bg-success"></div>
                                    </a>
                                    <div class="media-body">
                                        <div class="friend-header">Josephin Doe</div>
                                        <span>20min ago</span>
                                    </div>
                                </div>
                                <div class="media friendlist-box" data-id="3" data-status="online" data-username="Alice" data-toggle="tooltip" data-placement="left" title="Alice">
                                    <a class="media-left" href="#!">
                                        <img class="media-object img-circle" src="{{ url('/') }}/assets/images/avatar-2.png" alt="Generic placeholder image">
                                        <div class="live-status bg-success"></div>
                                    </a>
                                    <div class="media-body">
                                        <div class="friend-header">Alice</div>
                                        <span>1 hour ago</span>
                                    </div>
                                </div>
                                <div class="media friendlist-box" data-id="7" data-status="offline" data-username="Michael Scofield" data-toggle="tooltip" data-placement="left" title="Michael Scofield">
                                    <a class="media-left" href="#!">
                                        <img class="media-object img-circle" src="{{ url('/') }}/assets/images/avatar-3.png" alt="Generic placeholder image">
                                        <div class="live-status bg-danger"></div>
                                    </a>
                                    <div class="media-body">
                                        <div class="friend-header">Michael Scofield</div>
                                        <span>3 hours ago</span>
                                    </div>
                                </div>
                                <div class="media friendlist-box" data-id="5" data-status="online" data-username="Irina Shayk" data-toggle="tooltip" data-placement="left" title="Irina Shayk">
                                    <a class="media-left" href="#!">
                                        <img class="media-object img-circle" src="{{ url('/') }}/assets/images/avatar-4.png" alt="Generic placeholder image">
                                        <div class="live-status bg-success"></div>
                                    </a>
                                    <div class="media-body">
                                        <div class="friend-header">Irina Shayk</div>
                                        <span>1 day ago</span>
                                    </div>
                                </div>
                                <div class="media friendlist-box" data-id="6" data-status="offline" data-username="Sara Tancredi" data-toggle="tooltip" data-placement="left" title="Sara Tancredi">
                                    <a class="media-left" href="#!">
                                        <img class="media-object img-circle" src="{{ url('/') }}/assets/images/avatar-5.png" alt="Generic placeholder image">
                                        <div class="live-status bg-danger"></div>
                                    </a>
                                    <div class="media-body">
                                        <div class="friend-header">Sara Tancredi</div>
                                        <span>2 days ago</span>
                                    </div>
                                </div>
                                <div class="media friendlist-box" data-id="1" data-status="online" data-username="Josephin Doe" data-toggle="tooltip" data-placement="left" title="Josephin Doe">
                                    <a class="media-left" href="#!">
                                        <img class="media-object img-circle" src="{{ url('/') }}/assets/images/avatar-1.png" alt="Generic placeholder image">
                                        <div class="live-status bg-success"></div>
                                    </a>
                                    <div class="media-body">
                                        <div class="friend-header">Josephin Doe</div>
                                        <span>20min ago</span>
                                    </div>
                                </div>
                                <div class="media friendlist-box" data-id="3" data-status="online" data-username="Alice" data-toggle="tooltip" data-placement="left" title="Alice">
                                    <a class="media-left" href="#!">
                                        <img class="media-object img-circle" src="{{ url('/') }}/assets/images/avatar-2.png" alt="Generic placeholder image">
                                        <div class="live-status bg-success"></div>
                                    </a>
                                    <div class="media-body">
                                        <div class="friend-header">Alice</div>
                                        <span>1 hour ago</span>
                                    </div>
                                </div>
                                <div class="media friendlist-box" data-id="1" data-status="online" data-username="Josephin Doe" data-toggle="tooltip" data-placement="left" title="Josephin Doe">

                                    <a class="media-left" href="#!">
                                        <img class="media-object img-circle" src="{{ url('/') }}/assets/images/avatar-1.png" alt="Generic placeholder image">
                                        <div class="live-status bg-success"></div>
                                    </a>
                                    <div class="media-body">
                                        <div class="friend-header">Josephin Doe</div>
                                        <span>20min ago</span>
                                    </div>
                                </div>
                                <div class="media friendlist-box" data-id="3" data-status="online" data-username="Alice" data-toggle="tooltip" data-placement="left" title="Alice">
                                    <a class="media-left" href="#!">
                                        <img class="media-object img-circle" src="{{ url('/') }}/assets/images/avatar-2.png" alt="Generic placeholder image">
                                        <div class="live-status bg-success"></div>
                                    </a>
                                    <div class="media-body">
                                        <div class="friend-header">Alice</div>
                                        <span>1 hour ago</span>
                                    </div>
                                </div>
                                <div class="media friendlist-box" data-id="1" data-status="online" data-username="Josephin Doe" data-toggle="tooltip" data-placement="left" title="Josephin Doe">

                                    <a class="media-left" href="#!">
                                        <img class="media-object img-circle" src="{{ url('/') }}/assets/images/avatar-1.png" alt="Generic placeholder image">
                                        <div class="live-status bg-success"></div>
                                    </a>
                                    <div class="media-body">
                                        <div class="friend-header">Josephin Doe</div>
                                        <span>20min ago</span>
                                    </div>
                                </div>
                                <div class="media friendlist-box" data-id="3" data-status="online" data-username="Alice" data-toggle="tooltip" data-placement="left" title="Alice">
                                    <a class="media-left" href="#!">
                                        <img class="media-object img-circle" src="{{ url('/') }}/assets/images/avatar-2.png" alt="Generic placeholder image">
                                        <div class="live-status bg-success"></div>
                                    </a>
                                    <div class="media-body">
                                        <div class="friend-header">Alice</div>
                                        <span>1 hour ago</span>
                                    </div>
                                </div>
                                <div class="media friendlist-box" data-id="1" data-status="online" data-username="Josephin Doe" data-toggle="tooltip" data-placement="left" title="Josephin Doe">

                                    <a class="media-left" href="#!">
                                        <img class="media-object img-circle" src="{{ url('/') }}/assets/images/avatar-1.png" alt="Generic placeholder image">
                                        <div class="live-status bg-success"></div>
                                    </a>
                                    <div class="media-body">
                                        <div class="friend-header">Josephin Doe</div>
                                        <span>20min ago</span>
                                    </div>
                                </div>
                                <div class="media friendlist-box" data-id="1" data-status="online" data-username="Josephin Doe" data-toggle="tooltip" data-placement="left" title="Josephin Doe">

                                    <a class="media-left" href="#!">
                                        <img class="media-object img-circle" src="{{ url('/') }}/assets/images/avatar-1.png" alt="Generic placeholder image">
                                        <div class="live-status bg-success"></div>
                                    </a>
                                    <div class="media-body">
                                        <div class="friend-header">Josephin Doe</div>
                                        <span>20min ago</span>
                                    </div>
                                </div>
                                <div class="media friendlist-box" data-id="1" data-status="online" data-username="Josephin Doe" data-toggle="tooltip" data-placement="left" title="Josephin Doe">

                                    <a class="media-left" href="#!">
                                        <img class="media-object img-circle" src="{{ url('/') }}/assets/images/avatar-1.png" alt="Generic placeholder image">
                                        <div class="live-status bg-success"></div>
                                    </a>
                                    <div class="media-body">
                                        <div class="friend-header">Josephin Doe</div>
                                        <span>20min ago</span>
                                    </div>
                                </div>
                                <div class="media friendlist-box" data-id="1" data-status="online" data-username="Josephin Doe" data-toggle="tooltip" data-placement="left" title="Josephin Doe">

                                    <a class="media-left" href="#!">
                                        <img class="media-object img-circle" src="{{ url('/') }}/assets/images/avatar-1.png" alt="Generic placeholder image">
                                        <div class="live-status bg-success"></div>
                                    </a>
                                    <div class="media-body">
                                        <div class="friend-header">Josephin Doe</div>
                                        <span>20min ago</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="showChat_inner">
                <div class="media chat-inner-header">
                    <a class="back_chatBox">
                        <i class="icofont icofont-rounded-left"></i> Josephin Doe
                    </a>
                </div>
                <div class="media chat-messages">
                    <a class="media-left photo-table" href="#!">
                        <img class="media-object img-circle m-t-5" src="{{ url('/') }}/assets/images/avatar-1.png" alt="Generic placeholder image">
                        <div class="live-status bg-success"></div>
                    </a>
                    <div class="media-body chat-menu-content">
                        <div class="">
                            <p class="chat-cont">I'm just looking around. Will you tell me something about yourself?
                            </p>
                            <p class="chat-time">8:20 a.m.</p>
                        </div>
                    </div>
                </div>
                <div class="media chat-messages">
                    <div class="media-body chat-menu-reply">
                        <div class="">
                            <p class="chat-cont">I'm just looking around. Will you tell me something about yourself?
                            </p>
                            <p class="chat-time">8:20 a.m.</p>
                        </div>
                    </div>
                    <div class="media-right photo-table">
                        <a href="#!">
                            <img class="media-object img-circle m-t-5" src="{{ url('/') }}/assets/images/avatar-2.png" alt="Generic placeholder image">
                            <div class="live-status bg-success"></div>
                        </a>
                    </div>
                </div>
                <div class="media chat-reply-box">
                    <div class="md-input-wrapper">
                        <input type="text" class="md-form-control" id="inputEmail" name="inputEmail">
                        <label>Share your thoughts</label>
                        <span class="highlight"></span>
                        <span class="bar"></span> <button type="button" class="chat-send waves-effect waves-light">
                            <i class="icofont icofont-location-arrow f-20 "></i>
                        </button>

                        <button type="button" class="chat-send waves-effect waves-light">
                            <i class="icofont icofont-location-arrow f-20 "></i>
                        </button>
                    </div>

                </div>
            </div>
            <!-- Sidebar chat end-->
            <div class="content-wrapper">
                <!-- Container-fluid starts -->
                @yield('section')
                <!-- Container-fluid ends -->
            </div>
        </div>


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

        <!-- custom js -->
        <script type="text/javascript" src="{{ url('/') }}/assets/js/main.min.js"></script>
        <script type="text/javascript" src="{{ url('/') }}/assets/pages/dashboard.js"></script>
        <script type="text/javascript" src="{{ url('/') }}/assets/pages/elements.js"></script>
        <script src="{{ url('/') }}/assets/js/menu.min.js"></script>
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
        <script src="{{ url('/') }}/datatables/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
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
        </script>

    </body>

</html>