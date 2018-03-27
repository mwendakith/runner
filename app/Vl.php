<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\VlNation;
use App\VlDivision;
use App\VlTest;
use DB;

class Vl extends Model
{
    //

	// protected $mysqli;

	// public function __construct(){
	// 	parent::__construct();
	// 	$this->mysqli = new \mysqli(env('DB_HOST', '127.0.0.1'), env('DB_USERNAME', 'forge'), env('DB_PASSWORD', ''), env('DB_DATABASE', 'forge'));
	// }


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

    	$noage_a = $n->getalltestedviraloadsbyage($year, $start_month, 0);
    	$less2_a = $n->getalltestedviraloadsbyage($year, $start_month, 6);
    	$less9_a = $n->getalltestedviraloadsbyage($year, $start_month, 7);
    	$less14_a = $n->getalltestedviraloadsbyage($year, $start_month, 8);
    	$less19_a = $n->getalltestedviraloadsbyage($year, $start_month, 9);
    	$less24_a = $n->getalltestedviraloadsbyage($year, $start_month, 10);
    	$over25_a = $n->getalltestedviraloadsbyage($year, $start_month, 11);

    	// $adults=$less19 +$less24 + $over25 ;
		// $paeds=$less2 + $less9 + $less14;

		$ldl_a = $n->getalltestedviraloadsbyresult($year, $start_month, 1);
		$less1k_a = $n->getalltestedviraloadsbyresult($year, $start_month, 2);
		$less5k_a = $n->getalltestedviraloadsbyresult($year, $start_month, 3);
		$above5k_a = $n->getalltestedviraloadsbyresult($year, $start_month, 4);
		$invalids_a = $n->getalltestedviraloadsbyresult($year, $start_month, 5);
		// $sustx=$less5k +  $above5k;

		$plas_a = $n->getalltestedviraloadsamplesbytypedetails($year, $start_month, 1);
		$edta_a = $n->getalltestedviraloadsamplesbytypedetails($year, $start_month, 3);
		$dbs_a = $n->getalltestedviraloadsamplesbytypedetails($year, $start_month, 2);

		$aplas_a = $n->getalltestedviraloadsamplesbytypedetails($year, $start_month, 1, false);
		$aedta_a = $n->getalltestedviraloadsamplesbytypedetails($year, $start_month, 3, false);
		$adbs_a = $n->getalltestedviraloadsamplesbytypedetails($year, $start_month, 2, false);

		$male_a = $n->getalltestedviraloadbygender($year, $start_month, 1);
		$female_a = $n->getalltestedviraloadbygender($year, $start_month, 2);
		$nogender_a = $n->getalltestedviraloadbygender($year, $start_month, 3);

		$tat = $n->get_tat($year, $start_month);

		// Loop through the months and insert data into the national summary
		for ($i=$start_month; $i < 12; $i++) { 
			$month = $i + 1;
			if($year == Date('Y') && $month > Date('m')){ break; }

			$rec = $this->checknull($rec_a->where('month', $month));
			$tested = $this->checknull($tested_a->where('month', $month));
			$actualpatients = $this->checknull($actualpatients_a->where('month', $month));
			$rej = $this->checknull($rej_a->where('month', $month));
			$sites = $this->checknull($sites_a->where('month', $month));

			$conftx = $this->checknull($conftx_a->where('month', $month));
			$conf2VL = $this->checknull($conf2VL_a->where('month', $month));

			$baseline = $this->checknull($baseline_a->where('month', $month));
			$baselinefail = $this->checknull($baselinefail_a->where('month', $month));

			$noage = $this->checknull($noage_a->where('month', $month));
			$less2 = $this->checknull($less2_a->where('month', $month));
			$less9 = $this->checknull($less9_a->where('month', $month));
			$less14 = $this->checknull($less14_a->where('month', $month));
			$less19 = $this->checknull($less19_a->where('month', $month));
			$less24 = $this->checknull($less24_a->where('month', $month));
			$over25 = $this->checknull($over25_a->where('month', $month));
			$adults = $less19 + $less24 + $over25;
			$paeds = $less2 + $less9 + $less14;

			$ldl = $this->checknull($ldl_a->where('month', $month));
			$less1k = $this->checknull($less1k_a->where('month', $month));
			$less5k = $this->checknull($less5k_a->where('month', $month));
			$above5k = $this->checknull($above5k_a->where('month', $month));
			$invalids = $this->checknull($invalids_a->where('month', $month));
			$sustx = $less5k +  $above5k;

			$plas = $this->checknull($plas_a->where('month', $month));
			$edta = $this->checknull($edta_a->where('month', $month));
			$dbs = $this->checknull($dbs_a->where('month', $month));

			$aplas = $this->checknull($aplas_a->where('month', $month));
			$aedta = $this->checknull($aedta_a->where('month', $month));
			$adbs = $this->checknull($adbs_a->where('month', $month));

			$male = $this->checknull($male_a->where('month', $month));
			$female = $this->checknull($female_a->where('month', $month));
			$nogender = $this->checknull($nogender_a->where('month', $month));

			$tt = $this->check_tat($tat->where('month', $month));
			// $tt = $this->checktat($tat->where('month', $month));

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

			// $update_statements .= $this->update_query('vl_national_summary', $data_array, ['year' => $year, 'month' => $month]);
			// $updates++;

			// if($updates == 150){
			// 	$this->mysqli->multi_query($update_statements);
			// 	$update_statements = '';
			// 	$updates = 0;
			// }

			// $sql = "UPDATE vl_national_summary set received='$rec',alltests='$tested' ,actualpatients='$actualpatients' ,sustxfail='$sustx',confirmtx='$conftx',repeattests='$rs',confirm2vl='$conf2VL',rejected='$rej',dbs='$dbs',plasma='$plas',edta='$edta',maletest='$male',femaletest='$female',nogendertest='$nogender',adults='$adults',paeds='$paeds',noage='$noage',Undetected='$ldl',less1000='$less1k',less5000='$less5k',above5000='$above5k',invalids='$invalids',sitessending='$sites' ,less2='$less2',less9='$less9',less14='$less14',less19='$less19',less24='$less24',over25='$over25', tat1='$t1', tat2='$t2', tat3='$t3', tat4='$t4',baseline='$baseline',baselinesustxfail='$baselinefail', dateupdated='$today'  WHERE month='$month' AND year='$year' ";

		}
		// End of for loop

