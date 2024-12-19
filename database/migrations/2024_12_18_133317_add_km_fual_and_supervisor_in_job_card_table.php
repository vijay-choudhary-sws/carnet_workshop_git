<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_job_cards', function (Blueprint $table) {
            $table->integer('discount')->default('0')->after('amount');
            $table->string('final_amount')->default('0')->after('discount');
            $table->string('advance')->default('0')->after('final_amount');
            $table->integer('km_runing')->default('0')->after('balance_amount');
            $table->integer('fual_level')->default('0')->after('km_runing');
            $table->integer('supervisor_id')->default('0')->after('fual_level');
            $table->tinyInteger('status')->default('0')->after('supervisor_id');
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
            $table->dropColumn('discount');
            $table->dropColumn('final_amount');
            $table->dropColumn('advance');
            $table->dropColumn('km_runing');
            $table->dropColumn('fual_level');
            $table->dropColumn('supervisor_id');
            $table->dropColumn('status');
        });
    }
};
