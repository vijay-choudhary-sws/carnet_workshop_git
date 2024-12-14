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
        Schema::create('job_card_spare_parts', function (Blueprint $table) {
            $table->id();
            $table->string('jobcard_no');
            $table->integer('product_stock_id');
            $table->string('product_stock_name');
            $table->integer('quantity')->default(0);
            $table->integer('price')->default(0);
            $table->integer('total_amount')->default(0);
            $table->integer('discount')->default(0);
            $table->integer('final_amount')->default(0);
            $table->integer('machanic_id')->nullable();
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
        Schema::dropIfExists('job_card_spare_parts');
    }
};
