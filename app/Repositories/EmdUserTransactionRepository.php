<?php
namespace App\Repositories;

use App\Http\Controllers\EmdUserProfileCommentController;
use App\Interfaces\EmdUserTransactionInterface;
use App\Models\EmdPricingPlan;
use App\Models\EmdPricingPlanAllow;
use App\Models\EmdUserTransaction;
use App\Models\EmdUserTransactionAllow;
use App\Models\EmdWebUser;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EmdUserTransactionRepository implements EmdUserTransactionInterface
{
    public function __construct(
        protected EmdUserTransaction $emd_user_transaction_model,
        protected EmdPricingPlan $emd_pricing_plan_model,
        protected EmdWebUser $emd_web_user_model,
        protected EmdUserTransactionAllow $emd_user_transaction_allow_model,
        protected EmdPricingPlanAllow $emd_pricing_plan_allow_model,
        protected EmdUserProfileCommentController $emd_user_profile_comment_controller,
        protected User $user_model) {
    }

    public function view_all_transaction($type): LengthAwarePaginator
    {
        return $this->emd_user_transaction_model->with('user')->where('order_status', @$type)->orderByDESC('id')->paginate(100);
    }
    public function view_single_transaction($id): ?EmdUserTransaction
    {
        return $this->emd_user_transaction_model->where('id', $id)->first();
    }
    public function emd_transaction_search_req($request): EmdUserTransaction | Collection
    {
        return $this->emd_user_transaction_model->where('order_no', $request['order_no'])->get();
    }
    public static function EmdPayproDynamicPlanLink(float $price = 5, float $discount_per = 0, string $currency = 'USD', string $plan_title = null, string $plan_desc = null, int $web_api = 0, int $days = 7, array $plan_availabilities = [['tool_id' => 0, 'queries_limit' => 100, 'allow_json' => ['modes' => 3, 'words_limit' => 50]]]): array
    {
        if (!auth()->guard('web_user_sess')->check()) {
            return ['error' => true, 'mess' => config('emd-response-string.login_req_for_dynamic_plan')];
        }
        $emd_dynamic_plan = EmdPricingPlan::where('is_custom', EmdPricingPlan::DYNAMIC_PLAN)->first();
        if ($emd_dynamic_plan) {
            if ($plan_title == null) {
                $plan_title = $emd_dynamic_plan->name;
            }
        } else {
            return ['error' => true, 'mess' => config('emd-response-string.dynamic_plan_not_available')];
        }

        $add_plan['name'] = $plan_title;
        $add_plan['plan_type'] = 0;
        $add_plan['price'] = $price;
        $add_plan['sale_price'] = $price;
        $add_plan['discount_percentage'] = $discount_per;
        $add_plan['duration'] = $days;
        $add_plan['duration_type'] = 0;
        $add_plan['paypro_product_id'] = @$emd_dynamic_plan->paypro_product_id;
        $add_plan['is_api'] = $web_api;
        $add_plan['is_popular'] = 0;
        $add_plan['is_custom'] = EmdPricingPlan::USER_CREATED_PLAN;
        $plan_create = EmdPricingPlan::create($add_plan);

        foreach ($plan_availabilities as $plan_availability) {
            $req_tool_id = 0;
            if (@$plan_availability['tool_id'] > 0) {
                $tool_get = Tool::where('parent_id', $plan_availability['tool_id'])->first();
                if (@$tool_get) {
                    $req_tool_id = $tool_get->id;
                }
            }
            $add_plan_allow['emd_pricing_plan_id'] = $plan_create->id;
            $add_plan_allow['tool_id'] = $req_tool_id;
            $add_plan_allow['queries_limit'] = @$plan_availability['queries_limit'] ?: 100;
            $add_plan_allow['allow_modes'] = json_encode(@$plan_availability['allow_modes']) ?: json_encode([]);
            EmdPricingPlanAllow::create($add_plan_allow);
        }
        return ['error' => false, 'mess' => paypro_dynamic(price: $price, discount_per: $discount_per, currency: $currency, name: $plan_title, description: $plan_desc, dynamic_product_id: @$emd_dynamic_plan->paypro_product_id) . "&x-pid=" . $plan_create->id . "&x-uid=" . auth()->guard('web_user_sess')->id()];
    }
    public function emd_paypro_callback($request): array
    {
        $paypro_pricing_plan_check = $this->emd_pricing_plan_model->where('paypro_product_id', @$request['PRODUCT_ID'])->first();
        if (!$paypro_pricing_plan_check) {
            return [false, 'paypro product not available'];
        }

        $order_customer_field_explode = explode(",", @$request['ORDER_CUSTOM_FIELDS']);
        if (strpos(@$order_customer_field_explode[0], 'x-uid') !== false) {
            $user_id = explode("=", $order_customer_field_explode[0])[1];
            $emd_pricing_plan_id = explode("=", $order_customer_field_explode[1])[1];
        } else {
            $user_id = explode("=", $order_customer_field_explode[1])[1];
            $emd_pricing_plan_id = explode("=", $order_customer_field_explode[0])[1];
        }
        $web_user_find = $this->emd_web_user_model->where('user_id', @$user_id)->first();
        if (!$web_user_find) {
            $user_row = $this->user_model->where('id', @$user_id)->first();
            if (!$user_row) {
                return [false, 'user not available'];
            } else {
                $new_user_register['user_id'] = @$user_id;
                $new_user_register['social_id'] = 0;
                $new_user_register['register_from'] = 'running';
                $new_user_register['api_key'] = sha1(md5($user_id . ":" . time()));
                $new_user_register['is_web_premium'] = $this->emd_web_user_model::FREE_USER;
                $new_user_register['is_api_premium'] = $this->emd_web_user_model::FREE_USER;
                $this->emd_web_user_model->create();
            }
        }
        $pricing_plan = $this->emd_pricing_plan_model->where('id', @$emd_pricing_plan_id)->where('paypro_product_id', @$request['PRODUCT_ID'])->first();
        if (!$pricing_plan) {
            return [false, 'pricing plan not available'];
        }
        $pricing_plan_is_api = $pricing_plan->is_api;
        $order_status = @$request['ORDER_STATUS'];
        $todate = date("Y-m-d");
        $pricing_plan_duration = @$pricing_plan->duration;
        $expiry_date = date("Y-m-d", strtotime($todate . " +" . @$pricing_plan_duration . " day"));

        // start from previous plan exipry date
        $previous_plan_expiry_date = @$this->emd_user_transaction_model->whereHas('emd_pricing_plan', function ($qruery) use ($pricing_plan_is_api) {
            return $qruery->where('is_api', $pricing_plan_is_api)->whereNot('is_custom', $this->emd_pricing_plan_model::REGISTERED_PLAN);
        })
            ->where('user_id', $user_id)
            ->where('expiry_date', '>', $todate)
            ->where(function ($query) {
                $query->where('is_refund', null)
                    ->orWhere('is_refund', EmdUserTransaction::TRAN_RUNNING);
            })->where('order_status', $this->emd_user_transaction_model::OS_PROCESSED)->latest()->first()->expiry_date;
        if (!$previous_plan_expiry_date) {
            $expiry_date = $expiry_date;
        } else {
            $expiry_date = date("Y-m-d", strtotime($previous_plan_expiry_date . " +" . @$pricing_plan_duration . " day"));
        }

        // code end
        $order_no = @$request['ORDER_ID'];
        $is_refund = EmdUserTransaction::TRAN_RUNNING;
        if ($order_status == $this->emd_user_transaction_model::OS_PROCESSED) {
            $is_order_no_already = $this->emd_user_transaction_model->where('order_no', $order_no)->where('order_status', $this->emd_user_transaction_model::OS_PROCESSED)->first();
            if ($is_order_no_already) {
                return [false, "Order ID already Processed"];
            }
            $emd_web_user = $this->emd_web_user_model->where('user_id', $user_id)->first();
            $for_web_and_api_premium = $this->emd_web_user_model::FREE_USER;
            if ($pricing_plan->is_api == $this->emd_pricing_plan_model::WEB_AND_API_PLAN) {
                $for_web_and_api_premium = $this->emd_web_user_model::PREMIUM_USER;
            }
            if ($emd_web_user->is_web_premium != $this->emd_web_user_model::PREMIUM_USER) {
                $web_user['is_web_premium'] = $pricing_plan->is_api == $this->emd_pricing_plan_model::WEB_PLAN ? $this->emd_web_user_model::PREMIUM_USER : $for_web_and_api_premium;
            }
            if ($emd_web_user->is_api_premium != $this->emd_web_user_model::PREMIUM_USER) {
                $web_user['is_api_premium'] = $pricing_plan->is_api == $this->emd_pricing_plan_model::API_PLAN ? $this->emd_web_user_model::PREMIUM_USER : $for_web_and_api_premium;
            }
            $web_user['user_id'] = $user_id; // no need for this
            $this->emd_web_user_model->where('user_id', $user_id)->update($web_user);
        } else if ($order_status == $this->emd_user_transaction_model::OS_REFUNDED || $order_status == $this->emd_user_transaction_model::OS_CHANGE_PLAN || $order_status == $this->emd_user_transaction_model::OS_CHARGE_BACK) {
            $is_able_to_refund = $this->emd_user_transaction_model->where('order_no', $order_no)->where('product_no', $pricing_plan->paypro_product_id)->where('is_refund', EmdUserTransaction::TRAN_RUNNING)->where('order_status', $this->emd_user_transaction_model::OS_PROCESSED)->first();
            if (!$is_able_to_refund) {
                return [false, "Order id not related to product id for refund OR already refunded"];
            }
            $is_refund = EmdUserTransaction::TRAN_REFUNDED;
            $user_transaction_refund['is_refund'] = $is_refund;
            $this->emd_user_transaction_model->where('order_no', $order_no)->update($user_transaction_refund);
            $emd_user_transaction_model_actives = $this->emd_user_transaction_model->whereHas('emd_pricing_plan', function ($qruery) use ($pricing_plan_is_api) {
                return $qruery->where('is_api', $pricing_plan_is_api);
            })->where('user_id', $user_id)->where('is_refund', EmdUserTransaction::TRAN_RUNNING)->where('order_status', $this->emd_user_transaction_model::OS_PROCESSED)->where('expiry_date', '>', $todate)->get();
            $itration = 0;
            $new_expiry_date = "";
            foreach ($emd_user_transaction_model_actives as $emd_user_transaction_model_active) {
                if ($itration == 0) {
                    $new_expiry_date = date("Y-m-d", strtotime($emd_user_transaction_model_active->purchase_date . " +" . @$emd_user_transaction_model_active->plan_days . " day"));
                    $emd_user_transaction_update['expiry_date'] = $new_expiry_date;
                    $this->emd_user_transaction_model->where('id', $emd_user_transaction_model_active->id)->update($emd_user_transaction_update);
                    ++$itration;
                } else {
                    $new_expiry_date = date("Y-m-d", strtotime($new_expiry_date . " +" . @$emd_user_transaction_model_active->plan_days . " day"));
                    $emd_user_transaction_update['expiry_date'] = $new_expiry_date;
                    $this->emd_user_transaction_model->where('id', $emd_user_transaction_model_active->id)->update($emd_user_transaction_update);
                }
            }
        }

        $user_transaction['user_id'] = $user_id;
        $user_transaction['emd_pricing_plan_id'] = $pricing_plan->id;
        $user_transaction['order_no'] = $order_no;
        $user_transaction['product_no'] = $pricing_plan->paypro_product_id;
        $user_transaction['order_status'] = $order_status;
        $user_transaction['order_currency_code'] = @$request['ORDER_CURRENCY_CODE'];
        $user_transaction['order_item_price'] = @$request['ORDER_ITEM_UNIT_PRICE'];
        $user_transaction['payment_method_name'] = @$request['PAYMENT_METHOD_NAME'];
        $user_transaction['payment_from'] = "website";
        $user_transaction['purchase_date'] = $todate;
        $user_transaction['plan_days'] = $pricing_plan_duration;
        $user_transaction['expiry_date'] = ($order_status == $this->emd_user_transaction_model::OS_PROCESSED) ? $expiry_date : date("Y-m-d");
        $user_transaction['is_refund'] = $is_refund;
        $user_transaction['renewal_type'] = @$request['SUBSCRIPTION_RENEWAL_TYPE'];
        $user_transaction['is_test_mode'] = @$request['TEST_MODE'];
        if ($order_status == $this->emd_user_transaction_model::OS_CHANGE_PLAN) {
            $user_transaction['all_json_transaction'] = json_encode($request);
        } else {
            $user_transaction['all_json_transaction'] = json_encode($request->all());
        }
        $emd_user_transaction_record = $this->emd_user_transaction_model->create($user_transaction);

        if ($order_status == $this->emd_user_transaction_model::OS_PROCESSED) {
            $emd_pricing_plan_allow_rows = $this->emd_pricing_plan_allow_model->where('emd_pricing_plan_id', $pricing_plan->id)->get();
            foreach ($emd_pricing_plan_allow_rows as $emd_pricing_plan_allow_row) {
                $emd_user_transaction_allow_add['user_id'] = $user_id;
                $emd_user_transaction_allow_add['emd_user_transaction_id'] = $emd_user_transaction_record->id;
                $emd_user_transaction_allow_add['tool_id'] = $emd_pricing_plan_allow_row->tool_id;
                $emd_user_transaction_allow_add['queries_limit'] = $emd_pricing_plan_allow_row->queries_limit;
                $emd_user_transaction_allow_add['allow_json'] = $emd_pricing_plan_allow_row->allow_json;
                $emd_user_transaction_allow_add['queries_used'] = 0;
                $this->emd_user_transaction_allow_model->create($emd_user_transaction_allow_add);
            }
            $this->emd_user_transaction_model->where('user_id', $user_id)->where('is_test_mode', $this->emd_user_transaction_model::REGISTER_MODE)->whereNot('id', $emd_user_transaction_record->id)->update(['is_refund' => $this->emd_user_transaction_model::TRAN_EXP_USED]);
        }
        return [true, "Successfully " . $order_status];
    }
    public function emd_user_plan_change($request, $user_id): bool
    {
        $emd_user_transaction_get = $this->emd_user_transaction_model->where('order_no', $request['order_no'])->where('user_id', $user_id)->first();
        $data['PRODUCT_ID'] = $emd_user_transaction_get->product_no;
        $data['TEST_MODE'] = 1;
        $data['SUBSCRIPTION_RENEWAL_TYPE'] = "None";
        $data['PAYMENT_METHOD_NAME'] = 'None';
        $data['ORDER_ITEM_PRICE'] = 0;
        $data['ORDER_CURRENCY_CODE'] = "None";
        $data['ORDER_ID'] = $request['order_no'];
        $data['ORDER_STATUS'] = $this->emd_user_transaction_model::OS_CHANGE_PLAN;
        $data['ORDER_CUSTOM_FIELDS'] = 'x-pid=' . $emd_user_transaction_get->emd_pricing_plan_id . ',x-uid=' . $user_id;
        $deactive = $this->emd_paypro_callback($data);
        if ($deactive[0]) {
            $data2['product_no'] = $request['product_no'];
            $data2['order_status'] = $this->emd_user_transaction_model::OS_CHANGE_PLAN;
            $this->assign_plan_to_user($data2, $user_id);
        }
        $this->emd_user_profile_comment_controller->add_profile_comment(array('user_id' => $user_id, 'action_type' => 'ChangePlan', 'detail' => $request['detail']));
        return true;
    }

    public function emd_custom_premium($request, $user_id): bool
    {
        $this->assign_plan_to_user($request, $user_id);
        $this->emd_user_profile_comment_controller->add_profile_comment(array('user_id' => $user_id, 'action_type' => 'SetPlan&Premium', 'detail' => $request['detail']));
        return true;
    }

    public static function assign_plan_to_user($request, $user_id): bool
    {
        $pricing_plan = EmdPricingPlan::where('paypro_product_id', @$request['product_no'])->first();
        $pricing_plan_is_api = $pricing_plan->is_api;
        $order_status = EmdUserTransaction::OS_PROCESSED;
        $todate = date("Y-m-d");
        $expiry_date = date("Y-m-d", strtotime($todate . " +" . @$pricing_plan->duration . " day"));

        // start from previous plan exipry date
        $previous_plan_expiry_date = EmdUserTransaction::whereHas('emd_pricing_plan', function ($qruery) use ($pricing_plan_is_api) {
            return $qruery->where('is_api', $pricing_plan_is_api)->whereNot('is_custom', EmdPricingPlan::REGISTERED_PLAN);
        })
            ->where('user_id', $user_id)
            ->where('expiry_date', '>', $todate)
            ->where(function ($query) {
                $query->where('is_refund', null)
                    ->orWhere('is_refund', EmdUserTransaction::TRAN_RUNNING);
            })->where('order_status', EmdUserTransaction::OS_PROCESSED)->latest()->first()?->expiry_date;
        if (!$previous_plan_expiry_date) {
            $expiry_date = $expiry_date;
        } else {
            $expiry_date = date("Y-m-d", strtotime($previous_plan_expiry_date . " +" . @$pricing_plan->duration . " day"));
        }
        // code end

        $order_no = "C" . time();
        $is_refund = EmdUserTransaction::TRAN_RUNNING;
        $emd_web_user = EmdWebUser::where('user_id', $user_id)->first();
        $for_web_and_api_premium = EmdWebUser::FREE_USER;
        if ($pricing_plan->is_api == EmdPricingPlan::WEB_AND_API_PLAN) {
            $for_web_and_api_premium = EmdWebUser::PREMIUM_USER;
        }
        if ($emd_web_user->is_web_premium != EmdWebUser::PREMIUM_USER) {
            $web_user['is_web_premium'] = $pricing_plan->is_api == EmdPricingPlan::WEB_PLAN ? EmdWebUser::PREMIUM_USER : $for_web_and_api_premium;
        }
        if ($emd_web_user->is_api_premium != EmdWebUser::PREMIUM_USER) {
            $web_user['is_api_premium'] = $pricing_plan->is_api == EmdPricingPlan::API_PLAN ? EmdWebUser::PREMIUM_USER : $for_web_and_api_premium;
        }
        $web_user['user_id'] = $user_id; // no need for this
        EmdWebUser::where('user_id', $user_id)->update($web_user);

        $user_transaction['user_id'] = $user_id;
        $user_transaction['emd_pricing_plan_id'] = $pricing_plan->id;
        $user_transaction['order_no'] = $order_no;
        $user_transaction['product_no'] = $pricing_plan->paypro_product_id;
        $user_transaction['order_status'] = $order_status;
        $user_transaction['order_currency_code'] = "None";
        $user_transaction['order_item_price'] = 0;
        $user_transaction['payment_method_name'] = "None";
        $user_transaction['payment_from'] = "None";
        $user_transaction['purchase_date'] = $todate;
        $user_transaction['plan_days'] = $pricing_plan->duration;
        $user_transaction['expiry_date'] = $expiry_date;
        $user_transaction['is_refund'] = $is_refund;
        $user_transaction['renewal_type'] = EmdUserTransaction::RENEWAL_NONE;
        $user_transaction['is_test_mode'] = $pricing_plan->is_custom == EmdPricingPlan::REGISTERED_PLAN ? EmdUserTransaction::REGISTER_MODE : EmdUserTransaction::TEST_MODE;
        if (@$request['order_status'] == EmdUserTransaction::OS_CHANGE_PLAN || $request['order_status'] == EmdUserTransaction::OS_REGISTER_PLAN) {
            $user_transaction['all_json_transaction'] = json_encode($request);
        } else {
            $user_transaction['all_json_transaction'] = json_encode($request->all());
        }

        $emd_user_transaction_record = EmdUserTransaction::create($user_transaction);

        if ($order_status == EmdUserTransaction::OS_PROCESSED) {
            $emd_pricing_plan_allow_rows = EmdPricingPlanAllow::where('emd_pricing_plan_id', $pricing_plan->id)->get();
            foreach ($emd_pricing_plan_allow_rows as $emd_pricing_plan_allow_row) {
                $emd_user_transaction_allow_add['user_id'] = $user_id;
                $emd_user_transaction_allow_add['emd_user_transaction_id'] = $emd_user_transaction_record->id;
                $emd_user_transaction_allow_add['tool_id'] = $emd_pricing_plan_allow_row->tool_id;
                $emd_user_transaction_allow_add['queries_limit'] = $emd_pricing_plan_allow_row->queries_limit;
                $emd_user_transaction_allow_add['allow_json'] = $emd_pricing_plan_allow_row->allow_json;
                $emd_user_transaction_allow_add['queries_used'] = 0;
                EmdUserTransactionAllow::create($emd_user_transaction_allow_add);
            }
            EmdUserTransaction::where('user_id', $user_id)->where('is_test_mode', EmdUserTransaction::REGISTER_MODE)->whereNot('id', $emd_user_transaction_record->id)->update(['is_refund' => EmdUserTransaction::TRAN_EXP_USED]);
        }

        return true;
    }
    public function emd_transaction_date_filter_page($start_date, $end_date): EmdUserTransaction | Collection
    {
        return $this->emd_user_transaction_model->whereBetween('purchase_date', [$start_date, $end_date])->get();
    }
    public static function AvailablePlanDetail(int $emd_tool_id, int $user_id): EmdUserTransactionAllow | Collection
    {
        return EmdUserTransactionAllow::whereHas('emd_user_transaction', function ($query) {
            $query->whereHas('emd_pricing_plan', function ($query1) {
                $query1->where('is_api', '!=', EmdPricingPlan::API_PLAN);
            })->where('expiry_date', '>=', date("Y-m-d"))->where('is_refund', EmdUserTransaction::TRAN_RUNNING)->where('order_status', EmdUserTransaction::OS_PROCESSED);
        })->where(function ($query2) use ($emd_tool_id) {
            $query2->where('tool_id', $emd_tool_id)
                ->orWhere('tool_id', 0);
        })
            ->where('user_id', $user_id)
            ->get();
    }
}
