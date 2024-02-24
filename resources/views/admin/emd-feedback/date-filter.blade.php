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
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="header-title">Emd Feedbacks
                                ({{ Carbon\Carbon::parse(request()->route('start_date'))->format('d M Y') }} To
                                {{ Carbon\Carbon::parse(request()->route('end_date'))->format('d M Y') }})</h4>
                        </div>
                        <div class="col-md-6">
                            <x-admin.date-filter></x-admin.date-filter>
                        </div>
                    </div>

                    <table id="feedback_table" class="table dt-responsive nowrap w-100  order-column">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>User</th>
                                <th>Tool</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Message</th>
                                <th>Rating</th>
                                <th>Date</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (@$emd_feedbacks as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($item->user_id > 1)
                                            <a
                                                href="{{ route('emd_view_web_user_detail', ['id' => @$item?->user?->id ?? 0]) }}">{{ @$item?->user?->name }}
                                                /<br> {{ @$item?->user?->email }}</a>
                                        @else
                                            Guest
                                        @endif
                                    </td>
                                    <td>{{ $item?->tool?->name ?: 'None' }} </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->message }}</td>
                                    <td>{{ $item->rating }}</td>
                                    <td>{{ Carbon\Carbon::parse($item->created_at)->format('d-M-Y') }}</td>
                                    <td>
                                        @can('delete_feedback')
                                            <a class="btn btn-danger"
                                                href="{{ route('emd_delete_feedback', ['id' => $item->id]) }}">
                                                Delete
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
                $("#feedback_table").dataTable({
                    "paging": false,
                    "pageLength": 1000,
                    "info": false
                });
            });
        </script>
    @endsection
