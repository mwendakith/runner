<?php

use Illuminate\Foundation\Inspiring;
use \App\V2\Vl;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('clean:storage', function () {
	\App\Cleaner::clean_storage();
})->describe('Alter MyIsam tables to InnoDB.');

Artisan::command('update:vl-datim-suppression', function () {
	$n = new Vl;
	$a = $n->update_suppression_datim();
	$this->comment($a);
})->describe('Update VL Datim Suppression.');

Artisan::command('update:vl-dhis {year?} {--month=0}', function ($year=null, $month=0) {
	if(!$year) $year = date('Y');
	$n = new Vl;
	$a = $n->update_dhis($month, $year);
	$this->comment($a);
})->describe('Update VL DHIS table.');

Artisan::command('create:missing-rows {year}', function ($year) {
	\App\Cleaner::eid_missing_site_rows($year);
	\App\Cleaner::vl_missing_site_rows($year);
})->describe('Alter MyIsam tables to InnoDB.');




