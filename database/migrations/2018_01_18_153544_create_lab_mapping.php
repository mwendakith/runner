<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabMapping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_mapping', function (Blueprint $table) {
            $table->increments('ID');
            $table->date('dateupdated')->nullable();
            $table->integer('lab')->unsigned();
            $table->integer('month')->unsigned();
            $table->integer('year')->unsigned();
            $table->integer('county')->unsigned();
            $table->integer('total')->unsigned()->default(0)->nullable();
            $table->integer('site_sending')->unsigned()->default(0)->nullable();

            $table->index('lab');
            $table->index('month');
            $table->index('year');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lab_mapping');
    }
}
