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
        Schema::create('emd_feedback', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->nullable()->default(0);
            $table->integer("tool_id")->nullable()->default(0);
            $table->string("name",50)->nullable();
            $table->string("email",200)->nullable();
            $table->string("message",1000)->nullable();
            $table->tinyInteger("rating")->nullable()->default(0);
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
        Schema::dropIfExists('emd_feedback');
    }
};
