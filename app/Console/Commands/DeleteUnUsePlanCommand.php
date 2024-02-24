<?php

namespace App\Console\Commands;

use App\Models\EmdPricingPlan;
use Illuminate\Console\Command;

class DeleteUnUsePlanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:un-use-plan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete those plan which are not purchase but created by user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $un_use_plans = EmdPricingPlan::whereDoesntHave('emd_user_transactions')->where('is_custom', EmdPricingPlan::USER_CREATED_PLAN)->where('created_at', '<', date("Y-m-d"))->get();
        foreach ($un_use_plans as $item) {
            $item->delete();
        }
        return Command::SUCCESS;
    }
}
