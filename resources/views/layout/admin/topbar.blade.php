<div class="navbar-custom">
    <div class="container-fluid">
        <ul class="list-unstyled topnav-menu float-end mb-0">


            <li class="dropdown d-none d-lg-inline-block">
                <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen"
                    href="index.html#">
                    <i class="fe-maximize noti-icon"></i>
                </a>
            </li>
            <li class="dropdown notification-list topbar-dropdown">
                <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown"
                    href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="{{ asset('web_assets/admin/images/users/user-1.jpg') }}" alt="user-image"
                        class="rounded-circle">
                    <span class="pro-user-name ms-1">
                        {{ auth()->guard('admin_sess')->user()->name }} <i class="mdi mdi-chevron-down"></i>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                    <!-- item-->
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div>

                    <!-- item-->
                    <a href="{{ route('home') }}" target="_blank" class="dropdown-item notify-item">
                        <i class="fa fa-home" aria-hidden="true"></i>
                        <span>{{ config('app.name') }}</span>
                    </a>

                    <!-- item-->
                    <a href="{{ route('logout') }}" class="dropdown-item notify-item">
                        <i class="fe-log-out me-1"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </li>
        </ul>

        <a href="{{ route('home') }}" target="_blank" class="logo-box" style="padding-left: 15px;">
            <div class="logo">
                <img src="{{ asset('web_assets/admin/images/users/user-1.jpg') }}" alt="">
            </div>
            <div class="text">
                <span class="name">{{ config('app.name') }}</span>
                <span class="url">{{ route('home') }}</span>
            </div>
        </a>

        <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
            <li>
                <button class="button-menu-mobile waves-effect waves-light">
                    <i class="fe-menu"></i>
                </button>
            </li>

            <li>
                <!-- Mobile menu toggle (Horizontal Layout)-->
                <a class="navbar-toggle nav-link" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
                <!-- End mobile menu toggle-->
            </li>


        </ul>
        <div class="clearfix"></div>

    </div>
</div>
