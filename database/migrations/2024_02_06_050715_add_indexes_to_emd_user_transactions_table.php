<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $indexes = DB::select("SHOW INDEX FROM emd_user_transactions");
            $indexes_name_array = [];
            foreach ($indexes as $key => $value) {
                $indexes_name_array[] = (json_decode(json_encode($value), true)['Key_name']);
            }
            if (!in_array("emd_user_transactions_user_id_index", $indexes_name_array)) {
                $table->index('user_id', 'emd_user_transactions_user_id_index');
            }
            if (!in_array("emd_user_transactions_order_no_index", $indexes_name_array)) {
                $table->index('order_no', 'emd_user_transactions_order_no_index');
            }
            if (!in_array("emd_user_transactions_order_status_index", $indexes_name_array)) {
                $table->index('order_status', 'emd_user_transactions_order_status_index');
            }
            if (!in_array("emd_user_transactions_is_refund_index", $indexes_name_array)) {
                $table->index('is_refund', 'emd_user_transactions_is_refund_index');
            }
            if (!in_array("emd_user_transactions_expiry_date_index", $indexes_name_array)) {
                $table->index('expiry_date', 'emd_user_transactions_expiry_date_index');
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
            $table->dropIndex('emd_user_transactions_user_id_index');
            $table->dropIndex('emd_user_transactions_order_no_index');
            $table->dropIndex('emd_user_transactions_order_status_index');
            $table->dropIndex('emd_user_transactions_is_refund_index');
            $table->dropIndex('emd_user_transactions_expiry_date_index');
        });
    }
};
