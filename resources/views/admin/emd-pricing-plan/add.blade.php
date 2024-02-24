@extends('admin')
@section('head')
    <style>
        .hide_this {
            display: none;
        }
    </style>
@endsection
@section('content')
    <div class="row mt-4">

        <div class="card">
            <div class="card-body pt-0">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="message">

                </div>
                <h4 class="my-3">Basic Information:</h4>
                <form id="blog_form" action="{{ route('emd_pricing_plan_create') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="ex: Standard Plan"
                                value="" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Label</label>
                            <input type="text" name="label" class="form-control" placeholder="Label" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Short Detail</label>
                            <input type="text" name="short_detail" class="form-control" placeholder="Short Detail"
                                value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="blog" class="form-label">Plan Type</label>
                            <select class="form-select" id="" name="plan_type">
                                @foreach (App\Models\EmdPricingPlan::PLAN_TYPE as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Recurring Detail</label>
                            <input type="text" name="recurring_detail" class="form-control"
                                placeholder="ex: Billed after 2 month" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Price</label>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="Price"
                                value="" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Sale Price</label>
                            <input type="number" step="0.01" name="sale_price" class="form-control"
                                placeholder="Sale Price" value="" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Discount in Percentage</label>
                            <input type="number" name="discount_percentage" class="form-control" placeholder="ex: 30"
                                value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="" class="form-label">Plan Duration (in Days)</label>
                            <input type="number" name="duration" class="form-control"
                                placeholder="ex: 7, 30, 60, 90, 180, 365" value="" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="blog" class="form-label">Duration Type</label>
                            <select class="form-select" id="" name="duration_type">
                                @foreach (App\Models\EmdPricingPlan::DURATION_TYPE as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class=" col-md-6 mb-3">
                            <label for="contact" class="form-label">Paypro Coupan Code</label>
                            <input class="form-control" name="coupan_paypro"
                                id="ex: paypro copupan code while will be use in product link" />
                        </div>
                        <div class=" col-md-6 mb-3">
                            <label for="contact" class="form-label">Paypro Product Id</label>
                            <input class="form-control" id="paypro_product_id" name="paypro_product_id"
                                placeholder="ex: paypro product id :  80121" required="" />
                        </div>
                        <div class=" col-md-6 mb-3">
                            <label for="contact" class="form-label">Mobile App Product Id (PID)</label>
                            <input class="form-control" id="mobile_app_product_id" name="mobile_app_product_id"
                                placeholder="ex: mobile app product id :  com.enzipe.package.weekly" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="blog" class="form-label">For (Web or API)</label>
                            <select class="form-select" id="" name="is_api">
                                @foreach (App\Models\EmdPricingPlan::IS_API as $key => $val)
                                    <option value="{{ $key }}">{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="blog" class="form-label">Popular</label>
                            <select class="form-select" id="" name="is_popular">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        <div class=" col-md-6 mb-3">
                            <label for="contact" class="form-label">Unique Key</label>
                            <input class="form-control" name="unique_key" placeholder="ex: standard_plan" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="blog" class="form-label">Category</label>
                            <select class="form-select" id="custom_type_change" name="is_custom">
                                <option value="{{ App\Models\EmdPricingPlan::SIMPLE_PLAN }}">
                                    {{ App\Models\EmdPricingPlan::CUSTOM_TYPE[0] }}</option>
                                @can('add_custom_pricing_plan')
                                    <option value="{{ App\Models\EmdPricingPlan::CUSTOM_PLAN }}">
                                        {{ App\Models\EmdPricingPlan::CUSTOM_TYPE[1] }}</option>
                                @endcan
                                @can('add_dynamic_pricing_plan')
                                    <option value="{{ App\Models\EmdPricingPlan::DYNAMIC_PLAN }}">
                                        {{ App\Models\EmdPricingPlan::CUSTOM_TYPE[2] }}</option>
                                @endcan
                                <option value="{{ App\Models\EmdPricingPlan::REGISTERED_PLAN }}">
                                    {{ App\Models\EmdPricingPlan::CUSTOM_TYPE[4] }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="blog" class="form-label">Only Mobile Plan</label>
                            <select class="form-select" id="is_mobile" name="is_mobile">
                                @foreach (App\Models\EmdPricingPlan::MOBILE_OR_WEB as $key => $val)
                                    <option value="{{ $key }}">{{ $key == 0 ? 'No' : 'Yes' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="paypro_key hide_this">
                                <b>Key: </b>
                                {{ substr(md5(config('constants.emd_paypro_dynamic_plan_key') . '-key'), 0, 32) }}<br>
                                <b>IV: </b>
                                {{ substr(md5(config('constants.emd_paypro_dynamic_plan_key') . '-iv'), 0, 16) }}
                            </div>
                        </div>
                    </div>
                    <div style="text-align: right">
                        @can('add_pricing_plan')
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                Submit
                            </button>
                        @endcan
                    </div>
                </form>
            </div> <!-- end card-body -->
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#custom_type_change").change(function() {
                var type = parseInt($(this).val());
                if (type == 2) {
                    $(".paypro_key").removeClass("hide_this");
                }
            });
            $("#is_mobile").change(function() {
                var type = parseInt($(this).val());
                if (type == 0) {
                    $("#paypro_product_id").attr("required", true);
                    $("#mobile_app_product_id").removeAttr("required");
                } else {
                    $("#paypro_product_id").removeAttr("required");
                    $("#mobile_app_product_id").attr("required", true);
                }
            });
        });
    </script>
@endsection
