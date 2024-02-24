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
        Schema::table('emd_pricing_plan_allows', function (Blueprint $table) {
            if (!Schema::hasColumn('emd_pricing_plan_allows', 'tool_slug_key')) {
                $table->string('tool_slug_key')->nullable()->after("tool_id");
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
        Schema::table('emd_pricing_plan_allows', function (Blueprint $table) {
            if (Schema::hasColumn('emd_pricing_plan_allows', 'tool_slug_key')) {
                $table->dropColumn('tool_slug_key');
            }
        });
    }
};
