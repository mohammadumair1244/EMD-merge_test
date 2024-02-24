@extends('admin')
@section('head')
    <style>

    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">EMD Web Users</h4>
                    <table id="users_table" class="table w-100">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Register Platform</th>
                                <th>Web Pre</th>
                                <th>API Pre</th>
                                <th>Restore</th>
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
                                        @can('user_restore')
                                            <a href="{{ route('admin.user.restore', ['id' => $item->user_id ?? 0]) }}"
                                                class="btn btn-success">Restore</a>
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
    <script>
        $(document).ready(function() {
            $("#users_table").dataTable();
        });
    </script>
@endsection
