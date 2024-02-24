<div class="navbar-custom" style="left: 190px !important">
    <div class="container-fluid">
        <div class="row">
            <div class="col-1"></div>
            <div class="col-9 menu_search_bar">
                <label for="">Search</label>
                <input type="text" id="search_query" class="form-control">
            </div>
            <div class="col-2">
                <ul class="list-unstyled topnav-menu mb-0 float-end">
                    <li class="dropdown d-none d-lg-inline-block">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen"
                            href="index.html#">
                            <i class="fe-maximize noti-icon"></i>
                        </a>
                    </li>
                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
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
            </div>
        </div>

    </div>
</div>
