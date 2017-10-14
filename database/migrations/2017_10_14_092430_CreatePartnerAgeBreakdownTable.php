<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnerAgeBreakdownTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('ip_age_breakdown', function (Blueprint $table) {
            $table->increments('ID');
            $table->integer('year')->unsigned();
            $table->integer('month')->unsigned();
            $table->integer('partner')->unsigned();
            $table->integer('age_band_id')->unsigned();
            $table->integer('pos')->unsigned()->default(0);
            $table->integer('neg')->unsigned()->default(0);

            $table->index(['year']);
            $table->index(['month']);
            $table->index(['partner']);
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
        Schema::dropIfExists('ip_age_breakdown');
    }
}
