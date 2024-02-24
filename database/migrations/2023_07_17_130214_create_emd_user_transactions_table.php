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
        Schema::create('emd_user_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->nullable();
            $table->integer("emd_pricing_plan_id")->nullable();
            $table->string('order_no', 50)->nullable()->comment('payment api => order id');
            $table->string('product_no', 50)->nullable()->comment('payment api => product id');
            $table->string('order_status', 30)->nullable()->comment('payment api => order status');
            $table->string('order_currency_code', 5)->nullable()->comment('payment api => USD,PKR, etc');
            $table->float('order_item_price', 11, 2)->nullable()->comment('payment api => product item price');
            $table->string('payment_method_name', 30)->nullable()->comment('payment api => Visa,Master, etc');
            $table->string("payment_from")->nullable()->comment("ex: web,android, ios");
            $table->date("purchase_date")->nullable();
            $table->integer("plan_days")->nullable()->comment('is equal to emd_pricing_plan duration column');
            $table->date("expiry_date")->nullable();
            $table->tinyInteger("is_refund")->nullable()->comment("0 for continue, 1 for refund, 2 for complete use or expired");
            $table->string("renewal_type", 20)->nullable()->comment("ex: Manual, Auto");
            $table->tinyInteger("is_test_mode")->nullable()->comment("0 for original, 1 for test, 2 for register");
            $table->longText("all_json_transaction")->nullable();
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
        Schema::dropIfExists('emd_user_transactions');
    }
};
