<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ViralNation;
use App\ViralDivision;
use DB;

class Viral extends Model
{
    //

    public function update_nation($year = null){
    	if($year == null){
    		$year = Date('Y');
    	}
    	// Instantiate new object
    	$n = new ViralNation;

    	$today=date("Y-m-d");

    	echo "\n Begin  viralload nation update at " . date('d/m/Y h:i:s a', time());

    	// Get collection instances of the data

    	$rec_a = $n->getallreceivediraloadsamples($year);
    	$tested_a = $n->getalltestedviraloadsamples($year);
    	$actualpatients_a = $n->getallactualpatients($year);
    	$rej_a = $n->getallrejectedviraloadsamples($year);
    	$sites_a = $n->GetSupportedfacilitysFORViralLoad($year);

    	$conftx_a = $n->GetNationalConfirmed2VLs($year);
    	$conf2VL_a = $n->GetNationalConfirmedFailure($year);
    	$rs=0;

    	$baseline_a = $n->GetNationalBaseline($year);
    	$baselinefail_a = $n->GetNationalBaselineFailure($year);

    	$noage_a = $n->getalltestedviraloadsbyage($year, 1);
    	$less2_a = $n->getalltestedviraloadsbyage($year, 6);
    	$less9_a = $n->getalltestedviraloadsbyage($year, 7);
    	$less14_a = $n->getalltestedviraloadsbyage($year, 8);
    	$less19_a = $n->getalltestedviraloadsbyage($year, 9);
    	$less24_a = $n->getalltestedviraloadsbyage($year, 10);
    	$over25_a = $n->getalltestedviraloadsbyage($year, 11);

    	// $adults=$less19 +$less24 + $over25 ;
		// $paeds=$less2 + $less9 + $less14;

		$ldl_a = $n->getalltestedviraloadsbyresult($year, 1);
		$less1k_a = $n->getalltestedviraloadsbyresult($year, 2);
		$less5k_a = $n->getalltestedviraloadsbyresult($year, 3);
		$above5k_a = $n->getalltestedviraloadsbyresult($year, 4);
		$invalids_a = $n->getalltestedviraloadsbyresult($year, 5);
		// $sustx=$less5k +  $above5k;

		$plas_a = $n->getalltestedviraloadsamplesbytypedetails($year, 1);
		$edta_a = $n->getalltestedviraloadsamplesbytypedetails($year, 2);
		$dcap_a = $n->getalltestedviraloadsamplesbytypedetails($year, 3);
		$dven_a = $n->getalltestedviraloadsamplesbytypedetails($year, 4);
		// $dbs=$dcap + $dven;

		$male_a = $n->getalltestedviraloadbygender($year, 1);
		$female_a = $n->getalltestedviraloadbygender($year, 2);
		$nogender_a = $n->getalltestedviraloadbygender($year, 3);

		$tat = $n->GetNatTATs($year);
		$tat = collect($tat);

		$count = $rec_a->count();

		// Loop through the months and insert data into the national summary
		for ($i=0; $i < $count; $i++) { 
			$month = $i + 1;

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
			$dcap = $this->checknull($dcap_a->where('month', $month));
			$dven = $this->checknull($dven_a->where('month', $month));
			$dbs=$dcap + $dven;

			$male = $this->checknull($male_a->where('month', $month));
			$female = $this->checknull($female_a->where('month', $month));
			$nogender = $this->checknull($nogender_a->where('month', $month));

			$tt = $this->checktat($tat->where('month', $month));

			$data_array = array(
				'received' => $rec, 'alltests' => $tested, 'actualpatients' => $actualpatients,
				'sustxfail' => $sustx, 'confirmtx' => $conftx, 'repeattests' => $rs,
				'confirm2vl' => $conf2VL, 'rejected' => $rej, 'dbs' => $dbs, 'plasma' => $plas,
				'edta' => $edta, 'maletest' => $male, 'femaletest' => $female,
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

			// $sql = "UPDATE vl_national_summary set received='$rec',alltests='$tested' ,actualpatients='$actualpatients' ,sustxfail='$sustx',confirmtx='$conftx',repeattests='$rs',confirm2vl='$conf2VL',rejected='$rej',dbs='$dbs',plasma='$plas',edta='$edta',maletest='$male',femaletest='$female',nogendertest='$nogender',adults='$adults',paeds='$paeds',noage='$noage',Undetected='$ldl',less1000='$less1k',less5000='$less5k',above5000='$above5k',invalids='$invalids',sitessending='$sites' ,less2='$less2',less9='$less9',less14='$less14',less19='$less19',less24='$less24',over25='$over25', tat1='$t1', tat2='$t2', tat3='$t3', tat4='$t4',baseline='$baseline',baselinesustxfail='$baselinefail', dateupdated='$today'  WHERE month='$month' AND year='$year' ";

		}
		// End of for loop

		echo "\n Completed entry into viralload national summary at " . date('d/m/Y h:i:s a', time());

		echo $this->finish_nation($year, $today);


    }

    private function finish_nation($year, $today){
    	$n = new ViralNation;
    	for ($type=1; $type < 6; $type++) { 
			$table = $this->get_table(0, $type);

			echo "\n Begin " . $table[0] . " update at " . date('d/m/Y h:i:s a', time());
			
			// Get ids of the necessary table
			$divs = $data = DB::connection('vl')
			->table($table[1])->select('ID')
			->when($type, function($query) use ($type){
				if($type == 1){
					return $query->where('subID', 1);
				}				
			})
			->get();

			foreach ($divs as $key => $value) {	

				// Get collection instances of the data
		    	$rec_a = $n->getallreceivediraloadsamplesbydash($year, $type, $value->ID);
		    	$tested_a = $n->getalltestedviraloadsamplesbydash($year, $type, $value->ID);
		    	$rej_a = $n->getallrejectedviraloadsamplesbydash($year, $type, $value->ID);

		    	$conftx_a = $n->GetNationalConfirmed2VLsbydash($year, $type, $value->ID);
		    	$conf2VL_a = $n->GetNationalConfirmedFailurebydash($year, $type, $value->ID);
		    	$rs = $n->getallrepeattviraloadsamplesbydash($year, $type, $value->ID);

		    	if ($type != 1) {

			    	$noage_a = $n->getalltestedviraloadsamplesbyagebydash($year, $type, $value->ID, 1);
			    	$less2_a = $n->getalltestedviraloadsamplesbyagebydash($year, $type, $value->ID, 6);
			    	$less9_a = $n->getalltestedviraloadsamplesbyagebydash($year, $type, $value->ID, 7);
			    	$less14_a = $n->getalltestedviraloadsamplesbyagebydash($year, $type, $value->ID, 8);
			    	$less19_a = $n->getalltestedviraloadsamplesbyagebydash($year, $type, $value->ID, 9);
			    	$less24_a = $n->getalltestedviraloadsamplesbyagebydash($year, $type, $value->ID, 10);
			    	$over25_a = $n->getalltestedviraloadsamplesbyagebydash($year, $type, $value->ID, 11);
			    }

		    	// $adults=$less19 +$less24 + $over25 ;
				// $paeds=$less2 + $less9 + $less14;

				$ldl_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $type, $value->ID, 1);
				$less1k_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $type, $value->ID, 2);
				$less5k_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $type, $value->ID, 3);
				$above5k_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $type, $value->ID, 4);
				$invalids_a = $n->getalltestedviraloadsamplesbyresultbydash($year, $type, $value->ID, 5);
				// $sustx=$less5k +  $above5k;

