<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountyAgeBreakdownTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('county_age_breakdown', function (Blueprint $table) {
            $table->increments('ID');
            $table->date('dateupdated')->nullable();
            $table->integer('year')->unsigned();
            $table->integer('month')->unsigned();
            $table->integer('county')->unsigned();
            $table->integer('age_band_id')->unsigned();
            $table->integer('pos')->unsigned()->default(0);
            $table->integer('neg')->unsigned()->default(0);

            $table->index(['year']);
            $table->index(['month']);
            $table->index(['county']);
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
        Schema::dropIfExists('county_age_breakdown');
    }
}
