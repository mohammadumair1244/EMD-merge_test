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
                <div class="col-md-5">
                    <h4 class="header-title">{{ request()->route('type') ?? 'Processed' }} Transaction</h4>
                </div>
                <div class="col-md-4">
                    <span class="color_label expire-plan">Expired</span>
                    <span class="color_label refund-plan">Refund</span>
                    {{-- <span class="color_label test-mode">Test</span> --}}
                </div>
                <div class="col-md-3">
                    <x-admin.date-filter></x-admin.date-filter>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-0">
                    <table id="transaction_table" class="table table-borderless emd-table1 transectionTable">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Order# / Payment From</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Price</th>
                                <th>Purcahse - Expiry</th>
                                <th>First or Recurring</th>
                                <th>Duration</th>
                                <th>Renew</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (@$emd_transactions as $item)
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
                                        <br>
                                        {{ @$item?->payment_from }}
                                    </td>
                                    <td>{{ @$item?->user?->name }}</td>
                                    <td>
                                        @can('view_user_detail')
                                            <a
                                                href="{{ route('emd_view_web_user_detail', ['id' => @$item?->user?->id ?? 0]) }}">
                                                {{ @$item?->user?->email }}
                                            </a>
                                        @else
                                            {{ @$item?->user?->email }}
                                        @endcan
                                    </td>
                                    <td>{{ $item->order_item_price }} {{ $item->order_currency_code }}</td>
                                    <td>{{ Carbon\Carbon::parse($item->purchase_date)->format('d M Y') }} -
                                        {{ Carbon\Carbon::parse($item->expiry_date)->format('d M Y') }}</td>
                                    <td>{{ \App\Models\EmdUserTransaction::FIRST_OR_RECURRING[$item->first_or_recurring ?? 0] }}
                                    </td>
                                    <td>{{ $item->plan_days }} Days</td>

                                    <td>{{ $item->renewal_type }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        {{ $emd_transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script defer src="{{ asset('web_assets/admin/js/date-filter.js') }}"></script>
    {{-- <script defer src="{{ asset('web_assets/admin/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script defer src="{{ asset('web_assets/admin/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script defer src="{{ asset('web_assets/admin/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script>
        $(document).ready(function() {
            $("#transaction_table").dataTable({
                "paging": false,
                "pageLength": 1000,
                "info": false
            });
        });
    </script> --}}
@endsection
