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
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60)->nullable();
            $table->string('slug', 100)->nullable();
            $table->longText('content')->nullable();
            $table->mediumInteger('parent_id')->nullable();
            $table->string('lang', 5)->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 255)->nullable();
            $table->tinyInteger('is_home')->default(0);
            $table->integer('request_limit')->nullable(0)->default(10)->comment('limit will be apply on 1 mint');
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
        Schema::dropIfExists('tools');
    }
};
