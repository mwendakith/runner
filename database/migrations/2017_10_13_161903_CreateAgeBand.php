<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgeBand extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('age_bands', function (Blueprint $table) {
            $table->increments('ID');
            $table->string('name');
            $table->double('lower', 10, 5)->unsigned()->nullable();
            $table->double('upper', 10, 5)->unsigned()->nullable();
            $table->integer('age_range_id');
            $table->string('age_range');
            $table->double('lower_range', 10, 5)->unsigned()->nullable();
            $table->double('upper_range', 10, 5)->unsigned()->nullable();
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
        Schema::dropIfExists('age_bands');
    }
}
