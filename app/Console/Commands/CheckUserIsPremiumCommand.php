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
        $web_premium_users = EmdWebUser::where('is_web_premium', EmdWebUser::PREMIUM_USER)->get();
        foreach ($web_premium_users as $single_web_user) {
            $single_web_user->is_web_premium = $this->check_next_transaction($single_web_user->user_id, EmdPricingPlan::WEB_PLAN);
            $single_web_user->save();
        }
        $api_premium_users = EmdWebUser::where('is_api_premium', EmdWebUser::PREMIUM_USER)->get();
        foreach ($api_premium_users as $single_api_user) {
            $single_api_user->is_api_premium = $this->check_next_transaction($single_api_user->user_id, EmdPricingPlan::API_PLAN);
            $single_api_user->save();
        }
        return Command::SUCCESS;
    }

    public function check_next_transaction($user_id, $is_api)
    {
        $available = EmdUserTransaction::whereHas('emd_pricing_plan', function ($query) use ($is_api) {
            return $query->whereIn('is_api', [$is_api, EmdPricingPlan::WEB_AND_API_PLAN]);
        })->where('is_refund', EmdUserTransaction::TRAN_RUNNING)->where('order_status', EmdUserTransaction::OS_PROCESSED)->where('expiry_date', '>=', date("Y-m-d"))->where('user_id', $user_id)->first();
        return $available ? 1 : 0;
    }
}
