<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class VlInsert extends Model
{
    //
    public function rejections($year=null, $month=null){
    	if($year == null){
    		$year = Date('Y');
    	}
    	if($month == null){
    		$month = Date('m');
    	}

    	echo "\n Begin vl rejection insert at " . date('d/m/Y h:i:s a', time());

    	$reasons = DB::connection('vl')->table('viralrejectedreasons')->select('ID')->get();
    	$counties = DB::connection('vl')->table('countys')->select('ID')->get();
    	$subcounties = DB::connection('vl')->table('districts')->select('ID')->get();
    	$partners = DB::connection('vl')->table('partners')->select('ID')->get();
    	$labs = DB::connection('vl')->table('labs')->select('ID')->get();
    	$sites = DB::connection('vl')->table('facilitys')->select('ID')->get();

    	$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->ID);
			$i++;
		}
		DB::table('vl_national_rejections')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($counties as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->ID, 'county' => $val->ID);
				$i++;
			}
		}
		DB::table('vl_county_rejections')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($subcounties as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->ID, 'subcounty' => $val->ID);
				$i++;
			}
		}
		DB::table('vl_subcounty_rejections')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($partners as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->ID, 'partner' => $val->ID);
				$i++;
			}
		}
		DB::table('vl_partner_rejections')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($labs as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->ID, 'lab' => $val->ID);
				$i++;
			}
		}
		DB::table('vl_lab_rejections')->insert($data_array);

		// $data_array=null;
    	// $i=0;

		// foreach ($reasons as $key => $value) {
		// 	foreach ($sites as $k => $val) {
		// 		$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->ID, 'facility' => $val->ID);
		// 		$i++;
		// 	}
		// }
		// DB::table('site_rejections')->insert($data_array);

		echo "\n Completed vl rejection insert at " . date('d/m/Y h:i:s a', time());
    }
}
