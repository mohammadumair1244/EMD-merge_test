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
        Schema::create('emd_pricing_plan_allows', function (Blueprint $table) {
            $table->id();
            $table->integer("emd_pricing_plan_id")->nullable();
            $table->integer("tool_id")->nullable();
            $table->integer("queries_limit")->nullable()->comment("query limit for {key_name} in this plan");
            $table->longText("allow_json")->nullable()->default(json_encode([]))->comment("json with key : value");
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
        Schema::dropIfExists('emd_pricing_plan_allows');
    }
};
