<?php

namespace App\Console\Commands;

use App\Models\EmdPricingPlanAllow;
use App\Models\EmdUserTransactionAllow;
use App\Models\Tool;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class TooltoToolKeyInPlanAllowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tool:toolkey';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tool id to tool slug key in pricing plan allow and user transaction allow tables';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (Schema::hasColumn('emd_pricing_plan_allows', 'tool_slug_key')) {
            $emd_pricing_plan_allows = EmdPricingPlanAllow::get();
            foreach ($emd_pricing_plan_allows as $plan_item) {
                $plan_item->tool_slug_key = str_replace('-', '_', Tool::where('id', $plan_item->tool_id)->first()?->slug ?? 'all-web-tool');
                $plan_item->save();
            }
        }
        if (Schema::hasColumn('emd_user_transaction_allows', 'tool_slug_key')) {
            $emd_user_transaction_allows = EmdUserTransactionAllow::get();
            foreach ($emd_user_transaction_allows as $allow_item) {
                $allow_item->tool_slug_key = str_replace('-', '_', Tool::where('id', $allow_item->tool_id)->first()?->slug ?? 'all-web-tool');
                $allow_item->save();
            }
        }
        return Command::SUCCESS;
    }
}
