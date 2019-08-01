<?php

namespace App;


class Backup 
{
    //

    // For POC (vl_poc_summary), column is lab but division is poc 
    public function update_division($start_month, $year=null, $type=1, $column='county', $division='county', $div_table='countys', $sum_table='vl_county_summary', $rej_table='vl_county_rejections'){
    	if($year == null){
    		$year = Date('Y');
    	}
    	// Instantiate new object
    	$n = new VlDivision;
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

    	echo "\n Begin  viralload {$column} update at " . date('d/m/Y h:i:s a', time());

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
		$edta_a = $n->getalltestedviraloadsamplesbytypedetails($year, $start_month, $division, 3);
		$dbs_a = $n->getalltestedviraloadsamplesbytypedetails($year, $start_month, $division, 2);

		$aplas_a = $n->getalltestedviraloadsamplesbytypedetails($year, $start_month, $division, 1, false);
		$aedta_a = $n->getalltestedviraloadsamplesbytypedetails($year, $start_month, $division, 3, false);
		$adbs_a = $n->getalltestedviraloadsamplesbytypedetails($year, $start_month, $division, 2, false);

		$male_a = $n->getalltestedviraloadbygender($year, $start_month, $division, 1);
		$female_a = $n->getalltestedviraloadbygender($year, $start_month, $division, 2);
		$nogender_a = $n->getalltestedviraloadbygender($year, $start_month, $division, 3);

		if($type == 5){
			$eqa_a = $n->get_eqa_tests($year, $start_month, $division);
			$fake_a = $n->false_confirmatory($year, $start_month, $division);
			$controls_a = $n->control_samples($year, $start_month);
			$calibrations_a = $n->calibration_samples($year, $start_month);
		}

		$tat = $n->get_tat($year, $start_month, $division);

		// Loop through the months and insert data into the national summary
		for ($i=$start_month; $i < 12; $i++) { 
			$month = $i + 1;
			if($year == Date('Y') && $month > Date('m')){ break; }

			// Loop through divisions i.e. counties, subcounties, partners and sites
			for ($it=0; $it < $array_size; $it++) { 

				$wheres = ['month' => $month, $column => $div_array[$it]];
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
		}
		// End of for loop

		echo "\n Completed entry into viralload {$column} summary at " . date('d/m/Y h:i:s a', time());

		if($type == 5 && $division == 'poc') return null;

		if($type == 5){

			$summary_table = "vl_poc_summary";
			foreach ($tested_a as $key => $value) {
				if($value->lab < 15) continue;
				$wheres = ['month' => $value->month, 'lab' => $value->lab];

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

				$locator = ['year' => $year, 'month' => $value->month, 'facility' => $value->lab];

				$row = DB::table($summary_table)->where($locator)->first();

				if(!$row){
					$data_array = array_merge($locator, $data_array);
					DB::table($summary_table)->insert($data_array);
				}
				else{
					DB::table($summary_table)->where('id', $row->ID)->update($data_array);
				}
			}
			echo "\n Completed entry into viralload poc summary at " . date('d/m/Y h:i:s a', time());
		}


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

    
    // Div type is the type of division eg county, subcounty, partner and facility
    public function finish_division($start_month, $year, $today, &$div_array, $division, $div_type){
    	ini_set("memory_limit", "-1");

    	$n = new VlDivision;
    	$array_size = sizeof($div_array);

    	for ($type=1; $type < 7; $type++) { 

    		if($type == 3 && $division == "facility") continue;

			$table = $this->get_table($div_type, $type);

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

				// Get collection instances of the data
		    	// $rec_a = $n->getallreceivediraloadsamplesbydash($year, $month, $division, $type, $value->id);
		    	$tested_a = $n->getalltestedviraloadsamplesbydash($year, $start_month, $division, $type, $value->id);
		    	$rej_a = $n->getallrejectedviraloadsamplesbydash($year, $start_month, $division, $type, $value->id);
		    	// $rs_a = $n->getallrepeattviraloadsamplesbydash($year, $start_month, $division, $type, $value->id);

		    	$conftx_a = $n->GetNationalConfirmed2VLsbydash($year, $start_month, $division, $type, $value->id);
		    	$conf2VL_a = $n->GetNationalConfirmedFailurebydash($year, $start_month, $division, $type, $value->id);

		    	if ($type != 1 && $type != 6) {

			    	$noage_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $division, $type, $value->id, 0);
			    	$less2_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $division, $type, $value->id, 6);
			    	$less9_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $division, $type, $value->id, 7);
			    	$less14_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $division, $type, $value->id, 8);
			    	$less19_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $division, $type, $value->id, 9);
			    	$less24_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $division, $type, $value->id, 10);
			    	$over25_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $division, $type, $value->id, 11);
			    }

		    	// $adults=$less19 +$less24 + $over25 ;
				// $paeds=$less2 + $less9 + $less14;

				$ldl_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $start_month, $division, $type, $value->id, 1);
				$less1k_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $start_month, $division, $type, $value->id, 2);
				$less5k_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $start_month, $division, $type, $value->id, 3);
				$above5k_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $start_month, $division, $type, $value->id, 4);
				$invalids_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $start_month, $division, $type, $value->id, 5);
				// $sustx=$less5k +  $above5k;

				if($type != 4 && $type != 6){

					$plas_a = $n->getalltestedviraloadsamplesbytypedetailsbydash($year, $start_month, $division, $type, $value->id, 1);
					$edta_a = $n->getalltestedviraloadsamplesbytypedetailsbydash($year, $start_month, $division, $type, $value->id, 3);
					$dbs_a = $n->getalltestedviraloadsamplesbytypedetailsbydash($year, $start_month, $division, $type, $value->id, 2);
				}

				if($type != 2 && $type != 6){

					$male_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $start_month, $division, $type, $value->id, 1);
					$female_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $start_month, $division, $type, $value->id, 2);
					$nogender_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $start_month, $division, $type, $value->id, 3);

					if($type == 1 && $div_type == 1){
						$male_nonsup_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $start_month, $division, $type, $value->id, 1, true);
						$female_nonsup_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $start_month, $division, $type, $value->id, 2, true);
						$nogender_nonsup_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $start_month, $division, $type, $value->id, 3, true);
					}
				}

				if ($type != 5) {
					$baseline_a = $n->GetNationalBaselinebydash($year, $start_month, $division, $type, $value->id);
					$baselinefail_a = $n->GetNationalBaselineFailurebydash($year, $start_month, $division, $type, $value->id);
				}


				// Loop through the months and insert data
				for ($i=$start_month; $i < 12; $i++) { 
					$month = $i + 1;
					if($year == Date('Y') && $month > Date('m')){ break; }

					// Loop through divisions i.e. counties, subcounties, partners and sites
					for ($it=0; $it < $array_size; $it++) { 
						$wheres = ['month' => $month, $division => $div_array[$it]];

						if($division == 'partner' && $div_array[$it] == 55 && $year < 2019) continue;

						// $rec = $this->checknull($rec_a, $wheres);
						$tested = $this->checknull($tested_a, $wheres);

						if($tested == 0) continue;

						$rej = $this->checknull($rej_a, $wheres);
						// $rs = $this->checknull($rs_a, $wheres);
						$rs = 0;

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

							if($type == 1 && $div_type == 1){
						
								$males = $this->checknull($male_nonsup_a, $wheres);
								$females = $this->checknull($female_nonsup_a, $wheres);
								$nogenders = $this->checknull($nogender_nonsup_a, $wheres);

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
						
						$locator = array_merge(['year' => $year, $table[2] => $value->id], $wheres);

						DB::table($table[0])->where($locator)->update($data_array);
						// DB::table($table[0])->where('year', $year)->where('month', $month)->where($table[2], $value->id)->where($division, $div_array[$it])->update($data_array);
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

    public function eid_agebreakdown(){


		// Set the following to null in order to free memory
		$alltests_a = $eqatests_a = $tests_a = $patienttests_a = $patienttestsPOS_a = $received_a = $firstdna_a = $confirmdna_a = $posrepeats_a = $confirmdnaPOS_a = $posrepeatsPOS_a = $infantsless2m_a = $infantsless2mPOS_a = $infantsless2w_a = $infantsless2wPOS_a = $infantsless46w_a = $infantsless46wPOS_a = $infantsabove2m_a = $infantsabove2mPOS_a = $adulttests_a = $adulttestsPOS_a = $pos_a = $neg_a = $fail_a = $rd_a = $rdd_a = $rej_a = $enrolled_a = $ltfu_a = $dead_a = $adult_a = $transout_a = $other_a = $v_cp_a = $v_ad_a = $v_vl_a = $v_rp_a = $v_uf_a = $sitesending_a = $avgage_a = $medage_a = $tat = null;

		// echo "\n Begin eid nation age breakdown update at " . date('d/m/Y h:i:s a', time());

		// Get national age_breakdown
		// $age1pos_a = $n->GetTestOutcomesbyAgeBand($year, 1, 2);
		// $age1neg_a = $n->GetTestOutcomesbyAgeBand($year, 1, 1);
		// $age2pos_a = $n->GetTestOutcomesbyAgeBand($year, 2, 2);
		// $age2neg_a = $n->GetTestOutcomesbyAgeBand($year, 2, 1);
		// $age3pos_a = $n->GetTestOutcomesbyAgeBand($year, 3, 2);
		// $age3neg_a = $n->GetTestOutcomesbyAgeBand($year, 3, 1);
		// $age4pos_a = $n->GetTestOutcomesbyAgeBand($year, 4, 2);
		// $age4neg_a = $n->GetTestOutcomesbyAgeBand($year, 4, 1);
		// $age5pos = 0;
		// $age5neg = 0;
		// $age6pos_a = $n->GetTestOutcomesbyAgeBand($year, 6, 2);
		// $age6neg_a = $n->GetTestOutcomesbyAgeBand($year, 6, 1);
		
		// $age9pos_a = $n->GetTestOutcomesbyAgeBand($year, 9, 2);
		// $age9neg_a = $n->GetTestOutcomesbyAgeBand($year, 9, 1);
		// $age10pos_a = $n->GetTestOutcomesbyAgeBand($year, 10, 2);
		// $age10neg_a = $n->GetTestOutcomesbyAgeBand($year, 10, 1);
		// $age11pos_a = $n->GetTestOutcomesbyAgeBand($year, 11, 2);
		// $age11neg_a = $n->GetTestOutcomesbyAgeBand($year, 11, 1);
		// $age12pos_a = $n->GetTestOutcomesbyAgeBand($year, 12, 2);
		// $age12neg_a = $n->GetTestOutcomesbyAgeBand($year, 12, 1);
		// $age13pos_a = $n->GetTestOutcomesbyAgeBand($year, 13, 2);
		// $age13neg_a = $n->GetTestOutcomesbyAgeBand($year, 13, 1);
		// $age14pos_a = $n->GetTestOutcomesbyAgeBand($year, 14, 2);
		// $age14neg_a = $n->GetTestOutcomesbyAgeBand($year, 14, 1);

		// // Loop through the months and insert data into the national agebreakdown
		// for ($i=0; $i < 12; $i++) { 
		// 	$month = $i + 1;
		// 	if($year == Date('Y') && $month > Date('m')){ break; }

		// 	$age1pos = $this->checknull($age1pos_a->where('month', $month));
		// 	$age1neg = $this->checknull($age1neg_a->where('month', $month));
		// 	$age2pos = $this->checknull($age2pos_a->where('month', $month));
		// 	$age2neg = $this->checknull($age2neg_a->where('month', $month));
		// 	$age3pos = $this->checknull($age3pos_a->where('month', $month));
		// 	$age3neg = $this->checknull($age3neg_a->where('month', $month));
		// 	$age4pos = $this->checknull($age4pos_a->where('month', $month));
		// 	$age4neg = $this->checknull($age4neg_a->where('month', $month));

		// 	$age6pos = $this->checknull($age6pos_a->where('month', $month));
		// 	$age6neg = $this->checknull($age6neg_a->where('month', $month));

		// 	$age9pos = $this->checknull($age9pos_a->where('month', $month));
		// 	$age9neg = $this->checknull($age9neg_a->where('month', $month));
		// 	$age10pos = $this->checknull($age10pos_a->where('month', $month));
		// 	$age10neg = $this->checknull($age10neg_a->where('month', $month));
		// 	$age11pos = $this->checknull($age11pos_a->where('month', $month));
		// 	$age11neg = $this->checknull($age11neg_a->where('month', $month));
		// 	$age12pos = $this->checknull($age12pos_a->where('month', $month));
		// 	$age12neg = $this->checknull($age12neg_a->where('month', $month));
		// 	$age13pos = $this->checknull($age13pos_a->where('month', $month));
		// 	$age13neg = $this->checknull($age13neg_a->where('month', $month));
		// 	$age14pos = $this->checknull($age14pos_a->where('month', $month));
		// 	$age14neg = $this->checknull($age14neg_a->where('month', $month));


		// 	$data_array = array(
		// 		'sixweekspos' => $age1pos, 'sixweeksneg' => $age1neg, 'sevento3mpos' => $age2pos,
		// 		'sevento3mneg' => $age2neg, 'threemto9mpos' => $age3pos, 
		// 		'threemto9mneg' => $age3neg, 'ninemto18mpos' => $age4pos,
		// 		'ninemto18mneg' => $age4neg, 'above18mpos' => $age5pos, 'above18mneg' => $age5neg,
		// 		'nodatapos' => $age6pos, 'nodataneg' => $age6neg, 'less2wpos' => $age9pos,
		// 		'less2wneg' => $age9neg, 'twoto6wpos' => $age10pos, 'twoto6wneg' => $age10neg,
		// 		'sixto8wpos' => $age11pos, 'sixto8wneg' => $age11neg, 'sixmonthpos' => $age12pos,
		// 		'sixmonthneg' => $age12neg, 'ninemonthpos' => $age13pos, 
		// 		'ninemonthneg' => $age13neg, 'twelvemonthpos' => $age14pos,
		// 		'twelvemonthneg' => $age14neg, 'dateupdated' => $today
		// 	);

		// 	DB::table('national_agebreakdown')->where('year', $year)->where('month', $month)->update($data_array);

			// $sql = "UPDATE national_agebreakdown set sixweekspos='$age1pos', sixweeksneg='$age1neg', sevento3mpos='$age2pos', sevento3mneg='$age2neg'	,threemto9mpos='$age3pos',threemto9mneg='$age3neg',ninemto18mpos='$age4pos',ninemto18mneg='$age4neg',above18mpos='$age5pos',above18mneg='$age5neg',nodatapos='$age6pos',nodataneg='$age6neg', less2wpos='$age9pos',less2wneg='$age9neg',twoto6wpos='$age10pos',twoto6wneg='$age10neg',sixto8wpos='$age11pos',sixto8wneg='$age11neg',sixmonthpos='$age12pos',sixmonthneg='$age12neg',ninemonthpos='$age13pos',ninemonthneg='$age13neg',twelvemonthpos='$age14pos',twelvemonthneg='$age14neg',sorted=9 WHERE month='$month' AND year='$year'";

		// }
		// End of for loop

		// echo "\n Completed entry into eid national age breakdown at " . date('d/m/Y h:i:s a', time());
    }

    public function division_age()
    {


		echo "\n Begin entry into eid {$column} age breakdown at " . date('d/m/Y h:i:s a', time());
		Get national age_breakdown
		$age1pos_a = $n->GetTestOutcomesbyAgeBand($year, 1, 2, $division);
		$age1neg_a = $n->GetTestOutcomesbyAgeBand($year, 1, 1, $division);
		$age2pos_a = $n->GetTestOutcomesbyAgeBand($year, 2, 2, $division);
		$age2neg_a = $n->GetTestOutcomesbyAgeBand($year, 2, 1, $division);
		$age3pos_a = $n->GetTestOutcomesbyAgeBand($year, 3, 2, $division);
		$age3neg_a = $n->GetTestOutcomesbyAgeBand($year, 3, 1, $division);
		$age4pos_a = $n->GetTestOutcomesbyAgeBand($year, 4, 2, $division);
		$age4neg_a = $n->GetTestOutcomesbyAgeBand($year, 4, 1, $division);
		$age5pos = 0;
		$age5neg = 0;
		$age6pos_a = $n->GetTestOutcomesbyAgeBand($year, 6, 2, $division);
		$age6neg_a = $n->GetTestOutcomesbyAgeBand($year, 6, 1, $division);
		
		$age9pos_a = $n->GetTestOutcomesbyAgeBand($year, 9, 2, $division);
		$age9neg_a = $n->GetTestOutcomesbyAgeBand($year, 9, 1, $division);
		$age10pos_a = $n->GetTestOutcomesbyAgeBand($year, 10, 2, $division);
		$age10neg_a = $n->GetTestOutcomesbyAgeBand($year, 10, 1, $division);
		$age11pos_a = $n->GetTestOutcomesbyAgeBand($year, 11, 2, $division);
		$age11neg_a = $n->GetTestOutcomesbyAgeBand($year, 11, 1, $division);
		$age12pos_a = $n->GetTestOutcomesbyAgeBand($year, 12, 2, $division);
		$age12neg_a = $n->GetTestOutcomesbyAgeBand($year, 12, 1, $division);
		$age13pos_a = $n->GetTestOutcomesbyAgeBand($year, 13, 2, $division);
		$age13neg_a = $n->GetTestOutcomesbyAgeBand($year, 13, 1, $division);
		$age14pos_a = $n->GetTestOutcomesbyAgeBand($year, 14, 2, $division);
		$age14neg_a = $n->GetTestOutcomesbyAgeBand($year, 14, 1, $division);

		// Loop through the months and insert data into the national agebreakdown
		for ($i=0; $i < 12; $i++) { 
			$month = $i + 1;

			if($year == Date('Y') && $month > Date('m')){ break; }

			// Loop through divisions
			for ($it=0; $it < $array_size; $it++) {
				$age1pos = $this->checknull($age1pos_a->where('month', $month)->where($column, $div_array[$it]));
				$age1neg = $this->checknull($age1neg_a->where('month', $month)->where($column, $div_array[$it]));
				$age2pos = $this->checknull($age2pos_a->where('month', $month)->where($column, $div_array[$it]));
				$age2neg = $this->checknull($age2neg_a->where('month', $month)->where($column, $div_array[$it]));
				$age3pos = $this->checknull($age3pos_a->where('month', $month)->where($column, $div_array[$it]));
				$age3neg = $this->checknull($age3neg_a->where('month', $month)->where($column, $div_array[$it]));
				$age4pos = $this->checknull($age4pos_a->where('month', $month)->where($column, $div_array[$it]));
				$age4neg = $this->checknull($age4neg_a->where('month', $month)->where($column, $div_array[$it]));

				$age6pos = $this->checknull($age6pos_a->where('month', $month)->where($column, $div_array[$it]));
				$age6neg = $this->checknull($age6neg_a->where('month', $month)->where($column, $div_array[$it]));

				$age9pos = $this->checknull($age9pos_a->where('month', $month)->where($column, $div_array[$it]));
				$age9neg = $this->checknull($age9neg_a->where('month', $month)->where($column, $div_array[$it]));
				$age10pos = $this->checknull($age10pos_a->where('month', $month)->where($column, $div_array[$it]));
				$age10neg = $this->checknull($age10neg_a->where('month', $month)->where($column, $div_array[$it]));
				$age11pos = $this->checknull($age11pos_a->where('month', $month)->where($column, $div_array[$it]));
				$age11neg = $this->checknull($age11neg_a->where('month', $month)->where($column, $div_array[$it]));
				$age12pos = $this->checknull($age12pos_a->where('month', $month)->where($column, $div_array[$it]));
				$age12neg = $this->checknull($age12neg_a->where('month', $month)->where($column, $div_array[$it]));
				$age13pos = $this->checknull($age13pos_a->where('month', $month)->where($column, $div_array[$it]));
				$age13neg = $this->checknull($age13neg_a->where('month', $month)->where($column, $div_array[$it]));
				$age14pos = $this->checknull($age14pos_a->where('month', $month)->where($column, $div_array[$it]));
				$age14neg = $this->checknull($age14neg_a->where('month', $month)->where($column, $div_array[$it]));


				$data_array = array(
					'sixweekspos' => $age1pos, 'sixweeksneg' => $age1neg, 'sevento3mpos' => $age2pos,
					'sevento3mneg' => $age2neg, 'threemto9mpos' => $age3pos, 
					'threemto9mneg' => $age3neg, 'ninemto18mpos' => $age4pos,
					'ninemto18mneg' => $age4neg, 'above18mpos' => $age5pos, 'above18mneg' => $age5neg,
					'nodatapos' => $age6pos, 'nodataneg' => $age6neg, 'less2wpos' => $age9pos,
					'less2wneg' => $age9neg, 'twoto6wpos' => $age10pos, 'twoto6wneg' => $age10neg,
					'sixto8wpos' => $age11pos, 'sixto8wneg' => $age11neg, 'sixmonthpos' => $age12pos,
					'sixmonthneg' => $age12neg, 'ninemonthpos' => $age13pos, 
					'ninemonthneg' => $age13neg, 'twelvemonthpos' => $age14pos,
					'twelvemonthneg' => $age14neg, 'dateupdated' => $today
				);
				if ($type==2) {
					$column="subcounty";
				}

				DB::table($age_table)->where('year', $year)->where('month', $month)->where($column, $div_array[$it])->update($data_array);
				
				if ($type==2) {
					$column = $column2;
				}
			}
			// End of division loop
		}
		// End of months loop

		echo "\n Completed entry into eid {$column} age breakdown at " . date('d/m/Y h:i:s a', time());
    }

    // Div type is the type of division eg county, subcounty, partner and facility
    /*public function finish_division($start_month, $year, $today, &$div_array, $column, $division, $div_type, $array_size){
    	ini_set("memory_limit", "-1");

    	$n = new VlDivision;
    	$update_statements = '';
    	$updates = 0;
    	$column2 = $column;

    	for ($type=3; $type < 4; $type++) { 

    		// if($type == 3 && $column == "facility"){
			// 	continue;
			// }

			$table = $this->get_table($div_type, $type);

			echo "\n Begin " . $table[0] . " update at " . date('d/m/Y h:i:s a', time());
			
			// Get ids of the necessary table
			$divs = $data = DB::connection('vl')
			->table($table[1])->select('ID')
			->when($type, function($query) use ($type){
				if($type == 1 || $type == 6){
					return $query->where('subID', 1);
				}				
			})
			->get();

			foreach ($divs as $key => $value) {	

				// Get collection instances of the data
		    	// $rec_a = $n->getallreceivediraloadsamplesbydash($year, $start_month, $division, $type, $value->ID);
		    	$tested_a = $n->getalltestedviraloadsamplesbydash($year, $start_month, $division, $type, $value->ID);
		    	$rej_a = $n->getallrejectedviraloadsamplesbydash($year, $start_month, $division, $type, $value->ID);

		    	$conftx_a = $n->GetNationalConfirmed2VLsbydash($year, $start_month, $division, $type, $value->ID);
		    	$conf2VL_a = $n->GetNationalConfirmedFailurebydash($year, $start_month, $division, $type, $value->ID);
		    	$rs = $n->getallrepeattviraloadsamplesbydash($year, $start_month, $division, $type, $value->ID);

		    	if ($type != 1 && $type != 6) {

			    	$noage_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $division, $type, $value->ID, 0);
			    	$less2_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $division, $type, $value->ID, 6);
			    	$less9_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $division, $type, $value->ID, 7);
			    	$less14_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $division, $type, $value->ID, 8);
			    	$less19_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $division, $type, $value->ID, 9);
			    	$less24_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $division, $type, $value->ID, 10);
			    	$over25_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $division, $type, $value->ID, 11);
			    }

		    	// $adults=$less19 +$less24 + $over25 ;
				// $paeds=$less2 + $less9 + $less14;

				$ldl_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $start_month, $division, $type, $value->ID, 1);
				$less1k_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $start_month, $division, $type, $value->ID, 2);
				$less5k_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $start_month, $division, $type, $value->ID, 3);
				$above5k_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $start_month, $division, $type, $value->ID, 4);
				$invalids_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $start_month, $division, $type, $value->ID, 5);
				// $sustx=$less5k +  $above5k;

				if($type != 4 && $type != 6){

					$plas_a = $n->getalltestedviraloadsamplesbytypedetailsbydash($year, $start_month, $division, $type, $value->ID, 1);
					$edta_a = $n->getalltestedviraloadsamplesbytypedetailsbydash($year, $start_month, $division, $type, $value->ID, 3);
					$dbs_a = $n->getalltestedviraloadsamplesbytypedetailsbydash($year, $start_month, $division, $type, $value->ID, 2);
				}

				if($type != 2 && $type != 6){

					$male_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $start_month, $division, $type, $value->ID, 1);
					$female_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $start_month, $division, $type, $value->ID, 2);
					$nogender_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $start_month, $division, $type, $value->ID, 3);

				}

				if ($type != 5) {
					$baseline_a = $n->GetNationalBaselinebydash($year, $start_month, $division, $type, $value->ID);
					$baselinefail_a = $n->GetNationalBaselineFailurebydash($year, $start_month, $division, $type, $value->ID);
				}

				// Loop through the months and insert data
				for ($i=$start_month; $i < 12; $i++) { 
					$month = $i + 1;
					if($year == Date('Y') && $month > Date('m')){ break; }

					// Loop through divisions i.e. counties, subcounties, partners and sites
					for ($it=0; $it < $array_size; $it++) { 
						// $rec = $this->checknull($rec_a->where('month', $month));
						$tested = $this->checknull($tested_a->where('month', $month)->where($column, $div_array[$it]));

						// if($tested == 0){
						// 	continue;
						// }

						$rej = $this->checknull($rej_a->where('month', $month)->where($column, $div_array[$it]));

						$conftx = $this->checknull($conftx_a->where('month', $month)->where($column, $div_array[$it]));
						$conf2VL = $this->checknull($conf2VL_a->where('month', $month)->where($column, $div_array[$it]));

						$ldl = $this->checknull($ldl_a->where('month', $month)->where($column, $div_array[$it]));
						$less1k = $this->checknull($less1k_a->where('month', $month)->where($column, $div_array[$it]));
						$less5k = $this->checknull($less5k_a->where('month', $month)->where($column, $div_array[$it]));
						$above5k = $this->checknull($above5k_a->where('month', $month)->where($column, $div_array[$it]));
						$invalids = $this->checknull($invalids_a->where('month', $month)->where($column, $div_array[$it]));
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

							$noage = $this->checknull($noage_a->where('month', $month)->where($column, $div_array[$it]));
							$less2 = $this->checknull($less2_a->where('month', $month)->where($column, $div_array[$it]));
							$less9 = $this->checknull($less9_a->where('month', $month)->where($column, $div_array[$it]));
							$less14 = $this->checknull($less14_a->where('month', $month)->where($column, $div_array[$it]));
							$less19 = $this->checknull($less19_a->where('month', $month)->where($column, $div_array[$it]));
							$less24 = $this->checknull($less24_a->where('month', $month)->where($column, $div_array[$it]));
							$over25 = $this->checknull($over25_a->where('month', $month)->where($column, $div_array[$it]));
							$adults = $less19 + $less24 + $over25;
							$paeds = $less2 + $less9 + $less14;

							$age_array = array('less2' => $less2, 'less9' => $less9,
							'less14' => $less14, 'less19' => $less19, 'less24' => $less24,
							'over25' => $over25, 'adults' => $adults, 'paeds' => $paeds,
							'noage' => $noage);

							$data_array = array_merge($age_array, $data_array);

						}

						if($type != 4 && $type != 6){

							$plas = $this->checknull($plas_a->where('month', $month)->where($column, $div_array[$it]));
							$edta = $this->checknull($edta_a->where('month', $month)->where($column, $div_array[$it]));
							$dbs = $this->checknull($dbs_a->where('month', $month)->where($column, $div_array[$it]));

							$sample_array = array('dbs' => $dbs, 'plasma' => $plas, 'edta' => $edta);

							$data_array = array_merge($sample_array, $data_array);

						}

						if ($type != 2 && $type != 6) {
						
							$male = $this->checknull($male_a->where('month', $month)->where($column, $div_array[$it]));
							$female = $this->checknull($female_a->where('month', $month)->where($column, $div_array[$it]));
							$nogender = $this->checknull($nogender_a->where('month', $month)->where($column, $div_array[$it]));

							$gender_array = array('maletest' => $male, 'femaletest' => $female, 'nogendertest' => $nogender);

							$data_array = array_merge($gender_array, $data_array);
						}

						if ($type != 5) {
						
							$baseline = $this->checknull($baseline_a->where('month', $month)->where($column, $div_array[$it]));
							$baselinefail = $this->checknull($baselinefail_a->where('month', $month)->where($column, $div_array[$it]));

							$baseline_array = array('baseline' => $baseline, 'baselinesustxfail' => $baselinefail);

							$data_array = array_merge($baseline_array, $data_array);
						}

						if($div_type == 2){
							$column = "subcounty";
						}
						

						// DB::table($table[0])->where('year', $year)->where('month', $month)->where($table[2], $value->ID)->where($column, $div_array[$it])->update($data_array);

						$search_array = ['year' => $year, 'month' => $month, $table[2] => $value->ID, $column => $div_array[$it]];
						$update_statements .= $this->update_query($table[0], $data_array, $search_array);
						$updates++;

						if($updates == 200){
							$this->mysqli->multi_query($update_statements);
							$update_statements = '';
							$updates = 0;
						}

						$column = $column2;
					}

				}
				// End of for loop for months

			}
			// End of looping through ids of each table e.g. agecategory
			echo "\n Completed " . $table[0] . " update at " . date('d/m/Y h:i:s a', time());
		}
		$this->mysqli->multi_query($update_statements);
		// End of looping of params
    }*/

    public function update_suppression()
    {
    	


			// $noage_sup = 
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 1)->where('age2', 0)) +
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 2)->where('age2', 0));
			// $noage_nonsup = 
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 3)->where('age2', 0)) +
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 4)->where('age2', 0));

			// $less2_sup = 
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 1)->where('age2', 6)) +
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 2)->where('age2', 6));
			// $less2_nonsup = 
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 3)->where('age2', 6)) +
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 4)->where('age2', 6));

			// $less9_sup = 
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 1)->where('age2', 7)) +
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 2)->where('age2', 7));
			// $less9_nonsup = 
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 3)->where('age2', 7)) +
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 4)->where('age2', 7));

			// $less14_sup = 
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 1)->where('age2', 8)) +
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 2)->where('age2', 8));
			// $less14_nonsup = 
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 3)->where('age2', 8)) +
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 4)->where('age2', 8));

			// $less19_sup = 
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 1)->where('age2', 9)) +
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 2)->where('age2', 9));
			// $less19_nonsup = 
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 3)->where('age2', 9)) +
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 4)->where('age2', 9));

			// $less24_sup = 
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 1)->where('age2', 10)) +
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 2)->where('age2', 10));
			// $less24_nonsup = 
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 3)->where('age2', 10)) +
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 4)->where('age2', 10));

			// $over25_sup = 
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 1)->where('age2', 11)) +
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 2)->where('age2', 11));
			// $over25_nonsup = 
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 3)->where('age2', 11)) +
			// (int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 4)->where('age2', 11));



			// $male_sup = 
			// (int) $this->checknull($gender_data->where('facility', $value->ID)->where('rcategory', 1)->where('gender', 'M')) +
			// (int) $this->checknull($gender_data->where('facility', $value->ID)->where('rcategory', 2)->where('gender', 'M'));
			// $male_nonsup = 
			// (int) $this->checknull($gender_data->where('facility', $value->ID)->where('rcategory', 3)->where('gender', 'M')) +
			// (int) $this->checknull($gender_data->where('facility', $value->ID)->where('rcategory', 4)->where('gender', 'M'));


			// $female_sup = 
			// (int) $this->checknull($gender_data->where('facility', $value->ID)->where('rcategory', 1)->where('gender', 'F')) +
			// (int) $this->checknull($gender_data->where('facility', $value->ID)->where('rcategory', 2)->where('gender', 'F'));
			// $female_nonsup = 
			// (int) $this->checknull($gender_data->where('facility', $value->ID)->where('rcategory', 3)->where('gender', 'F')) +
			// (int) $this->checknull($gender_data->where('facility', $value->ID)->where('rcategory', 4)->where('gender', 'F'));


			// $nogender_sup = 
			// (int) $this->checknull($gender_data->where('facility', $value->ID)->where('rcategory', 1)->where('gender', 'No Data')) +
			// (int) $this->checknull($gender_data->where('facility', $value->ID)->where('rcategory', 2)->where('gender', 'No Data'));
			// $nogender_nonsup = 
			// (int) $this->checknull($gender_data->where('facility', $value->ID)->where('rcategory', 3)->where('gender', 'No Data')) +
			// (int) $this->checknull($gender_data->where('facility', $value->ID)->where('rcategory', 4)->where('gender', 'No Data'));
    }
}
