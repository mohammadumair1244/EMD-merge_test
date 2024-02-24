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
        Schema::create('emd_custom_fields', function (Blueprint $table) {
            $table->id();
            $table->integer("tool_id")->nullable()->default(0)->comment('if 0 then not for specific tool');
            $table->integer("emd_custom_page_id")->nullable()->default(0)->comment('if 0 then not for specific custom page');
            $table->string("name",50)->nullable();
            $table->string("key",50)->nullable();
            $table->string("description",100)->nullable();
            $table->integer("default_val")->nullable()->default(0);
            $table->tinyInteger("is_active")->nullable()->default(1);
            $table->tinyInteger("is_all_pages")->nullable()->default(0)->comment('for 1 means this key will be show in all pages');
            $table->tinyInteger("is_tool_pages")->nullable()->default(0)->comment('for 1 means this key will be show in all tool pages');
            $table->tinyInteger("is_custom_pages")->nullable()->default(0)->comment('for 1 means this key will be show in all custom pages');
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
        Schema::dropIfExists('emd_custom_fields');
    }
};
