<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVlSiteSuppression extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('vl_site_suppression', function (Blueprint $table) {
            $table->increments('ID');
            $table->date('dateupdated')->nullable();
            $table->integer('facility')->unsigned();
            $table->integer('suppressed')->unsigned()->default(0)->nullable();
            $table->integer('nonsuppressed')->unsigned()->default(0)->nullable();
            $table->double('suppression', 5, 4)->unsigned()->default(0)->nullable();
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
        Schema::dropIfExists('vl_site_suppression');
    }
}
