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

        .status_label {
            color: white;
            padding: 1px 15px;
            border-radius: 5px;
        }

        .premium {
            background: green;
        }

        .free {
            background: red;
        }

        .refunded {
            background: black;
        }

        .expired {
            background: orange;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">User List</h4>
                    <table id="users_table" class="table dt-responsive nowrap w-100  order-column">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>User Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        @can('view_user_detail')
                                            <a href="{{ route('emd_view_web_user_detail', ['id' => @$item->id]) }}">{{ $item->email }}</a>
                                        @else
                                            {{ $item->email }}
                                        @endcan
                                    </td>
                                    <td><span
                                            class="status_label {{ Str::lower(@$item->is_user_status) }}">{{ @$item->is_user_status }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        {{ $users->links() }}
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
    <script>
        // $(document).ready(function() {
        //     $("#users_table").dataTable();
        // });
    </script>
@endsection