				if($type != 4){

					$plas_a = $n->getalltestedviraloadsamplesbytypedetailsbydash($year, $type, $value->ID, 1);
					$edta_a = $n->getalltestedviraloadsamplesbytypedetailsbydash($year, $type, $value->ID, 2);
					$dcap_a = $n->getalltestedviraloadsamplesbytypedetailsbydash($year, $type, $value->ID, 3);
					$dven_a = $n->getalltestedviraloadsamplesbytypedetailsbydash($year, $type, $value->ID, 4);
					// $dbs=$dcap + $dven;
				}

				if($type != 2){

					$male_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $type, $value->ID, 1);
					$female_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $type, $value->ID, 2);
					$nogender_a = $n->getalltestedviraloadsamplesbygenderbydash($year, $type, $value->ID, 3);

				}

				// Loop through the months and insert data into the national summary
				for ($i=0; $i < 12; $i++) { 
					$month = $i + 1;

					// $rec = $this->checknull($rec_a->where('month', $month));
					$tested = $this->checknull($tested_a->where('month', $month));
					$rej = $this->checknull($rej_a->where('month', $month));

					$conftx = $this->checknull($conftx_a->where('month', $month));
					$conf2VL = $this->checknull($conf2VL_a->where('month', $month));

					$ldl = $this->checknull($ldl_a->where('month', $month));
					$less1k = $this->checknull($less1k_a->where('month', $month));
					$less5k = $this->checknull($less5k_a->where('month', $month));
					$above5k = $this->checknull($above5k_a->where('month', $month));
					$invalids = $this->checknull($invalids_a->where('month', $month));
					$sustx = $less5k +  $above5k;

					$data_array = array(
						'tests' => $tested,
						'sustxfail' => $sustx, 'confirmtx' => $conftx, 'repeattests' => $rs,
						'confirm2vl' => $conf2VL, 'rejected' => $rej, 'Undetected' => $ldl, 'less1000' => $less1k,
						'less5000' => $less5k, 'above5000' => $above5k, 'invalids' => $invalids,
						'dateupdated' => $today
					);					

					if($type != 1){

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

						$data_array = array_merge($age_array, $data_array);

					}

					if($type != 4){

						$plas = $this->checknull($plas_a->where('month', $month));
						$edta = $this->checknull($edta_a->where('month', $month));
						$dcap = $this->checknull($dcap_a->where('month', $month));
						$dven = $this->checknull($dven_a->where('month', $month));
						$dbs=$dcap + $dven;

						$sample_array = array('dbs' => $dbs, 'plasma' => $plas, 'edta' => $edta);

						$data_array = array_merge($sample_array, $data_array);

					}

					if ($type != 2) {
					
						$male = $this->checknull($male_a->where('month', $month));
						$female = $this->checknull($female_a->where('month', $month));
						$nogender = $this->checknull($nogender_a->where('month', $month));

						$gender_array = array('maletest' => $male, 'femaletest' => $female, 'nogendertest' => $nogender,);

						$data_array = array_merge($gender_array, $data_array);
					}
					

					DB::table($table[0])->where('year', $year)->where('month', $month)->where($table[2], $value->ID)->update($data_array);

				}
				// End of for loop for months

			}
			// End of looping through ids of each table e.g. agecategory
			echo "\n Completed " . $table[0] . " update at " . date('d/m/Y h:i:s a', time());
		}
		// End of looping of params
    }

    public function checknull($var){
    	if($var->isEmpty()){
    		return 0;
    	}else{
    		return $var->first()->totals;
    	}
    }

    public function checktat($var){
    	if($var->isEmpty()){
    		return array('tat1' => 0, 'tat2' => 0, 'tat3' => 0, 'tat4' => 0);
    	}else{
    		return $var->first();
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
    			default:
    				break;
    		}
    	}
    	return $name;
    }
}