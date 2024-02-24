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
        Schema::create('emd_custom_pages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60)->nullable();
            $table->string('slug', 100)->nullable();
            $table->string('page_key', 100)->nullable();
            $table->string('blade_file', 100)->nullable();
            $table->longText('content')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 255)->nullable();
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
        Schema::dropIfExists('emd_custom_pages');
    }
};
