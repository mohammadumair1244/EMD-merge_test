<?php

namespace App\Http\Middleware;

use App\Models\EmdPricingPlan;
use App\Models\EmdUserTransaction;
use App\Models\EmdUserTransactionAllow;
use App\Models\EmdWebUser;
use Closure;
use Illuminate\Http\Request;

class EmdWebsiteModesCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->guard('web_user_sess')->check()) {
            $user_id = auth()->guard('web_user_sess')->id();
            $is_web_premium = EmdWebUser::where('user_id', $user_id)->where('is_web_premium', EmdWebUser::PREMIUM_USER)->first();
            if ($is_web_premium) {
                $is_available_plan = EmdUserTransactionAllow::whereHas('emd_user_transaction', function ($query) {
                    return $query->whereHas('emd_pricing_plan', function ($query1) {
                        return $query1->where('is_api', '!=', EmdPricingPlan::API_PLAN);
                    })->where('expiry_date', '>=', date("Y-m-d"))->where('is_refund', EmdUserTransaction::TRAN_RUNNING)->where('order_status', EmdUserTransaction::OS_PROCESSED);
                });
                if (@$request->has('tool_id')) {
                    $is_available_plan = $is_available_plan->where('tool_id', @$request->tool_id ?: 0);
                }
                $is_available_plan = $is_available_plan->where('user_id', $user_id)->whereColumn('queries_limit', '>=', 'queries_used')->orderBy('allow_modes', 'DESC')->first();
                if ($is_available_plan) {
                    if ($is_available_plan->allow_modes >= @$request->modes) {
                        return $next($request);
                    } else {
                        return response()->json(['emd_middleware_mess' => 'not allow to this mode']);
                    }
                } else {
                    return response()->json(['emd_middleware_mess' => 'plan not available']);
                }
            }
        }
        return $next($request);
    }
}
