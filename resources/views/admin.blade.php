<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title> {{ Str::title(str_replace('admin', '', str_replace('-', ' ', implode(' ', request()->segments())))) }}
        {{-- EMD - {{ env('APP_NAME') }} --}}
        {{-- @if (config('constants.version'))
            {{ config('constants.version') }}
        @endif --}}
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name=”robots” content="noindex, nofollow">
    <!-- App favicon -->
    @yield('head')
    <!-- App css -->
    <link href="{{ asset('web_assets/admin/css/config/default/bootstrap.min.css?v1.0.1') }}" rel="stylesheet"
        type="text/css" id="bs-default-stylesheet" />
    <link href="{{ asset('web_assets/admin/css/config/default/app.min.css?v1.0.1') }}" rel="stylesheet" type="text/css"
        id="app-default-stylesheet" />
    <!-- icons -->
    <link rel="stylesheet" href="{{ asset('web_assets/admin/css/style.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<!-- body start -->

<body class="loading"
    data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "sidebar": { "color": "gradient", "size": "default", "showuser": false}, "topbar": {"color": "light"}, "showRightSidebarOnPageLoad": false}'>
    @if (config('constants.nofollow_noindex') == 'yes')
        <div class="admin-navbar">
            <marquee>Website is no-follow no-index</marquee>
        </div>
    @endif
    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        @include('layout.admin.topbar')
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        @include('layout.admin.leftBar')
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

                    @yield('content')

                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            @include('layout.admin.footer')
            <!-- end Footer -->
        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->

    <!-- Right Sidebar -->

    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script defer src="{{ asset('web_assets/admin/js/vendor.min.js') }}"></script>

    @yield('script')
    <!-- App js-->
    <script defer src="{{ asset('web_assets/admin/js/app.min.js') }}"></script>
    <script src="{{ asset('web_assets/admin/js/search_filter.js') }}"></script>
    <script>
        var emd_menu_routes = "{{ json_encode(array_merge(config('emd_admin_menu_routes'),config('emd_other_admin_menu_routes'))) }}";
        emd_menu_routes = emd_menu_routes.replaceAll('&quot;', '"');
        emd_menu_routes = JSON.parse(emd_menu_routes);
        autocomplete(document.getElementById("search_query"), emd_menu_routes);
    </script>
</body>

</html>
