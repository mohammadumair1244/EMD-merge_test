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
        Schema::create('emd_plan_zone_prices', function (Blueprint $table) {
            $table->id();
            $table->integer("emd_pricing_plan_id")->nullable();
            $table->string("emd_country_id", 3)->nullable();
            $table->float("price", 11, 2)->nullable();
            $table->float("sale_price", 11, 2)->nullable();
            $table->integer("discount_percentage")->nullable()->comment("only interger value");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emd_plan_zone_prices');
    }
};
