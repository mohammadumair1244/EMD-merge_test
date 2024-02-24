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
            if (!Schema::hasColumn('emd_pricing_plans', 'is_custom')) {
                $table->tinyInteger('is_custom')->default(0)->comment("0 for no custom plan, 1 for custom plan, 2 for dynamic plan, 3 for dynamic sale plan");
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
            if (Schema::hasColumn('emd_pricing_plans', 'is_custom')) {
                $table->dropColumn('is_custom');
            }
        });
    }
};
