<!DOCTYPE html>
@if (!is_null(request()->lang))
    <html lang="{{ request()->lang }}">
@else
    <html lang="{{ config('constants.native_languge') }}">
@endif

<head>
    {{-- HEAD --}}
    @include('layout.frontend.head')
    {{-- HEAD END --}}
    @if (auth()->guard('admin_sess')->check())
        <style>
            .admin-navbar {
                overflow: hidden;
                position: fixed;
                top: 87px;
                z-index: 999999;
                right: 0;
                display: flex;
                flex-direction: column;
                row-gap: 3px;
            }

            .admin-navbar a {
                float: left;
                background: #333;
                display: block;
                color: #f2f2f2;
                text-align: center;
                padding: 5px 10px;
                text-decoration: none;
            }
        </style>
    @endif
    @if (config('constants.nofollow_noindex') == 'yes')
        <style>
            .noindex-warning {
                background-color: #d12a2acf;
                font-size: 1rem;
                text-align: center;
                color: white
            }
        </style>
    @endif
    <style>
        .no-www-warning {
            background-color: #2b4258cf;
            font-size: 1rem;
            text-align: center;
            color: white;
        }
    </style>
</head>

<body>
    @if (auth()->guard('admin_sess')->check())
        @if (isset($tool) || isset($blog))
            <div class="admin-navbar">
                @if (isset($tool))
                    <a href="{{ route('tool.edit', ['tool' => $tool]) }}" target="_blank">Edit Tool</a>
                @endif
                @if (isset($blog))
                    <a href="{{ URL('admin/blog/' . $blog['id'] . '/edit') }}" target="_blank">Edit Blog</a>
                @endif
            </div>
        @endif
    @endif
    @if (config('constants.nofollow_noindex') == 'yes')
        <div class="noindex-warning">Website is no-follow no-index</div>
    @endif
    @if (!in_array('www', explode('.', $_SERVER['HTTP_HOST'])))
        <div class="no-www-warning">Website is no www redirect</div>
    @endif
    {{-- HEADER --}}
    @include('layout.frontend.header')
    {{-- HEADER END --}}
    {{-- CONTENT --}}
    @yield('content')
    {{-- CONTENT END --}}
    {{-- FOOTER --}}
    @include('layout.frontend.footer')
    {{-- FOOTER END --}}
</body>

</html>
