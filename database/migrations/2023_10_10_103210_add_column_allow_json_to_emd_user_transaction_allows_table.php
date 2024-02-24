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
        Schema::table('emd_user_transaction_allows', function (Blueprint $table) {
            if (!Schema::hasColumn('emd_user_transaction_allows', 'allow_json')) {
                $table->longText("allow_json")->nullable()->default(json_encode([]))->after('queries_used')->comment("json with key : value");
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
            if (Schema::hasColumn('emd_user_transaction_allows', 'allow_json')) {
                $table->dropColumn('allow_json');
            }
        });
    }
};
