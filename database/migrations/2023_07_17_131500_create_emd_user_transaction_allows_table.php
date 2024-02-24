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
        Schema::create('emd_user_transaction_allows', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->nullable();
            $table->integer("emd_user_transaction_id")->nullable();
            $table->integer("tool_id")->nullable();
            $table->integer("queries_limit")->nullable()->comment("query limit for {key_name} in this plan");
            $table->integer("queries_used")->nullable()->comment("queries used {key_name} in this plan");
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
        Schema::dropIfExists('emd_user_transaction_allows');
    }
};
