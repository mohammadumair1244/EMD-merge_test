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
            if (!Schema::hasColumn('emd_pricing_plans', 'is_mobile')) {
                $table->tinyInteger('is_mobile')->default(0)->after("is_custom")->comment('0 for web and 1 for mobile');
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
            if (Schema::hasColumn('emd_pricing_plans', 'is_mobile')) {
                $table->dropColumn('is_mobile');
            }
        });
    }
};
