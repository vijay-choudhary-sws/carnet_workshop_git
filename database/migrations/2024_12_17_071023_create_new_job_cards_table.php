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
        Schema::create('new_job_cards', function (Blueprint $table) {
            $table->id();
            $table->string('jobcard_number');
            $table->string('customer_name');
            $table->string('vehical');
            $table->string('vehical_number');
            $table->timestamp('entry_date');
            $table->string('amount');
            $table->string('balance_amount');
            $table->softDeletes();
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
        Schema::dropIfExists('new_job_cards');
    }
};
