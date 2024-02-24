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

        /* ARAS Design */
        .user-info-title {
            display: flex;
            align-content: center;
            justify-content: space-between;
        }

        .user-info-title .user-id {
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
            background: #fff;
        }

        .user-info-card .name-sec {
            height: 40px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info-card .name {
            font-size: 24px;
            font-weight: bold;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background-color: blue;
            color: White;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 16px;
        }

        .user-info-card .premium-badge {
            padding: 2px 10px;
            font-size: 12px;
            border: 1px solid #ccc;
            border-radius: 20px;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (session()->has('error'))
                <p class="alert alert-blue">{{ session()->get('error') }}</p>
            @endif
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-8">
            @if (!$emd_web_user)
                @dd('User not available')
            @endif
            @php
                $for_web_transactions = $user_transactions
                    ->where('emd_pricing_plan.is_api', 0)
                    ->where('is_refund', App\Models\EmdUserTransaction::TRAN_RUNNING)
                    ->where('order_status', App\Models\EmdUserTransaction::OS_PROCESSED)
                    ->where('expiry_date', '>=', date('Y-m-d'));
                $for_api_transactions = $user_transactions
                    ->where('emd_pricing_plan.is_api', 1)
                    ->where('is_refund', App\Models\EmdUserTransaction::TRAN_RUNNING)
                    ->where('order_status', App\Models\EmdUserTransaction::OS_PROCESSED)
                    ->where('expiry_date', '>=', date('Y-m-d'));
                $for_web_api_transactions = $user_transactions
                    ->where('emd_pricing_plan.is_api', 2)
                    ->where('is_refund', App\Models\EmdUserTransaction::TRAN_RUNNING)
                    ->where('order_status', App\Models\EmdUserTransaction::OS_PROCESSED)
                    ->where('expiry_date', '>=', date('Y-m-d'));
            @endphp
            <div class="user-info-title mb-2">
                <div>
                    <h3 class="d-inline-block m-0">User Information</h3>
                    <small for="" class="user-id">#{{ request()->route('id') }}</small>
                </div>
                <div>
                    @can('login_user_as')
                        <a class="btn btn-secondary"
                            href="{{ route('user_login_by_admin', ['id' => @$emd_web_user?->user_id ?? 0]) }}"
                            target="_blank">Login
                            as</a>
                    @endcan
                </div>
            </div>
            <div class="card user-info-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-1">
                            <span
                                class="user-avatar">{{ Str::upper(@explode(' ', @$emd_web_user->user->name)[0][0] ?: 'N' . @explode(' ', @$emd_web_user->user->name)[1][0] ?: 'N') }}</span>
                        </div>

                        <div class="col-8">
                            <div class="name-sec">
                                <span class="name">{{ @$emd_web_user->user->name }}</span>
                                <span class="premium-badge">
                                    Web {{ App\Models\EmdWebUser::PREMIUM_TYPE[$emd_web_user->is_web_premium] }},
                                    API {{ App\Models\EmdWebUser::PREMIUM_TYPE[$emd_web_user->is_api_premium] }}
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table-borderless">
                                        <tr>
                                            <td>Email:</td>
                                            <td>{{ @$emd_web_user->user->email }}</td>
                                        </tr>
                                        <tr>
                                            <td>API Key</td>
                                            <td>{{ @$emd_web_user->api_key }}</td>
                                        </tr>
                                        <tr>
                                            <td>Register At</td>
                                            <td>{{ Carbon\Carbon::parse(@$emd_web_user->user->created_at)->format('d M Y') }}
                                                <label
                                                    class="badge badge-secondary bg-secondary">{{ @$emd_web_user->register_from }}</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Social ID:</td>
                                            <td>{{ @$emd_web_user->social_id }}</td>
                                        </tr>
                                        <tr>
                                            <td>Country</td>
                                            <td>{{ @$emd_web_user->country }}</td>
                                        </tr>
                                        <tr>
                                            <td>Register Ip</td>
                                            <td>{{ @$emd_web_user->ip }}</td>
                                        </tr>
                                        <tr>
                                            <td>Web Query</td>
                                            <td>
                                                {{ $for_web_transactions->sum('emd_user_transaction_allows_sum_queries_used') }}
                                                /
                                                {{ $for_web_transactions->sum('emd_user_transaction_allows_sum_queries_limit') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>API Query</td>
                                            <td>
                                                {{ $for_api_transactions->sum('emd_user_transaction_allows_sum_queries_used') }}
                                                /
                                                {{ $for_api_transactions->sum('emd_user_transaction_allows_sum_queries_limit') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Web & API Query</td>
                                            <td>
                                                {{ $for_web_api_transactions->sum('emd_user_transaction_allows_sum_queries_used') }}
                                                /
                                                {{ $for_web_api_transactions->sum('emd_user_transaction_allows_sum_queries_limit') }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-3 d-flex align-items-center">
                            <small>last modified:
                                {{ Carbon\Carbon::parse(@$emd_web_user->updated_at)->format('d M Y') }}</small>
                        </div>
                    </div>
                </div>
            </div>
            <h3 class="">Current Memberships</h3>
            @php
                $active_user_transactions = $user_transactions
                    ->where('expiry_date', '>=', date('Y-m-d'))
                    ->where('is_refund', App\Models\EmdUserTransaction::TRAN_RUNNING)
                    ->where('order_status', App\Models\EmdUserTransaction::OS_PROCESSED);
            @endphp
            @forelse($active_user_transactions as $active_plan)
                <div class="card">
                    <div class="card-body membership-card">
                        <div class="head">
                            <span class="plan-name">{{ @$active_plan->emd_pricing_plan->name }}<label
                                    class="badge {{ $active_plan->renewal_type == App\Models\EmdUserTransaction::RENEWAL_AUTO ? 'badge-warning bg-warning' : 'badge-info bg-info' }}">{{ $active_plan->renewal_type }}</label></span>
                            <span class="pricing-duration">{{ @$active_plan->order_item_price }}
                                {{ @$active_plan->order_currency_code }} | {{ $active_plan->plan_days }} Days</span>
                        </div>
                        <div class="dates mt-2">
                            <b>Purchase At:</b>
                            {{ Carbon\Carbon::parse(@$active_plan->purchase_date)->format('d M Y') }}<span
                                class="left"></span><br>
                            <b>Expire At:</b>
                            {{ Carbon\Carbon::parse(@$active_plan->expiry_date)->format('d M Y') }}<span class="left">
                                {{ Carbon\Carbon::parse(date('Y-m-d'))->diffInDays(Carbon\Carbon::parse(@$active_plan->expiry_date)->format('Y-m-d')) }}
                                days
                                left</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card">
                    <div class="card-body membership-card">
                        <div class="head">Not Available</div>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="col-4">
            @can('user_delete')
                <div class="col-md-12">
                    @if (@!$emd_web_user->is_web_premium && @!$emd_web_user->is_api_premium)
                        <form action="{{ route('emd_deactive_user_account', ['user_id' => @$emd_web_user?->user_id ?? 0]) }}"
                            method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <textarea name="detail" rows="1" class="form-control" @required(true)
                                        placeholder="Write the reason for this action"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-danger">DeActive</button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            @endcan
            @can('view_user_comment')
                <h3>Comments</h3>
                <div class="card" style="height: 400px; overflow-y: scroll;">
                    <div class="card-body">
                        @forelse ($emd_user_profile_comments as $emd_user_profile_comment)
                            <div class="user-comment">
                                <div class="head">{{ @$emd_user_profile_comment?->action_user?->name }}</div>
                                <b>{{ @$emd_user_profile_comment?->action_type }}</b>
                                <p>{{ @$emd_user_profile_comment?->detail }}</p>
                                <span
                                    class="date">{{ Carbon\Carbon::parse(@$emd_user_profile_comment->created_at)->format('d M Y h:m A') }}</span>
                            </div>
                        @empty
                            <div class="user-comment">
                                Not Available
                            </div>
                        @endforelse
                    </div>
                </div>
            @endcan
        </div>
        @can('view_user_transaction')
            <h3>({{ @$emd_web_user->user->name }}) All Transaction</h3>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <table id="users_table" class="table dt-responsive nowrap w-100  order-column">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Order#</th>
                                        <th>Plan / Type</th>
                                        <th>Price</th>
                                        <th>Duration</th>
                                        <th>Purchase - Expiry</th>
                                        <th>Refund</th>
                                        <th>Renewal</th>
                                        <th>Payment Mode</th>
                                        <th>Queries</th>
                                        <th>Used Queries</th>
                                        <th>Uses %</th>
                                        <th>Available</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (@$user_transactions as $item)
                                        <tr
                                            class="{{ $item->expiry_date >= date('Y-m-d') ? ($item->is_refund == App\Models\EmdUserTransaction::TRAN_REFUNDED ? 'refund-plan' : '') : 'expire-plan' }} {{ @$item->is_test_mode != 0 ? 'test-mode' : '' }}">
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
                                            <td>{{ @$item->emd_pricing_plan->name }} /
                                                ({{ App\Models\EmdPricingPlan::IS_API[(int) @$item->emd_pricing_plan->is_api] }})
                                            </td>
                                            <td>{{ @$item->order_item_price }} {{ @$item->order_currency_code }}</td>
                                            <td>{{ @$item->plan_days }} Days</td>
                                            <td>{{ Carbon\Carbon::parse(@$item->purchase_date)->format('d M Y') }} -
                                                {{ Carbon\Carbon::parse(@$item->expiry_date)->format('d M Y') }}</td>
                                            <td>{{ App\Models\EmdUserTransaction::TRAN_STATUS[(int) @$item->is_refund] }}
                                            </td>
                                            <td>{{ @$item->renewal_type }}</td>
                                            <td>{{ App\Models\EmdUserTransaction::TEST_MODE_TYPE[$item->is_test_mode] }}</td>
                                            <td>{{ @$item->emd_user_transaction_allows_sum_queries_limit }}</td>
                                            <td>{{ @$item->emd_user_transaction_allows_sum_queries_used }}</td>
                                            <td>{{ round(($item->emd_user_transaction_allows_sum_queries_used * 100) / ($item->emd_user_transaction_allows_sum_queries_limit ?: 1), 2) }}%
                                            </td>
                                            <td>
                                                @can('view_user_query_detail')
                                                    <a class="btn btn-primary"
                                                        href="{{ route('emd_query_availability', ['transaction_id' => $item->id]) }}">
                                                        Avail
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
            </div>
        @endcan
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h4>Update Info</h4>
                        </div>
                    </div>
                    <form action="{{ route('emd_update_user_info', ['user_id' => request()->route('id')]) }}"
                        method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <b>Name</b>
                                <input type="text" name="name" value="{{ @$emd_web_user->user->name }}"
                                    class="form-control" @required(true)>
                                <label for="">Comment</label>
                                <textarea name="detail" rows="1" class="form-control" @required(true)
                                    placeholder="Write the reason for this action"></textarea>
                            </div>
                            <div class="col-md-6">
                                <b>Email</b>
                                <input type="email" name="email" value="{{ @$emd_web_user->user->email }}"
                                    class="form-control" @required(true)>
                                <br>
                                @can('user_update')
                                    <button class="btn btn-primary" type="submit">Update Profile</button>
                                @endcan
                            </div>
                        </div>
                    </form>
                    <h4>Set Custom Setting</h4>
                    <div class="row">
                        @can('user_set_custom_premium')
                            <div class="col-md-3">
                                <form action="{{ route('emd_custom_premium', ['user_id' => request()->route('id')]) }}"
                                    method="POST">
                                    @csrf
                                    <label for="">Select Plan</label>
                                    <select name="product_no" class="form-control" id="">
                                        @foreach ($emd_pricing_plans as $emd_pricing_plan)
                                            <option value="{{ $emd_pricing_plan->paypro_product_id }}">
                                                {{ $emd_pricing_plan->name }}
                                                ({{ App\Models\EmdPricingPlan::IS_API[$emd_pricing_plan->is_api] }})
                                                @if (@$emd_pricing_plan->is_custom == App\Models\EmdPricingPlan::CUSTOM_PLAN)
                                                    {Custom}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="">Comment</label>
                                    <textarea name="detail" rows="1" class="form-control" @required(true)
                                        placeholder="Write the reason for this action"></textarea>
                                    <br>
                                    <button type="submit" class="btn btn-info">Set Premium</button>
                                </form>
                            </div>
                        @endcan
                        @can('update_user_password')
                            <div class="col-md-3">
                                <form action="{{ route('emd_change_user_password', ['user_id' => request()->route('id')]) }}"
                                    method="POST">
                                    @csrf
                                    <label for="">Set New Password</label>
                                    <input type="text" name="password" value="{{ substr(md5(time()), 0, 10) }}"
                                        class="form-control" required="" minlength="8">
                                    <label for="">Comment</label>
                                    <textarea name="detail" rows="1" class="form-control" @required(true)
                                        placeholder="Write the reason for this action"></textarea>
                                    <br>
                                    <button type="submit" class="btn btn-warning">Update Password</button>
                                </form>
                            </div>
                        @endcan
                        @can('user_add_web_query')
                            @if (@count($for_web_transactions) > 0 && $emd_web_user->is_web_premium)
                                <div class="col-md-3">
                                    <form
                                        action="{{ route('emd_add_more_user_query', ['user_id' => request()->route('id')]) }}"
                                        method="post">
                                        @csrf
                                        <input type="hidden" value="0" name="is_web_or_api">
                                        <label for="">Add Query Limit (Web)</label>
                                        <input type="number" placeholder="Web Query" name="queries" class="form-control"
                                            @required(true)>
                                        <label for="">Comment</label>
                                        <textarea name="detail" rows="1" class="form-control" @required(true)
                                            placeholder="Write the reason for this action"></textarea>
                                        <label for="">Expiry Date</label>
                                        <input type="date"
                                            value="{{ @$for_web_transactions->sortByDesc('expiry_date')->first()?->expiry_date ?: date('Y-m-d') }}"
                                            name="expiry_date" class="form-control">
                                        <br>
                                        <button class="btn btn-primary" type="submit">Update Query & Expiry Date</button>
                                    </form>
                                </div>
                            @endif
                        @endcan
                        @can('user_add_api_query')
                            @if (@count($for_api_transactions) > 0 && $emd_web_user->is_api_premium)
                                <div class="col-md-3">
                                    <form
                                        action="{{ route('emd_add_more_user_query', ['user_id' => request()->route('id')]) }}"
                                        method="post">
                                        @csrf
                                        <input type="hidden" value="1" name="is_web_or_api">
                                        <label for="">Add Query Limit (API)</label>
                                        <input type="number" placeholder="API Query" name="queries" class="form-control"
                                            @required(true)>
                                        <label for="">Comment</label>
                                        <textarea name="detail" rows="1" class="form-control" @required(true)
                                            placeholder="Write the reason for this action"></textarea>
                                        <label for="">Expiry Date</label>
                                        <input type="date"
                                            value="{{ @$for_api_transactions->sortByDesc('expiry_date')->first()?->expiry_date ?: date('Y-m-d') }}"
                                            name="expiry_date" class="form-control">
                                        <br>
                                        <button class="btn btn-primary" type="submit">Update Query & Expiry Date</button>
                                    </form>
                                </div>
                            @endif
                        @endcan
                        @can('user_add_web_api_query')
                            @if (@count($for_web_api_transactions) > 0 && $emd_web_user->is_web_premium && $emd_web_user->is_api_premium)
                                <div class="col-md-3">
                                    <form
                                        action="{{ route('emd_add_more_user_query', ['user_id' => request()->route('id')]) }}"
                                        method="post">
                                        @csrf
                                        <input type="hidden" value="2" name="is_web_or_api">
                                        <label for="">Add Query Limit (Web & API)</label>
                                        <input type="number" placeholder="Web & API Query" name="queries"
                                            class="form-control" @required(true)>
                                        <label for="">Comment</label>
                                        <textarea name="detail" rows="1" class="form-control" @required(true)
                                            placeholder="Write the reason for this action"></textarea>
                                        <label for="">Expiry Date</label>
                                        <input type="date"
                                            value="{{ @$for_web_api_transactions->sortByDesc('expiry_date')->first()?->expiry_date ?: date('Y-m-d') }}"
                                            name="expiry_date" class="form-control">
                                        <br>
                                        <button class="btn btn-primary" type="submit">Update Query & Expiry Date</button>
                                    </form>
                                </div>
                            @endif
                        @endcan
                        @can('change_user_plan')
                            @if (count($emd_user_active_plans) > 0 && ($emd_web_user->is_web_premium || $emd_web_user->is_api_premium))
                                <div class="col-md-3">
                                    <form action="{{ route('emd_user_plan_change', ['user_id' => request()->route('id')]) }}"
                                        method="post">
                                        @csrf
                                        <label for=""> Change Plan (Current Active Plans)</label>
                                        <select name="order_no" id="" class="form-control">
                                            @foreach ($emd_user_active_plans as $emd_user_active_plan)
                                                <option value="{{ $emd_user_active_plan->order_no }}">
                                                    {{ $emd_user_active_plan->emd_pricing_plan->name . ' (' . $emd_user_active_plan->order_no . ')' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="">Select Plan</label>
                                        <select name="product_no" class="form-control" id="">
                                            @foreach ($emd_pricing_plans as $emd_pricing_plan)
                                                <option value="{{ $emd_pricing_plan->paypro_product_id }}">
                                                    {{ $emd_pricing_plan->name }}
                                                    ({{ App\Models\EmdPricingPlan::IS_API[$emd_pricing_plan->is_api] }})
                                                    @if (@$emd_pricing_plan->is_custom)
                                                        {Custom}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="">Comment</label>
                                        <textarea name="detail" rows="1" class="form-control" @required(true)
                                            placeholder="Write the reason for this action"></textarea>
                                        <br>
                                        <button class="btn btn-warning" type="submit">Change Plan</button>
                                    </form>
                                </div>
                            @endif
                        @endcan
                    </div>
                </div> <!-- end card body-->
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
