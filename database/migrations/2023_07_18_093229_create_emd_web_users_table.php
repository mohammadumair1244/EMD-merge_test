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
        Schema::create('emd_web_users', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->nullable();
            $table->string("register_from", 50)->nullable()->comment("ex: web, google, facebook etc.");
            $table->string("social_id", 150)->nullable()->comment("external login platform id");
            $table->string("api_key", 250)->nullable();
            $table->tinyInteger("is_web_premium")->nullable();
            $table->tinyInteger("is_api_premium")->nullable();
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
        Schema::dropIfExists('emd_web_users');
    }
};
