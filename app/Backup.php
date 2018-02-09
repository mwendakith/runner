<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    //

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
    public function finish_division($start_month, $year, $today, &$div_array, $column, $division, $div_type, $array_size){
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
    }
}
