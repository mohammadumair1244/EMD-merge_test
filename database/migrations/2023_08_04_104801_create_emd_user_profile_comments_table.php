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
        Schema::create('emd_user_profile_comments', function (Blueprint $table) {
            $table->id();
            $table->integer('action_user_id')->nullable();
            $table->string("action_type", 100)->nullable();
            $table->integer('user_id')->nullable();
            $table->string("detail", 500)->nullable();
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
        Schema::dropIfExists('emd_user_profile_comments');
    }
};
