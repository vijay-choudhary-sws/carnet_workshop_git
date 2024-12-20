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
        Schema::table('new_job_cards', function (Blueprint $table) {
            $table->integer('customer_id')->after('jobcard_number');
            $table->integer('vehicle_id')->after('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_job_cards', function (Blueprint $table) {
            $table->dropColumn('customer_id');
            $table->dropColumn('vehicle_id');
        });
    }
};
