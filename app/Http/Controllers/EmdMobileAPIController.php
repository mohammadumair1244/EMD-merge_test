<?php

namespace App\Http\Controllers;

use App\Interfaces\EmdWebUserInterface;
use App\Models\EmdPricingPlan;
use App\Models\EmdUserTransaction;
use App\Models\EmdWebUser;
use App\Repositories\EmdWebUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class EmdMobileAPIController extends Controller
{
    public function __construct(protected EmdWebUserInterface $emd_web_user_interface)
    {
    }
    public function register(Request $request)
    {
        $credentials = $request->only('name', 'email', 'password');
        $validator = FacadesValidator::make($credentials, [
            'name' => 'required|min:3|string',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => true, 'mess' => $validator->errors()->first()], 401);
        }
        $register = $this->emd_web_user_interface->emd_register_with_web($request, '127.0.0.1', EmdWebUser::REGISTER_FROM_MOBILE);
        if ($register[0]) {
            return response()->json(['error' => false, 'mess' => $register[1], 'api_key' => $register[2]]);
        } else {
            return response()->json(['error' => true, 'mess' => $register[1], 'api_key' => null]);
        }
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $validator = FacadesValidator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => true, 'mess' => $validator->errors()->first()], 401);
        }
        $login = $this->emd_web_user_interface->emd_login_with_web($request, @$request->header()[config('constants.user_ip_get')][0] ?? '127.0.0.1', EmdWebUser::REGISTER_FROM_MOBILE);
        if ($login[0]) {
            return response()->json(['error' => false, 'mess' => $login[1], 'api_key' => $login[2]]);
        } else {
            return response()->json(['error' => true, 'mess' => $login[1], 'api_key' => null]);
        }
    }
    public function user_info(Request $request)
    {
        $credentials = $request->only('api_key');
        $validator = FacadesValidator::make($credentials, [
            'api_key' => 'required|min:10',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => true, 'mess' => $validator->errors()->first()], 401);
        }
        $user_info_get = $this->emd_web_user_interface->emd_user_info($request->api_key);
        if ($user_info_get) {
            $query_detail = EmdWebUserRepository::EmdAvailableQuery(separate: true, api_key: $request->api_key);
            $last_plan = EmdUserTransaction::whereHas('emd_pricing_plan', function ($query) {
                return $query->where('is_api', EmdPricingPlan::WEB_PLAN);
            })->where('user_id', $user_info_get->user_id)->where('order_status', EmdUserTransaction::OS_PROCESSED)->orderBy("expiry_date", "DESC")->first();
            $expiry_date_get = @$last_plan?->expiry_date;
            $plan_name = @$last_plan?->emd_pricing_plan?->name;
            $data = [
                'name' => $user_info_get?->user?->name ?? 'none',
                "email" => $user_info_get?->user?->email ?? 'none',
                "api_key" => $request->api_key,
                "is_web_premium" => $user_info_get->is_web_premium,
                "is_api_premium" => $user_info_get->is_api_premium,
                "social_id" => $user_info_get->social_id,
                "register_from" => $user_info_get->register_from,
                "query_used" => $query_detail[1],
                "query_limit" => $query_detail[0],
                "expiry_date" => $expiry_date_get,
                "plan" => $plan_name,
            ];
            return response()->json(['error' => false, 'mess' => 'Success', 'data' => $data]);
        } else {
            return response()->json(['error' => true, 'mess' => "Invalid User API KEY", 'data' => null]);
        }
    }
}
