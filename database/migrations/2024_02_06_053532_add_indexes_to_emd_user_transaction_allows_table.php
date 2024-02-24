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
        Schema::table('emd_user_transaction_allows', function (Blueprint $table) {
            $indexes = DB::select("SHOW INDEX FROM emd_user_transaction_allows");
            $indexes_name_array = [];
            foreach ($indexes as $key => $value) {
                $indexes_name_array[] = (json_decode(json_encode($value), true)['Key_name']);
            }
            if (!in_array("emd_user_transaction_allows_user_id_index", $indexes_name_array)) {
                $table->index('user_id', 'emd_user_transaction_allows_user_id_index');
            }
            if (!in_array("emd_user_transaction_allows_emd_user_transaction_id_index", $indexes_name_array)) {
                $table->index('emd_user_transaction_id', 'emd_user_transaction_allows_emd_user_transaction_id_index');
            }
            if (!in_array("emd_user_transaction_allows_tool_slug_key_index", $indexes_name_array)) {
                $table->index('tool_slug_key', 'emd_user_transaction_allows_tool_slug_key_index');
            }
            if (!in_array("emd_user_transaction_allows_queries_limit_index", $indexes_name_array)) {
                $table->index('queries_limit', 'emd_user_transaction_allows_queries_limit_index');
            }
            if (!in_array("emd_user_transaction_allows_queries_used_index", $indexes_name_array)) {
                $table->index('queries_used', 'emd_user_transaction_allows_queries_used_index');
            }
            if (!in_array("emd_user_transaction_allows_tool_id_index", $indexes_name_array)) {
                $table->index('tool_id', 'emd_user_transaction_allows_tool_id_index');
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
        Schema::table('emd_user_transaction_allows', function (Blueprint $table) {
            $table->dropIndex('emd_user_transaction_allows_user_id_index');
            $table->dropIndex('emd_user_transaction_allows_emd_user_transaction_id_index');
            $table->dropIndex('emd_user_transaction_allows_tool_slug_key_index');
            $table->dropIndex('emd_user_transaction_allows_queries_limit_index');
            $table->dropIndex('emd_user_transaction_allows_queries_used_index');
            $table->dropIndex('emd_user_transaction_allows_tool_id_index');
        });
    }
};
