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
            $table->integer('month')->unsigned();
            $table->integer('year')->unsigned();
            $table->integer('rejected_reason')->unsigned();
            $table->integer('total')->unsigned()->default(0)->nullable();

            // $table->integer('serum_rings')->unsigned()->default(0)->nullable();
            // $table->integer('clotted_samples')->unsigned()->default(0)->nullable();
            // $table->integer('samples_packaged_together')->unsigned()->default(0)->nullable();
            // $table->integer('small_spots')->unsigned()->default(0)->nullable();
            // $table->integer('poor_drying')->unsigned()->default(0)->nullable();
            // $table->integer('other')->unsigned()->default(0)->nullable();
            // $table->integer('overage_adult')->unsigned()->default(0)->nullable();
            // $table->integer('expired_filter_paper')->unsigned()->default(0)->nullable();
            // $table->integer('humidity_indicator')->unsigned()->default(0)->nullable();
            // $table->integer('under_aged')->unsigned()->default(0)->nullable();
            // $table->integer('overage_child')->unsigned()->default(0)->nullable();
            // $table->integer('no_request_form')->unsigned()->default(0)->nullable();
            // $table->integer('patientid_error')->unsigned()->default(0)->nullable();
            // $table->integer('oversaturation')->unsigned()->default(0)->nullable();
            // $table->integer('scratched_dbs_spots')->unsigned()->default(0)->nullable();
            // $table->integer('double_entry')->unsigned()->default(0)->nullable();
            // $table->integer('insufficient_sample_volume')->unsigned()->default(0)->nullable();
            // $table->index(['month', 'year', 'rejected_reason']);
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
