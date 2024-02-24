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

                    <h4 class="header-title">Emd Feedbacks List</h4>

                    <table id="transaction_table" class="table dt-responsive nowrap w-100  order-column">
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
                                        @can('restore_feedback')
                                            <a class="btn btn-info"
                                                href="{{ route('emd_restore_feedback', ['id' => $item->id]) }}">
                                                Restore
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
    @endsection
