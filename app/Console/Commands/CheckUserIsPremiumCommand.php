<?php

namespace App\Console\Commands;

use App\Models\EmdPricingPlan;
use App\Models\EmdUserTransaction;
use App\Models\EmdWebUser;
use Illuminate\Console\Command;

class CheckUserIsPremiumCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkuser:premium';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert user premium to free if plan expire';

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle()
    {
        $expire_transactions = EmdUserTransaction::with('emd_pricing_plan')->where('expiry_date', date("Y-m-d"))->where('order_status', EmdUserTransaction::OS_PROCESSED)->where('is_refund', EmdUserTransaction::TRAN_RUNNING)->get();
        foreach ($expire_transactions as $trans_item) {
            $trans_item->is_refund = EmdUserTransaction::TRAN_EXP_USED;
            $trans_item->save();
            $web_user = EmdWebUser::where('user_id', $trans_item->user_id)->first();
            if ($web_user) {
                if ($trans_item->emd_pricing_plan->is_api == EmdPricingPlan::WEB_PLAN) {
                    $web_user->is_web_premium = $this->check_next_transaction_except_today(user_id: $trans_item->user_id, is_api: EmdPricingPlan::WEB_PLAN);
                } else if ($trans_item->emd_pricing_plan->is_api == EmdPricingPlan::API_PLAN) {
                    $web_user->is_api_premium = $this->check_next_transaction_except_today(user_id: $trans_item->user_id, is_api: EmdPricingPlan::API_PLAN);
                } else if ($trans_item->emd_pricing_plan->is_api == EmdPricingPlan::WEB_AND_API_PLAN) {
                    $web_user->is_web_premium = $this->check_next_transaction_except_today(user_id: $trans_item->user_id, is_api: EmdPricingPlan::WEB_PLAN);
                    $web_user->is_api_premium = $this->check_next_transaction_except_today(user_id: $trans_item->user_id, is_api: EmdPricingPlan::API_PLAN);
                }
                $web_user->save();
            }

        }
        return Command::SUCCESS;
    }

    public function check_next_transaction_except_today($user_id, $is_api)
    {
        $available = EmdUserTransaction::whereHas('emd_pricing_plan', function ($query) use ($is_api) {
            return $query->whereIn('is_api', [$is_api, EmdPricingPlan::WEB_AND_API_PLAN]);
        })->where('is_refund', EmdUserTransaction::TRAN_RUNNING)->where('order_status', EmdUserTransaction::OS_PROCESSED)->where('expiry_date', '>', date("Y-m-d"))->where('user_id', $user_id)->first();
        return $available ? 1 : 0;
    }
}
