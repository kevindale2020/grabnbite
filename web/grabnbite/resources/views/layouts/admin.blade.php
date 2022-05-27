<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>{{ config('name', 'GRABNBITE') }}</title>

    <!-- Fonts -->
    <link href="{{asset('css/font-face.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/font-awesome-4.7/css/font-awesome.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/font-awesome-5/css/fontawesome-all.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/mdi-font/css/material-design-iconic-font.min.css')}}" rel="stylesheet" media="all">

     <!-- Bootstrap CSS-->
    <link href="{{asset('vendor/bootstrap-4.1/bootstrap.min.css')}}" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="{{asset('vendor/animsition/animsition.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/wow/animate.css" rel="stylesheet')}}" media="all">
    <link href="{{asset('vendor/css-hamburgers/hamburgers.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/slick/slick.css" rel="stylesheet')}}" media="all">
    <link href="{{asset('vendor/select2/select2.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('vendor/perfect-scrollbar/perfect-scrollbar.css')}}" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="{{asset('css/theme.css')}}" rel="stylesheet" media="all">
    <style type="text/css">
        .account-item > .content > a {
            color: #333;
            text-transform: none;
            font-weight: 500;
        }
        .page-wrapper {
            background: #f5f5f5 !important;
        }
        .page-container {
            background: #f5f5f5 !important;
        }
        .notifi-dropdown {
            max-height: 575px;
            overflow-y: scroll;
        }
    </style>
