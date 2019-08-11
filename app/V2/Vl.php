<?php

namespace App\V2;

use App\V2\VlNation;
use App\V2\VlDivision;
use App\V2\VlFacility;
use DB;

class Vl
{
	private $age_gender_tables = ['vl_national_age_gender', 'vl_county_age_gender', 'vl_subcounty_age_gender', 'vl_partner_age_gender', 'vl_site_age_gender'];

	private $rejection_tables = ['vl_national_rejections', 'vl_county_rejections', 'vl_subcounty_rejections', 'vl_partner_rejections', 'vl_site_rejections'];

    public function update_nation($start_month, $year=null){
    	if($year == null){
    		$year = Date('Y');
    	}
    	// Instantiate new object
    	$n = new VlNation;

    	$today=date("Y-m-d");

    	$update_statements = "";
    	$updates = 0;

    	echo "\n Begin viralload nation update at " . date('d/m/Y h:i:s a', time());

    	// Get collection instances of the data

    	$rec_a = $n->getallreceivediraloadsamples($year, $start_month);
    	$tested_a = $n->getalltestedviraloadsamples($year, $start_month);
    	$actualpatients_a = $n->getallactualpatients($year, $start_month);
    	$rej_a = $n->getallrejectedviraloadsamples($year, $start_month);
    	$sites_a = $n->GetSupportedfacilitysFORViralLoad($year, $start_month);

    	$conftx_a = $n->GetNationalConfirmed2VLs($year, $start_month);
    	$conf2VL_a = $n->GetNationalConfirmedFailure($year, $start_month);
    	$rs=0;

    	$baseline_a = $n->GetNationalBaseline($year, $start_month);
    	$baselinefail_a = $n->GetNationalBaselineFailure($year, $start_month);

    	$ages_array = $n->getalltestedviraloadsbyage($year, $start_month);
    	$results_array = $n->getalltestedviraloadsbyresult($year, $start_month);
    	$sampletype_array = $n->getalltestedviraloadsamplesbytypedetails($year, $start_month);
    	$sampletype_a_array = $n->getalltestedviraloadsamplesbytypedetails($year, $start_month, false);
    	$gender_array = $n->getalltestedviraloadbygender($year, $start_month, false);

		$tat = $n->get_tat($year, $start_month);

		// Loop through the months and insert data into the national summary
		for ($i=$start_month; $i < 12; $i++) { 
			$month = $i + 1;
			if($year == Date('Y') && $month > Date('m')){ break; }

			$wheres = ['month' => $month];

			$rec = $this->checknull($rec_a, $wheres);
			$tested = $this->checknull($tested_a, $wheres);
			$actualpatients = $this->checknull($actualpatients_a, $wheres);
			$rej = $this->checknull($rej_a, $wheres);
			$sites = $this->checknull($sites_a, $wheres);

			$conftx = $this->checknull($conftx_a, $wheres);
			$conf2VL = $this->checknull($conf2VL_a, $wheres);

			$baseline = $this->checknull($baseline_a, $wheres);
			$baselinefail = $this->checknull($baselinefail_a, $wheres); 

			$noage = $this->checknull($ages_array->where('age_category', 0), $wheres);
			$less2 = $this->checknull($ages_array->where('age_category', 6), $wheres);
			$less9 = $this->checknull($ages_array->where('age_category', 7), $wheres);
			$less14 = $this->checknull($ages_array->where('age_category', 8), $wheres);
			$less19 = $this->checknull($ages_array->where('age_category', 9), $wheres);
			$less24 = $this->checknull($ages_array->where('age_category', 10), $wheres);
			$over25 = $this->checknull($ages_array->where('age_category', 11), $wheres);
			$adults = $less19 + $less24 + $over25;
			$paeds = $less2 + $less9 + $less14;

			$ldl = $this->checknull($results_array->where('rcategory', 1), $wheres);
			$less1k = $this->checknull($results_array->where('rcategory', 2), $wheres);
			$less5k = $this->checknull($results_array->where('rcategory', 3), $wheres);
			$above5k = $this->checknull($results_array->where('rcategory', 4), $wheres);
			$invalids = $this->checknull($results_array->where('rcategory', 5), $wheres);
			$sustx = $less5k +  $above5k;

			$plas = $this->checknull($sampletype_array->where('sampletype', 1), $wheres);
			$edta = $this->checknull($sampletype_array->where('sampletype', 2), $wheres);
			$dbs = $this->checknull($sampletype_array->where('sampletype', 3), $wheres);

			$aplas = $this->checknull($sampletype_a_array->where('sampletype', 1), $wheres);
			$aedta = $this->checknull($sampletype_a_array->where('sampletype', 2), $wheres);
			$adbs = $this->checknull($sampletype_a_array->where('sampletype', 3), $wheres);

			$male = $this->checknull($gender_array->where('sex', 1), $wheres);
			$female = $this->checknull($gender_array->where('sex', 2), $wheres);
			$nogender = $this->checknull($gender_array->where('sex', 3), $wheres);

			$tt = $this->check_tat($tat, $wheres);

			$data_array = array(
				'received' => $rec, 'alltests' => $tested, 'actualpatients' => $actualpatients,
				'sustxfail' => $sustx, 'confirmtx' => $conftx, 'repeattests' => $rs,
				'confirm2vl' => $conf2VL, 'rejected' => $rej, 'dbs' => $dbs, 'plasma' => $plas,
				'edta' => $edta, 'alldbs' => $adbs, 'allplasma' => $aplas, 'alledta' => $aedta,
				 'maletest' => $male, 'femaletest' => $female,
				'nogendertest' => $nogender, 'adults' => $adults, 'paeds' => $paeds,
				'noage' => $noage, 'Undetected' => $ldl, 'less1000' => $less1k,
				'less5000' => $less5k, 'above5000' => $above5k, 'invalids' => $invalids,
				'sitessending' => $sites, 'less2' => $less2, 'less9' => $less9,
				'less14' => $less14, 'less19' => $less19, 'less24' => $less24,
				'over25' => $over25, 'tat1' => $tt['tat1'], 'tat2' => $tt['tat2'],
				'tat3' => $tt['tat3'], 'tat4' => $tt['tat4'], 'baseline' => $baseline,
				'baselinesustxfail' => $baselinefail, 'dateupdated' => $today
			);

			DB::table('vl_national_summary')->where('year', $year)->where('month', $month)->update($data_array);
		}
		// End of for loop

		// $this->mysqli->multi_query($update_statements);

		echo "\n Completed entry into viralload national summary at " . date('d/m/Y h:i:s a', time());

		echo $this->finish_nation($start_month, $year, $today);
		echo $this->nation_rejections($start_month, $year, $today);
		echo $this->nation_age_gender($start_month, $year, $today);
    }

    public function nation_rejections($start_month, $year=null){

    	if($year == null){
    		$year = Date('Y');
    	}
    	// Instantiate new object
    	$n = new VlNation;
    	$update_statements = '';
    	$updates = 0;

    	$today=date("Y-m-d");

    	echo "\n Begin viralload nation rejections update at " . date('d/m/Y h:i:s a', time());

    	$reasons = DB::connection('eid_vl')->table('viralrejectedreasons')->select('id')->get();
    	$rej_a = $n->national_rejections($year, $start_month);

    	foreach ($reasons as $key => $value) {

    		// Loop through the months and insert data into the national summary
			for ($i=$start_month; $i < 12; $i++) { 
				$month = $i + 1;
				if($year == Date('Y') && $month > Date('m')){ break; }

				$rej = $this->checknull($rej_a->where('month', $month)->where('rejectedreason', $value->id));

				if($rej == 0) continue;

				$data_array = ['dateupdated' => $today, 'total' => $rej];
				DB::table('vl_national_rejections')->where('year', $year)->where('month', $month)
				->where('rejected_reason', $value->id)->update($data_array);
			}
    	}

    	echo "\n Completed viralload nation rejections update at " . date('d/m/Y h:i:s a', time());
    }

    public function finish_nation($start_month, $year, $today){
    	$n = new VlNation;

    	for ($type=1; $type < 7; $type++) { 

			$table = $this->get_table(0, $type);

			echo "\n Begin " . $table[0] . " update at " . date('d/m/Y h:i:s a', time());
			
			// Get ids of the necessary table
			$divs = $data = DB::connection('eid_vl')
			->table($table[1])->select('id')
			->when($type, function($query) use ($type){
				if($type == 1 || $type == 6) return $query->where('subid', 1);
				if($type == 5) return $query->where('flag', 1);
			})
			->get();

			foreach ($divs as $key => $value) {	

				// Get collection instances of the data

		    	// $rec_a = $n->getallreceivediraloadsamplesbydash($year, $start_month, $type, $value->id);
		    	$tested_a = $n->getalltestedviraloadsamplesbydash($year, $start_month, $type, $value->id);
		    	if($tested_a->isEmpty()) continue;
		    	$rej_a = $n->getallrejectedviraloadsamplesbydash($year, $start_month, $type, $value->id);
		    	$rs_a = $n->getallrepeattviraloadsamplesbydash($year, $start_month, $type, $value->id);
				

		    	$conftx_a = $n->GetNationalConfirmed2VLsbydash($year, $start_month, $type, $value->id);
		    	$conf2VL_a = $n->GetNationalConfirmedFailurebydash($year, $start_month, $type, $value->id);

		    	if ($type != 1 && $type != 6) {

			    	$age_categories_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $type, $value->id);

			    	if ($type == 3) {
				    	$age_categories_nonsup_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $type, $value->id, true);
			    	}
			    }

		    	// $adults=$less19 +$less24 + $over25 ;
				// $paeds=$less2 + $less9 + $less14;

				$rcategories_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $start_month, $type, $value->id);
				// $sustx=$less5k +  $above5k;

				if($type != 4 && $type != 6){
					$sample_types_a = $n->getalltestedviraloadsamplesbytypedetailsbydash($year, $start_month, $type, $value->id);
				}

				if($type != 2 && $type != 6){

					$sexes_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $start_month, $type, $value->id);

