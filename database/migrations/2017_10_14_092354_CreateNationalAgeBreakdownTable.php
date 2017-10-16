<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNationalAgeBreakdownTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('national_age_breakdown', function (Blueprint $table) {
            $table->increments('ID');
            $table->date('dateupdated')->nullable();
            $table->integer('year')->unsigned();
            $table->integer('month')->unsigned();
            $table->integer('age_band_id')->unsigned();
            $table->integer('pos')->unsigned()->default(0);
            $table->integer('neg')->unsigned()->default(0);

            $table->index(['year']);
            $table->index(['month']);
            $table->index(['age_band_id']);
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
        Schema::dropIfExists('national_age_breakdown');
    }
}
