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
                    <h4 class="header-title">Trash Pricing Plans</h4>
                    <table id="blog_table" class="table table-borderless w-100 emd-table1">
                        <thead>
                            <tr>
                                <th class="sr">Sr.</th>
                                <th>Name / Type</th>
                                <th>Sale Price</th>
                                <th>Discount</th>
                                <th>Duration</th>
                                <th>Web / API</th>
                                <th>Recurring</th>
                                <th>Queries</th>
                                <th class="actioncell"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($emd_pricing_plans as $item)
                                <tr style="color: {{ $item->is_popular ? 'green' : '' }}">
                                    <td class="sr">{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }} <br> <b><i>{{ @$item::PLAN_TYPE[$item->plan_type] }}</i></b>
                                    </td>
                                    <td>{{ $item->sale_price }}</td>
                                    <td>{{ $item->discount_percentage }}%</td>
                                    <td>{{ $item->duration }} Days</td>
                                    <td>{{ App\Models\EmdPricingPlan::IS_API[$item->is_api] }}</td>
                                    <td>{{ $item->recurring_detail }}</td>
                                    <td>{{ $item->emd_pricing_plan_allows_sum_queries_limit }}</td>
                                    <td class="actioncell">
                                        @can('restore_pricing_plan')
                                            <a class="btn table-btn btn-info"
                                                href="{{ route('emd_pricing_plan_restore', ['id' => $item->id]) }}">
                                                Restore
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th>Trash Plan not Available</th>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div> <!-- end card body-->
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
