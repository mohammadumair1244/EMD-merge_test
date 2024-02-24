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
            $table->tinyInteger('is_active')->default(0)->after('is_api');
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
            $table->dropColumn('is_active');
        });
    }
};
