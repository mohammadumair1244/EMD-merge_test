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
                    <div class="row">
                        <div class="col-md-4">
                            <h4 class="header-title">Upload Email's List</h4>
                            <form action="{{ route('campaign.emd_email_list_create') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <label for="">Email List Title</label>
                                <input type="text" class="form-control" name="title" required="">
                                <label for="">Select CSV File with 1 Column (Email)</label>
                                <input type="file" class="form-control" accept=".csv" name="email_csv" required="">
                                <br>
                                @can('email_campaign_email_list_upload')
                                    <button class="btn btn-success">Upload</button>
                                @endcan
                            </form>
                        </div>
                        <div class="col-md-8">
                            <h4 class="header-title">Email's Lists</h4>
                            <table class="table dt-responsive nowrap w-100  order-column">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Title</th>
                                        <th>Email</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($emails as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->title }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>
                                                @can('email_campaign_email_list_delete')
                                                    <a href="{{ route('campaign.emd_email_list_delete', ['id' => $item->id]) }}"
                                                        class="btn btn-danger">Trash</a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                {{ $emails->links() }}
                            </div>
                        </div>
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
        $(document).ready(function() {
            $("#users_table").dataTable();
        });
    </script>
@endsection
