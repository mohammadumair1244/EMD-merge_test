<?php

namespace App\Http\Controllers;

use App\Models\EmdPricingPlan;
use App\Models\EmdUserTransaction;
use App\Models\EmdWebUser;
use App\Repositories\EmdUserTransactionRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Imdhemy\Purchases\Facades\Subscription;

class EmdMobilePaymentRenewalController extends Controller
{
    public function mobile_payment(Request $request)
    {
        $credentials = $request->only('pid', 'user_id', 'server_token', 'order_id', 'payment_method_name');
        $validator = Validator::make($credentials, [
            'pid' => 'required',
            'user_id' => 'required',
            'server_token' => 'required',
            'order_id' => 'required',
            'payment_method_name' => 'required|in:Ios,Android',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => true, 'mess' => $validator->errors()->first()], 401);
        }

        $payment_from_mobile = @$request->input('payment_method_name');
        $server_token = $request->input('server_token');
        $order_id = $request->input('order_id');
        $pid = $request->input('pid');

        if ($payment_from_mobile == "Ios") {
            try {
                $receiptResponse = Subscription::appStore()->receiptData($server_token)->verifyReceipt();
                // $receiptResponse_array = $receiptResponse->toArray();
                // if ($receiptResponse_array['latest_receipt'] != $server_token) {
                //     return response()->json(['error' => false, 'mess' => 'Faked Receipt']);
                // }
            } catch (\Throwable $th) {
                return response()->json(['error' => false, 'mess' => 'Faked Receipt Catch']);
            }
        } else if ($payment_from_mobile == "Android") {
            try {
                $subscriptionReceipt = Subscription::googlePlay()->id($pid)->token($server_token)->get();
                $subscriptionReceipt_array = $subscriptionReceipt->toArray();
                if (@$subscriptionReceipt_array['orderId'] == null || @$subscriptionReceipt_array['orderId'] == "") {
                    return response()->json(['error' => false, 'mess' => 'Faked Receipt']);
                }
            } catch (\Throwable $th) {
                return response()->json(['error' => false, 'mess' => 'Faked Receipt Catch']);
            }
        } else {
            return response()->json(['error' => false, 'mess' => 'Payment method from invalid']);
        }

        $emd_pricing_plan = EmdPricingPlan::where('mobile_app_product_id', $pid)->first();
        if ($emd_pricing_plan == null) {
            return response()->json(['error' => false, 'mess' => 'PID not available']);
        }
        $user_id = (int) $request->input('user_id');
        $user = EmdWebUser::where('user_id', $user_id)->first();
        if ($user) {
            $user->server_token = $request->input('server_token');
            $user->save();
        } else {
            return response()->json(['error' => false, 'mess' => 'User id Invalid']);
        }
        $data = [];
        $data['id'] = $emd_pricing_plan->id;
        $data['order_status'] = EmdUserTransaction::MOBILE_SIDE_PURCHASE;
        $data['order_no'] = $order_id;
        $data['payment_from'] = $payment_from_mobile;
        $data['is_test_mode'] = EmdUserTransaction::ORIGINAL_MODE;
        $data['order_currency_code'] = @$request->input('order_currency_code');
        $data['order_item_price'] = @$request->input('order_item_price');
        $data['renewal_type'] = "M-" . @$request->input('subscription_renewal_type');
        $trans_status = EmdUserTransactionRepository::assign_plan_to_user(request: $data, user_id: $user_id, plan_id: true);
        if ($trans_status) {
            return response()->json(['error' => false, 'mess' => 'Successfully Premium']);
        } else {
            return response()->json(['error' => false, 'mess' => 'Product ID not found']);
        }
    }

    public static function GoogleSubscriptionRenewal($pid, $server_token)
    {
        $emd_pricing_plan = EmdPricingPlan::where('mobile_app_product_id', $pid)->first();
        if ($emd_pricing_plan == null) {
            return response()->json(['error' => false, 'mess' => 'PID not available']);
        }
        $emd_web_user_get = EmdWebUser::where('server_token', $server_token)->first();
        if ($emd_web_user_get) {
            $user_id = $emd_web_user_get->user_id;
            $data = [];
            $data['id'] = $emd_pricing_plan->id;
            $data['order_status'] = EmdUserTransaction::MOBILE_SIDE_PURCHASE;
            $data['order_no'] = "M-G" . time();
            $data['payment_from'] = "Android";
            $data['is_test_mode'] = EmdUserTransaction::ORIGINAL_MODE;
            $trans_status = EmdUserTransactionRepository::assign_plan_to_user(request: $data, user_id: $user_id, plan_id: true);
            if ($trans_status) {
                return response()->json(['error' => false, 'mess' => 'Successfully Premium']);
            } else {
                return response()->json(['error' => false, 'mess' => 'Product ID not found']);
            }
        } else {
            return response()->json(['error' => false, 'mess' => 'Server Token Invalid']);
        }
    }

    public static function AppleSubscriptionRenewal($pid, $server_token)
    {
        $emd_pricing_plan = EmdPricingPlan::where('mobile_app_product_id', $pid)->first();
        if ($emd_pricing_plan == null) {
            return response()->json(['error' => false, 'mess' => 'PID not available']);
        }
        $transaction = EmdUserTransaction::where('order_no', $server_token)->first();
        if ($transaction) {
            $user_id = $transaction->user_id;
            $data = [];
            $data['id'] = $emd_pricing_plan->id;
            $data['order_status'] = EmdUserTransaction::MOBILE_SIDE_PURCHASE;
            $data['order_no'] = "M-A" . time();
            $data['payment_from'] = "Ios";
            $data['is_test_mode'] = EmdUserTransaction::ORIGINAL_MODE;
            $trans_status = EmdUserTransactionRepository::assign_plan_to_user(request: $data, user_id: $user_id, plan_id: true);
            if ($trans_status) {
                return response()->json(['error' => false, 'mess' => 'Successfully Premium']);
            } else {
                return response()->json(['error' => false, 'mess' => 'Product ID not found']);
            }
        } else {
            return response()->json(['error' => false, 'mess' => 'Server Token Invalid']);
        }
    }
}