		// $this->mysqli->multi_query($update_statements);

		echo "\n Completed entry into viralload national summary at " . date('d/m/Y h:i:s a', time());

		echo $this->finish_nation($start_month, $year, $today);
		echo $this->nation_rejections($start_month, $year, $today);
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

    	$reasons = DB::connection('vl')->table('viralrejectedreasons')->select('ID')->get();

    	foreach ($reasons as $key => $value) {
    		$rej_a = $n->national_rejections($year, $start_month, $value->ID);

    		// Loop through the months and insert data into the national summary
			for ($i=$start_month; $i < 12; $i++) { 
				$month = $i + 1;
				if($year == Date('Y') && $month > Date('m')){ break; }

				$rej = $this->checknull($rej_a->where('month', $month));

				if($rej == 0){
					continue;
				}

				$data_array = array(
					'dateupdated' => $today, 'total' => $rej
				);
				DB::table('vl_national_rejections')->where('year', $year)->where('month', $month)
				->where('rejected_reason', $value->ID)->update($data_array);

				// $update_statements .= $this->update_query('vl_national_rejections', $data_array, ['year' => $year, 'month' => $month, 'rejected_reason' => $value->ID]);
				// $updates++;

				// if($updates == 150){
				// 	$this->mysqli->multi_query($update_statements);
				// 	$update_statements = '';
				// 	$updates = 0;
				// }	
			}
    	}
    	// $this->mysqli->multi_query($update_statements);

