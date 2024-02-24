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
        Schema::create('emd_email_campaigns', function (Blueprint $table) {
            $table->id();
            $table->integer('emd_email_template_id')->nullable();
            $table->string('from_email',100)->nullable();
            $table->string('from_name',100)->nullable();
            $table->string('from_subject',100)->nullable();
            $table->string('title',50)->nullable();
            $table->date('start_date')->nullable();
            $table->string('user_status',50)->nullable()->comment('user status like Premium,Free,Expired,Refunded etc...');
            $table->integer('per_hour_emails')->default(0)->comment('no of emails send in 1 hours');
            $table->tinyInteger('status')->default(0)->comment('0 for stop, 1 for running, 2 for completed');
            $table->integer('total_emails')->default(0)->comment('total emails for this campaign');
            $table->integer('send_emails')->default(0)->comment('sended emails for this campaign');
            $table->string('testing_email',100)->nullable();
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
        Schema::dropIfExists('emd_email_campaigns');
    }
};
