<?php

namespace App\Http\Controllers;

use App\Interfaces\EmdUserTransactionInterface;
use App\Models\EmdTransactionLog;
use App\Repositories\EmdUserTransactionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmdUserTransactionController extends Controller
{
    public function __construct(protected EmdUserTransactionInterface $emd_user_transaction_interface)
    {
    }
    public function view_all_transaction($type = "Processed")
    {
        $this->authorize('view_all_transactions');
        return view('admin.emd-transaction.index')->with([
            'emd_transactions' => $this->emd_user_transaction_interface->view_all_transaction($type),
        ]);
    }
    public function view_single_transaction($id)
    {
        $this->authorize('view_transaction_detail');
        return view('admin.emd-transaction.detail')->with([
            'emd_transaction' => $this->emd_user_transaction_interface->view_single_transaction($id),
        ]);
    }
    public function emd_transaction_search_page()
    {
        $this->authorize('view_all_transactions');
        return view('admin.emd-transaction.search')->with(['transaction' => []]);
    }
    public function emd_transaction_search_req(Request $request)
    {
        $this->authorize('view_all_transactions');
        $transaction = $this->emd_user_transaction_interface->emd_transaction_search_req($request);
        return view('admin.emd-transaction.search')->with(['transaction' => $transaction]);
    }
    public function emd_transaction_date_filter_page($start_date, $end_date)
    {
        $this->authorize('view_all_transactions');
        $transaction = $this->emd_user_transaction_interface->emd_transaction_date_filter_page($start_date, $end_date);
        return view('admin.emd-transaction.date-filter')->with(['transaction' => $transaction]);
    }
    public function emd_paypro_callback(Request $request)
    {
        // $allow_paypro_ips = ["2604:a880:400:d1::b6c:c001", "2604:a880:400:d0::1843:7001", "198.199.123.239", "157.230.8.40"];
        $data['order_no'] = @$request?->ORDER_ID ?: 'E' . time();
        $data['trans_log'] = json_encode($request->all());
        $data['status_message'] = 'Pending...';
        $data['paypro_ip'] = @$request->ip() ?: '0.0.0.0';
        $trans_log = EmdTransactionLog::create($data);
        $required_fields = ['ORDER_ID', 'PRODUCT_ID', 'ORDER_STATUS', 'ORDER_ITEM_UNIT_PRICE', 'ORDER_CUSTOM_FIELDS', 'ORDER_CURRENCY_CODE', 'PAYMENT_METHOD_NAME'];
        $credentials = @$request->only($required_fields);
        $validator = Validator::make($credentials, [
            'ORDER_ID' => 'required|string|min:4|max:100',
            'PRODUCT_ID' => 'required|string|min:4|max:100',
            'ORDER_STATUS' => 'required|string|min:4|max:100',
            'ORDER_ITEM_UNIT_PRICE' => 'required|string',
            'ORDER_CUSTOM_FIELDS' => 'required|string|min:4|max:100',
            'ORDER_CURRENCY_CODE' => 'required|string|min:2|max:100',
            'PAYMENT_METHOD_NAME' => 'required|string|min:2|max:100',
        ]);
        if ($validator->fails()) {
            $errors_list = '';
            foreach ($required_fields as $val) {
                $error_get = @$validator->errors()?->get($val)[0] . "," ?: ',';
                if ($error_get != ",") {
                    $errors_list .= $val . " Field is required, ";
                }
            }
            $trans_log->status = 401;
            $trans_log->status_message = substr($errors_list, 0, -2);
            $trans_log->save();
            return response()->json(['message' => false], 422);
        }
        $paypro_callback_fun = $this->emd_user_transaction_interface->emd_paypro_callback($request);
        if ($paypro_callback_fun[0]) {
            $trans_log->status = 200;
            $trans_log->status_message = @$paypro_callback_fun[1] ?: 'Successfully Done';
            $trans_log->save();
            return response()->json(['message' => true, 'result' => $paypro_callback_fun[1]], 200);
        } else {
            $trans_log->status = 402;
            $trans_log->status_message = @$paypro_callback_fun[1] ?: 'something went wrong';
            $trans_log->save();
            return response()->json(['message' => false, 'result' => $paypro_callback_fun[1]], 422);
        }
    }
    public function emd_custom_premium(Request $request, $user_id)
    {
        $this->authorize('user_set_custom_premium');
        $this->emd_user_transaction_interface->emd_custom_premium($request, $user_id);
        return back()->with('error', "Successfully Add Plan & Premium");
    }
    public function emd_user_plan_change(Request $request, $user_id)
    {
        $this->authorize('change_user_plan');
        $this->emd_user_transaction_interface->emd_user_plan_change($request, $user_id);
        return back()->with('error', "Successfully Plan Change");
    }
    public static function EmdPayproDynamicPlanLink(float $price = 5, float $discount_per = 0, string $currency = 'USD', string $plan_title = null, string $plan_desc = null, int $web_api = 0, int $days = 7, array $plan_availabilities = [['tool_id' => 0, 'queries_limit' => 100, 'allow_json' => ['modes' => 3, 'words_limit' => 50]]]): array
    {
        return EmdUserTransactionRepository::EmdPayproDynamicPlanLink(price: $price, discount_per: $discount_per, currency: $currency, plan_title: $plan_title, plan_desc: $plan_desc, web_api: $web_api, days: $days, plan_availabilities: $plan_availabilities);
    }
    public function emd_transaction_without_original($type)
    {
        $this->authorize('view_all_transactions');
        return view('admin.emd-transaction.test')->with([
            'emd_transactions_test_register' => $this->emd_user_transaction_interface->emd_transaction_without_original($type),
        ]);
    }
}
