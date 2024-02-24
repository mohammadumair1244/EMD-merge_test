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
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('emd_transaction_search_req') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <h4 class="header-title">Search Transaction</h4>
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

                    <table class="table table-borderless emd-table1 transectionTable">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Order#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Price</th>
                                <th>Purcahse - Expiry</th>
                                <th>Duration</th>
                                <th>Renew</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (@$transaction as $item)
                                <tr
                                    class="{{ $item->expiry_date >= date('Y-m-d') ? ($item->is_refund == App\Models\EmdUserTransaction::TRAN_REFUNDED ? 'refund-plan' : '') : 'expire-plan' }} {{ @$item->is_test_mode != 0 ? 'test-mode' : '' }} ">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @can('view_transaction_detail')
                                            <a href="{{ route('emd_single_transaction', ['id' => $item->id]) }}">
                                                {{ $item->order_no }}
                                            </a>
                                        @else
                                            {{ $item->order_no }}
                                        @endcan
                                    </td>
                                    <td>{{ @$item?->user?->name }}</td>
                                    <td>
                                        @can('view_user_detail')
                                            <a href="{{ route('emd_view_web_user_detail', ['id' => @$item?->user?->id ?? 0]) }}">
                                                {{ @$item?->user?->email }}
                                            </a>
                                        @else
                                            {{ @$item?->user?->email }}
                                        @endcan
                                    </td>
                                    <td>{{ @$item?->order_status }}</td>
                                    <td>{{ $item->order_item_price }} {{ $item->order_currency_code }}</td>
                                    <td>{{ Carbon\Carbon::parse($item->purchase_date)->format('d M Y') }} -
                                        {{ Carbon\Carbon::parse($item->expiry_date)->format('d M Y') }}</td>
                                    <td>{{ $item->plan_days }} Days</td>

                                    <td>{{ $item->renewal_type }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection
    @section('script')
    @endsection
