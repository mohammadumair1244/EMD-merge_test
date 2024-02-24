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
        Schema::table('emd_custom_pages', function (Blueprint $table) {
            if (!Schema::hasColumn('emd_custom_pages', 'sitemap')) {
                $table->tinyInteger('sitemap')->default(1)->after("meta_description")->comment('1 for show this url in sitemap else not show this url in sitemap');
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
        Schema::table('emd_custom_pages', function (Blueprint $table) {
            if (Schema::hasColumn('emd_custom_pages', 'sitemap')) {
                $table->dropColumn('sitemap');
            }
        });
    }
};
