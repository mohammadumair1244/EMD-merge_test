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

        .label-tag {
            color: white;
            padding: 2px 8px;
            border-radius: 5px;
        }

        .premium-label {
            background: #0080009c;
            color: white;
            padding: 2px 8px;
            border-radius: 5px;
        }

        .free-label {
            background: black;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('web_assets/admin/css/date-filter.css') }}" />
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="header-title">Contacts
                                ({{ Carbon\Carbon::parse(request()->route('start_date'))->format('d M Y') }} To
                                {{ Carbon\Carbon::parse(request()->route('end_date'))->format('d M Y') }})</h4>
                        </div>
                        <div class="col-md-6">
                            <x-admin.date-filter></x-admin.date-filter>
                        </div>
                    </div>

                    <table id="contacts_table" class="table">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>User</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach (@$contacts as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if (@$item?->user_id > 0)
                                            <a
                                                href="{{ route('emd_view_web_user_detail', ['id' => @$item?->user_id ?? 0]) }}">{{ $item->user->name }}</a>
                                            <br>
                                            @if (@$item?->user?->emd_web_user?->is_web_premium || @$item?->user?->emd_web_user?->is_api_premium)
                                                <label class="label-tag premium-label">Premium</label>
                                            @else
                                                <label class="label-tag free-label">Free</label>
                                            @endif
                                        @else
                                            Guest
                                        @endif
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>
                                        <p class="message">{{ $item->message }}</p>
                                    </td>
                                    <td>{{ Carbon\Carbon::parse($item->created_at)->format('d-M-Y H:i a') }}</td>
                                    <td>
                                        @can('delete_contact_us')
                                            <form action="{{ route('contact.destroy', ['contact' => $item]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">TRASH</button>
                                            </form>
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
    <script defer src="{{ asset('web_assets/admin/js/date-filter.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#contacts_table").dataTable();
        });
    </script>
@endsection
