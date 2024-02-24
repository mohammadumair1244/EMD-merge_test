<?php

namespace App\Console\Commands;

use App\Models\EmdPricingPlanAllow;
use App\Models\EmdUserTransactionAllow;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class PricingPlanColumToJsonCommand extends Command
{
    protected $allow_modes_column_key = 'allow_modes'; // here you can change key name of allow_modes which you have set in custom fields
    protected $per_req_limit_column_key = 'per_request_limit'; // here you can change key name of per_request_limit which you have set in custom fields
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plan:column-to-json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'emd_pricing_plan_allows and emd_user_transaction_allows table data shift from column to json';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (Schema::hasColumn('emd_pricing_plan_allows', 'allow_modes') && Schema::hasColumn('emd_pricing_plan_allows', 'per_request_limit')) {
            $emd_pricing_plan_allows = EmdPricingPlanAllow::get();
            foreach ($emd_pricing_plan_allows as $plan_item) {
                $data1 = json_decode($plan_item->allow_json, true);
                $data1[$this->allow_modes_column_key] = $plan_item->allow_modes;
                $data1[$this->per_req_limit_column_key] = $plan_item->per_request_limit;
                $plan_item->allow_json = $data1;
                $plan_item->save();
            }
        }
        if (Schema::hasColumn('emd_user_transaction_allows', 'allow_modes') && Schema::hasColumn('emd_user_transaction_allows', 'per_request_limit')) {
            $emd_user_transaction_allows = EmdUserTransactionAllow::get();
            foreach ($emd_user_transaction_allows as $tran_item) {
                $data2 = json_decode($tran_item->allow_json, true);
                $data2[$this->allow_modes_column_key] = $tran_item->allow_modes;
                $data2[$this->per_req_limit_column_key] = $tran_item->per_request_limit;
                $tran_item->allow_json = $data2;
                $tran_item->save();
            }
        }
        return Command::SUCCESS;
    }
}
