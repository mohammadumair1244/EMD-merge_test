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
        Schema::create('emd_microsoft_clarities', function (Blueprint $table) {
            $table->id();
            $table->integer("tool_id")->default(0)->comment('if 0 then not for specific tool');
            $table->integer("emd_custom_page_id")->default(0)->comment('if 0 then not for specific custom page');
            $table->tinyInteger("is_tool_pages")->default(0)->comment('for 1 means this key will be show in all tool pages');
            $table->tinyInteger("is_custom_pages")->default(0)->comment('for 1 means this key will be show in all custom pages');
            $table->tinyInteger("show_percentage")->default(0)->comment('no of percentage for show on page');
            $table->longText('clarity_json')->nullable()->default(json_encode([]));
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emd_microsoft_clarities');
    }
};
