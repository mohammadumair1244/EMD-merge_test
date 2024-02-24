@extends('admin')
@section('head')
    <link href="{{ asset('web_assets/admin/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('web_assets/admin/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" type="text/css" />
    <style>
        tbody tr td {
            vertical-align: middle;
        }

        .message {
            max-width: 400px;
            overflow: hidden;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Contacts Table</h4>

                    <table id="contacts_table" class="table">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Message</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach (@$contacts as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>
                                        <p class="message">
                                            {{ $item->message }}
                                        </p>
                                    </td>
                                    <td>
                                        @can('restore_contact_us')
                                            <a href="{{ route('dashboard.contacts.restore', ['id' => $item->id]) }}"
                                                class="btn btn-success mb-2">RESTORE</a>
                                        @endcan
                                        @can('delete_contact_us')
                                            <a href="{{ route('dashboard.contacts.destroy', ['id' => $item->id]) }}"
                                                class="btn btn-danger mb-2">DESTROY</a>
                                        @endcan
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
            $("#contacts_table").dataTable();
        });
    </script>
@endsection
