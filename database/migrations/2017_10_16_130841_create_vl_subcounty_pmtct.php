<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVlSubcountyPmtct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('vl_subcounty_pmtct', function (Blueprint $table) {
            $table->increments('ID');
            $table->date('dateupdated')->nullable();
            $table->integer('year')->unsigned();
            $table->integer('month')->unsigned();
            $table->integer('subcounty')->unsigned();
            $table->integer('pmtcttype')->unsigned();

            $table->integer('sustxfail')->unsigned()->default(0);
            $table->integer('confirmtx')->unsigned()->default(0);
            $table->integer('confirm2vl')->unsigned()->default(0);
            $table->integer('baseline')->unsigned()->default(0);
            $table->integer('baselinesustxfail')->unsigned()->default(0);
            $table->integer('rejected')->unsigned()->default(0);
            $table->integer('invalids')->unsigned()->default(0);

            $table->integer('undetected')->unsigned()->default(0);
            $table->integer('less1000')->unsigned()->default(0);
            $table->integer('less5000')->unsigned()->default(0);
            $table->integer('above5000')->unsigned()->default(0);

            $table->index(['year']);
            $table->index(['month']);
            $table->index(['subcounty']);
            $table->index(['pmtcttype']);
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
        Schema::dropIfExists('vl_subcounty_pmtct');
    }
}
