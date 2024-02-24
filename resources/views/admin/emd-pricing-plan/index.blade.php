@extends('admin')
@section('head')
    <style>
        .btn {
            padding: 2px 8px !important;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mt-4">
            @can('view_pricing_plan')
                <h3>Pricing Plans</h3>
                <div class="card">
                    <div class="card-body p-0">
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
                                    {{-- <th>Order No</th> --}}
                                    <th>Active</th>
                                    <th class="actioncell"></th>
                                    <th class="actioncell"></th>
                                    <th class="actioncell"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($web_pricing_plans as $item)
                                    <tr style="color: {{ $item->is_popular ? 'green' : '' }}">
                                        <td class="sr">{{ $loop->iteration }}</td>
                                        <td>
                                            @can('edit_pricing_plan')
                                                <a href="{{ route('emd_pricing_plan_edit', ['id' => $item->id]) }}">
                                                    {{ $item->name }}
                                                </a>
                                            @else
                                                {{ $item->name }}
                                            @endcan
                                            <br> <b><i>{{ @$item::PLAN_TYPE[$item->plan_type] }}</i></b>
                                        </td>
                                        <td>{{ $item->sale_price }}</td>
                                        <td>{{ $item->discount_percentage }}%</td>
                                        <td>{{ $item->duration }} Days</td>
                                        <td>{{ App\Models\EmdPricingPlan::IS_API[$item->is_api] }}</td>
                                        <td>{{ $item->recurring_detail }}</td>
                                        <td>{{ $item->emd_pricing_plan_allows_sum_queries_limit }}</td>
                                        {{-- <td>
                                            @can('edit_pricing_plan')
                                                <select class="form-control ordering_no" id="{{ $item->id }}">
                                                    @for ($start_no = 1; $start_no <= count($emd_pricing_plans->where('is_custom', 0)); $start_no++)
                                                        <option value="{{ $start_no }}"
                                                            {{ $start_no == $item->ordering_no ? 'selected' : '' }}>
                                                            {{ $start_no }}</option>
                                                    @endfor
                                                </select>
                                            @endcan
                                        </td> --}}
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" id="{{ $item->id }}"
                                                    {{ $item->is_active ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td class="actioncell">
                                            @can('add_pricing_plan')
                                                <a class="btn table-btn btn-primary"
                                                    href="{{ route('emd_view_and_add_pricing_plan_allow', ['plan_id' => $item->id]) }}">
                                                    Set Query
                                                </a>
                                            @endcan
                                        </td>
                                        <td class="actioncell">
                                            @can('add_pricing_plan')
                                                <a class="btn table-btn btn-secondary"
                                                    href="{{ route('emd_view_and_add_zone_pricing', ['plan_id' => $item->id]) }}">
                                                    Set Zone Price
                                                </a>
                                            @endcan
                                        </td>
                                        <td class="actioncell">
                                            @can('delete_pricing_plan')
                                                <form action="{{ route('emd_pricing_plan_destroy', ['id' => $item->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn table-btn btn-danger">Trash</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <th>Plan not Available</th>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div> <!-- end card body-->
                </div>
            @endcan
            @can('view_custom_pricing_plan')
                <h3>Custom Pricing Plans</h3>
                <div class="card">
                    <div class="card-body p-0">
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
                                    {{-- <th>Order No</th> --}}
                                    <th>Active</th>
                                    <th class="actioncell"></th>
                                    <th class="actioncell"></th>
                                    <th class="actioncell"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($custom_pricing_plans as $item)
                                    <tr style="color: {{ $item->is_popular ? 'green' : '' }}">
                                        <td class="sr">{{ $loop->iteration }}</td>
                                        <td>
                                            @can('edit_pricing_plan')
                                                <a href="{{ route('emd_pricing_plan_edit', ['id' => $item->id]) }}">
                                                    {{ $item->name }}
                                                </a>
                                            @else
                                                {{ $item->name }}
                                            @endcan
                                            <br> <b><i>{{ @$item::PLAN_TYPE[$item->plan_type] }}</i></b>
                                        </td>
                                        <td>{{ $item->sale_price }}</td>
                                        <td>{{ $item->discount_percentage }}%</td>
                                        <td>{{ $item->duration }} Days</td>
                                        <td>{{ App\Models\EmdPricingPlan::IS_API[$item->is_api] }}</td>
                                        <td>{{ $item->recurring_detail }}</td>
                                        <td>{{ $item->emd_pricing_plan_allows_sum_queries_limit }}</td>
                                        {{-- <td>
                                            @can('edit_pricing_plan')
                                                <select class="form-control ordering_no" id="{{ $item->id }}">
                                                    @for ($start_no = 1; $start_no <= count($emd_pricing_plans->where('is_custom', 0)); $start_no++)
                                                        <option value="{{ $start_no }}"
                                                            {{ $start_no == $item->ordering_no ? 'selected' : '' }}>
                                                            {{ $start_no }}</option>
                                                    @endfor
                                                </select>
                                            @endcan
                                        </td> --}}
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" id="{{ $item->id }}"
                                                    {{ $item->is_active ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td class="actioncell">
                                            @can('add_pricing_plan')
                                                <a class="btn table-btn btn-primary"
                                                    href="{{ route('emd_view_and_add_pricing_plan_allow', ['plan_id' => $item->id]) }}">
                                                    Set Query
                                                </a>
                                            @endcan
                                        </td>
                                        <td class="actioncell">
                                            @can('add_pricing_plan')
                                                <a class="btn table-btn btn-secondary"
                                                    href="{{ route('emd_view_and_add_zone_pricing', ['plan_id' => $item->id]) }}">
                                                    Set Zone Price
                                                </a>
                                            @endcan
                                        </td>
                                        <td class="actioncell">
                                            @can('delete_pricing_plan')
                                                <form action="{{ route('emd_pricing_plan_destroy', ['id' => $item->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn table-btn btn-danger">Trash</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <th>Plan not Available</th>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div> <!-- end card body-->
                </div>
            @endcan
            @can('view_dynamic_pricing_plan')
                <h3>Dynamic Pricing Plans</h3>
                <div class="card">
                    <div class="card-body p-0">
                        <table id="blog_table" class="table table-borderless w-100 emd-table1">
                            <thead>
                                <tr>
                                    <th class="sr">Sr.</th>
                                    <th>Name</th>
                                    <th>Paypro Product ID</th>
                                    <th>Key</th>
                                    <th>IV</th>
                                    <th class="actioncell"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dynamic_pricing_plans as $item)
                                    <tr style="color: {{ $item->is_popular ? 'green' : '' }}">
                                        <td class="sr">{{ $loop->iteration }}</td>
                                        <td>
                                            @can('edit_pricing_plan')
                                                <a href="{{ route('emd_pricing_plan_edit', ['id' => $item->id]) }}">
                                                    {{ $item->name }}
                                                </a>
                                            @else
                                                {{ $item->name }}
                                            @endcan
                                        </td>
                                        <td>{{ $item->paypro_product_id }}</td>
                                        <td>{{ substr(md5(config('constants.emd_paypro_dynamic_plan_key') . '-key'), 0, 32) }}
                                        </td>
                                        <td>{{ substr(md5(config('constants.emd_paypro_dynamic_plan_key') . '-iv'), 0, 16) }}
                                        </td>
                                        <td class="actioncell">
                                            @can('delete_pricing_plan')
                                                <form action="{{ route('emd_pricing_plan_destroy', ['id' => $item->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn table-btn btn-danger">Trash</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <th>Plan not Available</th>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div> <!-- end card body-->
                </div>
            @endcan
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $(".ordering_no").change(function() {
                var id = $(this).attr("id");
                var ordering_no = $(this).val();
                $.ajax({
                    url: "{{ route('emd_pricing_plan_ordering_no') }}",
                    method: 'POST',
                    data: {
                        id: id,
                        ordering_no: ordering_no,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        window.location.reload();
                    }
                });
            });
            var checkbox = $('input[type="checkbox"]');
            checkbox.on('change', function() {
                var isChecked = $(this).prop('checked');
                var iid = $(this).attr('id');
                if (isChecked) {
                    window.location.href = '/admin/emd-pricing-plan/active/' + iid + "/1";
                } else {
                    window.location.href = '/admin/emd-pricing-plan/active/' + iid + "/0";
                }
            });
        });
    </script>
@endsection
