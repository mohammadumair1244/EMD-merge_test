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
                            <div class="col-md-4">
                                <h4 class="header-title">EMD Web Users</h4>
                            </div>
                            @can('view_user_detail')
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <input type="email" placeholder="Email" name="email" class="form-control"
                                                @required(true)>
                                            @if (session()->has('error'))
                                                <div class="alert">
                                                    {{ session()->get('error') }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-info">Search User</button>
                                        </div>
                                    </div>
                                </div>
                            @endcan
                            <div class="col-md-4">
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
                                <th>Is API Pre</th>
                                <th>Login</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($emd_web_users as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ @$item->user->name }}</td>
                                    <td>
                                        @can('view_user_detail')
                                            <a href="{{ route('emd_view_web_user_detail', ['id' => @$item->user_id ?? 0]) }}">
                                                {{ @$item->user->email }}
                                            </a>
                                        @else
                                            {{ @$item->user->email }}
                                        @endcan
                                    </td>
                                    <td>{{ @$item->register_from }}</td>
                                    <td>{{ App\Models\EmdWebUser::PREMIUM_TYPE[@$item->is_web_premium] }}</td>
                                    <td>{{ App\Models\EmdWebUser::PREMIUM_TYPE[@$item->is_api_premium] }}</td>
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
                    <div class="d-flex justify-content-end">
                        {{ $emd_web_users->links() }}
                    </div>
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
