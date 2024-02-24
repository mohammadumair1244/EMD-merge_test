<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emd_pricing_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('emd_pricing_plans', 'mobile_app_product_id')) {
                $table->string('mobile_app_product_id', 1000)->nullable()->after("paypro_product_id")->comment('for mobile app product id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emd_pricing_plans', function (Blueprint $table) {
            if (Schema::hasColumn('emd_pricing_plans', 'mobile_app_product_id')) {
                $table->dropColumn('mobile_app_product_id');
            }
        });
    }
};
