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
        Schema::table('emd_web_users', function (Blueprint $table) {
            $indexes = DB::select("SHOW INDEX FROM emd_web_users");
            $indexes_name_array = [];
            foreach ($indexes as $key => $value) {
                $indexes_name_array[] = (json_decode(json_encode($value), true)['Key_name']);
            }
            if (!in_array("emd_web_users_user_id_index", $indexes_name_array)) {
                $table->index('user_id', 'emd_web_users_user_id_index');
            }
            if (!in_array("emd_web_users_api_key_index", $indexes_name_array)) {
                $table->index('api_key', 'emd_web_users_api_key_index');
            }
            if (!in_array("emd_web_users_is_web_premium_index", $indexes_name_array)) {
                $table->index('is_web_premium', 'emd_web_users_is_web_premium_index');
            }
            if (!in_array("emd_web_users_is_api_premium_index", $indexes_name_array)) {
                $table->index('is_api_premium', 'emd_web_users_is_api_premium_index');
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
        Schema::table('emd_web_users', function (Blueprint $table) {
            $table->dropIndex('emd_web_users_user_id_index');
            $table->dropIndex('emd_web_users_api_key_index');
            $table->dropIndex('emd_web_users_is_web_premium_index');
            $table->dropIndex('emd_web_users_is_api_premium_index');
        });
    }
};
