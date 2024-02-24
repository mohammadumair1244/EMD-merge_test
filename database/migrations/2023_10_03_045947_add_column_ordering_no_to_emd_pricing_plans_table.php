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
            $table->tinyInteger('ordering_no')->nullable()->default(1)->after('is_api');
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
            $table->dropColumn('ordering_no');
        });
    }
};
