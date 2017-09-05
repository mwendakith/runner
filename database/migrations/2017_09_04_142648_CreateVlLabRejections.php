<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVlLabRejections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('vl_lab_rejections', function (Blueprint $table) {
            $table->increments('ID');
            $table->date('dateupdated')->nullable();
            $table->integer('lab')->unsigned();
            $table->integer('month')->unsigned();
            $table->integer('year')->unsigned();
            $table->integer('rejected_reason')->unsigned();
            $table->integer('total')->unsigned()->default(0)->nullable();
            $table->index(['month', 'year', 'rejected_reason', 'lab']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('vl_lab_rejections');
    }
}
