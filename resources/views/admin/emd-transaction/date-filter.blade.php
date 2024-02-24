@extends('admin')
@section('head')
    <style>
        .expire-plan {
            background: #6400ff14;
            color: black;
        }

        .refund-plan {
            background: #ff000038;
            color: black;
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
                            <h4 class="header-title">Transaction
                                ({{ Carbon\Carbon::parse(request()->route('start_date'))->format('d M Y') }} To
                                {{ Carbon\Carbon::parse(request()->route('end_date'))->format('d M Y') }})</h4>
                        </div>
                        <div class="col-md-6">
                            <x-admin.date-filter></x-admin.date-filter>
                        </div>
                    </div>
                    <table id="transaction_table" class="table dt-responsive nowrap w-100  order-column">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>order no</th>
                                <th>User / Email</th>
                                <th>order status</th>
                                <th>price / currency</th>
                                <th>purchase date</th>
                                <th>Plan days</th>
                                <th>expiry date</th>
                                <th>is refund</th>
                                <th>renewal</th>
                                <th>Payment Mode</th>
                                <th>view</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (@$transaction as $item)
                                <tr
                                    class="{{ $item->expiry_date >= date('Y-m-d') ? ($item->is_refund == App\Models\EmdUserTransaction::TRAN_REFUNDED ? 'refund-plan' : '') : 'expire-plan' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a
                                            href="{{ route('emd_view_web_user_detail', ['id' => @$item?->user?->id ?? 0]) }}">{{ @$item?->user?->name }}
                                            /<br> {{ @$item?->user?->email }}</a></td>
                                    <td>{{ $item->order_no }} </td>
                                    <td>{{ $item->order_status }}</td>
                                    <td>{{ $item->order_item_price }} {{ $item->order_currency_code }}</td>
                                    <td>{{ Carbon\Carbon::parse($item->purchase_date)->format('d M Y') }}</td>
                                    <td>{{ $item->plan_days }}</td>
                                    <td>{{ Carbon\Carbon::parse($item->expiry_date)->format('d M Y') }}</td>
                                    <td>{{ App\Models\EmdUserTransaction::TRAN_STATUS[(int) @$item->is_refund] }} </td>
                                    <td>{{ $item->renewal_type }}</td>
                                    <td>{{ App\Models\EmdUserTransaction::TEST_MODE_TYPE[$item->is_test_mode] }}</td>
                                    <td>
                                        @can('view_transaction_detail')
                                            <a class="btn btn-info"
                                                href="{{ route('emd_single_transaction', ['id' => $item->id]) }}">
                                                View
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
        <script defer src="{{ asset('web_assets/admin/js/date-filter.js') }}"></script>
    @endsection
