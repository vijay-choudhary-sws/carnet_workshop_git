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
        Schema::create('spare_parts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('spare_part_type');
            $table->integer('label_id');
            $table->string('image')->nullable();
            $table->string('part_number')->nullable();
            $table->string('brand')->nullable();
            $table->integer('unit_id');
            $table->string('suitable_for')->nullable();
            $table->string('price', 22)->default('0');
            $table->string('discount', 22)->default('0');
            $table->integer('stock')->default(0);
            $table->tinyInteger('sp_type')->default(0);
            $table->text('description')->nullable();
            $table->integer('category_id')->nullable();
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
        Schema::dropIfExists(table: 'spare_parts');
    }
};
