<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class EidInsert extends Model
{
    //
    public function rejections($year=null, $month=null){
    	if($year == null){
    		$year = Date('Y');
    	}
    	if($month == null){
    		$month = Date('m');
    	}
		ini_set("memory_limit", "-1");

    	echo "\n Begin eid rejection insert at " . date('d/m/Y h:i:s a', time());

    	$reasons =  DB::connection('eid')->table('rejectedreasons')->select('ID')->orderBy('ID')->get();
    	$counties =  DB::connection('eid')->table('countys')->select('ID')->orderBy('ID')->get();
    	$subcounties =  DB::connection('eid')->table('districts')->select('ID')->orderBy('ID')->get();
    	$partners =  DB::connection('eid')->table('partners')->select('ID')->orderBy('ID')->get();
    	$labs = DB::connection('eid')->table('labs')->select('ID')->orderBy('ID')->get();
    	$sites = DB::connection('eid')->table('facilitys')->select('ID')->orderBy('ID')->get();

    	$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->ID);
			$i++;
		}
		DB::table('national_rejections')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($counties as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->ID, 'county' => $val->ID);
				$i++;
			}
		}
		DB::table('county_rejections')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($subcounties as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->ID, 'subcounty' => $val->ID);
				$i++;
			}
		}
		DB::table('subcounty_rejections')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($partners as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->ID, 'partner' => $val->ID);
				$i++;
			}
		}
		DB::table('ip_rejections')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($labs as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->ID, 'lab' => $val->ID);
				$i++;
			}
		}
		DB::table('lab_rejections')->insert($data_array);

		echo "\n Completed eid else rejection insert at " . date('d/m/Y h:i:s a', time());

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($sites as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->ID, 'facility' => $val->ID);
				$i++;
			}
			DB::table('site_rejections')->insert($data_array);
			$data_array=null;
	    	$i=0;
		}
		

		echo "\n Completed eid rejection insert at " . date('d/m/Y h:i:s a', time());
    }
}
