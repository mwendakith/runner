<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteRejections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('site_rejections', function (Blueprint $table) {
            $table->increments('ID');
            $table->date('dateupdated')->nullable();
            $table->integer('facility')->unsigned();
            $table->integer('month')->unsigned();
            $table->integer('year')->unsigned();
            $table->integer('rejected_reason')->unsigned();
            $table->integer('total')->unsigned()->default(0)->nullable();
            $table->index(['month', 'year', 'rejected_reason', 'facility']);
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
        Schema::dropIfExists('site_rejections');
    }
}
