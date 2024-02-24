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
        Schema::table('users', function (Blueprint $table) {
            $indexes = DB::select("SHOW INDEX FROM users");
            $indexes_name_array = [];
            foreach ($indexes as $key => $value) {
                $indexes_name_array[] = (json_decode(json_encode($value), true)['Key_name']);
            }
            if (!in_array("users_email_index", $indexes_name_array)) {
                $table->index('email', 'users_email_index');
            }
            if (!in_array("users_admin_level_index", $indexes_name_array)) {
                $table->index('admin_level', 'users_admin_level_index');
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_email_index');
            $table->dropIndex('users_admin_level_index');
        });
    }
};
