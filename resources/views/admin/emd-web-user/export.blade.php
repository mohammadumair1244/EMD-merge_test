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
                    <h4 class="header-title">Status Wise Emails</h4>
                    <table id="users_table" class="table dt-responsive nowrap w-100  order-column">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>User Status</th>
                                <th>No of Emails</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($emails_count_status_wise as $key => $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $key }}</td>
                                    <td>{{ $item }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-3">
                            @can('export_web_users')
                                <h4>Export Emails</h4>
                                <form action="{{ route('web_users_export_req') }}" method="post">
                                    @csrf
                                    <label for="">Select User Status</label>
                                    <select multiple name="user_status[]" class="form-control">
                                        @foreach ($emails_count_status_wise as $key => $value)
                                            <option value="{{ $key }}">{{ $key . ' (' . $value . ')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <br>
                                    @can('export_web_users')
                                        <button type="submit" class="btn btn-info">Export</button>
                                    @endcan
                                </form>
                            @endcan
                        </div>
                    </div>
                </div> <!-- end card body-->
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
