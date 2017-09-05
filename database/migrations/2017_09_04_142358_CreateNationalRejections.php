<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNationalRejections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('national_rejections', function (Blueprint $table) {
            $table->increments('ID');
            $table->date('dateupdated')->nullable();
            $table->integer('month')->unsigned()->default(6);
            $table->integer('year')->unsigned()->default(6);
            $table->integer('serum_rings')->unsigned()->default(0)->nullable();
            $table->integer('clotted_samples')->unsigned()->default(0)->nullable();
            $table->integer('samples_packaged_together')->unsigned()->default(0)->nullable();
            $table->integer('small_spots')->unsigned()->default(0)->nullable();
            $table->integer('poor_drying')->unsigned()->default(0)->nullable();
            $table->integer('other')->unsigned()->default(0)->nullable();
            $table->integer('overage_adult')->unsigned()->default(0)->nullable();
            $table->integer('expired_filter_paper')->unsigned()->default(0)->nullable();
            $table->integer('serum_rings')->unsigned()->default(0)->nullable();
            $table->integer('serum_rings')->unsigned()->default(0)->nullable();
            $table->integer('serum_rings')->unsigned()->default(0)->nullable();
            $table->integer('serum_rings')->unsigned()->default(0)->nullable();
            $table->integer('serum_rings')->unsigned()->default(0)->nullable();
            $table->integer('serum_rings')->unsigned()->default(0)->nullable();
            $table->integer('serum_rings')->unsigned()->default(0)->nullable();
            $table->integer('serum_rings')->unsigned()->default(0)->nullable();
            $table->integer('serum_rings')->unsigned()->default(0)->nullable();
            $table->integer('serum_rings')->unsigned()->default(0)->nullable();
            $table->integer('serum_rings')->unsigned()->default(0)->nullable();
            $table->index(['month', 'year']);
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
        Schema::dropIfExists('national_rejections');
    }
}
