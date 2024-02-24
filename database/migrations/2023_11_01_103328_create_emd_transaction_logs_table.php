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
        Schema::create('emd_transaction_logs', function (Blueprint $table) {
            $table->id();
            $table->string("order_no", 100)->nullable();
            $table->longText("trans_log")->nullable()->default(json_encode([]));
            $table->integer("status")->default(0);
            $table->string("status_message", 300)->nullable();
            $table->string("paypro_ip",250)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emd_transaction_logs');
    }
};
