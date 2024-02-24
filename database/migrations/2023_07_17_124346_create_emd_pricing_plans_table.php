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
        Schema::create('emd_pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->string("name", 50)->nullable();
            $table->string("label", 100)->nullable();
            $table->string("short_detail", 150)->nullable();
            $table->tinyInteger("plan_type")->nullable()->default(0)->comment("define plan type array in model you can change array values");
            $table->string("recurring_detail", 100)->nullable()->comment("ex: billed after 3 month");
            $table->float("price", 11, 2)->nullable();
            $table->float("sale_price", 11, 2)->nullable();
            $table->integer("discount_percentage")->nullable()->comment("only interger value");
            $table->integer("duration")->nullable()->comment("in days");
            $table->tinyInteger("duration_type")->nullable()->comment("define duration type array in model you can change array values");
            $table->string("coupan_paypro", 300)->nullable()->comment("uses: linked in paypro product url");
            $table->integer("paypro_product_id")->nullable();
            $table->tinyInteger("is_api")->nullable()->default(0)->comment("0 for web, 1 for api, 2 for web & api");
            $table->tinyInteger("is_popular")->nullable()->default(0)->comment("1 for popular plan");
            $table->tinyInteger("is_custom")->nullable()->default(0)->comment("0 for no custom plan, 1 for custom plan, 2 for dynamic plan, 3 for dynamic sale plan");
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
        Schema::dropIfExists('emd_pricing_plans');
    }
};
