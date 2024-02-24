@extends('admin')
@section('head')
    <style>

    </style>
    <link rel="stylesheet" href="{{ asset('web_assets/admin/css/date-filter.css') }}" />
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('emd_user_search_by_email') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="header-title">EMD Web User
                                    ({{ Carbon\Carbon::parse(request()->route('start_date'))->format('d M Y') }} To
                                    {{ Carbon\Carbon::parse(request()->route('end_date'))->format('d M Y') }})</h4>
                            </div>
                            <div class="col-md-6">
                                <x-admin.date-filter></x-admin.date-filter>
                            </div>
                        </div>
                    </form>
                    <table id="users_table" class="table w-100">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Register Platform</th>
                                <th>Web Pre</th>
                                <th>API Pre</th>
                                <th>View</th>
                                <th>Login</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($emd_web_users as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ @$item->user->name }}</td>
                                    <td>{{ @$item->user->email }}</td>
                                    <td>{{ @$item->register_from }}</td>
                                    <td>{{ App\Models\EmdWebUser::PREMIUM_TYPE[@$item->is_web_premium] }}</td>
                                    <td>{{ App\Models\EmdWebUser::PREMIUM_TYPE[@$item->is_api_premium] }}</td>
                                    <td>
                                        @can('view_user_detail')
                                            <a class="btn btn-info"
                                                href="{{ route('emd_view_web_user_detail', ['id' => @$item->user_id ?? 0]) }}">
                                                View
                                            </a>
                                        @endcan
                                    </td>
                                    <td>
                                        @can('login_user_as')
                                            <a class="btn btn-primary"
                                                href="{{ route('user_login_by_admin', ['id' => @$item->user_id ?? 0]) }}"
                                                target="_blank">Login as</a>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                            @endforelse

                        </tbody>
                    </table>
                </div> <!-- end card body-->
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script defer src="{{ asset('web_assets/admin/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script defer src="{{ asset('web_assets/admin/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script defer src="{{ asset('web_assets/admin/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script defer src="{{ asset('web_assets/admin/js/date-filter.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#users_table").dataTable({
                "paging": false,
                "pageLength": 1000,
                "info": false
            });
        });
    </script>
@endsection
