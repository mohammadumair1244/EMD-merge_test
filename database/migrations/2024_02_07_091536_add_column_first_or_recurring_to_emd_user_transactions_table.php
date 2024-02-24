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
        Schema::table('emd_user_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('emd_user_transactions', 'first_or_recurring')) {
                $table->string('first_or_recurring', 200)->default(0)->after("is_test_mode")->comment('0 for other, 1 for first time payment, 2 for recurring payment');
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
        Schema::table('emd_user_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('emd_user_transactions', 'first_or_recurring')) {
                $table->dropColumn('first_or_recurring');
            }
        });
    }
};
