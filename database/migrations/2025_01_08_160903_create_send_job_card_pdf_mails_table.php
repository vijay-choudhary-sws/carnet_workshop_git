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
        Schema::create('send_job_card_pdf_mails', function (Blueprint $table) {
            $table->id();
            $table->integer('jobcard_id');
            $table->tinyInteger('template_type')->default(0);
            $table->text('path')->nullable();
            $table->tinyInteger('is_generated')->default(0);
            $table->tinyInteger('is_send')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('send_job_card_pdf_mails');
    }
};