    	echo "\n Completed viralload nation rejections update at " . date('d/m/Y h:i:s a', time());
    }

    public function finish_nation($start_month, $year, $today){
    	$n = new VlNation;
    	$update_statements = '';
    	$updates = 0;

    	for ($type=1; $type < 7; $type++) { 

			$table = $this->get_table(0, $type);

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

		    	// $rec_a = $n->getallreceivediraloadsamplesbydash($year, $start_month, $type, $value->ID);
		    	$tested_a = $n->getalltestedviraloadsamplesbydash($year, $start_month, $type, $value->ID);
		    	$rej_a = $n->getallrejectedviraloadsamplesbydash($year, $start_month, $type, $value->ID);
		    	$rs_a = $n->getallrepeattviraloadsamplesbydash($year, $start_month, $type, $value->ID);
				

		    	$conftx_a = $n->GetNationalConfirmed2VLsbydash($year, $start_month, $type, $value->ID);
		    	$conf2VL_a = $n->GetNationalConfirmedFailurebydash($year, $start_month, $type, $value->ID);

		    	if ($type != 1 && $type != 6) {

			    	$noage_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $type, $value->ID, 0);
			    	$less2_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $type, $value->ID, 6);
			    	$less9_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $type, $value->ID, 7);
			    	$less14_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $type, $value->ID, 8);
			    	$less19_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $type, $value->ID, 9);
			    	$less24_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $type, $value->ID, 10);
			    	$over25_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $type, $value->ID, 11);

			    	if ($type == 3) {

				    	$noages_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $type, $value->ID, 0, true);
				    	$less2s_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $type, $value->ID, 6, true);
				    	$less9s_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $type, $value->ID, 7, true);
				    	$less14s_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $type, $value->ID, 8, true);
				    	$less19s_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $type, $value->ID, 9, true);
				    	$less24s_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $type, $value->ID, 10, true);
				    	$over25s_a = $n->getalltestedviraloadsamplesbyagebydash($year, $start_month, $type, $value->ID, 11, true);
			    	}
			    }

		    	// $adults=$less19 +$less24 + $over25 ;
				// $paeds=$less2 + $less9 + $less14;

				$ldl_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $start_month, $type, $value->ID, 1);
				$less1k_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $start_month, $type, $value->ID, 2);
				$less5k_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $start_month, $type, $value->ID, 3);
				$above5k_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $start_month, $type, $value->ID, 4);
				$invalids_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $start_month, $type, $value->ID, 5);
				// $sustx=$less5k +  $above5k;


				if($type != 4 && $type != 6){

					$plas_a = $n->getalltestedviraloadsamplesbytypedetailsbydash($year, $start_month, $type, $value->ID, 1);
					$edta_a = $n->getalltestedviraloadsamplesbytypedetailsbydash($year, $start_month, $type, $value->ID, 3);
					$dbs_a = $n->getalltestedviraloadsamplesbytypedetailsbydash($year, $start_month, $type, $value->ID, 2);
				}

				if($type != 2 && $type != 6){

					$male_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $start_month, $type, $value->ID, 1);
					$female_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $start_month, $type, $value->ID, 2);
					$nogender_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $start_month, $type, $value->ID, 3);

					if($type == 1 || $type == 3){
						$males_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $start_month, $type, $value->ID, 1, true);
						$females_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $start_month, $type, $value->ID, 2, true);
						$nogenders_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $start_month, $type, $value->ID, 3, true);

					}

				}

				if ($type != 5) {
					$baseline_a = $n->GetNationalBaselinebydash($year, $start_month, $type, $value->ID);
					$baselinefail_a = $n->GetNationalBaselineFailurebydash($year, $start_month, $type, $value->ID);
				}

				// Loop through the months and insert data into the national summary
				for ($i=$start_month; $i < 12; $i++) { 
					$month = $i + 1;
					if($year == Date('Y') && $month > Date('m')){ break; }

					$tested = $this->checknull($tested_a->where('month', $month));

					if($tested == 0){
						continue;
					}

					// $rec = $this->checknull($rec_a->where('month', $month));
					$rej = $this->checknull($rej_a->where('month', $month));
					$rs = $this->checknull($rs_a->where('month', $month));

					$conftx = $this->checknull($conftx_a->where('month', $month));
					$conf2VL = $this->checknull($conf2VL_a->where('month', $month));

					$ldl = $this->checknull($ldl_a->where('month', $month));
					$less1k = $this->checknull($less1k_a->where('month', $month));
					$less5k = $this->checknull($less5k_a->where('month', $month));
					$above5k = $this->checknull($above5k_a->where('month', $month));
					$invalids = $this->checknull($invalids_a->where('month', $month));
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

						$noage = $this->checknull($noage_a->where('month', $month));
						$less2 = $this->checknull($less2_a->where('month', $month));
						$less9 = $this->checknull($less9_a->where('month', $month));
						$less14 = $this->checknull($less14_a->where('month', $month));
						$less19 = $this->checknull($less19_a->where('month', $month));
						$less24 = $this->checknull($less24_a->where('month', $month));
						$over25 = $this->checknull($over25_a->where('month', $month));
						$adults = $less19 + $less24 + $over25;
						$paeds = $less2 + $less9 + $less14;

						$age_array = array('less2' => $less2, 'less9' => $less9,
						'less14' => $less14, 'less19' => $less19, 'less24' => $less24,
						'over25' => $over25, 'adults' => $adults, 'paeds' => $paeds,
						'noage' => $noage);

						if($type == 3){

							$noage = $this->checknull($noages_a->where('month', $month));
							$less2 = $this->checknull($less2s_a->where('month', $month));
							$less9 = $this->checknull($less9s_a->where('month', $month));
							$less14 = $this->checknull($less14s_a->where('month', $month));
							$less19 = $this->checknull($less19s_a->where('month', $month));
							$less24 = $this->checknull($less24s_a->where('month', $month));
							$over25 = $this->checknull($over25s_a->where('month', $month));

							$age_array2 = array('less2_nonsuppressed' => $less2, 'less9_nonsuppressed' => $less9,
							'less14_nonsuppressed' => $less14, 'less19_nonsuppressed' => $less19, 'less24_nonsuppressed' => $less24,
							'over25_nonsuppressed' => $over25, 
							'noage_nonsuppressed' => $noage);

							$age_array = array_merge($age_array, $age_array2);
						}

						$data_array = array_merge($age_array, $data_array);

					}

					if($type != 4 && $type != 6){

						$plas = $this->checknull($plas_a->where('month', $month));
						$edta = $this->checknull($edta_a->where('month', $month));
						$dbs = $this->checknull($dbs_a->where('month', $month));

						$sample_array = array('dbs' => $dbs, 'plasma' => $plas, 'edta' => $edta);

						$data_array = array_merge($sample_array, $data_array);

					}

					if ($type != 2 && $type != 6) {
					
						$male = $this->checknull($male_a->where('month', $month));
						$female = $this->checknull($female_a->where('month', $month));
						$nogender = $this->checknull($nogender_a->where('month', $month));

						$gender_array = array('maletest' => $male, 'femaletest' => $female, 'nogendertest' => $nogender);

						if($type == 1 || $type == 3){
							$males = $this->checknull($males_a->where('month', $month));
							$females = $this->checknull($females_a->where('month', $month));
							$nogenders = $this->checknull($nogenders_a->where('month', $month));

							$gender_array2 = array('malenonsuppressed' => $males, 'femalenonsuppressed' => $females, 'nogendernonsuppressed' => $nogenders);
							$gender_array = array_merge($gender_array, $gender_array2);
						}

						$data_array = array_merge($gender_array, $data_array);
					}

					if ($type != 5) {
					
						// $sample = $this->check_sample($baseline_a->where('month', $month));
						$baseline = $this->checknull($baseline_a->where('month', $month));
						$baselinefail = $this->checknull($baselinefail_a->where('month', $month));

						$baseline_array = array('baseline' => $baseline, 'baselinesustxfail' => $baselinefail);

						$data_array = array_merge($baseline_array, $data_array);
					}

					// echo "\n Sample - {$value->ID}  Actual - {$sample} ";
					

					DB::table($table[0])->where('year', $year)->where('month', $month)->where($table[2], $value->ID)->update($data_array);

					// $update_statements .= $this->update_query($table[0], $data_array, ['year' => $year, 'month' => $month, $table[2] => $value->ID]);
					// $updates++;

					// if($updates == 150){
					// 	$this->mysqli->multi_query($update_statements);
					// 	$update_statements = '';
					// 	$updates = 0;
					// }	

				}
				// End of for loop for months

			}
			
			// End of looping through ids of each table e.g. agecategory
			echo "\n Completed " . $table[0] . " update at " . date('d/m/Y h:i:s a', time());
		}
		// $this->mysqli->multi_query($update_statements);
		// End of looping of params
    }

    public function update_division($start_month, $year=null, $type=1, $column='county', $division='view_facilitys.county', $div_table='countys', $sum_table='vl_county_summary', $rej_table='vl_county_rejections'){
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

    	$divs = $data = DB::connection('vl')
		->table($div_table)->select('ID')->get();

		foreach ($divs as $key => $value) {
			$div_array[$key] = $value->ID;
			$array_size++;
		}

    	echo "\n Begin  viralload {$column} update at " . date('d/m/Y h:i:s a', time());

    	$column2=$column;

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
		}

		$tat = $n->get_tat($year, $start_month, $division);
		// $tat = $n->GetNatTATs($year, $start_month, $div_array, $division, $column);
		// $tat = collect($tat);

		// $count = $rec_a->count();

		// Loop through the months and insert data into the national summary
		for ($i=$start_month; $i < 12; $i++) { 
			$month = $i + 1;
			if($year == Date('Y') && $month > Date('m')){ break; }

			// Loop through divisions i.e. counties, subcounties, partners and sites
			for ($it=0; $it < $array_size; $it++) { 
				$rec = $this->checknull($rec_a->where('month', $month)->where($column, $div_array[$it]));
				$tested = $this->checknull($tested_a->where('month', $month)->where($column, $div_array[$it]));

				if($rec == 0 && $tested == 0){
					continue;
				}

				// $actualpatients = $this->checknull($actualpatients_a->where('month', $month)->where($column, $div_array[$it]));
				$rej = $this->checknull($rej_a->where('month', $month)->where($column, $div_array[$it]));
				$rs = $this->checknull($rs_a->where('month', $month)->where($column, $div_array[$it]));
				$sites = $this->checknull($sites_a->where('month', $month)->where($column, $div_array[$it]));

				$conftx = $this->checknull($conftx_a->where('month', $month)->where($column, $div_array[$it]));
				$conf2VL = $this->checknull($conf2VL_a->where('month', $month)->where($column, $div_array[$it]));

				
				$baseline = $this->checknull($baseline_a->where('month', $month)->where($column, $div_array[$it]));
				$baselinefail = $this->checknull($baselinefail_a->where('month', $month)->where($column, $div_array[$it]));

				$noage = $this->checknull($noage_a->where('month', $month)->where($column, $div_array[$it]));
				$less2 = $this->checknull($less2_a->where('month', $month)->where($column, $div_array[$it]));
				$less9 = $this->checknull($less9_a->where('month', $month)->where($column, $div_array[$it]));
				$less14 = $this->checknull($less14_a->where('month', $month)->where($column, $div_array[$it]));
				$less19 = $this->checknull($less19_a->where('month', $month)->where($column, $div_array[$it]));
				$less24 = $this->checknull($less24_a->where('month', $month)->where($column, $div_array[$it]));
				$over25 = $this->checknull($over25_a->where('month', $month)->where($column, $div_array[$it]));
				$adults = $less19 + $less24 + $over25;
				$paeds = $less2 + $less9 + $less14;
				

				$ldl = $this->checknull($ldl_a->where('month', $month)->where($column, $div_array[$it]));
				$less1k = $this->checknull($less1k_a->where('month', $month)->where($column, $div_array[$it]));
				$less5k = $this->checknull($less5k_a->where('month', $month)->where($column, $div_array[$it]));
				$above5k = $this->checknull($above5k_a->where('month', $month)->where($column, $div_array[$it]));
				$invalids = $this->checknull($invalids_a->where('month', $month)->where($column, $div_array[$it]));
				$sustx = $less5k +  $above5k;

				$plas = $this->checknull($plas_a->where('month', $month)->where($column, $div_array[$it]));
				$edta = $this->checknull($edta_a->where('month', $month)->where($column, $div_array[$it]));
				$dbs = $this->checknull($dbs_a->where('month', $month)->where($column, $div_array[$it]));

				$aplas = $this->checknull($aplas_a->where('month', $month)->where($column, $div_array[$it]));
				$aedta = $this->checknull($aedta_a->where('month', $month)->where($column, $div_array[$it]));
				$adbs = $this->checknull($adbs_a->where('month', $month)->where($column, $div_array[$it]));



				$male = $this->checknull($male_a->where('month', $month)->where($column, $div_array[$it]));
				$female = $this->checknull($female_a->where('month', $month)->where($column, $div_array[$it]));
				$nogender = $this->checknull($nogender_a->where('month', $month)->where($column, $div_array[$it]));

				$tt = $this->check_tat($tat->where('month', $month)->where($column, $div_array[$it]));
				// $tt = $this->checktat($tat->where('month', $month)->where('division', $div_array[$it]));

				

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



				// if($type != 5){
				// 	$age_array = array('baseline' => $baseline,
				// 	'baselinesustxfail' => $baselinefail);

				// 	$data_array = array_merge($age_array, $data_array);
				// }

				if($type == 5){
					$eqa = $this->checknull($eqa_a->where('month', $month)->where($column, $div_array[$it]));
					$fake = $this->checknull($fake_a->where('month', $month)->where($column, $div_array[$it]));
					$controls = $this->checknull($controls_a->where('month', $month)->where('lab', $div_array[$it])) * 3;
					$data_array = array_merge(['eqa' => $eqa, 'fake_confirmatory' => $fake, 'controls' => $controls], $data_array);

					$column = "lab";
				}

				
				if ($type==2) {
					$column="subcounty";
				}

				// echo "\n Column - {$column} ID - {$div_array[$it]}";

				DB::table($sum_table)->where('year', $year)->where('month', $month)->where($column, $div_array[$it])->update($data_array);

				// $search_array = ['year' => $year, 'month' => $month, $column => $div_array[$it]];
				// $update_statements .= $this->update_query($sum_table, $data_array, $search_array);
				// $updates++;

				// if($updates == 150){
				// 	$this->mysqli->multi_query($update_statements);
				// 	$update_statements = '';
				// 	$updates = 0;
				// }	

				$column = $column2;
				
			}

		}
		// $this->mysqli->multi_query($update_statements);
		// End of for loop

		echo "\n Completed entry into viralload {$column} summary at " . date('d/m/Y h:i:s a', time());

		if ($type < 4) {
			echo $this->finish_division($start_month, $year, $today, $div_array, $column, $division, $type, $array_size);
			echo $this->division_rejections($start_month, $year, $today, $div_array, $column, $division, $type, $array_size, $rej_table);
		}
		if($type == 5){
			echo $this->division_rejections($start_month, $year, $today, $div_array, $column, $division, $type, $array_size, $rej_table);
			echo $this->lab_mapping($start_month, $year);			
		}

    }

    public function lab_mapping($start_month, $year=null){
        $counties = DB::table('countys')->select('ID')->orderBy('ID')->get();
        $labs = DB::table('labs')->select('ID')->orderBy('ID')->get();

    	$n = new VlDivision;
    	$update_statements = '';
    	$updates = 0;
    	$today=date("Y-m-d");

    	echo "\n Begin entry into vl lab mapping at " . date('d/m/Y h:i:s a', time());

    	$tests_a = $n->lab_county_tests($year, $start_month);
    	$supported_sites_a = $n->lab_mapping_sites($year, $start_month);

    	for ($i=$start_month; $i < 12; $i++) { 
			$month = $i + 1;
			if($year == Date('Y') && $month > Date('m')){ break; }

	    	foreach ($labs as $lab) {
	    		foreach ($counties as $county) {
	    			// $search = ['month' => $month, 'lab' => $lab->ID, 'county' => $county->ID];
	    			$search_array = ['month' => $month, 'lab' => $lab->ID, 'county' => $county->ID, 'year' => $year];
	    			$tests = $this->checknull( $tests_a->where('month', $month)->where('labtestedin', $lab->ID)->where('county', $county->ID) );
	    			if($tests == 0){
	    				continue;
	    			}
	    			$supported = $this->checknull($supported_sites_a->where('month', $month)->where('labtestedin', $lab->ID)->where('county', $county->ID));

	    			$data_array = ['total' => $tests, 'site_sending' => $supported];

	    			DB::table('vl_lab_mapping')->where($search_array)->update($data_array);

					// $update_statements .= $this->update_query('vl_lab_mapping', $data_array, $search_array);
					// $updates++;

					// if($updates == 150){
					// 	$this->mysqli->multi_query($update_statements);
					// 	$update_statements = '';
					// 	$updates = 0;
					// }	

	    		}
	    	}
	    }
	    // $this->mysqli->multi_query($update_statements);
    	echo "\n Completed entry into vl lab mapping at " . date('d/m/Y h:i:s a', time());
    }

    public function division_rejections($start_month, $year=null, $today, &$div_array, $column, $division, $div_type, $array_size, $rej_table){

    	if($year == null){
    		$year = Date('Y');
    	}
    	// Instantiate new object
    	$n = new VlDivision;
    	$update_statements = '';
    	$updates = 0;

    	$column2 = $column;

    	$today=date("Y-m-d");

    	echo "\n Begin viralload {$rej_table} update at " . date('d/m/Y h:i:s a', time());

    	$reasons = DB::connection('vl')->table('viralrejectedreasons')->select('ID')->get();

    	foreach ($reasons as $key => $value) {
    		$rej_a = $n->national_rejections($year, $start_month, $division, $value->ID);

    		// Loop through the months and insert data into the national summary
			for ($i=$start_month; $i < 12; $i++) { 
				$month = $i + 1;
				if($year == Date('Y') && $month > Date('m')){ break; }

				// Loop through divisions i.e. counties, subcounties, partners and sites
				for ($it=0; $it < $array_size; $it++) { 

					$rej = $this->checknull($rej_a->where('month', $month)->where($column, $div_array[$it]));

					if($rej == 0){
						continue;
					}

					$data_array = array(
						'dateupdated' => $today, 'total' => $rej
					);

					if ($div_type==2) {
						$column="subcounty";
					}

					if ($div_type==5) {
						$column="lab";
					}

					DB::table($rej_table)->where('year', $year)->where('month', $month)->where($column, $div_array[$it])
					->where('rejected_reason', $value->ID)->update($data_array);

					// $search_array = ['year' => $year, 'month' => $month, 'rejected_reason' => $value->ID, $column => $div_array[$it]];
					// $update_statements .= $this->update_query($rej_table, $data_array, $search_array);
					// $updates++;

					// if($updates == 150){
					// 	$this->mysqli->multi_query($update_statements);
					// 	$update_statements = '';
					// 	$updates = 0;
					// }	

					$column = $column2;
				}		
			}
    	}
    	// $this->mysqli->multi_query($update_statements);
    	echo "\n Completed viralload {$rej_table} update at " . date('d/m/Y h:i:s a', time());
    }

    // Div type is the type of division eg county, subcounty, partner and facility
    public function finish_division($start_month, $year, $today, &$div_array, $column, $division, $div_type, $array_size){
    	ini_set("memory_limit", "-1");

    	$n = new VlTest;
    	$update_statements = '';
    	$updates = 0;
    	$column2 = $column;

    	for ($type=1; $type < 7; $type++) { 

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


				// Loop through the months and insert data
				for ($i=$start_month; $i < 12; $i++) { 
					$month = $i + 1;
					if($year == Date('Y') && $month > Date('m')){ break; }

					// Get collection instances of the data
			    	// $rec_a = $n->getallreceivediraloadsamplesbydash($year, $month, $division, $type, $value->ID);
			    	$tested_a = $n->getalltestedviraloadsamplesbydash($year, $month, $division, $type, $value->ID);
			    	$rej_a = $n->getallrejectedviraloadsamplesbydash($year, $month, $division, $type, $value->ID);

			    	$conftx_a = $n->GetNationalConfirmed2VLsbydash($year, $month, $division, $type, $value->ID);
			    	$conf2VL_a = $n->GetNationalConfirmedFailurebydash($year, $month, $division, $type, $value->ID);
			    	$rs = $n->getallrepeattviraloadsamplesbydash($year, $month, $division, $type, $value->ID);

			    	if ($type != 1 && $type != 6) {

				    	$noage_a = $n->getalltestedviraloadsamplesbyagebydash($year, $month, $division, $type, $value->ID, 0);
				    	$less2_a = $n->getalltestedviraloadsamplesbyagebydash($year, $month, $division, $type, $value->ID, 6);
				    	$less9_a = $n->getalltestedviraloadsamplesbyagebydash($year, $month, $division, $type, $value->ID, 7);
				    	$less14_a = $n->getalltestedviraloadsamplesbyagebydash($year, $month, $division, $type, $value->ID, 8);
				    	$less19_a = $n->getalltestedviraloadsamplesbyagebydash($year, $month, $division, $type, $value->ID, 9);
				    	$less24_a = $n->getalltestedviraloadsamplesbyagebydash($year, $month, $division, $type, $value->ID, 10);
				    	$over25_a = $n->getalltestedviraloadsamplesbyagebydash($year, $month, $division, $type, $value->ID, 11);
				    }

			    	// $adults=$less19 +$less24 + $over25 ;
					// $paeds=$less2 + $less9 + $less14;

					$ldl_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $month, $division, $type, $value->ID, 1);
					$less1k_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $month, $division, $type, $value->ID, 2);
					$less5k_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $month, $division, $type, $value->ID, 3);
					$above5k_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $month, $division, $type, $value->ID, 4);
					$invalids_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $month, $division, $type, $value->ID, 5);
					// $sustx=$less5k +  $above5k;

					if($type != 4 && $type != 6){

						$plas_a = $n->getalltestedviraloadsamplesbytypedetailsbydash($year, $month, $division, $type, $value->ID, 1);
						$edta_a = $n->getalltestedviraloadsamplesbytypedetailsbydash($year, $month, $division, $type, $value->ID, 3);
						$dbs_a = $n->getalltestedviraloadsamplesbytypedetailsbydash($year, $month, $division, $type, $value->ID, 2);
					}

					if($type != 2 && $type != 6){

						$male_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $month, $division, $type, $value->ID, 1);
						$female_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $month, $division, $type, $value->ID, 2);
						$nogender_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $month, $division, $type, $value->ID, 3);

					}

					if ($type != 5) {
						$baseline_a = $n->GetNationalBaselinebydash($year, $month, $division, $type, $value->ID);
						$baselinefail_a = $n->GetNationalBaselineFailurebydash($year, $month, $division, $type, $value->ID);
					}

					// Loop through divisions i.e. counties, subcounties, partners and sites
					for ($it=0; $it < $array_size; $it++) { 
						// $rec = $this->checknull($rec_a->where('month', $month));
						$tested = $this->checknull($tested_a->where($column, $div_array[$it]));

						if($tested == 0){
							continue;
						}

						$rej = $this->checknull($rej_a->where($column, $div_array[$it]));

						$conftx = $this->checknull($conftx_a->where($column, $div_array[$it]));
						$conf2VL = $this->checknull($conf2VL_a->where($column, $div_array[$it]));

						$ldl = $this->checknull($ldl_a->where($column, $div_array[$it]));
						$less1k = $this->checknull($less1k_a->where($column, $div_array[$it]));
						$less5k = $this->checknull($less5k_a->where($column, $div_array[$it]));
						$above5k = $this->checknull($above5k_a->where($column, $div_array[$it]));
						$invalids = $this->checknull($invalids_a->where($column, $div_array[$it]));
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

							$noage = $this->checknull($noage_a->where($column, $div_array[$it]));
							$less2 = $this->checknull($less2_a->where($column, $div_array[$it]));
							$less9 = $this->checknull($less9_a->where($column, $div_array[$it]));
							$less14 = $this->checknull($less14_a->where($column, $div_array[$it]));
							$less19 = $this->checknull($less19_a->where($column, $div_array[$it]));
							$less24 = $this->checknull($less24_a->where($column, $div_array[$it]));
							$over25 = $this->checknull($over25_a->where($column, $div_array[$it]));
							$adults = $less19 + $less24 + $over25;
							$paeds = $less2 + $less9 + $less14;

							$age_array = array('less2' => $less2, 'less9' => $less9,
							'less14' => $less14, 'less19' => $less19, 'less24' => $less24,
							'over25' => $over25, 'adults' => $adults, 'paeds' => $paeds,
							'noage' => $noage);

							$data_array = array_merge($age_array, $data_array);

						}

						if($type != 4 && $type != 6){

							$plas = $this->checknull($plas_a->where($column, $div_array[$it]));
							$edta = $this->checknull($edta_a->where($column, $div_array[$it]));
							$dbs = $this->checknull($dbs_a->where($column, $div_array[$it]));

							$sample_array = array('dbs' => $dbs, 'plasma' => $plas, 'edta' => $edta);

							$data_array = array_merge($sample_array, $data_array);

						}

						if ($type != 2 && $type != 6) {
						
							$male = $this->checknull($male_a->where($column, $div_array[$it]));
							$female = $this->checknull($female_a->where($column, $div_array[$it]));
							$nogender = $this->checknull($nogender_a->where($column, $div_array[$it]));

							$gender_array = array('maletest' => $male, 'femaletest' => $female, 'nogendertest' => $nogender);

							$data_array = array_merge($gender_array, $data_array);
						}

						if ($type != 5) {
						
							$baseline = $this->checknull($baseline_a->where($column, $div_array[$it]));
							$baselinefail = $this->checknull($baselinefail_a->where($column, $div_array[$it]));

							$baseline_array = array('baseline' => $baseline, 'baselinesustxfail' => $baselinefail);

							$data_array = array_merge($baseline_array, $data_array);
						}

						if($div_type == 2){
							$column = "subcounty";
						}
						

						DB::table($table[0])->where('year', $year)->where('month', $month)->where($table[2], $value->ID)->where($column, $div_array[$it])->update($data_array);

						// $search_array = ['year' => $year, 'month' => $month, $table[2] => $value->ID, $column => $div_array[$it]];
						// $update_statements .= $this->update_query($table[0], $data_array, $search_array);
						// $updates++;

						// if($updates == 150){
						// 	$this->mysqli->multi_query($update_statements);
						// 	$update_statements = '';
						// 	$updates = 0;
						// }

						$column = $column2;
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
    	return $this->update_division($start_month, $year, 1, 'county', 'view_facilitys.county', 'countys', 'vl_county_summary', 'vl_county_rejections');
    }

    public function update_subcounties($start_month, $year=null){
    	return $this->update_division($start_month, $year, 2, 'district', 'view_facilitys.district', 'districts', 'vl_subcounty_summary', 'vl_subcounty_rejections');
    }

    public function update_partners($start_month, $year=null){
    	return $this->update_division($start_month, $year, 3, 'partner', 'view_facilitys.partner', 'partners', 'vl_partner_summary', 'vl_partner_rejections');
    }

    public function update_facilities($start_month, $year=null){
    	return $this->update_division($start_month, $year, 4, 'facility', 'viralsamples.facility', 'facilitys', 'vl_site_summary', 'vl_site_rejections');
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

    	$divs = DB::connection('vl')
		->table('facilitys')->select('ID')->get();

		foreach ($divs as $key => $value) {
			$div_array[$key] = $value->ID;
			$array_size++;
		}

		return $this->finish_division($start_month, $year, $today, $div_array, 'facility', 'viralsamples.facility', 4, $array_size);
    }

    public function update_labs($start_month, $year=null){
    	return $this->update_division($start_month, $year, 5, 'labtestedin', "viralsamples.labtestedin", 'labs', 'vl_lab_summary', 'vl_lab_rejections');

    }

    public function finish_labs(){
    	echo "\n Begin lablogs update at " . date('d/m/Y h:i:s a', time());

    	$logdate=	date("Y-m-d");
    	$datestatsupdated = date('Y-m-d H:i:s');

    	$labs = $data = DB::table('labs')->select('ID')->get();
    	$testtypes = $data = DB::table('testtype')->select('ID')->get();

    	foreach ($labs as $key => $lab) {
			foreach ($testtypes as $key2 => $ttype) {
				DB::table('lablogs')->insert([
					'lab' => $lab->ID, 'logdate' => $logdate, 'dateupdated' => $datestatsupdated,
					'testtype' => $ttype->ID
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
    	// $gender_data = $n->current_gender_suppression();
    	$age_data = $n->current_age_suppression();


		// $noage = $n->current_age_suppression(0);
    	// $less2 = $n->current_age_suppression(6);
    	// $less9 = $n->current_age_suppression(7);
    	// $less14 = $n->current_age_suppression(8);
    	// $less19 = $n->current_age_suppression(9);
    	// $less24 = $n->current_age_suppression(10);
    	// $over25 = $n->current_age_suppression(11);

    	// $noage_n= $n->current_age_suppression(0, false);
    	// $less2_n = $n->current_age_suppression(6, false);
    	// $less9_n = $n->current_age_suppression(7, false);
    	// $less14_n = $n->current_age_suppression(8, false);
    	// $less19_n = $n->current_age_suppression(9, false);
    	// $less24_n = $n->current_age_suppression(10, false);
    	// $over25_n = $n->current_age_suppression(11, false);

    	$male = $n->current_gender_suppression(1);  
    	$female = $n->current_gender_suppression(2);  
    	$nogender = $n->current_gender_suppression(3);

    	$male_n = $n->current_gender_suppression(1, false);  
    	$female_n = $n->current_gender_suppression(2, false);  
    	$nogender_n = $n->current_gender_suppression(3, false);

    	$divs = DB::table('facilitys')->select('ID', 'totalartsep17')->get();

		$data = collect($data);


		foreach ($divs as $key => $value) {

			$suppressed = 
			(int) $this->checknull($data->where('facility', $value->ID)->where('rcategory', 1)) + 
			(int) $this->checknull($data->where('facility', $value->ID)->where('rcategory', 2));

			$nonsuppressed = 
			(int) $this->checknull($data->where('facility', $value->ID)->where('rcategory', 3)) + 
			(int) $this->checknull($data->where('facility', $value->ID)->where('rcategory', 4));

			$suppression=0;

			$tests =  ($suppressed + $nonsuppressed);
			$coverage = 0;

			if($tests == 0){
				continue;
				$suppression = 0;
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

			// $noage_sup =  $this->checknull($noage->where('facility', $value->ID));
			// $noage_nonsup =  $this->checknull($noage_n->where('facility', $value->ID));

			// $less2_sup = $this->checknull($less2->where('facility', $value->ID));
			// $less2_nonsup =  $this->checknull($less2_n->where('facility', $value->ID));

			// $less9_sup = $this->checknull($less9->where('facility', $value->ID));
			// $less9_nonsup =  $this->checknull($less9_n->where('facility', $value->ID));

			// $less14_sup = $this->checknull($less14->where('facility', $value->ID));
			// $less14_nonsup =  $this->checknull($less14_n->where('facility', $value->ID));

			// $less19_sup = $this->checknull($less19->where('facility', $value->ID));
			// $less19_nonsup =  $this->checknull($less19_n->where('facility', $value->ID));

			// $less24_sup = $this->checknull($less24->where('facility', $value->ID));
			// $less24_nonsup =  $this->checknull($less24_n->where('facility', $value->ID));

			// $over25_sup = $this->checknull($over25->where('facility', $value->ID));
			// $over25_nonsup =  $this->checknull($over25_n->where('facility', $value->ID));


			$male_sup = $this->checknull($male->where('facility', $value->ID));
			$male_nonsup = $this->checknull($male_n->where('facility', $value->ID));

			$female_sup = $this->checknull($female->where('facility', $value->ID));
			$female_nonsup = $this->checknull($female_n->where('facility', $value->ID)); 

			$nogender_sup = $this->checknull($nogender->where('facility', $value->ID));
			$nogender_nonsup = $this->checknull($nogender_n->where('facility', $value->ID));


			$noage_sup = 
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 1)->where('age2', 0)) +
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 2)->where('age2', 0));
			$noage_nonsup = 
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 3)->where('age2', 0)) +
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 4)->where('age2', 0));

			$less2_sup = 
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 1)->where('age2', 6)) +
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 2)->where('age2', 6));
			$less2_nonsup = 
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 3)->where('age2', 6)) +
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 4)->where('age2', 6));

			$less9_sup = 
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 1)->where('age2', 7)) +
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 2)->where('age2', 7));
			$less9_nonsup = 
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 3)->where('age2', 7)) +
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 4)->where('age2', 7));

			$less14_sup = 
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 1)->where('age2', 8)) +
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 2)->where('age2', 8));
			$less14_nonsup = 
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 3)->where('age2', 8)) +
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 4)->where('age2', 8));

			$less19_sup = 
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 1)->where('age2', 9)) +
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 2)->where('age2', 9));
			$less19_nonsup = 
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 3)->where('age2', 9)) +
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 4)->where('age2', 9));

			$less24_sup = 
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 1)->where('age2', 10)) +
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 2)->where('age2', 10));
			$less24_nonsup = 
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 3)->where('age2', 10)) +
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 4)->where('age2', 10));

			$over25_sup = 
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 1)->where('age2', 11)) +
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 2)->where('age2', 11));
			$over25_nonsup = 
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 3)->where('age2', 11)) +
			(int) $this->checknull($age_data->where('facility', $value->ID)->where('rcategory', 4)->where('age2', 11));



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

			$data_array = array('dateupdated' => $today, 'suppressed' => $suppressed, 
				'nonsuppressed' => $nonsuppressed, 'suppression' => $suppression, 'coverage' => $coverage,

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

			DB::table('vl_site_suppression')->where('facility', $value->ID)->update($data_array);
		}

    	echo "\n Completed entry into vl suppression at " . date('d/m/Y h:i:s a', time()); 
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


    public function checknull($var){
    	if($var->isEmpty()){
    		return 0;
    	}else{
    		// return $var->sum('totals');
    		return $var->first()->totals;
    	}
    }

    public function check_sample($var){
    	if($var->isEmpty()){
    		return 0;
    	}else{
    		// return $var->sum('totals');
    		return $var->first()->sampletype;
    	}
    }

    public function checktat($var){
    	if($var->isEmpty()){
    		return array('tat1' => 0, 'tat2' => 0, 'tat3' => 0, 'tat4' => 0);
    	}else{
    		return $var->first();
    	}
    }

     public function check_tat($var){
    	if($var->isEmpty()){
    		return array('tat1' => 0, 'tat2' => 0, 'tat3' => 0, 'tat4' => 0);
    	}else{
    		// $tat1 = $var->avg('tat1');
    		// $tat2 = $var->avg('tat2');
    		// $tat3 = $var->avg('tat3');
    		// $tat4 = $var->avg('tat4');
    		$t = $var->first();
    		return array('tat1' => $t->tat1, 'tat2' => $t->tat2, 'tat3' => $t->tat3, 'tat4' => $t->tat4);
    		// return array('tat1' => $tat1, 'tat2' => $tat2, 'tat3' => $tat3, 'tat4' => $tat4);
    		// return $var->first()->toArray();
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
    				$name = array("vl_national_regimen", "viralprophylaxis", "regimen");
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
    				$name = array("vl_county_regimen", "viralprophylaxis", "regimen");
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
    				$name = array("vl_subcounty_regimen", "viralprophylaxis", "regimen");
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
    				$name = array("vl_partner_regimen", "viralprophylaxis", "regimen");
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
    				$name = array("vl_site_regimen", "viralprophylaxis", "regimen");
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
    	$sql .= ";
    	 ";
    	return $sql;
    }
}
