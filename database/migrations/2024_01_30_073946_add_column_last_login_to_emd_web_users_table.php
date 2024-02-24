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
        Schema::table('emd_web_users', function (Blueprint $table) {
            if (!Schema::hasColumn('emd_web_users', 'last_login')) {
                $table->dateTime('last_login')->nullable()->after("is_api_premium")->comment('user last login date time');
            }
            if (!Schema::hasColumn('emd_web_users', 'login_ip')) {
                $table->string('login_ip', 200)->nullable()->after("is_api_premium")->comment('user last login ip');
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
            if (Schema::hasColumn('emd_web_users', 'last_login')) {
                $table->dropColumn('last_login');
            }
            if (Schema::hasColumn('emd_web_users', 'login_ip')) {
                $table->dropColumn('login_ip');
            }
        });
    }
};
