@extends('admin')
@section('head')
    <style>
        .expire-plan {
            background: #6400ff14 !important;
            color: black;
        }

        .refund-plan {
            background: #ff000038 !important;
            color: black;
        }

        .test-mode {
            background: #5e59594d;
        }

        .color_label {
            margin-right: 10px;
            padding: 5px 30px;
            border-radius: 5px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('web_assets/admin/css/date-filter.css') }}" />
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('view_trans_log_search') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <h4 class="header-title">Transaction Logs</h4>
                            </div>
                            <div class="col-md-4">
                                <input type="text" placeholder="Order NO" name="order_no" class="form-control"
                                    @required(true)>
                            </div>
                            <div class="col-md-4">
                                @can('view_all_transactions')
                                    <button type="submit" class="btn btn-info">Search Order NO</button>
                                @endcan
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-borderless emd-table1 transectionTable">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Order#</th>
                                <th>Paypro IP</th>
                                <th>Status</th>
                                <th>Message</th>
                                <th>Request Date</th>  
                                <th>Download JSON</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (@$trans_logs as $item)
                                <tr
                                    style="color: {{ $item->status == 401 ? 'red' : ($item->status == 402 ? 'orange' : '') }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a
                                            href="{{ route('view_trans_log_detail', ['id' => $item->id]) }}">{{ $item->order_no }}</a>
                                    </td>
                                    <td>{{ $item->paypro_ip }}</td>
                                    <td>{{ @$item?->status }}</td>
                                    <td>{{ @$item?->status_message }}</td>
                                    <td>{{ Carbon\Carbon::parse(@$item?->created_at)->format("d M Y H:i A") }}</td>
                                    <td>
                                        @can('download_transaction_log_json')
                                            <a href="{{ route('download_log_json', ['id' => $item->id]) }}"
                                                class="btn btn-info table-btn">Download</a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        {{ $trans_logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
