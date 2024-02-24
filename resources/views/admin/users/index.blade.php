@extends('admin')
@section('head')
    <link href="{{ asset('web_assets/admin/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('web_assets/admin/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" type="text/css" />
    <style>
        .feature {
            max-width: 250px;
            width: 60px;
            border-radius: 50%;
        }

        tbody tr td {
            vertical-align: middle;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">UsersTable</h4>

                    <table id="users_table" class="table dt-responsive nowrap w-100  order-column">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Hash</th>
                                <th>Delete</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($users as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        @if (
                                            $item->id !=
                                                auth()->guard('admin_sess')->id())
                                            @can('team_member_detail')
                                                <a href="{{ route('user.edit', ['user' => $item]) }}">{{ $item->email }}</a>
                                            @else
                                                {{ $item->email }}
                                            @endcan
                                        @else
                                            {{ $item->email }}
                                        @endif
                                    </td>
                                    <td>{{ \App\Models\User::USER_TYPE[$item->admin_level] }}</td>
                                    <td>{{ $item->hash }}</td>
                                    <td>
                                        @if (
                                            $item->id !=
                                                auth()->guard('admin_sess')->id())
                                            @can('team_member_delete')
                                                <form action="{{ route('user.destroy', ['user' => $item]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Trash</button>
                                                </form>
                                            @endcan
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
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
