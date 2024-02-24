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
        Schema::create('emd_email_settings', function (Blueprint $table) {
            $table->id();
            $table->integer("email_type")->nullable()->comment("1 for contact us, 2 for forgot password, 3 for new account etc..");
            $table->string("receiver_email")->nullable()->comment("email id for receiver");
            $table->string("send_from")->nullable()->comment("name of email sender");
            $table->string("subject")->nullable()->comment("email subject title");
            $table->string("template")->nullable()->comment("email template");
            $table->integer("is_active")->nullable()->comment("1 for active");
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
        Schema::dropIfExists('emd_email_settings');
    }
};
