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
            if (!Schema::hasColumn('emd_web_users', 'server_token')) {
                $table->longText('server_token')->nullable()->after("is_api_premium")->comment('for mobile in-app-purchase because Google & Apple not send user_id on auto renewal');
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
            if (Schema::hasColumn('emd_web_users', 'server_token')) {
                $table->dropColumn('server_token');
            }
        });
    }
};
