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
        Schema::table('tools', function (Blueprint $table) {
            if (!Schema::hasColumn('tools', 'request_limit')) {
                $table->integer('request_limit')->nullable(0)->default(10)->after('is_home')->comment('limit will be apply on 1 mint');
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
        Schema::table('tools', function (Blueprint $table) {
            if (Schema::hasColumn('tools', 'request_limit')) {
                $table->dropColumn('request_limit');
            }
        });
    }
};