</head>
<body class="animsition">
    <div class="page-wrapper">
        <!-- HEADER MOBILE-->
        <header class="header-mobile d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                        <a class="logo" href="index.html">
                            <img src="../images/icon/logo-main-horizontal.png" alt="CoolAdmin" />
                        </a>
                        <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <nav class="navbar-mobile">
                <div class="container-fluid">
                    <ul class="navbar-mobile__list list-unstyled">
                        @foreach(Auth::user()->roles as $role)
                            @if($role->name=='Superadmin')
                                <li>
                                    <a href="{{route('admin-index')}}">
                                        <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                                </li>
                                <li>
                                    <a href="{{route('admin-get-users')}}">
                                        <i class="fas fa-users"></i>Users</a>
                                </li>
                                <li>
                                    <a href="{{route('admin-all-ratings')}}">
                                        <i class="fas fa-star"></i>Ratings</a>
                                </li>
                            @else
                                <li>
                                    <a href="{{route('admin-index')}}">
                                        <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                                </li>
                                @if(!empty(Auth::user()->company->id))
                                    <li>
                                        <a href="{{route('admin-product')}}">
                                            <i class="fas fa-utensils"></i>Products</a>
                                    </li>
                                @endif

                                <li>
                                     <a href="{{route('admin-coupons')}}">
                                        <i class="fas fa-percent"></i>Coupons</a>
                                </li>
                                <li>
                                    <a href="{{route('admin-reviews')}}">
                                        <i class="fas fa-star"></i>Reviews</a>
                                </li>
                                <li>
                                    <a href="{{route('admin-order-view')}}">
                                        <i class="fas fa-table"></i>Orders</a>
                                </li>
                                <li>
                                    <a href="{{route('admin-get-reports')}}">
                                        <i class="fas fa-table"></i>Reports</a>
                                </li>
                            @endif
                         @endforeach
                    </ul>
                </div>
            </nav>
        </header>
        <!-- END HEADER MOBILE-->

        <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
                <a href="#">
                    <img src="../images/icon/logo-main-horizontal.png" alt="Cool Admin" />
                </a>
            </div>
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                        @foreach(Auth::user()->roles as $role)
                            @if($role->name=='Superadmin')
                                <li>
                                    <a href="{{route('admin-index')}}">
                                        <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                                </li>
                                <li>
                                    <a href="{{route('admin-get-users')}}">
                                        <i class="fas fa-users"></i>Users</a>
                                </li>
                                <li>
                                    <a href="{{route('admin-all-ratings')}}">
                                        <i class="fas fa-star"></i>Ratings</a>
                                </li>
                            @else
                                <li>
                                    <a href="{{route('admin-index')}}">
                                        <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                                </li>
                               @if(!empty(Auth::user()->company->id))
                                    <li>
                                        <a href="{{route('admin-product')}}">
                                            <i class="fas fa-utensils"></i>Products</a>
                                    </li>
                                @endif

                                <li>
                                    <a href="{{route('admin-coupons')}}">
                                        <i class="fas fa-percent"></i>Coupons</a>
                                </li>
                               <li>
                                    <a href="{{route('admin-reviews')}}">
                                        <i class="fas fa-star"></i>Reviews</a>
                                </li>
                                 <li>
                                    <a href="{{route('admin-order-view')}}">
                                        <i class="fas fa-table"></i>Orders</a>
                                </li>
                                <li>
                                    <a href="{{route('admin-get-reports')}}">
                                        <i class="fas fa-table"></i>Reports</a>
                                </li>
                            @endif
                         @endforeach    
                        {{-- <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-copy"></i>Pages</a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list">
                                <li>
                                    <a href="login.html">Login</a>
                                </li>
                                <li>
                                    <a href="register.html">Register</a>
                                </li>
                                <li>
                                    <a href="forget-pass.html">Forget Password</a>
                                </li>
                            </ul>
                        </li> --}}
                    </ul>
                </nav>
            </div>
        </aside>
        <!-- END MENU SIDEBAR-->

        <!-- PAGE CONTAINER-->
        <div class="page-container">
            <!-- HEADER DESKTOP-->
            <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">
                            <form class="form-header" action="" method="POST">
                                <input class="au-input au-input--xl" type="text" name="search" placeholder="Search for datas &amp; reports..." />
                                <button class="au-btn--submit" type="submit">
                                    <i class="zmdi zmdi-search"></i>
                                </button>
                            </form>
                            <div class="header-button">
                                <div class="noti-wrap">
                                    {{-- <div class="noti__item js-item-menu">
                                        <i class="zmdi zmdi-comment-more"></i>
                                        <span class="quantity">1</span>
                                        <div class="mess-dropdown js-dropdown">
                                            <div class="mess__title">
                                                <p>You have 2 news message</p>
                                            </div>
                                            <div class="mess__item">
                                                <div class="image img-cir img-40">
                                                    <img src="" alt="Michelle Moreno" />
                                                </div>
                                                <div class="content">
                                                    <h6>Michelle Moreno</h6>
                                                    <p>Have sent a photo</p>
                                                    <span class="time">3 min ago</span>
                                                </div>
                                            </div>
                                            <div class="mess__item">
                                                <div class="image img-cir img-40">
                                                    <img src="" alt="Diane Myers" />
                                                </div>
                                                <div class="content">
                                                    <h6>Diane Myers</h6>
                                                    <p>You are now connected on message</p>
                                                    <span class="time">Yesterday</span>
                                                </div>
                                            </div>
                                            <div class="mess__footer">
                                                <a href="#">View all messages</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="noti__item js-item-menu">
                                        <i class="zmdi zmdi-email"></i>
                                        <span class="quantity">1</span>
                                        <div class="email-dropdown js-dropdown">
                                            <div class="email__title">
                                                <p>You have 3 New Emails</p>
                                            </div>
                                            <div class="email__item">
                                                <div class="image img-cir img-40">
                                                    <img src="" alt="Cynthia Harvey" />
                                                </div>
                                                <div class="content">
                                                    <p>Meeting about new dashboard...</p>
                                                    <span>Cynthia Harvey, 3 min ago</span>
                                                </div>
                                            </div>
                                            <div class="email__item">
                                                <div class="image img-cir img-40">
                                                    <img src="" alt="Cynthia Harvey" />
                                                </div>
                                                <div class="content">
                                                    <p>Meeting about new dashboard...</p>
                                                    <span>Cynthia Harvey, Yesterday</span>
                                                </div>
                                            </div>
                                            <div class="email__item">
                                                <div class="image img-cir img-40">
                                                    <img src="" alt="Cynthia Harvey" />
                                                </div>
                                                <div class="content">
                                                    <p>Meeting about new dashboard...</p>
                                                    <span>Cynthia Harvey, April 12,,2018</span>
                                                </div>
                                            </div>
                                            <div class="email__footer">
                                                <a href="#">See all emails</a>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="noti__item js-item-menu">
                                        <i class="zmdi zmdi-notifications"></i>
                                        <span id="notification_counter"></span>
                                        <div class="notifi-dropdown js-dropdown">
                                            <div class="notifi__title">
                                                <p id="notification_counter_text"></p>
                                            </div>
                                            <div id="notification_lists"></div>
                                            {{-- <div class="notifi__item">
                                                <div class="bg-c1 img-cir img-40">
                                                    <i class="zmdi zmdi-email-open"></i>
                                                </div>
                                                <div class="content">
                                                    <p>You got a email notification</p>
                                                    <span class="date">April 12, 2018 06:50</span>
                                                </div>
                                            </div>
                                            <div class="notifi__item">
                                                <div class="bg-c2 img-cir img-40">
                                                    <i class="zmdi zmdi-account-box"></i>
                                                </div>
                                                <div class="content">
                                                    <p>Your account has been blocked</p>
                                                    <span class="date">April 12, 2018 06:50</span>
                                                </div>
                                            </div>
                                            <div class="notifi__item">
                                                <div class="bg-c3 img-cir img-40">
                                                    <i class="zmdi zmdi-file-text"></i>
                                                </div>
                                                <div class="content">
                                                    <p>You got a new file</p>
                                                    <span class="date">April 12, 2018 06:50</span>
                                                </div>
                                            </div>
                                            <div class="notifi__footer">
                                                <a href="#">All notifications</a>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="account-wrap">
                                    <div class="account-item clearfix js-item-menu">
                                        <div class="image">
                                            <img src="../images/users/{{Auth::user()->image}}" alt="John Doe" />
                                        </div>
                                        <div class="content">
                                            <a class="js-acc-btn" href="#">{{ Auth::user()->email }}</a>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                            {{-- <div class="info clearfix">
                                                <div class="image">
                                                    <a href="#">
                                                        <img src="images/icon/avatar-01.jpg" alt="John Doe" />
                                                    </a>
                                                </div>
                                                <div class="content">
                                                    <h5 class="name">
                                                        <a href="#">john doe</a>
                                                    </h5>
                                                    <span class="email">johndoe@example.com</span>
                                                </div>
                                            </div> --}}
                                            <div class="account-dropdown__body">
                                                <div class="account-dropdown__item">
                                                    <a href="{{route('admin-account')}}">
                                                        <i class="zmdi zmdi-account"></i>Account</a>
                                                </div>
                                                @foreach(Auth::user()->roles as $role)
                                                    @if($role->name=='Admin')
                                                        <div class="account-dropdown__item">
                                                            <a href="{{route('admin-company-profile')}}">
                                                                <i class="zmdi zmdi-money-box"></i>Business</a>
                                                        </div>
                                                    @endif
                                                @endforeach
                                               {{--  <div class="account-dropdown__item">
                                                    <a href="#">
                                                        <i class="zmdi zmdi-money-box"></i>Billing</a>
                                                </div> --}}
                                            </div>
                                            <div class="account-dropdown__footer">
                                                    <a href="{{route('logout')}}" onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();"><i class="zmdi zmdi-power"></i>Logout</a>
                                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                        @csrf
                                                   </form>       
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- HEADER DESKTOP-->

            <!-- MAIN CONTENT-->
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        @yield('content')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="copyright">
                                    <p>Copyright © 2018 Colorlib. All rights reserved. Template by <a href="https://colorlib.com">Colorlib</a>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
        </div>

    </div>

    <!-- Jquery JS-->
    <script src="{{asset('vendor/jquery-3.2.1.min.js')}}"></script>
    <!-- Bootstrap JS-->
    <script src="{{asset('vendor/bootstrap-4.1/popper.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap-4.1/bootstrap.min.js')}}"></script>
    <!-- Vendor JS       -->
    <script src="{{asset('vendor/slick/slick.min.js')}}">
    </script>
    <script src="{{asset('vendor/wow/wow.min.js')}}"></script>
    <script src="{{asset('vendor/animsition/animsition.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap-progressbar/bootstrap-progressbar.min.js')}}">
    </script>
    <script src="{{asset('vendor/counter-up/jquery.waypoints.min.js')}}"></script>
    <script src="{{asset('vendor/counter-up/jquery.counterup.min.js')}}">
    </script>
    <script src="{{asset('vendor/circle-progress/circle-progress.min.js')}}"></script>
    <script src="{{asset('vendor/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{asset('vendor/chartjs/Chart.bundle.min.js')}}"></script>
    <script src="{{asset('vendor/select2/select2.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js" type="text/javascript"></script>

    <!-- Main JS-->
    <script src="{{asset('js/main.js')}}"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script type="text/javascript">
        
        $(document).ready(function(){

            getNotifications();

            /* refresh every 1 sec page */
            setInterval(function(){
                getNotifications();
            }, 5000);
            /* end */

            $('.noti__item i').click(function(){

                $.ajax({
                    url: '{{route('admin-update-notification')}}',
                    method: 'POST',
                    async: false,
                    data: {'admin-update-notification' : 1},
                    success: function(data) {
                        if(data.success==1) {
                            getNotifications();
                        }
                    }
                });
            });

        });

        function getNotifications() {

            var output = "";

            $.ajax({

                url: '{{route('admin-get-notifications')}}',
                method: 'GET',
                success: function(data) {

                    var notification = data['notifications'];

                    if(data.overall==0) {

                        $('#notification_counter').removeClass('quantity');
                        $('#notification_counter').html('');
                        $('#notification_counter_text').html('You have no notifications');
                    } else {

                        if(data.rows==0) {
                            $('#notification_counter').removeClass('quantity');
                            $('#notification_counter').html('');
                        } else {
                            $('#notification_counter').addClass('quantity');
                            $('#notification_counter').html(data.rows);
                        }

                        if(data.overall<2) {
                            $('#notification_counter_text').html('You have '+data.overall+' notification');
                        } else {
                            $('#notification_counter_text').html('You have '+data.overall+' notifications');
                        }

                         for(var i=0; i<notification.length; i++) {

                            output+="<div class='notifi__item'>";
                            output+="<div class='bg-c2 img-cir img-40'>";
                            output+="<i class='zmdi zmdi-account-box'></i>";
                            output+="</div>";
                            if(notification[i].type=='Registration') {
                                output+="<a class='content' href='{{route('admin-get-users')}}'>";
                            } else if(notification[i].type=='Order') {
                                output+="<a class='content' href='{{route('admin-order-view')}}'>";
                            } else if(notification[i].type=='Ratings and Reviews') {
                                output+="<a class='content' href='{{route('admin-reviews')}}'>";
                            }
                            output+="<p>"+notification[i].content+"</p>";
                            output+="<span class='date'>"+moment(notification[i].date).format('MMMM Do YYYY, h:mm a')+"</span>";
                            output+="</a>";
                            output+="</div>";
                        }

                        $('#notification_lists').html(output);
                    }
                }
            });
        }
    </script>

    @yield('footer')

</body>
</html>