					if($type == 1 || $type == 3){
						$sexes_nonsup_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $start_month, $type, $value->id, true);
					}

				}

				if ($type != 5) {
					$baseline_a = $n->GetNationalBaselinebydash($year, $start_month, $type, $value->id);
					$baselinefail_a = $n->GetNationalBaselineFailurebydash($year, $start_month, $type, $value->id);
				}

				// Loop through the months and insert data into the national summary
				for ($i=$start_month; $i < 12; $i++) { 
					$month = $i + 1;
					if($year == Date('Y') && $month > Date('m')){ break; }

					$wheres = ['month' => $month];

					$tested = $this->checknull($tested_a, $wheres);

					if($tested == 0) continue;

					// $rec = $this->checknull($rec_a, $wheres);
					$rej = $this->checknull($rej_a, $wheres);
					$rs = $this->checknull($rs_a, $wheres);

					$conftx = $this->checknull($conftx_a, $wheres);
					$conf2VL = $this->checknull($conf2VL_a, $wheres);

					$ldl = $this->checknull($rcategories_a->where('rcategory', 1), $wheres);
					$less1k = $this->checknull($rcategories_a->where('rcategory', 2), $wheres);
					$less5k = $this->checknull($rcategories_a->where('rcategory', 3), $wheres);
					$above5k = $this->checknull($rcategories_a->where('rcategory', 4), $wheres);
					$invalids = $this->checknull($rcategories_a->where('rcategory', 5), $wheres);
					$sustx = $less5k +  $above5k;

					$data_array = array(
						'sustxfail' => $sustx, 'confirmtx' => $conftx, 
						'confirm2vl' => $conf2VL, 'rejected' => $rej, 'Undetected' => $ldl, 'less1000' => $less1k,
						'less5000' => $less5k, 'above5000' => $above5k, 'invalids' => $invalids,
						'dateupdated' => $today
					);

					if($type != 6){
						$data_array = array_merge($data_array, ['tests' => $tested, 'repeattests' => $rs]);
					}					

					if($type != 1 && $type != 6){

						$noage = $this->checknull($age_categories_a->where('age_category', 0), $wheres);
						$less2 = $this->checknull($age_categories_a->where('age_category', 6), $wheres);
						$less9 = $this->checknull($age_categories_a->where('age_category', 7), $wheres);
						$less14 = $this->checknull($age_categories_a->where('age_category', 8), $wheres);
						$less19 = $this->checknull($age_categories_a->where('age_category', 9), $wheres);
						$less24 = $this->checknull($age_categories_a->where('age_category', 10), $wheres);
						$over25 = $this->checknull($age_categories_a->where('age_category', 11), $wheres);
						$adults = $less19 + $less24 + $over25;
						$paeds = $less2 + $less9 + $less14;

						$age_array = array('less2' => $less2, 'less9' => $less9,
						'less14' => $less14, 'less19' => $less19, 'less24' => $less24,
						'over25' => $over25, 'adults' => $adults, 'paeds' => $paeds,
						'noage' => $noage);

						if($type == 3){

							$noage = $this->checknull($age_categories_nonsup_a->where('age_category', 0), $wheres);
							$less2 = $this->checknull($age_categories_nonsup_a->where('age_category', 6), $wheres);
							$less9 = $this->checknull($age_categories_nonsup_a->where('age_category', 7), $wheres);
							$less14 = $this->checknull($age_categories_nonsup_a->where('age_category', 8), $wheres);
							$less19 = $this->checknull($age_categories_nonsup_a->where('age_category', 9), $wheres);
							$less24 = $this->checknull($age_categories_nonsup_a->where('age_category', 10), $wheres);
							$over25 = $this->checknull($age_categories_nonsup_a->where('age_category', 11), $wheres);

							$age_array2 = array('less2_nonsuppressed' => $less2, 'less9_nonsuppressed' => $less9,
							'less14_nonsuppressed' => $less14, 'less19_nonsuppressed' => $less19, 'less24_nonsuppressed' => $less24,
							'over25_nonsuppressed' => $over25, 
							'noage_nonsuppressed' => $noage);

							$age_array = array_merge($age_array, $age_array2);
						}

						$data_array = array_merge($age_array, $data_array);

					}

					if($type != 4 && $type != 6){

						$plas = $this->checknull($sample_types_a->where('sampletype', 1), $wheres);
						$edta = $this->checknull($sample_types_a->where('sampletype', 2), $wheres);
						$dbs = $this->checknull($sample_types_a->where('sampletype', 3), $wheres);

						$sample_array = array('dbs' => $dbs, 'plasma' => $plas, 'edta' => $edta);

						$data_array = array_merge($sample_array, $data_array);

					}

					if ($type != 2 && $type != 6) {
					
						$male = $this->checknull($sexes_a->where('sex', 1), $wheres);
						$female = $this->checknull($sexes_a->where('sex', 2), $wheres);
						$nogender = $this->checknull($sexes_a->where('sex', 0), $wheres);

						$gender_array = array('maletest' => $male, 'femaletest' => $female, 'nogendertest' => $nogender);

						if($type == 1 || $type == 3){
							$males = $this->checknull($sexes_nonsup_a->where('sex', 1), $wheres);
							$females = $this->checknull($sexes_nonsup_a->where('sex', 2), $wheres);
							$nogenders = $this->checknull($sexes_nonsup_a->where('sex', 0), $wheres);

							$gender_array2 = array('malenonsuppressed' => $males, 'femalenonsuppressed' => $females, 'nogendernonsuppressed' => $nogenders);
							$gender_array = array_merge($gender_array, $gender_array2);
						}

						$data_array = array_merge($gender_array, $data_array);
					}

					if ($type != 5) {

						$baseline = $this->checknull($baseline_a, $wheres);
						$baselinefail = $this->checknull($baselinefail_a, $wheres);

						$baseline_array = array('baseline' => $baseline, 'baselinesustxfail' => $baselinefail);

						$data_array = array_merge($baseline_array, $data_array);
					}					

					DB::table($table[0])->where('year', $year)->where('month', $month)->where($table[2], $value->id)->update($data_array);
				}
				// End of for loop for months
			}			
			// End of looping through ids of each table e.g. agecategory
			echo "\n Completed " . $table[0] . " update at " . date('d/m/Y h:i:s a', time());
		}
		// End of looping of params
    }

    public function nation_age_gender($start_month, $year, $today)
    {
    	$ages = DB::connection('eid_vl')->table('agecategory')->where('subid', 1)->get();
    	$genders = DB::connection('eid_vl')->table('gender')->get();
    	$n = new VlNation;

		echo "\n Started vl_national_age_gender update at " . date('d/m/Y h:i:s a', time());

    	foreach ($ages as $age) {
    		foreach ($genders as $gender) {
    			$param = ['age_category' => $age->id, 'sex' => $gender->id];    			
    			$original_wheres = ['age' => $age->id, 'gender' => $gender->id];    			
		    	$rcategories_a = $n->get_results_by_multiple_params($year, $start_month, $param);

				// Loop through the months and insert data into the national summary
				for ($i=$start_month; $i < 12; $i++) { 
					$month = $i + 1;
					if($year == Date('Y') && $month > Date('m')){ break; }

					$wheres = ['month' => $month];

					$data_array['dateupdated'] = $today;
					$data_array['Undetected'] = $this->checknull($rcategories_a->where('rcategory', 1), $wheres);
					$data_array['less1000'] = $this->checknull($rcategories_a->where('rcategory', 2), $wheres);
					$data_array['less5000'] = $this->checknull($rcategories_a->where('rcategory', 3), $wheres);
					$data_array['above5000'] = $this->checknull($rcategories_a->where('rcategory', 4), $wheres);
					$data_array['invalids'] = $this->checknull($rcategories_a->where('rcategory', 5), $wheres);

					$locator = array_merge($original_wheres, $wheres);
					$locator = array_merge(['year' => $year, 'month' => $month], $locator);

					DB::table('vl_national_age_gender')->where($locator)->update($data_array);

				}
    		}
    	}
		echo "\n Completed vl_national_age_gender update at " . date('d/m/Y h:i:s a', time());
    }


    // For POC (vl_poc_summary), column is lab but division is poc 
    public function update_division($start_month, $year=null, $type=1, $column='county', $division='county', $div_table='countys', $sum_table='vl_county_summary', $rej_table='vl_county_rejections'){
    	if($year == null){
    		$year = Date('Y');
    	}
    	// Instantiate new object
    	$n = new VlFacility;
    	$update_statements = '';
    	$updates = 0;

    	ini_set("memory_limit", "-1");

    	$today=date("Y-m-d");

    	$div_array;
    	$array_size = 0;

    	$divs = $data = DB::connection('eid_vl')
		->table($div_table)->select('id')->get();

		foreach ($divs as $key => $value) {
			$div_array[$key] = $value->id;
			$array_size++;
		}

    	echo "\n Begin  viralload {$column} summary update at " . date('d/m/Y h:i:s a', time());

		// Loop through the months and insert data into the summary table
		for ($i=$start_month; $i < 12; $i++) { 
			$month = $i + 1;
			if($year == Date('Y') && $month > Date('m')){ break; }

	    	// Get collection instances of the data

	    	$rec_a = $n->getallreceivediraloadsamples($year, $month, $division);
	    	$tested_a = $n->getalltestedviraloadsamples($year, $month, $division);
	    	// $actualpatients_a = $n->getallactualpatients($year, $month, $division);
	    	$rej_a = $n->getallrejectedviraloadsamples($year, $month, $division);
	    	$sites_a = $n->GetSupportedfacilitysFORViralLoad($year, $month, $division);

	    	$conftx_a = $n->GetNationalConfirmed2VLs($year, $month, $division);
	    	$conf2VL_a = $n->GetNationalConfirmedFailure($year, $month, $division);
			$rs_a = $n->getallrepeattviraloadsamples($year, $month, $division);

	    	
			$baseline_a = $n->GetNationalBaseline($year, $month, $division);
	    	$baselinefail_a = $n->GetNationalBaselineFailure($year, $month, $division);

	    	$noage_a = $n->getalltestedviraloadsbyage($year, $month, $division, 0);
	    	$less2_a = $n->getalltestedviraloadsbyage($year, $month, $division, 6);
	    	$less9_a = $n->getalltestedviraloadsbyage($year, $month, $division, 7);
	    	$less14_a = $n->getalltestedviraloadsbyage($year, $month, $division, 8);
	    	$less19_a = $n->getalltestedviraloadsbyage($year, $month, $division, 9);
	    	$less24_a = $n->getalltestedviraloadsbyage($year, $month, $division, 10);
	    	$over25_a = $n->getalltestedviraloadsbyage($year, $month, $division, 11);
		    
	    	// $adults=$less19 +$less24 + $over25 ;
			// $paeds=$less2 + $less9 + $less14;

			$ldl_a = $n->getalltestedviraloadsbyresult($year, $month, $division, 1);
			$less1k_a = $n->getalltestedviraloadsbyresult($year, $month, $division, 2);
			$less5k_a = $n->getalltestedviraloadsbyresult($year, $month, $division, 3);
			$above5k_a = $n->getalltestedviraloadsbyresult($year, $month, $division, 4);
			$invalids_a = $n->getalltestedviraloadsbyresult($year, $month, $division, 5);
			// $sustx=$less5k +  $above5k;

			$plas_a = $n->getalltestedviraloadsamplesbytypedetails($year, $month, $division, 1);
			$edta_a = $n->getalltestedviraloadsamplesbytypedetails($year, $month, $division, 2);
			$dbs_a = $n->getalltestedviraloadsamplesbytypedetails($year, $month, $division, 3);

			$aplas_a = $n->getalltestedviraloadsamplesbytypedetails($year, $month, $division, 1, false);
			$aedta_a = $n->getalltestedviraloadsamplesbytypedetails($year, $month, $division, 2, false);
			$adbs_a = $n->getalltestedviraloadsamplesbytypedetails($year, $month, $division, 3, false);

			$male_a = $n->getalltestedviraloadbygender($year, $month, $division, 1);
			$female_a = $n->getalltestedviraloadbygender($year, $month, $division, 2);
			$nogender_a = $n->getalltestedviraloadbygender($year, $month, $division, 3);

			if($type == 5){
				$eqa_a = $n->get_eqa_tests($year, $month, $division);
				$fake_a = $n->false_confirmatory($year, $month, $division);
				$controls_a = $n->control_samples($year, $month);
				$calibrations_a = $n->calibration_samples($year, $month);
			}

			$tat = $n->get_tat($year, $month, $division);


			// Loop through divisions i.e. counties, subcounties, partners and sites
			for ($it=0; $it < $array_size; $it++) { 

				$wheres = [$column => $div_array[$it]];
				if($division == 'poc') $wheres = ['month' => $month];

				if($type == 3 && $div_array[$it] == 55 && $year < 2019) continue;

				$rec = $this->checknull($rec_a, $wheres);
				$tested = $this->checknull($tested_a, $wheres);

				if($rec == 0 && $tested == 0) continue;

				// $actualpatients = $this->checknull($actualpatients_a, $wheres);
				$rej = $this->checknull($rej_a, $wheres);
				$rs = $this->checknull($rs_a, $wheres);
				$sites = $this->checknull($sites_a, $wheres);

				$conftx = $this->checknull($conftx_a, $wheres);
				$conf2VL = $this->checknull($conf2VL_a, $wheres);

				
				$baseline = $this->checknull($baseline_a, $wheres);
				$baselinefail = $this->checknull($baselinefail_a, $wheres);

				$noage = $this->checknull($noage_a, $wheres);
				$less2 = $this->checknull($less2_a, $wheres);
				$less9 = $this->checknull($less9_a, $wheres);
				$less14 = $this->checknull($less14_a, $wheres);
				$less19 = $this->checknull($less19_a, $wheres);
				$less24 = $this->checknull($less24_a, $wheres);
				$over25 = $this->checknull($over25_a, $wheres);
				$adults = $less19 + $less24 + $over25;
				$paeds = $less2 + $less9 + $less14;
				

				$ldl = $this->checknull($ldl_a, $wheres);
				$less1k = $this->checknull($less1k_a, $wheres);
				$less5k = $this->checknull($less5k_a, $wheres);
				$above5k = $this->checknull($above5k_a, $wheres);
				$invalids = $this->checknull($invalids_a, $wheres);
				$sustx = $less5k +  $above5k;

				$plas = $this->checknull($plas_a, $wheres);
				$edta = $this->checknull($edta_a, $wheres);
				$dbs = $this->checknull($dbs_a, $wheres);

				$aplas = $this->checknull($aplas_a, $wheres);
				$aedta = $this->checknull($aedta_a, $wheres);
				$adbs = $this->checknull($adbs_a, $wheres);

				$male = $this->checknull($male_a, $wheres);
				$female = $this->checknull($female_a, $wheres);
				$nogender = $this->checknull($nogender_a, $wheres);

				$tt = $this->check_tat($tat, $wheres);
				

				$data_array = array(
					'received' => $rec, 'alltests' => $tested,
					'sustxfail' => $sustx, 'confirmtx' => $conftx, 'repeattests' => $rs,
					'confirm2vl' => $conf2VL, 'rejected' => $rej, 'dbs' => $dbs, 'plasma' => $plas,
					'edta' => $edta, 'alldbs' => $adbs, 'allplasma' => $aplas, 'alledta' => $aedta,
					'maletest' => $male, 'femaletest' => $female,
					'nogendertest' => $nogender, 'Undetected' => $ldl, 'less1000' => $less1k,
					'less5000' => $less5k, 'above5000' => $above5k, 'invalids' => $invalids,
					'sitessending' => $sites, 'tat1' => $tt['tat1'], 'tat2' => $tt['tat2'],
					'tat3' => $tt['tat3'], 'tat4' => $tt['tat4'],   'dateupdated' => $today,
					'less2' => $less2, 'less9' => $less9,
					'less14' => $less14, 'less19' => $less19, 'less24' => $less24,
					'over25' => $over25, 'adults' => $adults, 'paeds' => $paeds,
					'noage' => $noage, 'baseline' => $baseline, 'baselinesustxfail' => $baselinefail
				);

				if($type == 5){
					$eqa = $this->checknull($eqa_a, $wheres);
					$fake = $this->checknull($fake_a, $wheres);
					$controls = $this->checknull($controls_a, $wheres) * 3;
					// $controls = $this->checknull($controls_a, $wheres) * 3 + $this->checknull($calibrations_a, $wheres) * 8;
					$calibrations = $this->checknull($calibrations_a, $wheres) * 8;
					$data_array = array_merge(['eqa' => $eqa, 'fake_confirmatory' => $fake, 'controls' => $controls, 'calibrations' => $calibrations], $data_array);
				}

				if($division == 'poc'){
					DB::table($sum_table)->where('year', $year)->where('month', $month)->where($column, 11)->update($data_array);
					break;
				}

				DB::table($sum_table)->where('year', $year)->where('month', $month)->where($column, $div_array[$it])->update($data_array);
			}

			// If updating labs, also update vl poc summary
			// When division is poc, it only updates the poc row in the vl_lab_summary
			if($type == 5 && $division != 'poc'){
				$summary_table_two= "vl_poc_summary";

				foreach ($tested_a as $key => $value) {

					if($value->lab < 15) continue;
					$wheres = ['lab' => $value->lab];
					$tested = $value->totals;
					$rec = $this->checknull($rec_a, $wheres);

					$rej = $this->checknull($rej_a, $wheres);
					$rs = $this->checknull($rs_a, $wheres);
					$sites = $this->checknull($sites_a, $wheres);

					$conftx = $this->checknull($conftx_a, $wheres);
					$conf2VL = $this->checknull($conf2VL_a, $wheres);

					
					$baseline = $this->checknull($baseline_a, $wheres);
					$baselinefail = $this->checknull($baselinefail_a, $wheres);

					$noage = $this->checknull($noage_a, $wheres);
					$less2 = $this->checknull($less2_a, $wheres);
					$less9 = $this->checknull($less9_a, $wheres);
					$less14 = $this->checknull($less14_a, $wheres);
					$less19 = $this->checknull($less19_a, $wheres);
					$less24 = $this->checknull($less24_a, $wheres);
					$over25 = $this->checknull($over25_a, $wheres);
					$adults = $less19 + $less24 + $over25;
					$paeds = $less2 + $less9 + $less14;
					

					$ldl = $this->checknull($ldl_a, $wheres);
					$less1k = $this->checknull($less1k_a, $wheres);
					$less5k = $this->checknull($less5k_a, $wheres);
					$above5k = $this->checknull($above5k_a, $wheres);
					$invalids = $this->checknull($invalids_a, $wheres);
					$sustx = $less5k +  $above5k;

					$plas = $this->checknull($plas_a, $wheres);
					$edta = $this->checknull($edta_a, $wheres);
					$dbs = $this->checknull($dbs_a, $wheres);

					$aplas = $this->checknull($aplas_a, $wheres);
					$aedta = $this->checknull($aedta_a, $wheres);
					$adbs = $this->checknull($adbs_a, $wheres);

					$male = $this->checknull($male_a, $wheres);
					$female = $this->checknull($female_a, $wheres);
					$nogender = $this->checknull($nogender_a, $wheres);

					$tt = $this->check_tat($tat, $wheres);				

					$data_array = array(
						'received' => $rec, 'alltests' => $tested,
						'sustxfail' => $sustx, 'confirmtx' => $conftx, 'repeattests' => $rs,
						'confirm2vl' => $conf2VL, 'rejected' => $rej, 'dbs' => $dbs, 'plasma' => $plas,
						'edta' => $edta, 'alldbs' => $adbs, 'allplasma' => $aplas, 'alledta' => $aedta,
						'maletest' => $male, 'femaletest' => $female,
						'nogendertest' => $nogender, 'Undetected' => $ldl, 'less1000' => $less1k,
						'less5000' => $less5k, 'above5000' => $above5k, 'invalids' => $invalids,
						'sitessending' => $sites, 'tat1' => $tt['tat1'], 'tat2' => $tt['tat2'],
						'tat3' => $tt['tat3'], 'tat4' => $tt['tat4'],   'dateupdated' => $today,
						'less2' => $less2, 'less9' => $less9,
						'less14' => $less14, 'less19' => $less19, 'less24' => $less24,
						'over25' => $over25, 'adults' => $adults, 'paeds' => $paeds,
						'noage' => $noage, 'baseline' => $baseline, 'baselinesustxfail' => $baselinefail
					);

					// $eqa = $this->checknull($eqa_a, $wheres);
					// $fake = $this->checknull($fake_a, $wheres);
					// $controls = $this->checknull($controls_a, $wheres) * 3;
					// $data_array = array_merge(['eqa' => $eqa, 'fake_confirmatory' => $fake, 'controls' => $controls], $data_array);

					$locator = ['year' => $year, 'month' => $month, 'facility' => $value->lab];

					$row = DB::table($summary_table_two)->where($locator)->first();

					if(!$row){
						$data_array = array_merge($locator, $data_array);
						DB::table($summary_table_two)->insert($data_array);
					}
					else{
						DB::table($summary_table_two)->where('id', $row->ID)->update($data_array);
					}
				}
			}
			// Finish update vl_poc_summary when type=5 and division != poc


		}
		// End of for loop

		echo "\n Completed entry into viralload {$column} summary at " . date('d/m/Y h:i:s a', time());
		if($type == 5 && $division != 'poc') echo "\n Completed entry into viralload vl_poc_summary at " . date('d/m/Y h:i:s a', time());

		if($type == 5 && $division == 'poc') return null;


		if ($type < 5) {
			echo $this->division_age_gender($start_month, $year, $today, $div_array, $division, $type);
			if($type != 4) echo $this->finish_division($start_month, $year, $today, $div_array, $division, $type);
			echo $this->division_rejections($start_month, $year, $today, $div_array, $division, $type, $rej_table);
		}

		if($type == 5 && $division != 'poc'){
			echo $this->division_rejections($start_month, $year, $today, $div_array, $division, $type, $rej_table);

			echo $this->lab_mapping($start_month, $year);			
		}

    }



    public function lab_mapping($start_month, $year=null){
        $counties = DB::table('countys')->select('id')->orderBy('id')->get();
        $labs = DB::table('labs')->select('id')->orderBy('id')->get();

    	// $n = new VlDivision;
    	$n = new VlFacility;
    	$update_statements = '';
    	$updates = 0;
    	$today=date("Y-m-d");

    	echo "\n Begin entry into vl lab mapping at " . date('d/m/Y h:i:s a', time());

    	// $tests_a = $n->lab_county_tests($year, $start_month);
    	// $supported_sites_a = $n->lab_mapping_sites($year, $start_month);

    	for ($i=$start_month; $i < 12; $i++) { 
			$month = $i + 1;
			if($year == Date('Y') && $month > Date('m')){ break; }

	    	$tests_a = $n->lab_county_tests($year, $month);
	    	$supported_sites_a = $n->lab_mapping_sites($year, $month);


    		foreach ($counties as $county) {
		    	foreach ($labs as $lab) {
	    			$wheres = ['lab' => $lab->id, 'county' => $county->id];

	    			$search_array = array_merge($wheres, ['month' => $month, 'year' => $year]);
	    			$tests = $this->checknull($tests_a, $wheres);
	    			if($tests == 0) continue;
	    			$supported = $this->checknull($supported_sites_a, $wheres);

	    			$data_array = ['total' => $tests, 'site_sending' => $supported];

	    			DB::table('vl_lab_mapping')->where($search_array)->update($data_array);
	    		}
	    	}
	    }
	    // $this->mysqli->multi_query($update_statements);
    	echo "\n Completed entry into vl lab mapping at " . date('d/m/Y h:i:s a', time());
    }

    public function division_rejections($start_month, $year=null, $today, &$div_array, $division, $div_type, $rej_table){

    	if($year == null){
    		$year = Date('Y');
    	}
    	// Instantiate new object
    	$n = new VlDivision;
    	$array_size = sizeof($div_array);

    	$today=date("Y-m-d");

    	echo "\n Begin viralload {$rej_table} update at " . date('d/m/Y h:i:s a', time());

    	$reasons = DB::connection('eid_vl')->table('viralrejectedreasons')->select('id')->get();

    	foreach ($reasons as $key => $value) {
    		$rej_a = $n->national_rejections($year, $start_month, $division, $value->id);

    		if($division == 'lab') $poc_rej_a = $n->national_rejections($year, $start_month, 'poc', $value->id);

    		// Loop through the months and insert data into the national summary
			for ($i=$start_month; $i < 12; $i++) { 
				$month = $i + 1;
				if($year == Date('Y') && $month > Date('m')){ break; }

				// Loop through divisions i.e. counties, subcounties, partners and sites
				for ($it=0; $it < $array_size; $it++) { 
					if($division == 'partner' && $div_array[$it] == 55 && $year < 2019) continue;

					$rej = $this->checknull($rej_a->where('month', $month)->where($division, $div_array[$it]));

					if($rej == 0) continue;

					$data_array = ['dateupdated' => $today, 'total' => $rej];

					DB::table($rej_table)->where('year', $year)->where('month', $month)->where($division, $div_array[$it])
					->where('rejected_reason', $value->id)->update($data_array);
				}	

				if($division == 'lab')
				{					
					// Update POC row
					$rej = $this->checknull($poc_rej_a->where('month', $month));
					$data_array = ['total' => $rej, 'dateupdated' => $today];
					DB::table($rej_table)->where('year', $year)->where('month', $month)->where("lab", 11)->where('rejected_reason', $value->id)->update($data_array);
				}	
			}
    	}
    	echo "\n Completed viralload {$rej_table} update at " . date('d/m/Y h:i:s a', time());
    }

    // Div type is the type of division eg county, subcounty, partner and facility
    public function finish_division($start_month, $year, $today, &$div_array, $division, $div_type)
    {
    	ini_set("memory_limit", "-1");
    	$array_size = sizeof($div_array);

    	$n = new VlFacility;
    	$update_statements = '';
    	$updates = 0;

    	for ($type=1; $type < 7; $type++) { 
			$table = $this->get_table($div_type, $type);

			echo "\n Begin " . $table[0] . " update at " . date('d/m/Y h:i:s a', time());
			
			// Get ids of the necessary table
			$divs = $data = DB::connection('eid_vl')
			->table($table[1])->select('id')
			->when($type, function($query) use ($type){
				if($type == 1 || $type == 6) return $query->where('subid', 1);
				if($type == 5) return $query->where('flag', 1);
			})
			->get();

			foreach ($divs as $key => $value) {	


				// Loop through the months and insert data
				for ($i=$start_month; $i < 12; $i++) { 
					$month = $i + 1;
					if($year == Date('Y') && $month > Date('m')){ break; }

					// Get collection instances of the data
			    	// $rec_a = $n->getallreceivediraloadsamplesbydash($year, $month, $division, $type, $value->id);
			    	$tested_a = $n->getalltestedviraloadsamplesbydash($year, $month, $division, $type, $value->id);
			    	$rej_a = $n->getallrejectedviraloadsamplesbydash($year, $month, $division, $type, $value->id);

			    	if($tested_a->count() == 0 && $rej_a->count() == 0) continue;

			    	$conftx_a = $n->GetNationalConfirmed2VLsbydash($year, $month, $division, $type, $value->id);
			    	$conf2VL_a = $n->GetNationalConfirmedFailurebydash($year, $month, $division, $type, $value->id);
			    	// $rs = $n->getallrepeattviraloadsamplesbydash($year, $month, $division, $type, $value->id);
			    	$rs = 0;

			    	if ($type != 1 && $type != 6) {
				    	$noage_a = $n->getalltestedviraloadsamplesbyagebydash($year, $month, $division, $type, $value->id, 0);
				    	$less2_a = $n->getalltestedviraloadsamplesbyagebydash($year, $month, $division, $type, $value->id, 6);
				    	$less9_a = $n->getalltestedviraloadsamplesbyagebydash($year, $month, $division, $type, $value->id, 7);
				    	$less14_a = $n->getalltestedviraloadsamplesbyagebydash($year, $month, $division, $type, $value->id, 8);
				    	$less19_a = $n->getalltestedviraloadsamplesbyagebydash($year, $month, $division, $type, $value->id, 9);
				    	$less24_a = $n->getalltestedviraloadsamplesbyagebydash($year, $month, $division, $type, $value->id, 10);
				    	$over25_a = $n->getalltestedviraloadsamplesbyagebydash($year, $month, $division, $type, $value->id, 11);
				    }

			    	// $adults=$less19 +$less24 + $over25 ;
					// $paeds=$less2 + $less9 + $less14;

					$ldl_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $month, $division, $type, $value->id, 1);
					$less1k_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $month, $division, $type, $value->id, 2);
					$less5k_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $month, $division, $type, $value->id, 3);
					$above5k_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $month, $division, $type, $value->id, 4);
					$invalids_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $month, $division, $type, $value->id, 5);
					// $sustx=$less5k +  $above5k;

					if($type != 4 && $type != 6){
						$plas_a = $n->getalltestedviraloadsamplesbytypedetailsbydash($year, $month, $division, $type, $value->id, 1);
						$edta_a = $n->getalltestedviraloadsamplesbytypedetailsbydash($year, $month, $division, $type, $value->id, 2);
						$dbs_a = $n->getalltestedviraloadsamplesbytypedetailsbydash($year, $month, $division, $type, $value->id, 3);
					}

					if($type != 2 && $type != 6){
						$male_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $month, $division, $type, $value->id, 1);
						$female_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $month, $division, $type, $value->id, 2);
						$nogender_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $month, $division, $type, $value->id, 3);

					}

					if ($type != 5) {
						$baseline_a = $n->GetNationalBaselinebydash($year, $month, $division, $type, $value->id);
						$baselinefail_a = $n->GetNationalBaselineFailurebydash($year, $month, $division, $type, $value->id);
					}

					// Loop through divisions i.e. counties, subcounties, partners and sites
					for ($it=0; $it < $array_size; $it++) { 
						$wheres = [$division => $div_array[$it]];
						// $rec = $this->checknull($rec_a, $wheres);
						$tested = $this->checknull($tested_a, $wheres);

						if($tested == 0) continue;

						$rej = $this->checknull($rej_a, $wheres);

						$conftx = $this->checknull($conftx_a, $wheres);
						$conf2VL = $this->checknull($conf2VL_a, $wheres);

						$ldl = $this->checknull($ldl_a, $wheres);
						$less1k = $this->checknull($less1k_a, $wheres);
						$less5k = $this->checknull($less5k_a, $wheres);
						$above5k = $this->checknull($above5k_a, $wheres);
						$invalids = $this->checknull($invalids_a, $wheres);
						$sustx = $less5k +  $above5k;

						$data_array = array(
							'sustxfail' => $sustx, 'confirmtx' => $conftx,
							'confirm2vl' => $conf2VL, 'rejected' => $rej, 'Undetected' => $ldl, 'less1000' => $less1k,
							'less5000' => $less5k, 'above5000' => $above5k, 'invalids' => $invalids,
							'dateupdated' => $today
						);		

						if($type != 6){
							$data_array = array_merge($data_array, ['tests' => $tested, 'repeattests' => $rs]);
						}				

						if($type != 1 && $type != 6){

							$noage = $this->checknull($noage_a, $wheres);
							$less2 = $this->checknull($less2_a, $wheres);
							$less9 = $this->checknull($less9_a, $wheres);
							$less14 = $this->checknull($less14_a, $wheres);
							$less19 = $this->checknull($less19_a, $wheres);
							$less24 = $this->checknull($less24_a, $wheres);
							$over25 = $this->checknull($over25_a, $wheres);
							$adults = $less19 + $less24 + $over25;
							$paeds = $less2 + $less9 + $less14;

							$age_array = array('less2' => $less2, 'less9' => $less9,
							'less14' => $less14, 'less19' => $less19, 'less24' => $less24,
							'over25' => $over25, 'adults' => $adults, 'paeds' => $paeds,
							'noage' => $noage);

							$data_array = array_merge($age_array, $data_array);

						}

						if($type != 4 && $type != 6){

							$plas = $this->checknull($plas_a, $wheres);
							$edta = $this->checknull($edta_a, $wheres);
							$dbs = $this->checknull($dbs_a, $wheres);

							$sample_array = array('dbs' => $dbs, 'plasma' => $plas, 'edta' => $edta);

							$data_array = array_merge($sample_array, $data_array);

						}

						if ($type != 2 && $type != 6) {
						
							$male = $this->checknull($male_a, $wheres);
							$female = $this->checknull($female_a, $wheres);
							$nogender = $this->checknull($nogender_a, $wheres);

							$gender_array = array('maletest' => $male, 'femaletest' => $female, 'nogendertest' => $nogender);

							$data_array = array_merge($gender_array, $data_array);
						}

						if ($type != 5) {
						
							$baseline = $this->checknull($baseline_a, $wheres);
							$baselinefail = $this->checknull($baselinefail_a, $wheres);

							$baseline_array = array('baseline' => $baseline, 'baselinesustxfail' => $baselinefail);

							$data_array = array_merge($baseline_array, $data_array);
						}
						

						DB::table($table[0])->where('year', $year)->where('month', $month)->where($table[2], $value->id)->where($division, $div_array[$it])->update($data_array);
					}

				}
				// End of for loop for months
			}
			// End of looping through ids of each table e.g. agecategory
			echo "\n Completed " . $table[0] . " update at " . date('d/m/Y h:i:s a', time());
		}
		// End of looping of params
    }



    public function division_age_gender($start_month, $year, $today, &$div_array, $division, $div_type)
    {
    	$ages = DB::connection('eid_vl')->table('agecategory')->where('subid', 1)->get();
    	$genders = DB::connection('eid_vl')->table('gender')->get();
    	$n = new VlFacility;

    	$array_size = sizeof($div_array);

    	$table = $this->age_gender_tables[$div_type];

		echo "\n Started {$table} update at " . date('d/m/Y h:i:s a', time());

    	foreach ($ages as $age) {
    		foreach ($genders as $gender) {
    			$param = ['age_category' => $age->id, 'sex' => $gender->id];			
    			$original_wheres = ['age' => $age->id, 'gender' => $gender->id];  


				// Loop through the months and insert data into the national summary
				for ($i=$start_month; $i < 12; $i++) { 
					$month = $i + 1;
					if($year == Date('Y') && $month > Date('m')){ break; }

			    	$ldl_a = $n->get_results_by_multiple_params($year, $month, $division, array_merge($param, ['rcategory' => 1]));
			    	$less1k_a = $n->get_results_by_multiple_params($year, $month, $division, array_merge($param, ['rcategory' => 2]));
			    	$less5k_a = $n->get_results_by_multiple_params($year, $month, $division, array_merge($param, ['rcategory' => 3]));
			    	$above5k_a = $n->get_results_by_multiple_params($year, $month, $division, array_merge($param, ['rcategory' => 4]));
			    	$invalids_a = $n->get_results_by_multiple_params($year, $month, $division, array_merge($param, ['rcategory' => 5]));

					// Loop through divisions i.e. counties, subcounties, partners and sites
					for ($it=0; $it < $array_size; $it++) { 
						$wheres = [$division => $div_array[$it]];

						$data_array['dateupdated'] = $today;
						$data_array['Undetected'] = $this->checknull($ldl_a->where('rcategory', 1), $wheres);
						$data_array['less1000'] = $this->checknull($less1k_a->where('rcategory', 2), $wheres);
						$data_array['less5000'] = $this->checknull($less5k_a->where('rcategory', 3), $wheres);
						$data_array['above5000'] = $this->checknull($above5k_a->where('rcategory', 4), $wheres);
						$data_array['invalids'] = $this->checknull($invalids_a->where('rcategory', 5), $wheres);

						$locator = array_merge($original_wheres, $wheres);
						$locator = array_merge(['year' => $year, 'month' => $month], $locator);

						DB::table($table)->where($locator)->update($data_array);
					}
					// End of looping through divisions
				}
				// End of looping through months
    		}
    		// End of looping through genders
    	}
    	// End of looping though ages

		echo "\n Completed {$table} update at " . date('d/m/Y h:i:s a', time());
    }


    public function update_poc($start_month, $year=null){
    	if($year == null){
    		$year = Date('Y');
    	}
    	// Instantiate new object
    	$n = new VlDivision;
    	$update_statements = '';
    	$updates = 0;

    	ini_set("memory_limit", "-1");

    	$type=6;

    	$division = 'site_poc';
    	$column = 'facility';
    	$sum_table='vl_site_summary_poc';

    	$today = date('Y-m-d');

    	if($year == null) $year = Date('Y');


    	echo "\n Begin  viralload {$column} poc update at " . date('d/m/Y h:i:s a', time());

    	// Get collection instances of the data

    	$rec_a = $n->getallreceivediraloadsamples($year, $start_month, $division);
    	$tested_a = $n->getalltestedviraloadsamples($year, $start_month, $division);
    	// $actualpatients_a = $n->getallactualpatients($year, $start_month, $division);
    	$rej_a = $n->getallrejectedviraloadsamples($year, $start_month, $division);
    	$sites_a = $n->GetSupportedfacilitysFORViralLoad($year, $start_month, $division);

    	$conftx_a = $n->GetNationalConfirmed2VLs($year, $start_month, $division);
    	$conf2VL_a = $n->GetNationalConfirmedFailure($year, $start_month, $division);
		$rs_a = $n->getallrepeattviraloadsamples($year, $start_month, $division);

    	
		$baseline_a = $n->GetNationalBaseline($year, $start_month, $division);
    	$baselinefail_a = $n->GetNationalBaselineFailure($year, $start_month, $division);

    	$noage_a = $n->getalltestedviraloadsbyage($year, $start_month, $division, 0);
    	$less2_a = $n->getalltestedviraloadsbyage($year, $start_month, $division, 6);
    	$less9_a = $n->getalltestedviraloadsbyage($year, $start_month, $division, 7);
    	$less14_a = $n->getalltestedviraloadsbyage($year, $start_month, $division, 8);
    	$less19_a = $n->getalltestedviraloadsbyage($year, $start_month, $division, 9);
    	$less24_a = $n->getalltestedviraloadsbyage($year, $start_month, $division, 10);
    	$over25_a = $n->getalltestedviraloadsbyage($year, $start_month, $division, 11);
	    
    	// $adults=$less19 +$less24 + $over25 ;
		// $paeds=$less2 + $less9 + $less14;

		$ldl_a = $n->getalltestedviraloadsbyresult($year, $start_month, $division, 1);
		$less1k_a = $n->getalltestedviraloadsbyresult($year, $start_month, $division, 2);
		$less5k_a = $n->getalltestedviraloadsbyresult($year, $start_month, $division, 3);
		$above5k_a = $n->getalltestedviraloadsbyresult($year, $start_month, $division, 4);
		$invalids_a = $n->getalltestedviraloadsbyresult($year, $start_month, $division, 5);
		// $sustx=$less5k +  $above5k;

		$plas_a = $n->getalltestedviraloadsamplesbytypedetails($year, $start_month, $division, 1);
		$edta_a = $n->getalltestedviraloadsamplesbytypedetails($year, $start_month, $division, 2);
		$dbs_a = $n->getalltestedviraloadsamplesbytypedetails($year, $start_month, $division, 3);

		$aplas_a = $n->getalltestedviraloadsamplesbytypedetails($year, $start_month, $division, 1, false);
		$aedta_a = $n->getalltestedviraloadsamplesbytypedetails($year, $start_month, $division, 2, false);
		$adbs_a = $n->getalltestedviraloadsamplesbytypedetails($year, $start_month, $division, 3, false);

		$male_a = $n->getalltestedviraloadbygender($year, $start_month, $division, 1);
		$female_a = $n->getalltestedviraloadbygender($year, $start_month, $division, 2);
		$nogender_a = $n->getalltestedviraloadbygender($year, $start_month, $division, 3);

		if($type == 5){
			$eqa_a = $n->get_eqa_tests($year, $start_month, $division);
			$fake_a = $n->false_confirmatory($year, $start_month, $division);
			$controls_a = $n->control_samples($year, $start_month);
		}

		$tat = $n->get_tat($year, $start_month, $division);
		
		// DB::table($sum_table)->where('year', $year)->delete();

		// Loop through the months and insert data into the national summary
		for ($i=$start_month; $i < 12; $i++) { 
			$month = $i + 1;
			if($year == Date('Y') && $month > Date('m')){ break; }

			// Loop through divisions i.e. counties, subcounties, partners and sites
			// for ($it=0; $it < $array_size; $it++) { 

			foreach ($tested_a as $tested_row) {

				$wheres = ['month' => $month, 'facility' => $tested_row->facility];

				$rec = $this->checknull($rec_a, $wheres);
				$tested = $tested_row->totals;

				// $actualpatients = $this->checknull($actualpatients_a, $wheres);
				$rej = $this->checknull($rej_a, $wheres);
				$rs = $this->checknull($rs_a, $wheres);
				$sites = $this->checknull($sites_a, $wheres);

				$conftx = $this->checknull($conftx_a, $wheres);
				$conf2VL = $this->checknull($conf2VL_a, $wheres);

				
				$baseline = $this->checknull($baseline_a, $wheres);
				$baselinefail = $this->checknull($baselinefail_a, $wheres);

				$noage = $this->checknull($noage_a, $wheres);
				$less2 = $this->checknull($less2_a, $wheres);
				$less9 = $this->checknull($less9_a, $wheres);
				$less14 = $this->checknull($less14_a, $wheres);
				$less19 = $this->checknull($less19_a, $wheres);
				$less24 = $this->checknull($less24_a, $wheres);
				$over25 = $this->checknull($over25_a, $wheres);
				$adults = $less19 + $less24 + $over25;
				$paeds = $less2 + $less9 + $less14;
				

				$ldl = $this->checknull($ldl_a, $wheres);
				$less1k = $this->checknull($less1k_a, $wheres);
				$less5k = $this->checknull($less5k_a, $wheres);
				$above5k = $this->checknull($above5k_a, $wheres);
				$invalids = $this->checknull($invalids_a, $wheres);
				$sustx = $less5k +  $above5k;

				$plas = $this->checknull($plas_a, $wheres);
				$edta = $this->checknull($edta_a, $wheres);
				$dbs = $this->checknull($dbs_a, $wheres);

				$aplas = $this->checknull($aplas_a, $wheres);
				$aedta = $this->checknull($aedta_a, $wheres);
				$adbs = $this->checknull($adbs_a, $wheres);



				$male = $this->checknull($male_a, $wheres);
				$female = $this->checknull($female_a, $wheres);
				$nogender = $this->checknull($nogender_a, $wheres);

				$tt = $this->check_tat($tat, $wheres);
				

				$data_array = [
					'facility_tested_in' => $tested_row->lab_id,
					'received' => $rec, 'alltests' => $tested,
					'sustxfail' => $sustx, 'confirmtx' => $conftx, 'repeattests' => $rs,
					'confirm2vl' => $conf2VL, 'rejected' => $rej, 'dbs' => $dbs, 'plasma' => $plas,
					'edta' => $edta, 'alldbs' => $adbs, 'allplasma' => $aplas, 'alledta' => $aedta,
					'maletest' => $male, 'femaletest' => $female,
					'nogendertest' => $nogender, 'Undetected' => $ldl, 'less1000' => $less1k,
					'less5000' => $less5k, 'above5000' => $above5k, 'invalids' => $invalids,
					'sitessending' => $sites, 'tat1' => $tt['tat1'], 'tat2' => $tt['tat2'],
					'tat3' => $tt['tat3'], 'tat4' => $tt['tat4'],   'dateupdated' => $today,
					'less2' => $less2, 'less9' => $less9,
					'less14' => $less14, 'less19' => $less19, 'less24' => $less24,
					'over25' => $over25, 'adults' => $adults, 'paeds' => $paeds,
					'noage' => $noage, 'baseline' => $baseline, 'baselinesustxfail' => $baselinefail
				];

				if($type == 5){
					$eqa = $this->checknull($eqa_a, $wheres);
					$fake = $this->checknull($fake_a, $wheres);
					$controls = $this->checknull($controls_a, $wheres) * 3;
					$data_array = array_merge(['eqa' => $eqa, 'fake_confirmatory' => $fake, 'controls' => $controls], $data_array);
				}

				$wheres = array_merge($wheres, ['year' => $year]);

				$row = DB::table($sum_table)->where($wheres)->first();

				if($row) DB::table($sum_table)->where('id', $row->ID)->update($data_array);
				else{
					$data_array = array_merge($data_array, $wheres);
					 DB::table($sum_table)->insert($data_array);
				}				
			}

		}
		// End of for loop

		echo "\n Completed entry into viralload {$column} poc summary at " . date('d/m/Y h:i:s a', time());
    }

    // Finish POC
    public function finish_poc($start_month, $year=null){
    	ini_set("memory_limit", "-1");

    	$n = new VlFacility;
    	$update_statements = '';
    	$updates = 0;

    	$division = 'site_poc';
    	$column = 'facility';

    	$today = date('Y-m-d');

    	if($year == null) $year = Date('Y');

    	for ($type=1; $type < 3; $type++) { 

			$table = $this->get_table(6, $type);

			echo "\n Begin " . $table[0] . " update at " . date('d/m/Y h:i:s a', time());
			
			// Get ids of the necessary table
			$divs = $data = DB::connection('eid_vl')
			->table($table[1])->select('id')
			->when($type, function($query) use ($type){
				if($type == 1 || $type == 6){
					return $query->where('subid', 1);
				}				
			})
			->get();

			foreach ($divs as $key => $value) {	


				// Loop through the months and insert data
				for ($i=$start_month; $i < 12; $i++) { 
					$month = $i + 1;
					if($year == Date('Y') && $month > Date('m')){ break; }

					// Get collection instances of the data
			    	// $rec_a = $n->getallreceivediraloadsamplesbydash($year, $month, $division, $type, $value->id);
			    	$tested_a = $n->getalltestedviraloadsamplesbydash($year, $month, $division, $type, $value->id);
			    	$rej_a = $n->getallrejectedviraloadsamplesbydash($year, $month, $division, $type, $value->id);

			    	if($tested_a->count() == 0 && $rej_a->count() == 0) continue;

			    	$conftx_a = $n->GetNationalConfirmed2VLsbydash($year, $month, $division, $type, $value->id);
			    	$conf2VL_a = $n->GetNationalConfirmedFailurebydash($year, $month, $division, $type, $value->id);
			    	// $rs = $n->getallrepeattviraloadsamplesbydash($year, $month, $division, $type, $value->id);
			    	$rs = 0;

			    	if ($type != 1 && $type != 6) {

				    	$noage_a = $n->getalltestedviraloadsamplesbyagebydash($year, $month, $division, $type, $value->id, 0);
				    	$less2_a = $n->getalltestedviraloadsamplesbyagebydash($year, $month, $division, $type, $value->id, 6);
				    	$less9_a = $n->getalltestedviraloadsamplesbyagebydash($year, $month, $division, $type, $value->id, 7);
				    	$less14_a = $n->getalltestedviraloadsamplesbyagebydash($year, $month, $division, $type, $value->id, 8);
				    	$less19_a = $n->getalltestedviraloadsamplesbyagebydash($year, $month, $division, $type, $value->id, 9);
				    	$less24_a = $n->getalltestedviraloadsamplesbyagebydash($year, $month, $division, $type, $value->id, 10);
				    	$over25_a = $n->getalltestedviraloadsamplesbyagebydash($year, $month, $division, $type, $value->id, 11);
				    }

			    	// $adults=$less19 +$less24 + $over25 ;
					// $paeds=$less2 + $less9 + $less14;

					$ldl_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $month, $division, $type, $value->id, 1);
					$less1k_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $month, $division, $type, $value->id, 2);
					$less5k_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $month, $division, $type, $value->id, 3);
					$above5k_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $month, $division, $type, $value->id, 4);
					$invalids_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $month, $division, $type, $value->id, 5);
					// $sustx=$less5k +  $above5k;

					if($type != 4 && $type != 6){

						$plas_a = $n->getalltestedviraloadsamplesbytypedetailsbydash($year, $month, $division, $type, $value->id, 1);
						$edta_a = $n->getalltestedviraloadsamplesbytypedetailsbydash($year, $month, $division, $type, $value->id, 2);
						$dbs_a = $n->getalltestedviraloadsamplesbytypedetailsbydash($year, $month, $division, $type, $value->id, 3);
					}

					if($type != 2 && $type != 6){

						$male_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $month, $division, $type, $value->id, 1);
						$female_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $month, $division, $type, $value->id, 2);
						$nogender_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $month, $division, $type, $value->id, 3);

					}

					if ($type != 5) {
						$baseline_a = $n->GetNationalBaselinebydash($year, $month, $division, $type, $value->id);
						$baselinefail_a = $n->GetNationalBaselineFailurebydash($year, $month, $division, $type, $value->id);
					}

					// Loop through divisions i.e. counties, subcounties, partners and sites
					// for ($it=0; $it < $array_size; $it++) { 

					foreach ($tested_a as $tested_row) {

						$wheres = [$column => $tested_row->facility];
						// $rec = $this->checknull($rec_a, $wheres);
						$tested = $this->checknull($tested_a, $wheres);

						$rej = $this->checknull($rej_a, $wheres);

						$conftx = $this->checknull($conftx_a, $wheres);
						$conf2VL = $this->checknull($conf2VL_a, $wheres);

						$ldl = $this->checknull($ldl_a, $wheres);
						$less1k = $this->checknull($less1k_a, $wheres);
						$less5k = $this->checknull($less5k_a, $wheres);
						$above5k = $this->checknull($above5k_a, $wheres);
						$invalids = $this->checknull($invalids_a, $wheres);
						$sustx = $less5k +  $above5k;

						$data_array = array(
							'sustxfail' => $sustx, 'confirmtx' => $conftx,
							'confirm2vl' => $conf2VL, 'rejected' => $rej, 'Undetected' => $ldl, 'less1000' => $less1k,
							'less5000' => $less5k, 'above5000' => $above5k, 'invalids' => $invalids,
							'dateupdated' => $today
						);		

						if($type != 6){
							$data_array = array_merge($data_array, ['tests' => $tested, 'repeattests' => $rs]);
						}				

						if($type != 1 && $type != 6){

							$noage = $this->checknull($noage_a, $wheres);
							$less2 = $this->checknull($less2_a, $wheres);
							$less9 = $this->checknull($less9_a, $wheres);
							$less14 = $this->checknull($less14_a, $wheres);
							$less19 = $this->checknull($less19_a, $wheres);
							$less24 = $this->checknull($less24_a, $wheres);
							$over25 = $this->checknull($over25_a, $wheres);
							$adults = $less19 + $less24 + $over25;
							$paeds = $less2 + $less9 + $less14;

							$age_array = array('less2' => $less2, 'less9' => $less9,
							'less14' => $less14, 'less19' => $less19, 'less24' => $less24,
							'over25' => $over25, 'adults' => $adults, 'paeds' => $paeds,
							'noage' => $noage);

							$data_array = array_merge($age_array, $data_array);

						}

						if($type != 4 && $type != 6){

							$plas = $this->checknull($plas_a, $wheres);
							$edta = $this->checknull($edta_a, $wheres);
							$dbs = $this->checknull($dbs_a, $wheres);

							$sample_array = array('dbs' => $dbs, 'plasma' => $plas, 'edta' => $edta);

							$data_array = array_merge($sample_array, $data_array);

						}

						if ($type != 2 && $type != 6) {
						
							$male = $this->checknull($male_a, $wheres);
							$female = $this->checknull($female_a, $wheres);
							$nogender = $this->checknull($nogender_a, $wheres);

							$gender_array = array('maletest' => $male, 'femaletest' => $female, 'nogendertest' => $nogender);

							$data_array = array_merge($gender_array, $data_array);
						}

						if ($type != 5) {
						
							$baseline = $this->checknull($baseline_a, $wheres);
							$baselinefail = $this->checknull($baselinefail_a, $wheres);

							$baseline_array = array('baseline' => $baseline, 'baselinesustxfail' => $baselinefail);

							$data_array = array_merge($baseline_array, $data_array);
						}

						$wheres = ['year' => $year, 'month' => $month, 'facility' => $tested_row->facility, $table[2] => $value->id];

						$row = DB::table($table[0])->where($wheres)->first();

						if($row) DB::table($table[0])->where('ID', $row->ID)->update($data_array);
						else{
							$data_array = array_merge($data_array, $wheres);
							DB::table($table[0])->insert($data_array);	
						}	
					}

				}
				// End of for loop for months
			}
			// End of looping through ids of each table e.g. agecategory
			echo "\n Completed " . $table[0] . " update at " . date('d/m/Y h:i:s a', time());
		}
		// $this->mysqli->multi_query($update_statements);
		// End of looping of params
    }



    public function update_counties($start_month, $year=null){
    	return $this->update_division($start_month, $year, 1, 'county', 'county', 'countys', 'vl_county_summary', 'vl_county_rejections');
    }

    public function update_subcounties($start_month, $year=null){
    	return $this->update_division($start_month, $year, 2, 'subcounty', 'subcounty', 'districts', 'vl_subcounty_summary', 'vl_subcounty_rejections');
    }

    public function update_partners($start_month, $year=null){
    	return $this->update_division($start_month, $year, 3, 'partner', 'partner', 'partners', 'vl_partner_summary', 'vl_partner_rejections');
    }

    public function update_facilities($start_month, $year=null){
    	return $this->update_division($start_month, $year, 4, 'facility', 'facility', 'facilitys', 'vl_site_summary', 'vl_site_rejections');
    }

    public function finish_facilities($start_month, $year=null){
    	if($year == null){
    		$year = Date('Y');
    	}
    	// Instantiate new object
    	$n = new VlDivision;

    	$today=date("Y-m-d");

    	$div_array;
    	$array_size = 0;

    	$divs = DB::connection('eid_vl')->table('facilitys')->select('id')->get();

		foreach ($divs as $key => $value) {
			$div_array[$key] = $value->id;
			$array_size++;
		}

		// echo $this->finish_division($start_month, $year, $today, $div_array, 'facility', 'viralsamples.facility', 4, $array_size);
		return $this->finish_division($start_month, $year, $today, $div_array, 'facility', 4);
    }

    public function update_labs($start_month, $year=null){
    	echo $this->update_division($start_month, $year, 5, 'lab', 'poc', 'labs', 'vl_lab_summary', 'vl_lab_rejections');
    	return $this->update_division($start_month, $year, 5, 'lab', 'lab', 'labs', 'vl_lab_summary', 'vl_lab_rejections');
    }

    public function finish_labs(){
    	echo "\n Begin lablogs update at " . date('d/m/Y h:i:s a', time());

    	$logdate=	date("Y-m-d");
    	$datestatsupdated = date('Y-m-d H:i:s');

    	$labs = $data = DB::table('labs')->select('id')->get();
    	$testtypes = $data = DB::table('testtype')->select('id')->get();

    	foreach ($labs as $key => $lab) {
			foreach ($testtypes as $key2 => $ttype) {
				DB::table('lablogs')->insert([
					'lab' => $lab->id, 'logdate' => $logdate, 'dateupdated' => $datestatsupdated,
					'testtype' => $ttype->id
				]);
			}
		}

		echo "\n Completed lablogs update at " . date('d/m/Y h:i:s a', time());

    }

    public function update_patients(){
    	echo "\n Begin entry into vl patients at " . date('d/m/Y h:i:s a', time()); 

    	// Instantiate new object
    	$n = new VlDivision;

    	echo $n->update_patients();

    	echo "\n Completed entry into vl patients at " . date('d/m/Y h:i:s a', time()); 
    }

    public function update_suppression(){
    	ini_set("memory_limit", "-1");

    	echo "\n Begin entry into vl suppression at " . date('d/m/Y h:i:s a', time()); 

    	// Instantiate new object
    	$n = new VlDivision;

    	$today=date('Y-m-d');

    	$data = $n->suppression(); 

		$noage = $n->current_age_suppression(0);
    	$less2 = $n->current_age_suppression(6);
    	$less9 = $n->current_age_suppression(7);
    	$less14 = $n->current_age_suppression(8);
    	$less19 = $n->current_age_suppression(9);
    	$less24 = $n->current_age_suppression(10);
    	$over25 = $n->current_age_suppression(11);

    	$noage_n= $n->current_age_suppression(0, false);
    	$less2_n = $n->current_age_suppression(6, false);
    	$less9_n = $n->current_age_suppression(7, false);
    	$less14_n = $n->current_age_suppression(8, false);
    	$less19_n = $n->current_age_suppression(9, false);
    	$less24_n = $n->current_age_suppression(10, false);
    	$over25_n = $n->current_age_suppression(11, false);

    	$male = $n->current_gender_suppression(1);  
    	$female = $n->current_gender_suppression(2);  
    	$nogender = $n->current_gender_suppression(3);

    	$male_n = $n->current_gender_suppression(1, false);  
    	$female_n = $n->current_gender_suppression(2, false);  
    	$nogender_n = $n->current_gender_suppression(3, false);

    	$divs = DB::table('facilitys')->select('id', 'totalartsep17')->get();

		$data = collect($data);


		foreach ($divs as $key => $value) {

			$wheres = ['facility' => $value->id];

			$Undetected = (int) $this->checknull($data->where('rcategory', 1), $wheres);
			$less1000 = (int) $this->checknull($data->where('rcategory', 2), $wheres);
			$suppressed = $Undetected + $less1000;

			$less5000 = (int) $this->checknull($data->where('rcategory', 3), $wheres); 
			$above5000 = (int) $this->checknull($data->where('rcategory', 4), $wheres);
			$nonsuppressed = $less5000 + $above5000;

			$tests =  ($suppressed + $nonsuppressed);
			$suppression = $coverage = 0;

			if($tests == 0){
				// continue;
			}
			else{

				$suppression = ($suppressed * 100) / $tests;

				if($value->totalartsep17 != null){
					$coverage = ($tests * 100) / (int) $value->totalartsep17 ;
				}
				else{
					$coverage = 100;
				}
			}

			$noage_sup =  $this->checknull($noage, $wheres);
			$noage_nonsup =  $this->checknull($noage_n, $wheres);

			$less2_sup = $this->checknull($less2, $wheres);
			$less2_nonsup =  $this->checknull($less2_n, $wheres);

			$less9_sup = $this->checknull($less9, $wheres);
			$less9_nonsup =  $this->checknull($less9_n, $wheres);

			$less14_sup = $this->checknull($less14, $wheres);
			$less14_nonsup =  $this->checknull($less14_n, $wheres);

			$less19_sup = $this->checknull($less19, $wheres);
			$less19_nonsup =  $this->checknull($less19_n, $wheres);

			$less24_sup = $this->checknull($less24, $wheres);
			$less24_nonsup =  $this->checknull($less24_n, $wheres);

			$over25_sup = $this->checknull($over25, $wheres);
			$over25_nonsup = $this->checknull($over25_n, $wheres);


			$male_sup = $this->checknull($male, $wheres);
			$male_nonsup = $this->checknull($male_n, $wheres);

			$female_sup = $this->checknull($female, $wheres);
			$female_nonsup = $this->checknull($female_n, $wheres); 

			$nogender_sup = $this->checknull($nogender, $wheres);
			$nogender_nonsup = $this->checknull($nogender_n, $wheres);
			

			$data_array = array('dateupdated' => $today, 'suppressed' => $suppressed, 
				'nonsuppressed' => $nonsuppressed, 'suppression' => $suppression, 'coverage' => $coverage,

				'Undetected' => $Undetected, 'less1000' => $less1000, 'less5000' => $less5000, 'above5000' => $above5000,

				'noage_suppressed' => $noage_sup, 'noage_nonsuppressed' => $noage_nonsup,
				'less2_suppressed' => $less2_sup, 'less2_nonsuppressed' => $less2_nonsup,
				'less9_suppressed' => $less9_sup, 'less9_nonsuppressed' => $less9_nonsup,
				'less14_suppressed' => $less14_sup, 'less14_nonsuppressed' => $less14_nonsup,
				'less19_suppressed' => $less19_sup, 'less19_nonsuppressed' => $less19_nonsup,
				'less24_suppressed' => $less24_sup, 'less24_nonsuppressed' => $less24_nonsup,
				'over25_suppressed' => $over25_sup, 'over25_nonsuppressed' => $over25_nonsup,

				'male_suppressed' => $male_sup, 'male_nonsuppressed' => $male_nonsup,
				'female_suppressed' => $female_sup, 'female_nonsuppressed' => $female_nonsup,
				'nogender_suppressed' => $nogender_sup, 'nogender_nonsuppressed' => $nogender_nonsup
			);

			DB::table('vl_site_suppression')->where('facility', $value->id)->update($data_array);
		}

    	echo "\n Completed entry into vl suppression at " . date('d/m/Y h:i:s a', time()); 
    }

    public function update_suppression_datim(){
    	ini_set("memory_limit", "-1");

    	echo "\n Begin entry into vl suppression at " . date('d/m/Y h:i:s a', time()); 

    	// Instantiate new object
    	$n = new VlDivision;

    	$today=date('Y-m-d');

    	$b1s = $n->current_datim_suppression(0.0000001, 1, true);
    	$b1n = $n->current_datim_suppression(0.0000001, 1, false);

    	$b5s = $n->current_datim_suppression(1, 5, true);
    	$b5n = $n->current_datim_suppression(1, 5, false);

    	$b10s = $n->current_datim_suppression(5, 10, true);
    	$b10n = $n->current_datim_suppression(5, 10, false);

    	$b15s = $n->current_datim_suppression(10, 15, true);
    	$b15n = $n->current_datim_suppression(10, 15, false);

    	$b20s = $n->current_datim_suppression(15, 20, true);
    	$b20n = $n->current_datim_suppression(15, 20, false);

    	$b25s = $n->current_datim_suppression(20, 25, true);
    	$b25n = $n->current_datim_suppression(20, 25, false);

    	$b30s = $n->current_datim_suppression(25, 30, true);
    	$b30n = $n->current_datim_suppression(25, 30, false);

    	$b35s = $n->current_datim_suppression(30, 35, true);
    	$b35n = $n->current_datim_suppression(30, 35, false);

    	$b30s = $n->current_datim_suppression(25, 30, true);
    	$b30n = $n->current_datim_suppression(25, 30, false);

    	$b35s = $n->current_datim_suppression(30, 35, true);
    	$b35n = $n->current_datim_suppression(30, 35, false);

    	$b40s = $n->current_datim_suppression(35, 40, true);
    	$b40n = $n->current_datim_suppression(35, 40, false);

    	$b45s = $n->current_datim_suppression(40, 45, true);
    	$b45n = $n->current_datim_suppression(40, 45, false);

    	$b50s = $n->current_datim_suppression(45, 50, true);
    	$b50n = $n->current_datim_suppression(45, 50, false);

    	$a50s = $n->current_datim_suppression(50, false, true);
    	$a50n = $n->current_datim_suppression(50, false, false);

    	$ts = $n->current_datim_suppression(0, false, true);
    	$tn = $n->current_datim_suppression(0, false, false);

    	$divs = DB::table('facilitys')->select('id')->get();

		foreach ($divs as $key => $value) {

			$wheres = ['facility' => $value->id];
			$mw = ['facility' => $value->id, 'sex' => 1];
			$fw = ['facility' => $value->id, 'sex' => 2];
			$nw = ['facility' => $value->id, 'sex' => 0];			

			$data_array = array('dateupdated' => $today,
  
				'below1_m_sup' => $this->checknull($b1s, $mw),
				'below1_f_sup' => $this->checknull($b1s, $fw),
				'below1_u_sup' => $this->checknull($b1s, $nw),
				'below1_m_nonsup' => $this->checknull($b1n, $mw),
				'below1_f_nonsup' => $this->checknull($b1n, $fw),
				'below1_u_nonsup' => $this->checknull($b1n, $nw),

				'below5_m_sup' => $this->checknull($b5s, $mw),
				'below5_f_sup' => $this->checknull($b5s, $fw),
				'below5_u_sup' => $this->checknull($b5s, $nw),
				'below5_m_nonsup' => $this->checknull($b5n, $mw),
				'below5_f_nonsup' => $this->checknull($b5n, $fw),
				'below5_u_nonsup' => $this->checknull($b5n, $nw),

				'below10_m_sup' => $this->checknull($b10s, $mw),
				'below10_f_sup' => $this->checknull($b10s, $fw),
				'below10_u_sup' => $this->checknull($b10s, $nw),
				'below10_m_nonsup' => $this->checknull($b10n, $mw),
				'below10_f_nonsup' => $this->checknull($b10n, $fw),
				'below10_u_nonsup' => $this->checknull($b10n, $nw),

				'below15_m_sup' => $this->checknull($b15s, $mw),
				'below15_f_sup' => $this->checknull($b15s, $fw),
				'below15_u_sup' => $this->checknull($b15s, $nw),
				'below15_m_nonsup' => $this->checknull($b15n, $mw),
				'below15_f_nonsup' => $this->checknull($b15n, $fw),
				'below15_u_nonsup' => $this->checknull($b15n, $nw),

				'below20_m_sup' => $this->checknull($b20s, $mw),
				'below20_f_sup' => $this->checknull($b20s, $fw),
				'below20_u_sup' => $this->checknull($b20s, $nw),
				'below20_m_nonsup' => $this->checknull($b20n, $mw),
				'below20_f_nonsup' => $this->checknull($b20n, $fw),
				'below20_u_nonsup' => $this->checknull($b20n, $nw),

				'below25_m_sup' => $this->checknull($b25s, $mw),
				'below25_f_sup' => $this->checknull($b25s, $fw),
				'below25_u_sup' => $this->checknull($b25s, $nw),
				'below25_m_nonsup' => $this->checknull($b25n, $mw),
				'below25_f_nonsup' => $this->checknull($b25n, $fw),
				'below25_u_nonsup' => $this->checknull($b25n, $nw),

				'below30_m_sup' => $this->checknull($b30s, $mw),
				'below30_f_sup' => $this->checknull($b30s, $fw),
				'below30_u_sup' => $this->checknull($b30s, $nw),
				'below30_m_nonsup' => $this->checknull($b30n, $mw),
				'below30_f_nonsup' => $this->checknull($b30n, $fw),
				'below30_u_nonsup' => $this->checknull($b30n, $nw),

				'below35_m_sup' => $this->checknull($b35s, $mw),
				'below35_f_sup' => $this->checknull($b35s, $mw),
				'below35_u_sup' => $this->checknull($b35s, $mw),
				'below35_m_nonsup' => $this->checknull($b35n, $mw),
				'below35_f_nonsup' => $this->checknull($b35n, $mw),
				'below35_u_nonsup' => $this->checknull($b35n, $mw),

				'below40_m_sup' => $this->checknull($b40s, $mw),
				'below40_f_sup' => $this->checknull($b40s, $fw),
				'below40_u_sup' => $this->checknull($b40s, $nw),
				'below40_m_nonsup' => $this->checknull($b40n, $mw),
				'below40_f_nonsup' => $this->checknull($b40n, $fw),
				'below40_u_nonsup' => $this->checknull($b40n, $nw),

				'below45_m_sup' => $this->checknull($b45s, $mw),
				'below45_f_sup' => $this->checknull($b45s, $fw),
				'below45_u_sup' => $this->checknull($b45s, $nw),
				'below45_m_nonsup' => $this->checknull($b45n, $mw),
				'below45_f_nonsup' => $this->checknull($b45n, $fw),
				'below45_u_nonsup' => $this->checknull($b45n, $nw),

				'below50_m_sup' => $this->checknull($b50s, $mw),
				'below50_f_sup' => $this->checknull($b50s, $fw),
				'below50_u_sup' => $this->checknull($b50s, $nw),
				'below50_m_nonsup' => $this->checknull($b50n, $mw),
				'below50_f_nonsup' => $this->checknull($b50n, $fw),
				'below50_u_nonsup' => $this->checknull($b50n, $nw),

				'above50_m_sup' => $this->checknull($a50s, $mw),
				'above50_f_sup' => $this->checknull($a50s, $fw),
				'above50_u_sup' => $this->checknull($a50s, $nw),
				'above50_m_nonsup' => $this->checknull($a50n, $mw),
				'above50_f_nonsup' => $this->checknull($a50n, $fw),
				'above50_u_nonsup' => $this->checknull($a50n, $nw),

				'total_m_sup' => $this->checknull($ts, $mw),
				'total_f_sup' => $this->checknull($ts, $fw),
				'total_u_sup' => $this->checknull($ts, $nw),
				'total_m_nonsup' => $this->checknull($tn, $mw),
				'total_f_nonsup' => $this->checknull($tn, $fw),
				'total_u_nonsup' => $this->checknull($tn, $nw),
			);

			DB::table('vl_site_suppression_datim')->where('facility', $value->id)->update($data_array);
		}

    	echo "\n Completed entry into vl suppression datim at " . date('d/m/Y h:i:s a', time()); 
    }

    public function update_tat($year=null){
    	if($year == null){
    		$year = Date('Y');
    	}
    	// Instantiate new object
    	$n = new VlNation;

    	echo $n->update_tats($year);
    }

    public function update_confirmatory($year=null){
    	if($year == null){
    		$year = Date('Y');
    	}
    	// Instantiate new object
    	$n = new VlNation;

    	echo $n->confirmatory_report($year);
    	// echo $n->confirmatory_v();
    }


    public function checknull($var, $wheres=[]){
    	foreach ($wheres as $key => $value) {
    		$var = $var->where($key, $value);
    	}
    	return $var->first()->totals ?? 0;
    }


    public function checknull_test(&$var, $wheres=[]){
    	$obj = $var[0] ?? null;

    	if(!$obj) return 0;

    	foreach ($wheres as $key => $value) {
    		$var = $var->where($key, $value);
    		if($obj->$key != $value) return 0;
    	}
    	$obj = $var->shift();
    	return $obj->totals ?? 0;
    }



    public function check_tat($var, $wheres=[]){
    	foreach ($wheres as $key => $value) {
    		$var = $var->where($key, $value);
    	}
    	if($var->isEmpty()){
    		return array('tat1' => 0, 'tat2' => 0, 'tat3' => 0, 'tat4' => 0);
    	}else{
    		$t = $var->first();
    		return array('tat1' => $t->tat1, 'tat2' => $t->tat2, 'tat3' => $t->tat3, 'tat4' => $t->tat4);
    	}
    }

    private function get_table($division, $type){
    	$name;
    	if ($division == 0) {
    		switch ($type) {
    			case 1:
    				$name = array("vl_national_age", "agecategory", "age");
    				break;
    			case 2:
    				$name = array("vl_national_gender", "gender", "gender");
    				break;
    			case 3:
    				$name = array("vl_national_prophylaxis", "viralregimen", "regimen");
    				break;
    			case 4:
    				$name = array("vl_national_sampletype", "viralsampletypedetails", "sampletype");
    				break;
    			case 5:
    				$name = array("vl_national_justification", "viraljustifications", "justification");
    				break;
    			case 6:
    				$name = array("vl_national_pmtct", "viralpmtcttype", "pmtcttype");
    				break;
    			case 7:
    				$name = array("vl_national_rejections", "viralrejectedreasons", "rejectedreason");
    				break;
    			default:
    				break;
    		}
    	}
    	else if ($division == 1) {
    		switch ($type) {
    			case 1:
    				$name = array("vl_county_age", "agecategory", "age");
    				break;
    			case 2:
    				$name = array("vl_county_gender", "gender", "gender");
    				break;
    			case 3:
    				$name = array("vl_county_prophylaxis", "viralregimen", "regimen");
    				break;
    			case 4:
    				$name = array("vl_county_sampletype", "viralsampletypedetails", "sampletype");
    				break;
    			case 5:
    				$name = array("vl_county_justification", "viraljustifications", "justification");
    				break;
    			case 6:
    				$name = array("vl_county_pmtct", "viralpmtcttype", "pmtcttype");
    				break;
    			default:
    				break;
    		}
    	}

    	else if ($division == 2) {
    		switch ($type) {
    			case 1:
    				$name = array("vl_subcounty_age", "agecategory", "age");
    				break;
    			case 2:
    				$name = array("vl_subcounty_gender", "gender", "gender");
    				break;
    			case 3:
    				$name = array("vl_subcounty_prophylaxis", "viralregimen", "regimen");
    				break;
    			case 4:
    				$name = array("vl_subcounty_sampletype", "viralsampletypedetails", "sampletype");
    				break;
    			case 5:
    				$name = array("vl_subcounty_justification", "viraljustifications", "justification");
    				break;
    			case 6:
    				$name = array("vl_subcounty_pmtct", "viralpmtcttype", "pmtcttype");
    				break;
    			default:
    				break;
    		}
    	}

    	else if ($division == 3) {
    		switch ($type) {
    			case 1:
    				$name = array("vl_partner_age", "agecategory", "age");
    				break;
    			case 2:
    				$name = array("vl_partner_gender", "gender", "gender");
    				break;
    			case 3:
    				$name = array("vl_partner_prophylaxis", "viralregimen", "regimen");
    				break;
    			case 4:
    				$name = array("vl_partner_sampletype", "viralsampletypedetails", "sampletype");
    				break;
    			case 5:
    				$name = array("vl_partner_justification", "viraljustifications", "justification");
    				break;
    			case 6:
    				$name = array("vl_partner_pmtct", "viralpmtcttype", "pmtcttype");
    				break;
    			default:
    				break;
    		}
    	}

    	else if ($division == 4) {
    		switch ($type) {
    			case 1:
    				$name = array("vl_site_age", "agecategory", "age");
    				break;
    			case 2:
    				$name = array("vl_site_gender", "gender", "gender");
    				break;
    			case 3:
    				$name = array("vl_site_prophylaxis", "viralregimen", "regimen");
    				break;
    			case 4:
    				$name = array("vl_site_sampletype", "viralsampletypedetails", "sampletype");
    				break;
    			case 5:
    				$name = array("vl_site_justification", "viraljustifications", "justification");
    				break;
    			case 6:
    				$name = array("vl_site_pmtct", "viralpmtcttype", "pmtcttype");
    				break;
    			default:
    				break;
    		}
    	}

    	else if ($division == 6) {
    		switch ($type) {
    			case 1:
    				$name = array("vl_site_age_poc", "agecategory", "age");
    				break;
    			case 2:
    				$name = array("vl_site_gender_poc", "gender", "gender");
    				break;
    			case 3:
    				$name = array("vl_site_prophylaxis_poc", "viralregimen", "regimen");
    				break;
    			case 4:
    				$name = array("vl_site_sampletype_poc", "viralsampletypedetails", "sampletype");
    				break;
    			case 5:
    				$name = array("vl_site_justification_poc", "viraljustifications", "justification");
    				break;
    			case 6:
    				$name = array("vl_site_pmtct_poc", "viralpmtcttype", "pmtcttype");
    				break;
    			default:
    				break;
    		}
    	}
    	return $name;
    }


    public function update_query($table, $data_array, $search_array)
    {
    	$sql = "UPDATE `{$table}` SET ";

    	foreach ($data_array as $key => $value) {
    		$sql .= "`{$key}` = '{$value}', ";
    	}

    	$sql = substr($sql, 0, -2);

    	$sql .= ' WHERE ';

    	foreach ($search_array as $key => $value) {
    		$sql .= "`{$key}` = '{$value}' AND ";
    	}

    	$sql = substr($sql, 0, -5);
    	$sql .= "; ";
    	return $sql;
    }
}
