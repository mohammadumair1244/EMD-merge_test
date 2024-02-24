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
            $table->string('device', 30)->nullable()->after('is_api_premium');
            $table->string('browser', 30)->nullable()->after('is_api_premium');
            $table->string('city', 30)->nullable()->after('is_api_premium');
            $table->string('country', 30)->nullable()->after('is_api_premium');
            $table->string('ip', 200)->nullable()->after('is_api_premium');
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
            $table->dropColumn(['device', 'browser', 'country', 'city', 'ip']);
        });
    }
};
