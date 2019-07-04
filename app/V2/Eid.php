<?php

namespace App\V2;

use App\V2\EidNation;
use App\V2\EidDivision;
use App\V2\EidFacility;
use App\V2\EidPoc;
use DB;

class Eid
{

	// protected $mysqli;

	// public function __construct(){
	// 	$this->mysqli = new \mysqli(env('DB_HOST', '127.0.0.1'), env('DB_USERNAME', 'forge'), env('DB_PASSWORD', ''), env('DB_DATABASE', 'forge'), env('DB_PORT_WR', 3306));
	// }

    public function update_nation($year = null){
    	if($year == null){
    		$year = Date('Y');
    	}
    	// Instantiate new object
    	$n = new EidNation;

    	$update_statements = "";
    	$updates = 0;

    	$today=date("Y-m-d");

    	echo "\n Begin eid nation update at " . date('d/m/Y h:i:s a', time());

    	// Get collection instances of the data
    	$alltests_a = $n->CumulativeTestedSamples($year);
    	$eqatests_a = $n->OverallEQATestedSamples($year);

    	$tests_a = $n->OverallTestedSamples($year);
		
		$patienttests_a = $n->OverallTestedPatients($year);
		$patienttestsPOS_a = $n->OverallTestedPatients($year, true);
		$received_a = $n->OverallReceivedSamples($year);

		$firstdna_a = $n->getbypcr($year, 1);
		$posrepeats_a = $n->getbypcr($year, 2);
		$confirmdna_a = $n->getbypcr($year, 4);

		$posrepeatsPOS_a = $n->getbypcr($year, 2, true);
		$confirmdnaPOS_a = $n->getbypcr($year, 4, true);

		$infantsless2m_a = 		$n->Gettestedsamplescountrange($year, 1);
		$infantsless2mPOS_a = 	$n->Gettestedsamplescountrange($year, 1, true);
		$infantsless2w_a =		$n->Gettestedsamplescountrange($year, 3);
		$infantsless2wPOS_a =	$n->Gettestedsamplescountrange($year, 3, true);
		$infantsless46w_a =		$n->Gettestedsamplescountrange($year, 4);
		$infantsless46wPOS_a =	$n->Gettestedsamplescountrange($year, 4, true);
		$infantsabove2m_a =		$n->Gettestedsamplescountrange($year, 2);
		$infantsabove2mPOS_a = 	$n->Gettestedsamplescountrange($year, 2, true);
		$adulttests_a =			$n->Gettestedsamplescountrange($year, 5);
		$adulttestsPOS_a =		$n->Gettestedsamplescountrange($year, 5, true);	

		$pos_a = $n->OverallTestedSamplesOutcomes($year, 2, 1);
		$neg_a = $n->OverallTestedSamplesOutcomes($year, 1, 1);

		$rpos_a = $n->OverallTestedSamplesOutcomes($year, 2, 2);
		$rneg_a = $n->OverallTestedSamplesOutcomes($year, 1, 2);

		$allpos_a = $n->OverallTestedSamplesOutcomes($year, 2, 0);
		$allneg_a = $n->OverallTestedSamplesOutcomes($year, 1, 0);

		$fail_a = $n->OverallTestedSamplesOutcomes($year, 3, 1);
		$rd_a = $n->OverallTestedSamplesOutcomes($year, 5, 1);
		$rdd_a = $n->OverallTestedSamplesOutcomes($year, 6, 1);
		// $redraw=$fail + $rd + $rdd;
		
		//echo $alltests;
		$rej_a = $n->Getnationalrejectedsamples($year);

		$followups_a = $n->GetHEIFollowUpNational($year, 'enrollment_status');
		$validation_a = $n->GetHEIFollowUpNational($year, 'hei_validation');

		$sitesending_a = $n->GettotalEidsitesbytimeperiod($year);
		$avgage_a = $n->Getoverallaverageage($year);
		$medage_array = $n->Getoverallmedianage($year);
		$medage_a = collect($medage_array);

		$tat = $n->get_tat($year);

		// Loop through the months and insert data into the national summary
		for ($i=0; $i < 12; $i++) { 
			$month = $i + 1;
			if($year == Date('Y') && $month > Date('m')){ break; }

			$alltests = $this->checknull_month($alltests_a, $month);
			$eqatests = $this->checknull_month($eqatests_a, $month);
			$tests = $this->checknull_month($tests_a, $month);
			$patienttests = $this->checknull_month($patienttests_a, $month);
			$patienttestsPOS = $this->checknull_month($patienttestsPOS_a, $month);

			$received = $this->checknull_month($received_a, $month);
			$firstdna = $this->checknull_month($firstdna_a, $month);
			$confirmdna = $this->checknull_month($confirmdna_a, $month);

			$posrepeats = $this->checknull_month($posrepeats_a, $month);
			$confirmdnaPOS = $this->checknull_month($confirmdnaPOS_a, $month);

			$posrepeatsPOS = $this->checknull_month($posrepeatsPOS_a, $month);
			$confimPOS = $confirmdnaPOS + $posrepeatsPOS;

			$infantsless2m = $this->checknull_month($infantsless2m_a, $month);
			$infantsless2mPOS = $this->checknull_month($infantsless2mPOS_a, $month);
			$infantsless2w = $this->checknull_month($infantsless2w_a, $month);
			$infantsless2wPOS = $this->checknull_month($infantsless2wPOS_a, $month);
			$infantsless46w = $this->checknull_month($infantsless46w_a, $month);
			$infantsless46wPOS = $this->checknull_month($infantsless46wPOS_a, $month);
			$infantsabove2m = $this->checknull_month($infantsabove2m_a, $month);
			$infantsabove2mPOS = $this->checknull_month($infantsabove2mPOS_a, $month);
			$adulttests = $this->checknull_month($adulttests_a, $month);
			$adulttestsPOS = $this->checknull_month($adulttestsPOS_a, $month);


			$pos = $this->checknull_month($pos_a, $month);
			$neg = $this->checknull_month($neg_a, $month);
			$fail = $this->checknull_month($fail_a, $month);
			$rd = $this->checknull_month($rd_a, $month);
			$rdd = $this->checknull_month($rdd_a, $month);
			$redraw = $fail + $rd + $rdd;

			$rpos = $this->checknull_month($rpos_a, $month);
			$rneg = $this->checknull_month($rneg_a, $month);

			$allpos = $this->checknull_month($allpos_a, $month);
			$allneg = $this->checknull_month($allneg_a, $month);

			$rej = $this->checknull_month($rej_a, $month);

			$enrolled = $this->checknull_month($followups_a->where('enrollment_status', 1), $month);
			$ltfu = $this->checknull_month($followups_a->where('enrollment_status', 2), $month);
			$dead = $this->checknull_month($followups_a->where('enrollment_status', 3), $month);
			$adult = $this->checknull_month($followups_a->where('enrollment_status', 4), $month);
			$transout = $this->checknull_month($followups_a->where('enrollment_status', 5), $month);
			$other = $this->checknull_month($followups_a->where('enrollment_status', 6), $month);


			$v_cp = $this->checknull_month($validation_a->where('hei_validation', 1), $month);
			$v_ad = $this->checknull_month($validation_a->where('hei_validation', 2), $month);
			$v_vl = $this->checknull_month($validation_a->where('hei_validation', 3), $month);
			$v_rp = $this->checknull_month($validation_a->where('hei_validation', 4), $month);
			$v_uf = $this->checknull_month($validation_a->where('hei_validation', 5), $month);

			$sitesending = $this->checknull_month($sitesending_a, $month);
			$avgage = $this->checknull_month($avgage_a, $month);
			$medage = $this->checkmedage($medage_a->where('month', $month));

			// $tt = $tat[$i];			
			$tt = $this->check_tat($tat->where('month', $month));			

			$data_array = array(
				'avgage' => $avgage,	'medage' => $medage,	'received' => $received,
				'alltests' => $alltests, 'eqatests' => $eqatests, 'tests' => $tests,
				'firstdna' => $firstdna, 'confirmdna' => $confirmdna, 'repeatspos' => $posrepeats,
				'confirmedPOs' => $confirmdnaPOS, 'repeatposPOS' => $posrepeatsPOS,
				'infantsless2m' => $infantsless2m,	'infantsless2mPOs' => $infantsless2mPOS,
				'infantsless2w' => $infantsless2w,	'infantsless2wPOs' => $infantsless2wPOS,
				'infantsabove2m' => $infantsabove2m,
				'infantsabove2mPOs' => $infantsabove2mPOS, 'adults' => $adulttests,
				'adultsPOs' => $adulttestsPOS, 'actualinfants' => $patienttests,
				'actualinfantsPOs' => $patienttestsPOS, 'pos' => $pos, 'neg' => $neg,
				'allpos' => $allpos, 'allneg' => $allneg, 'rpos' => $rpos, 'rneg' => $rneg,
				'redraw' => $redraw, 'rejected' => $rej, 'enrolled' => $enrolled, 'dead' => $dead,
				'ltfu' => $ltfu, 'adult' => $adult, 'transout' => $transout, 'other' => $other,
				'validation_confirmedpos' => $v_cp, 'validation_repeattest' => $v_ad,
				'validation_viralload' => $v_vl, 'validation_adult' => $v_rp,
				'validation_unknownsite' => $v_uf, 'sitessending' => $sitesending,
				'tat1' => $tt['tat1'], 'tat2' => $tt['tat2'], 'tat3' => $tt['tat3'], 
				'tat4' => $tt['tat4'], 'dateupdated' => $today
			);

			DB::table('national_summary')->where('year', $year)->where('month', $month)->update($data_array);

			// $update_statements .= $this->update_query('national_summary', $data_array, ['year' => $year, 'month' => $month]);

		}
		// End of for loop

		// DB::update(DB::raw($update_statements), []);

		// $mysqli = new \mysqli(env('DB_HOST', '127.0.0.1'), env('DB_USERNAME', 'forge'), env('DB_PASSWORD', ''), env('DB_DATABASE', 'forge')); 
		// $this->mysqli->multi_query($update_statements);

		// $update_statements = '';

		echo "\n Completed entry into eid national summary at " . date('d/m/Y h:i:s a', time());


		echo $this->continue_nation($year, $today);

		echo "\n Begin entry into eid national rejections " . date('d/m/Y h:i:s a', time());

		// Start of rejections
		$reasons = $data = DB::connection('eid_vl')
		->table('rejectedreasons')->select('id')->get();

		// Loop through reasons
		foreach ($reasons as $key => $value) {
			$rej_a = $n->national_rejections($year, $value->id);

			// Loop through each month and update reason
			for ($i=0; $i < 12; $i++) { 
				$month = $i + 1;
				if($year == Date('Y') && $month > Date('m')){ break; }

				$rej = $this->checknull_month($rej_a, $month);

				if($rej == 0){
					continue;
				}

				$data_array = array(
					'total' => $rej, 'dateupdated' => $today
				);

				DB::table('national_rejections')->where('year', $year)->where('month', $month)->where('rejected_reason', $value->id)->update($data_array);

				// $update_statements .= $this->update_query('national_rejections', $data_array, ['year' => $year, 'month' => $month, 'rejected_reason' => $value->id]);

			}
			
		}
		// End of rejections
		// $this->mysqli->multi_query($update_statements);

		echo "\n Completed entry into eid national rejections at " . date('d/m/Y h:i:s a', time());

		// End of national function
    }

    public function continue_nation($year, $today){
    	$n = new EidNation;

    	$update_statements = "";
    	$updates = 0;

    	for ($type=1; $type < 5; $type++) { 

			$table = $this->get_table(0, $type);

			echo "\n Begin " . $table[0] . " update at " . date('d/m/Y h:i:s a', time());
			
			// Get ids of the necessary table
			$divs = $data = DB::connection('eid_vl')
			->table($table[1])
			->when($type, function($query) use ($type){
				if($type == 1){
					return $query->where('ptype', 2);
				}
				if($type == 2){
					return $query->where('ptype', 1);
				}								
			})
			->get();

			foreach ($divs as $key => $value) {

				if($type == 1){
					$result_a = $n->Getinfantprophpositivitycount($year, $value->id);
				}

				if($type == 2){
					$result_a = $n->Getinterventionspositivitycount($year, $value->id);
				}

				if($type == 3){
					$result_a = $n->GetNationalResultbyEntrypoint($year, $value->id);
				}

				if($type == 4){
					$result_a = $n->OutcomesByAgeBand($year, [$value->lower, $value->upper]);
				}

				// Loop through each month and update entrypoints
				for ($i=0; $i < 12; $i++) { 
					$month = $i + 1;
					if($year == Date('Y') && $month > Date('m')){ break; }

					$pos = $this->checknull_month($result_a->where('result', 2), $month);
					$neg = $this->checknull_month($result_a->where('result', 1), $month);

					$data_array = array('pos' => $pos, 'neg' => $neg, 'dateupdated' => $today);

					if($type != 4){		

						$fail = $this->checknull_month($result_a->where('result', 3), $month);
						$rd = $this->checknull_month($result_a->where('result', 5), $month);	

						$redraw = $fail + $rd;
						$tests = $pos + $neg +  $redraw;

						$data_array = array_merge($data_array, ['tests' => $tests, 'redraw' => $redraw]);
					}

					DB::table($table[0])->where('year', $year)->where('month', $month)
					->where($table[2], $value->id)->update($data_array);

					// $update_statements .= $this->update_query($table[0], $data_array, ['year' => $year, 'month' => $month, $table[2] => $value->id]);
					// $updates++;

					// if($updates == 200){
					// 	$this->mysqli->multi_query($update_statements);
					// 	$update_statements = '';
					// 	$updates = 0;
					// }	
				}

			}
			// $this->mysqli->multi_query($update_statements);
			// End of looping through ids of each table e.g. agecategory
			echo "\n Completed " . $table[0] . " update at " . date('d/m/Y h:i:s a', time());
		}
    }

    public function update_nation_yearly($year = null){
    	if($year == null){
    		$year = Date('Y');
    	}
    	// Instantiate new object
    	$n = new EidNation;

    	$update_statements = "";
    	$updates = 0;

    	$today=date("Y-m-d");

    	echo "\n Begin eid nation yearly update at " . date('d/m/Y h:i:s a', time());

    	// Get collection instances of the data
    	$alltests_a = $n->CumulativeTestedSamples($year, false);
    	$eqatests_a = $n->OverallEQATestedSamples($year, false);

    	$tests_a = $n->OverallTestedSamples($year, false);
		
		$patienttests_a = $n->OverallTestedPatients($year, false, false);
		$patienttestsPOS_a = $n->OverallTestedPatients($year, true, false);
		$received_a = $n->OverallReceivedSamples($year, false);

		$firstdna_a = $n->getbypcr($year, 1, false, false);
		$posrepeats_a = $n->getbypcr($year, 2, false, false);
		$confirmdna_a = $n->getbypcr($year, 4, false, false);

		$posrepeatsPOS_a = $n->getbypcr($year, 2, true, false);
		$confirmdnaPOS_a = $n->getbypcr($year, 4, true, false);


		$infantsless2m_a = 		$n->Gettestedsamplescountrange($year, 1, false, false);
		$infantsless2mPOS_a = 	$n->Gettestedsamplescountrange($year, 1, true, false);
		$infantsless2w_a =		$n->Gettestedsamplescountrange($year, 3, false, false);
		$infantsless2wPOS_a =	$n->Gettestedsamplescountrange($year, 3, true, false);
		$infantsless46w_a =		$n->Gettestedsamplescountrange($year, 4, false, false);
		$infantsless46wPOS_a =	$n->Gettestedsamplescountrange($year, 4, true, false);
		$infantsabove2m_a =		$n->Gettestedsamplescountrange($year, 2, false, false);
		$infantsabove2mPOS_a = 	$n->Gettestedsamplescountrange($year, 2, true, false);
		$adulttests_a =			$n->Gettestedsamplescountrange($year, 5, false, false);
		$adulttestsPOS_a =		$n->Gettestedsamplescountrange($year, 5, true, false);
		

		$pos_a = $n->OverallTestedSamplesOutcomes($year, 2, 1, false);
		$neg_a = $n->OverallTestedSamplesOutcomes($year, 1, 1, false);

		$rpos_a = $n->OverallTestedSamplesOutcomes($year, 2, 2, false);
		$rneg_a = $n->OverallTestedSamplesOutcomes($year, 1, 2, false);

		$allpos_a = $n->OverallTestedSamplesOutcomes($year, 2, 0, false);
		$allneg_a = $n->OverallTestedSamplesOutcomes($year, 1, 0, false);


		$fail_a = $n->OverallTestedSamplesOutcomes($year, 3, 1, false);
		$rd_a = $n->OverallTestedSamplesOutcomes($year, 5, 1, false);
		$rdd_a = $n->OverallTestedSamplesOutcomes($year, 6, 1, false);
		// $redraw=$fail + $rd + $rdd;
		
		//echo $alltests;
		$rej_a = $n->Getnationalrejectedsamples($year, false);

		$followups_a = $n->GetHEIFollowUpNational($year, 'enrollment_status', false);
		$validation_a = $n->GetHEIFollowUpNational($year, 'hei_validation', false);	
		
		$sitesending_a = $n->GettotalEidsitesbytimeperiod($year, false);
		$avgage_a = $n->Getoverallaverageage($year, false);
		$medage = $n->Getoverallmedianage($year, false);

		$tat = $n->get_tat($year, false);

		$alltests = $this->checknull($alltests_a);
		$eqatests = $this->checknull($eqatests_a);
		$tests = $this->checknull($tests_a);
		$patienttests = $this->checknull($patienttests_a);
		$patienttestsPOS = $this->checknull($patienttestsPOS_a);

		$received = $this->checknull($received_a);
		$firstdna = $this->checknull($firstdna_a);
		$confirmdna = $this->checknull($confirmdna_a);
		$posrepeats = $this->checknull($posrepeats_a);

		$confirmdnaPOS = $this->checknull($confirmdnaPOS_a);
		$posrepeatsPOS = $this->checknull($posrepeatsPOS_a);
		$confimPOS = $confirmdnaPOS + $posrepeatsPOS;

		$infantsless2m = $this->checknull($infantsless2m_a);
		$infantsless2mPOS = $this->checknull($infantsless2mPOS_a);
		$infantsless2w = $this->checknull($infantsless2w_a);
		$infantsless2wPOS = $this->checknull($infantsless2wPOS_a);
		$infantsless46w = $this->checknull($infantsless46w_a);
		$infantsless46wPOS = $this->checknull($infantsless46wPOS_a);
		$infantsabove2m = $this->checknull($infantsabove2m_a);
		$infantsabove2mPOS = $this->checknull($infantsabove2mPOS_a);
		$adulttests = $this->checknull($adulttests_a);
		$adulttestsPOS = $this->checknull($adulttestsPOS_a);


		$pos = $this->checknull($pos_a);
		$neg = $this->checknull($neg_a);
		$fail = $this->checknull($fail_a);
		$rd = $this->checknull($rd_a);
		$rdd = $this->checknull($rdd_a);
		$redraw = $fail + $rd + $rdd;

		$rpos = $this->checknull($rpos_a);
		$rneg = $this->checknull($rneg_a);
		
		$allpos = $this->checknull($allpos_a);
		$allneg = $this->checknull($allneg_a);

		$rej = $this->checknull($rej_a);

		$enrolled = $this->checknull($followups_a->where('enrollment_status', 1));
		$ltfu = $this->checknull($followups_a->where('enrollment_status', 2));
		$dead = $this->checknull($followups_a->where('enrollment_status', 3));
		$adult = $this->checknull($followups_a->where('enrollment_status', 4));
		$transout = $this->checknull($followups_a->where('enrollment_status', 5));
		$other = $this->checknull($followups_a->where('enrollment_status', 6));


		$v_cp = $this->checknull($validation_a->where('hei_validation', 1));
		$v_ad = $this->checknull($validation_a->where('hei_validation', 2));
		$v_vl = $this->checknull($validation_a->where('hei_validation', 3));
		$v_rp = $this->checknull($validation_a->where('hei_validation', 4));
		$v_uf = $this->checknull($validation_a->where('hei_validation', 5));

		$sitesending = $this->checknull($sitesending_a);
		$avgage = $this->checknull($avgage_a);

		$tt = $this->check_tat($tat);			

		$data_array = array(
			'avgage' => $avgage,	'medage' => $medage,	'received' => $received,
			'alltests' => $alltests, 'eqatests' => $eqatests, 'tests' => $tests,
			'firstdna' => $firstdna, 'confirmdna' => $confirmdna, 'repeatspos' => $posrepeats,
			'confirmedPOs' => $confirmdnaPOS, 'repeatposPOS' => $posrepeatsPOS,
			'infantsless2m' => $infantsless2m,
			'infantsless2mPOs' => $infantsless2mPOS, 'infantsless2w' => $infantsless2w,
			'infantsless2wPOs' => $infantsless2wPOS, 'infantsabove2m' => $infantsabove2m,
			'infantsabove2mPOs' => $infantsabove2mPOS, 'adults' => $adulttests,
			'adultsPOs' => $adulttestsPOS, 'actualinfants' => $patienttests,
			'actualinfantsPOs' => $patienttestsPOS, 'pos' => $pos, 'neg' => $neg,
			'allpos' => $allpos, 'allneg' => $allneg, 'rpos' => $rpos, 'rneg' => $rneg,
			'redraw' => $redraw, 'rejected' => $rej, 'enrolled' => $enrolled, 'dead' => $dead,
			'ltfu' => $ltfu, 'adult' => $adult, 'transout' => $transout, 'other' => $other,
			'validation_confirmedpos' => $v_cp, 'validation_repeattest' => $v_ad,
			'validation_viralload' => $v_vl, 'validation_adult' => $v_rp,
			'validation_unknownsite' => $v_uf, 'sitessending' => $sitesending,
			'tat1' => $tt['tat1'], 'tat2' => $tt['tat2'], 'tat3' => $tt['tat3'], 
			'tat4' => $tt['tat4'], 'dateupdated' => $today
		);

		DB::table('national_summary_yearly')->where('year', $year)->update($data_array);


		echo "\n Completed entry into eid national summary yearly at " . date('d/m/Y h:i:s a', time());
    }

    public function update_labs($year = null){
    	if($year == null){
    		$year = Date('Y');
    	}

    	echo "\n Begin eid lab summary update at " . date('d/m/Y h:i:s a', time());

    	// Instantiate new object
    	$n = new EidDivision;
    	$poc = new EidPoc;

    	$update_statements = "";
    	$updates = 0;


    	$today=date("Y-m-d");

    	$div_array;
    	$array_size = 0;

    	$divs = $data = DB::connection('eid_vl')
		->table('labs')->select('id')->get();

		foreach ($divs as $key => $value) {
			$div_array[$key] = $value->id;
			$array_size++;
		}

		$division = "lab";
		$division2 = "poc";
		$column = "lab";

		// Normal Labs data
		$noofbatches = $n->GettotalbatchesPerlab($year, $division);

		$received_a = $n->OverallReceivedSamples($year, $division);
		$rejectedsamples = $n->Getnationalrejectedsamples($year, $division);
		$testedsamples = $n->OverallTestedSamples($year, $division);
		$alltestedsamples = $n->CumulativeTestedSamples($year, $division);
		$EQAtestedsamples = $n->OverallEQATestedSamples($year, $division);

		$posrepeats_a = $n->getbypcr($year, 2, false, $division);
		$confirmdna_a = $n->getbypcr($year, 4, false, $division);

		$posrepeatsPOS_a = $n->getbypcr($year, 2, true, $division);
		$confirmdnaPOS_a = $n->getbypcr($year, 4, true, $division);

		$discrepant_a = $n->getbypcr($year, 5, false, $division);
		$discrepantpos_a = $n->getbypcr($year, 5, true, $division);

		$fake_a = $n->false_confirmatory($year, $division);
		$controls_a = $n->control_samples($year);

		$pos_a = $n->OverallTestedSamplesOutcomes($year, 2, 1, $division);
		$neg_a = $n->OverallTestedSamplesOutcomes($year, 1, 1, $division);
		$fail_a = $n->OverallTestedSamplesOutcomes($year, 5, 1, $division);
		$redraws_a = $n->OverallTestedSamplesOutcomes($year, 3, 1, $division); 
		
		$facilityssupported = $n->GettotalEidsitesbytimeperiod($year, $division);

		$tat = $n->get_tat($year, $division);


		// POC data
		$noofbatches2 = $poc->GettotalbatchesPerlab($year);

		$received_a2 = $poc->OverallReceivedSamples($year);
		$testedsamples2 = $poc->OverallTestedSamples($year);
		$rejectedsamples2 = $poc->Getnationalrejectedsamples($year);
		$alltestedsamples2 = $poc->CumulativeTestedSamples($year);
		$EQAtestedsamples2 = $poc->OverallEQATestedSamples($year);

		$posrepeats_a2 = $poc->getbypcr($year, 2, false);
		$confirmdna_a2 = $poc->getbypcr($year, 4, false);
		$discrepant_a2 = $poc->getbypcr($year, 5, false);

		$posrepeatsPOS_a2 = $poc->getbypcr($year, 2, true);
		$confirmdnaPOS_a2 = $poc->getbypcr($year, 4, true);
		$discrepantpos_a2 = $poc->getbypcr($year, 5, true);


		$pos_a2 = $poc->OverallTestedSamplesOutcomes($year, 2);
		$neg_a2 = $poc->OverallTestedSamplesOutcomes($year, 1);
		$fail_a2 = $poc->OverallTestedSamplesOutcomes($year, 5);
		$redraws_a2 = $poc->OverallTestedSamplesOutcomes($year, 3);
		
		$facilityssupported2 = $poc->GettotalEidsitesbytimeperiod($year);

		$tat2 = $poc->get_tat($year);

		// Loop through the months and insert data into the division summary
		for ($i=0; $i < 12; $i++) { 
			$month = $i + 1;

			if($year == Date('Y') && $month > Date('m')){ break; }

			// Loop through labs
			for ($it=0; $it < $array_size; $it++) { 


				$received = $this->checknull($received_a->where('month', $month)->where($column, $div_array[$it]));
				$alltests = $this->checknull($alltestedsamples->where('month', $month)->where($column, $div_array[$it]));
				$tests = $this->checknull($testedsamples->where('month', $month)->where($column, $div_array[$it]));
				$confirmdna = $this->checknull($confirmdna_a->where('month', $month)->where($column, $div_array[$it]));
				$posrepeats = $this->checknull($posrepeats_a->where('month', $month)->where($column, $div_array[$it]));

				$confirmdnaPOS = $this->checknull($confirmdnaPOS_a->where('month', $month)->where($column, $div_array[$it]));
				$posrepeatsPOS = $this->checknull($posrepeatsPOS_a->where('month', $month)->where($column, $div_array[$it]));

				$discrepant = $this->checknull($discrepant_a->where('month', $month)->where($column, $div_array[$it]));
				$discrepant_pos = $this->checknull($discrepantpos_a->where('month', $month)->where($column, $div_array[$it]));


				$fake = $this->checknull($fake_a->where('month', $month)->where($column, $div_array[$it]));
				$controls = $this->checknull($controls_a->where('month', $month)->where('lab', $div_array[$it])) * 2;
				
				$eqatests = $this->checknull($EQAtestedsamples->where('month', $month)->where($column, $div_array[$it]));

				$pos = $this->checknull($pos_a->where('month', $month)->where($column, $div_array[$it]));
				$neg = $this->checknull($neg_a->where('month', $month)->where($column, $div_array[$it]));
				$fail = $this->checknull($fail_a->where('month', $month)->where($column, $div_array[$it]));
				$redraws = $this->checknull($redraws_a->where('month', $month)->where($column, $div_array[$it]));
				$failed = $fail+$redraws;

				$batches = $this->checknull($noofbatches->where('month', $month)->where($column, $div_array[$it]));

				$rej = $this->checknull($rejectedsamples->where('month', $month)->where($column, $div_array[$it]));

				$sitesending = $this->checknull($facilityssupported->where('month', $month)->where($column, $div_array[$it]));

				$tt = $this->check_tat($tat->where('month', $month)->where($column, $div_array[$it]));

				$data_array = array(
					'received' => $received, 'alltests' => $alltests, 'tests' => $tests,
					'confirmdna' => $confirmdna, 'fake_confirmatory' => $fake,  'eqatests' => $eqatests, 
					'confirmedPOs' => $confirmdnaPOS, 'repeatposPOS' => $posrepeatsPOS,
					'tiebreaker' => $discrepant, 'tiebreakerPOS' => $discrepant_pos,
					'repeatspos' => $posrepeats, 'pos' => $pos, 'neg' => $neg,
					'redraw' => $failed, 'batches' => $batches, 'rejected' => $rej,
					'sitessending' => $sitesending, 'controls' => $controls,
					'tat1' => $tt['tat1'], 'tat2' => $tt['tat2'], 'tat3' => $tt['tat3'], 
					'tat4' => $tt['tat4'], 'dateupdated' => $today
				);

				DB::table("lab_summary")->where('year', $year)->where('month', $month)->where("lab", $div_array[$it])->update($data_array);
			}

			// Update POC sites data
			$received = $this->checknull($received_a2->where('month', $month));
			$alltests = $this->checknull($alltestedsamples2->where('month', $month));
			$tests = $this->checknull($testedsamples2->where('month', $month));
			$confirmdna = $this->checknull($confirmdna_a2->where('month', $month));
			$posrepeats = $this->checknull($posrepeats_a2->where('month', $month));
			$discrepant = $this->checknull($discrepant_a2->where('month', $month));

			$confirmdnaPOS = $this->checknull($confirmdnaPOS_a2->where('month', $month));
			$posrepeatsPOS = $this->checknull($posrepeatsPOS_a2->where('month', $month));
			$discrepant_pos = $this->checknull($discrepantpos_a2->where('month', $month));
			
			$eqatests = $this->checknull($EQAtestedsamples2->where('month', $month));

			$pos = $this->checknull($pos_a2->where('month', $month));
			$neg = $this->checknull($neg_a2->where('month', $month));
			$fail = $this->checknull($fail_a2->where('month', $month));
			$redraws = $this->checknull($redraws_a2->where('month', $month));
			$failed = $fail+$redraws;

			$batches = $this->checknull($noofbatches2->where('month', $month));

			$rej = $this->checknull($rejectedsamples2->where('month', $month));

			$sitesending = $this->checknull($facilityssupported2->where('month', $month));

			$tt = $this->check_tat($tat2->where('month', $month));
			// $tt = $this->checktat($tat->where('month', $month)->where('division', $div_array[$it]));

			$data_array = array(
				'received' => $received, 'alltests' => $alltests, 'tests' => $tests,
				'confirmdna' => $confirmdna, 'eqatests' => $eqatests, 
				'confirmedPOs' => $confirmdnaPOS, 'repeatposPOS' => $posrepeatsPOS,
				'tiebreaker' => $discrepant, 'tiebreakerPOS' => $discrepant_pos,
				'repeatspos' => $posrepeats, 'pos' => $pos, 'neg' => $neg,
				'redraw' => $failed, 'batches' => $batches, 'rejected' => $rej,
				'sitessending' => $sitesending,
				'tat1' => $tt['tat1'], 'tat2' => $tt['tat2'], 'tat3' => $tt['tat3'], 
				'tat4' => $tt['tat4'], 'dateupdated' => $today
			);

			DB::table("lab_summary")->where('year', $year)->where('month', $month)->where("lab", 11)->update($data_array);

		}
		echo "\n Completed eid lab summary update at " . date('d/m/Y h:i:s a', time());

		$poc_table = 'poc_summary';

		foreach ($testedsamples as $key => $value) {
			if($value->lab < 15) continue;
			$wheres = ['month' => $value->month, 'lab' => $value->lab];

			$received = $this->checknull($received_a, $wheres);
			$alltests = $this->checknull($alltestedsamples, $wheres);
			$tests = $this->checknull($testedsamples, $wheres);
			$confirmdna = $this->checknull($confirmdna_a, $wheres);
			$posrepeats = $this->checknull($posrepeats_a, $wheres);

			$confirmdnaPOS = $this->checknull($confirmdnaPOS_a, $wheres);
			$posrepeatsPOS = $this->checknull($posrepeatsPOS_a, $wheres);

			$discrepant = $this->checknull($discrepant_a, $wheres);
			$discrepant_pos = $this->checknull($discrepantpos_a, $wheres);


			$fake = $this->checknull($fake_a, $wheres);
			$controls = $this->checknull($controls_a, $wheres) * 2;
			
			$eqatests = $this->checknull($EQAtestedsamples, $wheres);

			$pos = $this->checknull($pos_a, $wheres);
			$neg = $this->checknull($neg_a, $wheres);
			$fail = $this->checknull($fail_a, $wheres);
			$redraws = $this->checknull($redraws_a, $wheres);
			$failed = $fail+$redraws;

			$batches = $this->checknull($noofbatches, $wheres);

			$rej = $this->checknull($rejectedsamples, $wheres);

			$sitesending = $this->checknull($facilityssupported, $wheres);

			$tt = $this->check_tat($tat, $wheres);

			$data_array = array(
				'received' => $received, 'alltests' => $alltests, 'tests' => $tests,
				'confirmdna' => $confirmdna, 'fake_confirmatory' => $fake,  'eqatests' => $eqatests, 
				'confirmedPOs' => $confirmdnaPOS, 'repeatposPOS' => $posrepeatsPOS,
				'tiebreaker' => $discrepant, 'tiebreakerPOS' => $discrepant_pos,
				'repeatspos' => $posrepeats, 'pos' => $pos, 'neg' => $neg,
				'redraw' => $failed, 'batches' => $batches, 'rejected' => $rej,
				'sitessending' => $sitesending, 'controls' => $controls,
				'tat1' => $tt['tat1'], 'tat2' => $tt['tat2'], 'tat3' => $tt['tat3'], 
				'tat4' => $tt['tat4'], 'dateupdated' => $today
			);

			$locator = ['year' => $year, 'month' => $value->month, 'facility' => $value->lab];

			$row = DB::table($poc_table)->where($locator)->first();

			if(!$row){
				$data_array = array_merge($locator, $data_array);
				DB::table($poc_table)->insert($data_array);
			}
			else{
				DB::table($poc_table)->where('id', $row->ID)->update($data_array);
			}
		}
		echo "\n Completed eid poc summary update at " . date('d/m/Y h:i:s a', time());

		echo "\n Begin entry into eid lab rejections " . date('d/m/Y h:i:s a', time());

		// Start of rejections
		$reasons = $data = DB::connection('eid_vl')
		->table('rejectedreasons')->select('id')->get();

		// Loop through reasons
		foreach ($reasons as $key => $value) {
			$rej_a = $n->national_rejections($year, $value->id, $division);
			$poc_rej_a = $n->national_rejections($year, $value->id, $division2);

			// Loop through each month and update reason
			for ($i=0; $i < 12; $i++) { 
				$month = $i + 1;
				if($year == Date('Y') && $month > Date('m')){ break; }

				// Loop through labs
				for ($it=0; $it < $array_size; $it++) {

					$rej = $this->checknull($rej_a->where('month', $month)->where($column, $div_array[$it]));

					if($rej == 0) continue;

					$data_array = ['total' => $rej, 'dateupdated' => $today];

					DB::table('lab_rejections')->where('year', $year)->where('month', $month)->where("lab", $div_array[$it])->where('rejected_reason', $value->id)->update($data_array);
				}

				// Update POC row
				$rej = $this->checknull($poc_rej_a->where('month', $month));
				$data_array = ['total' => $rej, 'dateupdated' => $today];
				DB::table('lab_rejections')->where('year', $year)->where('month', $month)->where("lab", 11)->where('rejected_reason', $value->id)->update($data_array);
			}
			
		}
		// End of rejections

		echo "\n Completed entry into eid lab rejections at " . date('d/m/Y h:i:s a', time());

		echo $this->lab_mapping($year);
    }

    public function lab_mapping($year=null){
        $counties = DB::table('countys')->select('id')->orderBy('id')->get();
        $labs = DB::table('labs')->select('id')->orderBy('id')->get();

    	$n = new EidDivision;

    	$update_statements = "";
    	$updates = 0;
    	$today=date("Y-m-d");

    	echo "\n Begin entry into eid lab mapping at " . date('d/m/Y h:i:s a', time());

    	$tests_a = $n->lab_county_tests($year);
    	$supported_sites_a = $n->lab_mapping_sites($year);

    	for ($i=0; $i < 12; $i++) { 
			$month = $i + 1;
			if($year == Date('Y') && $month > Date('m')){ break; }

	    	foreach ($labs as $lab) {
	    		foreach ($counties as $county) {
	    			// $search = ['month' => $month, 'lab' => $lab->id, 'county' => $county->id];
	    			$find = ['month' => $month, 'lab' => $lab->id, 'county' => $county->id, 'year' => $year];

	    			$tests = $this->checknull( $tests_a->where('month', $month)->where('lab', $lab->id)->where('county', $county->id) );
	    			if($tests == 0){
	    				continue;
	    			}
	    			
	    			$supported = $this->checknull( $supported_sites_a->where('month', $month)->where('lab', $lab->id)->where('county', $county->id) );

	    			$data_array = array('total' => $tests, 'site_sending' => $supported);

	    			DB::table('lab_mapping')->where($find)->update($data_array);

	    		}
	    	}
	    }

	    echo "\n Completed entry into eid lab mapping at " . date('d/m/Y h:i:s a', time());
    }


    // Will be used to enter data for divisions except labs
    // Types: 1=county, 2=subcounty, 3=partner, 4=sites
    public function division_updator($year=null, $type=1, $column='county', $division='view_facilitys.county', $div_table='countys', $sum_table='county_summary', $age_table='county_agebreakdown', $rej_table='county_rejections'){

    	if($year == null){
    		$year = Date('Y');
    	}

    	ini_set("memory_limit", "-1");

    	// Instantiate new object
    	$n = new EidDivision;

    	$update_statements = "";
    	$updates = 0;

    	$today=date("Y-m-d");

    	$div_array;
    	$array_size = 0;

    	$divs = $data = DB::connection('eid_vl')
		->table($div_table)->select('id')->get();

		foreach ($divs as $key => $value) {
			$div_array[$key] = $value->id;
			$array_size++;
		}

		echo "\n Begin eid {$column} update at " . date('d/m/Y h:i:s a', time());

		// Get collection instances of the data
    	$alltests_a = $n->CumulativeTestedSamples($year, $division);
    	$eqatests_a = $n->OverallEQATestedSamples($year, $division);

    	$tests_a = $n->OverallTestedSamples($year, $division);
		
		$patienttests_a = $n->OverallTestedPatients($year, false, $division);
		$patienttestsPOS_a = $n->OverallTestedPatients($year, true, $division);
		$received_a = $n->OverallReceivedSamples($year, $division);

		$firstdna_a = $n->getbypcr($year, 1, false, $division);
		$posrepeats_a = $n->getbypcr($year, 2, false, $division);
		$confirmdna_a = $n->getbypcr($year, 4, false, $division);

		$posrepeatsPOS_a = $n->getbypcr($year, 2, true, $division);
		$confirmdnaPOS_a = $n->getbypcr($year, 4, true, $division);


		$infantsless2m_a = 		$n->Gettestedsamplescountrange($year, 1, false, $division);
		$infantsless2mPOS_a = 	$n->Gettestedsamplescountrange($year, 1, true, $division);
		$infantsless2w_a =		$n->Gettestedsamplescountrange($year, 3, false, $division);
		$infantsless2wPOS_a =	$n->Gettestedsamplescountrange($year, 3, true, $division);
		$infantsless46w_a =		$n->Gettestedsamplescountrange($year, 4, false, $division);
		$infantsless46wPOS_a =	$n->Gettestedsamplescountrange($year, 4, true, $division);
		$infantsabove2m_a =		$n->Gettestedsamplescountrange($year, 2, false, $division);
		$infantsabove2mPOS_a = 	$n->Gettestedsamplescountrange($year, 2, true, $division);
		$adulttests_a =			$n->Gettestedsamplescountrange($year, 5, false, $division);
		$adulttestsPOS_a =		$n->Gettestedsamplescountrange($year, 5, true, $division);

		if($type == 4){
			$noage_a =			$n->Gettestedsamplescountrange($year, 0, false, $division);
			$noagePOS_a =		$n->Gettestedsamplescountrange($year, 0, true, $division);			
		}
		

		$pos_a = $n->OverallTestedSamplesOutcomes($year, 2, 1, $division);
		$neg_a = $n->OverallTestedSamplesOutcomes($year, 1, 1, $division);

		$rpos_a = $n->OverallTestedSamplesOutcomes($year, 2, 2, $division);
		$rneg_a = $n->OverallTestedSamplesOutcomes($year, 1, 2, $division);

		$allpos_a = $n->OverallTestedSamplesOutcomes($year, 2, 0, $division);
		$allneg_a = $n->OverallTestedSamplesOutcomes($year, 1, 0, $division);

		$fail_a = $n->OverallTestedSamplesOutcomes($year, 3, 1, $division);
		$rd_a = $n->OverallTestedSamplesOutcomes($year, 5, 1, $division);
		$rdd_a = $n->OverallTestedSamplesOutcomes($year, 6, 1, $division);
		// $redraw=$fail + $rd + $rdd;

		$rej_a = $n->Getnationalrejectedsamples($year, $division);
		
		$v = "enrollment_status";
		$enrolled_a = $n->GetHEIFollowUpNational($year, 1, $v, $division);
		$ltfu_a = $n->GetHEIFollowUpNational($year, 2, $v, $division);
		$dead_a = $n->GetHEIFollowUpNational($year, 3, $v, $division);
		$adult_a = $n->GetHEIFollowUpNational($year, 4, $v, $division);
		$transout_a = $n->GetHEIFollowUpNational($year, 5, $v, $division);
		$other_a = $n->GetHEIFollowUpNational($year, 6, $v, $division);


		$v = 'hei_validation';
		$v_cp_a = $n->GetHEIFollowUpNational($year, 1, $v, $division); //confirmedpos	
		$v_ad_a = $n->GetHEIFollowUpNational($year, 2, $v, $division); //adult
		$v_vl_a = $n->GetHEIFollowUpNational($year, 3, $v, $division); //vl
		$v_rp_a = $n->GetHEIFollowUpNational($year, 4, $v, $division); //repeat
		$v_uf_a = $n->GetHEIFollowUpNational($year, 5, $v, $division); //unknownfacility		
		
		$sitesending_a = $n->GettotalEidsitesbytimeperiod($year, $division);
		$avgage_a = $n->Getoverallaverageage($year, $division);
		$medage_a = $n->Getoverallmedianage($year, $div_array, $division, $column);
		$medage_a = collect($medage_a);
		
		$tat = $n->get_tat($year, $division);
		

		// Loop through the months and insert data into the division summary
		for ($i=0; $i < 12; $i++) { 
			$month = $i + 1;

			if($year == Date('Y') && $month > Date('m')){ break; }

			// Loop through divisions i.e. counties, subcounties, partners and sites
			for ($it=0; $it < $array_size; $it++) { 
				if($column == 'partner' && $div_array[$it] == 55 && $year < 2019) continue;

				$alltests = $this->checknull($alltests_a->where('month', $month)->where($column, $div_array[$it]));
				$received = $this->checknull($received_a->where('month', $month)->where($column, $div_array[$it]));

				if($alltests == 0 && $received == 0) continue;

				$eqatests = $this->checknull($eqatests_a->where('month', $month)->where($column, $div_array[$it]));
				$tests = $this->checknull($tests_a->where('month', $month)->where($column, $div_array[$it]));
				$patienttests = $this->checknull($patienttests_a->where('month', $month)->where($column, $div_array[$it]));
				$patienttestsPOS = $this->checknull($patienttestsPOS_a->where('month', $month)->where($column, $div_array[$it]));

				
				$firstdna = $this->checknull($firstdna_a->where('month', $month)->where($column, $div_array[$it]));
				$confirmdna = $this->checknull($confirmdna_a->where('month', $month)->where($column, $div_array[$it]));
				$posrepeats = $this->checknull($posrepeats_a->where('month', $month)->where($column, $div_array[$it]));
				$confirmdnaPOS = $this->checknull($confirmdnaPOS_a->where('month', $month)->where($column, $div_array[$it]));
				$posrepeatsPOS = $this->checknull($posrepeatsPOS_a->where('month', $month)->where($column, $div_array[$it]));
				$confimPOS = $confirmdnaPOS + $posrepeatsPOS;

				$infantsless2m = $this->checknull($infantsless2m_a->where('month', $month)->where($column, $div_array[$it]));
				$infantsless2mPOS = $this->checknull($infantsless2mPOS_a->where('month', $month)->where($column, $div_array[$it]));
				$infantsless2w = $this->checknull($infantsless2w_a->where('month', $month)->where($column, $div_array[$it]));
				$infantsless2wPOS = $this->checknull($infantsless2wPOS_a->where('month', $month)->where($column, $div_array[$it]));
				$infantsless46w = $this->checknull($infantsless46w_a->where('month', $month)->where($column, $div_array[$it]));
				$infantsless46wPOS = $this->checknull($infantsless46wPOS_a->where('month', $month)->where($column, $div_array[$it]));
				$infantsabove2m = $this->checknull($infantsabove2m_a->where('month', $month)->where($column, $div_array[$it]));
				$infantsabove2mPOS = $this->checknull($infantsabove2mPOS_a->where('month', $month)->where($column, $div_array[$it]));
				$adulttests = $this->checknull($adulttests_a->where('month', $month)->where($column, $div_array[$it]));
				$adulttestsPOS = $this->checknull($adulttestsPOS_a->where('month', $month)->where($column, $div_array[$it]));


				$pos = $this->checknull($pos_a->where('month', $month)->where($column, $div_array[$it]));
				$neg = $this->checknull($neg_a->where('month', $month)->where($column, $div_array[$it]));
				$fail = $this->checknull($fail_a->where('month', $month)->where($column, $div_array[$it]));
				$rd = $this->checknull($rd_a->where('month', $month)->where($column, $div_array[$it]));
				$rdd = $this->checknull($rdd_a->where('month', $month)->where($column, $div_array[$it]));
				$redraw = $fail + $rd + $rdd;

				$rpos = $this->checknull($rpos_a->where('month', $month)->where($column, $div_array[$it]));
				$rneg = $this->checknull($rneg_a->where('month', $month)->where($column, $div_array[$it]));

				$allpos = $this->checknull($allpos_a->where('month', $month)->where($column, $div_array[$it]));
				$allneg = $this->checknull($allneg_a->where('month', $month)->where($column, $div_array[$it]));

				$rej = $this->checknull($rej_a->where('month', $month)->where($column, $div_array[$it]));
				$enrolled = $this->checknull($enrolled_a->where('month', $month)->where($column, $div_array[$it]));
				$ltfu = $this->checknull($ltfu_a->where('month', $month)->where($column, $div_array[$it]));
				$dead = $this->checknull($dead_a->where('month', $month)->where($column, $div_array[$it]));
				$adult = $this->checknull($adult_a->where('month', $month)->where($column, $div_array[$it]));
				$transout = $this->checknull($transout_a->where('month', $month)->where($column, $div_array[$it]));
				$other = $this->checknull($other_a->where('month', $month)->where($column, $div_array[$it]));

				$v_cp = $this->checknull($v_cp_a->where('month', $month)->where($column, $div_array[$it]));
				$v_ad = $this->checknull($v_ad_a->where('month', $month)->where($column, $div_array[$it]));
				$v_vl = $this->checknull($v_vl_a->where('month', $month)->where($column, $div_array[$it]));
				$v_rp = $this->checknull($v_rp_a->where('month', $month)->where($column, $div_array[$it]));
				$v_uf = $this->checknull($v_uf_a->where('month', $month)->where($column, $div_array[$it]));

				$sitesending = $this->checknull($sitesending_a->where('month', $month)->where($column, $div_array[$it]));
				$avgage = $this->checknull($avgage_a->where('month', $month)->where($column, $div_array[$it]));
				$medage = $this->checkmedage($medage_a->where('month', $month)->where('division', $div_array[$it]));

				
				$tt = $this->check_tat($tat->where('month', $month)->where($column, $div_array[$it]));
				// $tt = $this->checktat($tat->where('month', $month)->where('division', $div_array[$it]));
				

				$data_array = array(
					'avgage' => $avgage,	'medage' => $medage,	'received' => $received,
					'alltests' => $alltests, 'eqatests' => $eqatests, 'tests' => $tests,
					'firstdna' => $firstdna, 'confirmdna' => $confirmdna, 'repeatspos' => $posrepeats,
					'confirmedPOs' => $confirmdnaPOS, 'repeatposPOS' => $posrepeatsPOS,
					'infantsless2m' => $infantsless2m,
					'infantsless2mPOs' => $infantsless2mPOS, 'infantsless2w' => $infantsless2w,
					'infantsless2wPOs' => $infantsless2wPOS, 'infantsabove2m' => $infantsabove2m,
					'infantsabove2mPOs' => $infantsabove2mPOS, 'adults' => $adulttests,
					'adultsPOs' => $adulttestsPOS, 'actualinfants' => $patienttests,
					'actualinfantsPOs' => $patienttestsPOS, 'pos' => $pos, 'neg' => $neg,
					'allpos' => $allpos, 'allneg' => $allneg, 'rpos' => $rpos, 'rneg' => $rneg,
					'redraw' => $redraw, 'rejected' => $rej, 'enrolled' => $enrolled, 'dead' => $dead,
					'ltfu' => $ltfu, 'adult' => $adult, 'transout' => $transout, 'other' => $other,
					'validation_confirmedpos' => $v_cp, 'validation_repeattest' => $v_ad,
					'validation_viralload' => $v_vl, 'validation_adult' => $v_rp,
					'validation_unknownsite' => $v_uf, 'sitessending' => $sitesending,
					'tat1' => $tt['tat1'], 'tat2' => $tt['tat2'], 'tat3' => $tt['tat3'], 'tat4' => $tt['tat4'],
					 'dateupdated' => $today
				);

				if ($type == 4) {
					$noage = $this->checknull($noage_a->where('month', $month)->where($column, $div_array[$it]));
					$noagePOS = $this->checknull($noagePOS_a->where('month', $month)->where($column, $div_array[$it]));
					$data_array = array_merge($data_array, ['noage' => $noage, 'noagePOS' => $noagePOS]);
				}

				DB::table($sum_table)->where('year', $year)->where('month', $month)->where($column, $div_array[$it])->update($data_array);

				// $update_statements .= $this->update_query($sum_table, $data_array, ['year' => $year, 'month' => $month, $column => $div_array[$it] ]);
				// $updates++;

				// if($updates == 200){
				// 	$this->mysqli->multi_query($update_statements);
				// 	$update_statements = '';
				// 	$updates = 0;
				// }	
			}
			// End of division loop
			
		}
		// End of summary
		// $this->mysqli->multi_query($update_statements);
		// $update_statements = '';
		// $updates = 0;
		echo "\n Completed entry into eid {$column} summary at " . date('d/m/Y h:i:s a', time());

		

		// Set the following to null in order to free memory
		$alltests_a = $eqatests_a = $tests_a = $patienttests_a = $patienttestsPOS_a = $received_a = $firstdna_a = $confirmdna_a = $posrepeats_a = $confirmdnaPOS_a = $posrepeatsPOS_a = $infantsless2m_a = $infantsless2mPOS_a = $infantsless2w_a = $infantsless2wPOS_a = $infantsless46w_a = $infantsless46wPOS_a = $infantsabove2m_a = $infantsabove2mPOS_a = $adulttests_a = $adulttestsPOS_a = $pos_a = $neg_a = $fail_a = $rd_a = $rdd_a = $rej_a = $enrolled_a = $ltfu_a = $dead_a = $adult_a = $transout_a = $other_a = $v_cp_a = $v_ad_a = $v_vl_a = $v_rp_a = $v_uf_a = $sitesending_a = $avgage_a = $medage_a = $tat = null;

		// Enter if not facility
		if($type != 4){		
			echo $this->continue_division($year, $today, $div_array, $division, $column, $type, $array_size);
		}
		else{	
			echo $this->continue_facility($year, $today, $div_array, $division, $column, $type, $array_size);			
		}


		echo "\n Begin entry into eid {$column} rejections " . date('d/m/Y h:i:s a', time());

		// Start of rejections
		$reasons = $data = DB::connection('eid_vl')
		->table('rejectedreasons')->select('id')->get();

		// Loop through reasons
		foreach ($reasons as $key => $value) {
			$rej_a = $n->national_rejections($year, $value->id, $division);

			// Loop through each month and update reason
			for ($i=0; $i < 12; $i++) { 
				$month = $i + 1;
				if($year == Date('Y') && $month > Date('m')){ break; }

				// Loop through divisions
				for ($it=0; $it < $array_size; $it++) {

					$rej = $this->checknull($rej_a->where('month', $month)->where($column, $div_array[$it]));

					if($rej == 0){
						continue;
					}

					$data_array = array(
						'total' => $rej, 'dateupdated' => $today
					);

					DB::table($rej_table)->where('year', $year)->where('month', $month)->where($column, $div_array[$it])->where('rejected_reason', $value->id)->update($data_array);

					// $update_statements .= $this->update_query($rej_table, $data_array, ['year' => $year, 'month' => $month, $column => $div_array[$it], 'rejected_reason' => $value->id]);
					// $updates++;

					// if($updates == 200){
					// 	$this->mysqli->multi_query($update_statements);
					// 	$update_statements = '';
					// 	$updates = 0;
					// }	
				}
			}
			
		}
		// End of rejections
		// $this->mysqli->multi_query($update_statements);
		echo "\n Completed entry into eid {$column} rejections at " . date('d/m/Y h:i:s a', time());

		// End of division updator
    }

    public function continue_division($year, $today, &$div_array, $division, $column, $div_type, $array_size){
    	$n = new EidDivision;

    	$update_statements = "";
    	$updates = 0;

    	for ($type=1; $type < 5; $type++) { 

			$table = $this->get_table($div_type, $type);

			echo "\n Begin eid " . $table[0] . " update at " . date('d/m/Y h:i:s a', time());
			
			// Get ids of the necessary table
			$divs = $data = DB::connection('eid_vl')
			->table($table[1])
			->when($type, function($query) use ($type){
				if($type == 1){
					return $query->where('ptype', 2);
				}
				if($type == 2){
					return $query->where('ptype', 1);
				}								
			})
			->get();

			foreach ($divs as $key => $value) {

				if($type == 1){
					$pos_a = $n->Getinfantprophpositivitycount($year, $value->id, 2, $division);
					$neg_a = $n->Getinfantprophpositivitycount($year, $value->id, 1, $division);
					$fail_a = $n->Getinfantprophpositivitycount($year, $value->id, 3, $division);
					$rd_a = $n->Getinfantprophpositivitycount($year, $value->id, 5, $division);
				}

				if($type == 2){
					$pos_a = $n->Getinterventionspositivitycount($year, $value->id, 2, $division);
					$neg_a = $n->Getinterventionspositivitycount($year, $value->id, 1, $division);
					$fail_a = $n->Getinterventionspositivitycount($year, $value->id, 3, $division);
					$rd_a = $n->Getinterventionspositivitycount($year, $value->id, 5, $division);
				}

				if($type == 3){
					$pos_a = $n->GetNationalResultbyEntrypoint($year, $value->id, 2, $division);
					$neg_a = $n->GetNationalResultbyEntrypoint($year, $value->id, 1, $division);
					$fail_a = $n->GetNationalResultbyEntrypoint($year, $value->id, 3, $division);
					$rd_a = $n->GetNationalResultbyEntrypoint($year, $value->id, 5, $division);
				}

				if($type == 4){
					$pos_a = $n->OutcomesByAgeBand($year, [$value->lower, $value->upper], 2, $division);
					$neg_a = $n->OutcomesByAgeBand($year, [$value->lower, $value->upper], 1, $division);
				}

				// Loop through each month and update entrypoints
				for ($i=0; $i < 12; $i++) { 
					$month = $i + 1;
					if($year == Date('Y') && $month > Date('m')){ break; }

					// Loop through divisions i.e. counties, subcounties, partners and sites
					for ($it=0; $it < $array_size; $it++) { 
						if($column == 'partner' && $div_array[$it] == 55 && $year < 2019) continue;

						$wheres = ['month' => $month, $column => $div_array[$it]];

						$pos = $this->checknull($pos_a, $wheres);
						$neg = $this->checknull($neg_a, $wheres);

						$data_array = array('pos' => $pos, 'neg' => $neg, 'dateupdated' => $today);

						if($type != 4){							

							$fail = $this->checknull($fail_a, $wheres);
							$rd = $this->checknull($rd_a, $wheres);

							$redraw = $fail + $rd;
							$tests = $pos + $neg +  $redraw;

							$data_array = array_merge($data_array, ['tests' => $tests, 'redraw' => $redraw]);
						}

						DB::table($table[0])->where('year', $year)->where('month', $month)
						->where($table[2], $value->id)->where($column, $div_array[$it])->update($data_array);

						// $update_statements .= $this->update_query($table[0], $data_array, ['year' => $year, 'month' => $month, $column => $div_array[$it], $table[2] => $value->id]);
						// $updates++;

						// if($updates == 200){
						// 	$this->mysqli->multi_query($update_statements);
						// 	$update_statements = '';
						// 	$updates = 0;
						// }	
					}
					// End of looping through divisions
				}
				//End of looping through months
			}
			// End of looping through ids of each table e.g. entry_points
			echo "\n Completed eid " . $table[0] . " update at " . date('d/m/Y h:i:s a', time());
		}
		// $this->mysqli->multi_query($update_statements);
    }

    public function continue_facility($year, $today, &$div_array, $division, $column, $div_type, $array_size){
    	$n = new EidFacility;

    	$update_statements = "";
    	$updates = 0;

    	for ($type=1; $type < 5; $type++) { 

			$table = $this->get_table($div_type, $type);

			echo "\n Begin eid " . $table[0] . " update at " . date('d/m/Y h:i:s a', time());
			
			// Get ids of the necessary table
			$divs = $data = DB::connection('eid_vl')
			->table($table[1])
			->when($type, function($query) use ($type){
				if($type == 1){
					return $query->where('ptype', 2);
				}
				if($type == 2){
					return $query->where('ptype', 1);
				}								
			})
			->get();

			foreach ($divs as $key => $value) {

				// Loop through each month and update entrypoints
				for ($i=0; $i < 12; $i++) { 
					$month = $i + 1;
					if($year == Date('Y') && $month > Date('m')){ break; }

					$ttttt = 0;

					if($type == 1){
						$pos_a = $n->Getinfantprophpositivitycount($year, $month, $value->id, 2, $division);
						$neg_a = $n->Getinfantprophpositivitycount($year, $month, $value->id, 1, $division);
						$fail_a = $n->Getinfantprophpositivitycount($year, $month, $value->id, 3, $division);
						$rd_a = $n->Getinfantprophpositivitycount($year, $month, $value->id, 5, $division);
					}

					if($type == 2){
						$pos_a = $n->Getinterventionspositivitycount($year, $month, $value->id, 2, $division);
						$neg_a = $n->Getinterventionspositivitycount($year, $month, $value->id, 1, $division);
						$fail_a = $n->Getinterventionspositivitycount($year, $month, $value->id, 3, $division);
						$rd_a = $n->Getinterventionspositivitycount($year, $month, $value->id, 5, $division);
					}

					if($type == 3){
						$pos_a = $n->GetNationalResultbyEntrypoint($year, $month, $value->id, 2, $division);
						$neg_a = $n->GetNationalResultbyEntrypoint($year, $month, $value->id, 1, $division);
						$fail_a = $n->GetNationalResultbyEntrypoint($year, $month, $value->id, 3, $division);
						$rd_a = $n->GetNationalResultbyEntrypoint($year, $month, $value->id, 5, $division);
					}

					if($type == 4){
						$pos_a = $n->OutcomesByAgeBand($year, $month, [$value->lower, $value->upper], 2, $division);
						$neg_a = $n->OutcomesByAgeBand($year, $month, [$value->lower, $value->upper], 1, $division);
					}

					// Loop through divisions i.e. counties, subcounties, partners and sites
					for ($it=0; $it < $array_size; $it++) { 
						if($column == 'partner' && $div_array[$it] == 55 && $year < 2019) continue;

						$pos = $this->checknull($pos_a->where($column, $div_array[$it]));
						$neg = $this->checknull($neg_a->where($column, $div_array[$it]));

						$data_array = array('pos' => $pos, 'neg' => $neg, 'dateupdated' => $today);

						if($type != 4){							

							$fail = $this->checknull($fail_a->where($column, $div_array[$it]));
							$rd = $this->checknull($rd_a->where($column, $div_array[$it]));

							$redraw = $fail + $rd;
							$tests = $pos + $neg +  $redraw;

							$data_array = array_merge($data_array, ['tests' => $tests, 'redraw' => $redraw]);
						}

						DB::table($table[0])->where('year', $year)->where('month', $month)
						->where($table[2], $value->id)->where($column, $div_array[$it])->update($data_array);

						// $update_statements .= $this->update_query($table[0], $data_array, ['year' => $year, 'month' => $month, $column => $div_array[$it], $table[2] => $value->id]);
						// $updates++;

						// if($updates == 200){
						// 	$this->mysqli->multi_query($update_statements);
						// 	$update_statements = '';
						// 	$updates = 0;
						// }	
					}
					// End of looping through divisions
				}
				//End of looping through months
			}
			// End of looping through ids of each table e.g. entry_points
			echo "\n Completed eid " . $table[0] . " update at " . date('d/m/Y h:i:s a', time());
		}
		// $this->mysqli->multi_query($update_statements);
    }



    // Will be used to enter data for divisions except labs
    // Types: 1=county, 2=subcounty, 3=partner, 4=sites
    public function poc_updator($year=null){

    	if($year == null) $year = Date('Y');

    	$division = 'site_poc';
    	$column = 'facility';
    	$sum_table = 'site_summary_poc';
    	$type=6;

    	ini_set("memory_limit", "-1");

    	// Instantiate new object
    	$n = new EidDivision;

    	$update_statements = "";
    	$updates = 0;

    	$today=date("Y-m-d");

		echo "\n Begin eid {$column} poc update at " . date('d/m/Y h:i:s a', time());

		// Get collection instances of the data
    	$alltests_a = $n->CumulativeTestedSamples($year, $division);
    	$eqatests_a = $n->OverallEQATestedSamples($year, $division);

    	$tests_a = $n->OverallTestedSamples($year, $division);
		
		$patienttests_a = $n->OverallTestedPatients($year, false, $division);
		$patienttestsPOS_a = $n->OverallTestedPatients($year, true, $division);
		$received_a = $n->OverallReceivedSamples($year, $division);

		$firstdna_a = $n->getbypcr($year, 1, false, $division);
		$posrepeats_a = $n->getbypcr($year, 2, false, $division);
		$confirmdna_a = $n->getbypcr($year, 4, false, $division);

		$posrepeatsPOS_a = $n->getbypcr($year, 2, true, $division);
		$confirmdnaPOS_a = $n->getbypcr($year, 4, true, $division);


		$infantsless2m_a = 		$n->Gettestedsamplescountrange($year, 1, false, $division);
		$infantsless2mPOS_a = 	$n->Gettestedsamplescountrange($year, 1, true, $division);
		$infantsless2w_a =		$n->Gettestedsamplescountrange($year, 3, false, $division);
		$infantsless2wPOS_a =	$n->Gettestedsamplescountrange($year, 3, true, $division);
		$infantsless46w_a =		$n->Gettestedsamplescountrange($year, 4, false, $division);
		$infantsless46wPOS_a =	$n->Gettestedsamplescountrange($year, 4, true, $division);
		$infantsabove2m_a =		$n->Gettestedsamplescountrange($year, 2, false, $division);
		$infantsabove2mPOS_a = 	$n->Gettestedsamplescountrange($year, 2, true, $division);
		$adulttests_a =			$n->Gettestedsamplescountrange($year, 5, false, $division);
		$adulttestsPOS_a =		$n->Gettestedsamplescountrange($year, 5, true, $division);

		if($type == 4){
			$noage_a =			$n->Gettestedsamplescountrange($year, 0, false, $division);
			$noagePOS_a =		$n->Gettestedsamplescountrange($year, 0, true, $division);			
		}
		

		$pos_a = $n->OverallTestedSamplesOutcomes($year, 2, 1, $division);
		$neg_a = $n->OverallTestedSamplesOutcomes($year, 1, 1, $division);

		$rpos_a = $n->OverallTestedSamplesOutcomes($year, 2, 2, $division);
		$rneg_a = $n->OverallTestedSamplesOutcomes($year, 1, 2, $division);

		$allpos_a = $n->OverallTestedSamplesOutcomes($year, 2, 0, $division);
		$allneg_a = $n->OverallTestedSamplesOutcomes($year, 1, 0, $division);

		$fail_a = $n->OverallTestedSamplesOutcomes($year, 3, 1, $division);
		$rd_a = $n->OverallTestedSamplesOutcomes($year, 5, 1, $division);
		$rdd_a = $n->OverallTestedSamplesOutcomes($year, 6, 1, $division);
		// $redraw=$fail + $rd + $rdd;

		$rej_a = $n->Getnationalrejectedsamples($year, $division);
		
		$v = "enrollment_status";
		$enrolled_a = $n->GetHEIFollowUpNational($year, 1, $v, $division);
		$ltfu_a = $n->GetHEIFollowUpNational($year, 2, $v, $division);
		$dead_a = $n->GetHEIFollowUpNational($year, 3, $v, $division);
		$adult_a = $n->GetHEIFollowUpNational($year, 4, $v, $division);
		$transout_a = $n->GetHEIFollowUpNational($year, 5, $v, $division);
		$other_a = $n->GetHEIFollowUpNational($year, 6, $v, $division);


		$v = 'hei_validation';
		$v_cp_a = $n->GetHEIFollowUpNational($year, 1, $v, $division); //confirmedpos	
		$v_ad_a = $n->GetHEIFollowUpNational($year, 2, $v, $division); //adult
		$v_vl_a = $n->GetHEIFollowUpNational($year, 3, $v, $division); //vl
		$v_rp_a = $n->GetHEIFollowUpNational($year, 4, $v, $division); //repeat
		$v_uf_a = $n->GetHEIFollowUpNational($year, 5, $v, $division); //unknownfacility		
		
		// $sitesending_a = $n->GettotalEidsitesbytimeperiod($year, $division);
		$avgage_a = $n->Getoverallaverageage($year, $division);
		// $medage_a = $n->Getoverallmedianage($year, $div_array, $division, $column);
		// $medage_a = collect($medage_a);
		
		$tat = $n->get_tat($year, $division);
		
		// DB::table($sum_table)->where('year', $year)->delete();

		// Loop through the months and insert data into the division summary
		for ($i=0; $i < 12; $i++) { 
			$month = $i + 1;

			if($year == Date('Y') && $month > Date('m')){ break; }

			// Loop through divisions i.e. counties, subcounties, partners and sites
			// for ($it=0; $it < $array_size; $it++) { 


			foreach ($alltests_a as $row) {

				$wheres = ['month' => $month, 'facility' => $row->facility];

				$alltests = $row->totals;
				$received = $this->checknull($received_a, $wheres);

				if($alltests == 0 && $received == 0) continue;

				$eqatests = $this->checknull($eqatests_a, $wheres);
				$tests = $this->checknull($tests_a, $wheres);
				$patienttests = $this->checknull($patienttests_a, $wheres);
				$patienttestsPOS = $this->checknull($patienttestsPOS_a, $wheres);

				
				$firstdna = $this->checknull($firstdna_a, $wheres);
				$confirmdna = $this->checknull($confirmdna_a, $wheres);
				$posrepeats = $this->checknull($posrepeats_a, $wheres);
				$confirmdnaPOS = $this->checknull($confirmdnaPOS_a, $wheres);
				$posrepeatsPOS = $this->checknull($posrepeatsPOS_a, $wheres);
				$confimPOS = $confirmdnaPOS + $posrepeatsPOS;

				$infantsless2m = $this->checknull($infantsless2m_a, $wheres);
				$infantsless2mPOS = $this->checknull($infantsless2mPOS_a, $wheres);
				$infantsless2w = $this->checknull($infantsless2w_a, $wheres);
				$infantsless2wPOS = $this->checknull($infantsless2wPOS_a, $wheres);
				$infantsless46w = $this->checknull($infantsless46w_a, $wheres);
				$infantsless46wPOS = $this->checknull($infantsless46wPOS_a, $wheres);
				$infantsabove2m = $this->checknull($infantsabove2m_a, $wheres);
				$infantsabove2mPOS = $this->checknull($infantsabove2mPOS_a, $wheres);
				$adulttests = $this->checknull($adulttests_a, $wheres);
				$adulttestsPOS = $this->checknull($adulttestsPOS_a, $wheres);


				$pos = $this->checknull($pos_a, $wheres);
				$neg = $this->checknull($neg_a, $wheres);
				$fail = $this->checknull($fail_a, $wheres);
				$rd = $this->checknull($rd_a, $wheres);
				$rdd = $this->checknull($rdd_a, $wheres);
				$redraw = $fail + $rd + $rdd;

				$rpos = $this->checknull($rpos_a, $wheres);
				$rneg = $this->checknull($rneg_a, $wheres);

				$allpos = $this->checknull($allpos_a, $wheres);
				$allneg = $this->checknull($allneg_a, $wheres);

				$rej = $this->checknull($rej_a, $wheres);
				$enrolled = $this->checknull($enrolled_a, $wheres);
				$ltfu = $this->checknull($ltfu_a, $wheres);
				$dead = $this->checknull($dead_a, $wheres);
				$adult = $this->checknull($adult_a, $wheres);
				$transout = $this->checknull($transout_a, $wheres);
				$other = $this->checknull($other_a, $wheres);

				$v_cp = $this->checknull($v_cp_a, $wheres);
				$v_ad = $this->checknull($v_ad_a, $wheres);
				$v_vl = $this->checknull($v_vl_a, $wheres);
				$v_rp = $this->checknull($v_rp_a, $wheres);
				$v_uf = $this->checknull($v_uf_a, $wheres);

				// $sitesending = $this->checknull($sitesending_a, $wheres);
				$sitesending = 1;
				$avgage = $this->checknull($avgage_a, $wheres);
				// $medage = $this->checkmedage($medage_a->where('month', $month)->where('division', $div_array[$it]));

				
				$tt = $this->check_tat($tat, $wheres);
				// $tt = $this->checktat($tat->where('month', $month)->where('division', $div_array[$it]));
				

				$data_array = array(
					'avgage' => $avgage,	'received' => $received,
					'alltests' => $alltests, 'eqatests' => $eqatests, 'tests' => $tests,
					'firstdna' => $firstdna, 'confirmdna' => $confirmdna, 'repeatspos' => $posrepeats,
					'confirmedPOs' => $confirmdnaPOS, 'repeatposPOS' => $posrepeatsPOS,
					'infantsless2m' => $infantsless2m,
					'infantsless2mPOs' => $infantsless2mPOS, 'infantsless2w' => $infantsless2w,
					'infantsless2wPOs' => $infantsless2wPOS, 'infantsabove2m' => $infantsabove2m,
					'infantsabove2mPOs' => $infantsabove2mPOS, 'adults' => $adulttests,
					'adultsPOs' => $adulttestsPOS, 'actualinfants' => $patienttests,
					'actualinfantsPOs' => $patienttestsPOS, 'pos' => $pos, 'neg' => $neg,
					'allpos' => $allpos, 'allneg' => $allneg, 'rpos' => $rpos, 'rneg' => $rneg,
					'redraw' => $redraw, 'rejected' => $rej, 'enrolled' => $enrolled, 'dead' => $dead,
					'ltfu' => $ltfu, 'adult' => $adult, 'transout' => $transout, 'other' => $other,
					'validation_confirmedpos' => $v_cp, 'validation_repeattest' => $v_ad,
					'validation_viralload' => $v_vl, 'validation_adult' => $v_rp,
					'validation_unknownsite' => $v_uf, 'sitessending' => $sitesending,
					'tat1' => $tt['tat1'], 'tat2' => $tt['tat2'], 'tat3' => $tt['tat3'], 'tat4' => $tt['tat4'],
					'dateupdated' => $today, 'facility_tested_in' => $row->lab_id
				);

				if ($type == 4) {
					$noage = $this->checknull($noage_a->where($wheres));
					$noagePOS = $this->checknull($noagePOS_a->where($wheres));
					$data_array = array_merge($data_array, ['noage' => $noage, 'noagePOS' => $noagePOS]);
				}

				$wheres = array_merge($wheres, ['year' => $year]);

				$row = DB::table($sum_table)->where($wheres)->first();

				if($row) DB::table($sum_table)->where('id', $row->ID)->update($data_array);
				else{
					$data_array = array_merge($data_array, $wheres);
					DB::table($sum_table)->insert($data_array);
				}
			}
			// End of division loop
			
		}
		// End of summary
		echo "\n Completed entry into eid {$column} poc summary at " . date('d/m/Y h:i:s a', time());

		// End of division updator
    }

    public function continue_poc($year=null){
    	$n = new EidFacility;

    	$division = 'site_poc';
    	$column = 'facility';

    	if($year == null) $year = Date('Y');

    	$today = date('Y-m-d');

    	$update_statements = "";
    	$updates = 0;

    	ini_set("memory_limit", "-1");

    	for ($type=3; $type < 5; $type++) { 

			$table = $this->get_table(6, $type);

			echo "\n Begin eid " . $table[0] . " update at " . date('d/m/Y h:i:s a', time());
			
			// Get ids of the necessary table
			// E.g. ids of entrypoints
			$divs = $data = DB::connection('eid_vl')
			->table($table[1])
			->when($type, function($query) use ($type){
				if($type == 1){
					return $query->where('ptype', 2);
				}
				if($type == 2){
					return $query->where('ptype', 1);
				}								
			})
			->get();

			foreach ($divs as $key => $value) {

				// Loop through each month and update entrypoints
				for ($i=0; $i < 12; $i++) { 
					$month = $i + 1;
					if($year == Date('Y') && $month > Date('m')){ break; }

					$ttttt = 0;

					if($type == 1){
						$pos_a = $n->Getinfantprophpositivitycount($year, $month, $value->id, 2, $division);
						$neg_a = $n->Getinfantprophpositivitycount($year, $month, $value->id, 1, $division);
						$fail_a = $n->Getinfantprophpositivitycount($year, $month, $value->id, 3, $division);
						$rd_a = $n->Getinfantprophpositivitycount($year, $month, $value->id, 5, $division);
					}

					else if($type == 2){
						$pos_a = $n->Getinterventionspositivitycount($year, $month, $value->id, 2, $division);
						$neg_a = $n->Getinterventionspositivitycount($year, $month, $value->id, 1, $division);
						$fail_a = $n->Getinterventionspositivitycount($year, $month, $value->id, 3, $division);
						$rd_a = $n->Getinterventionspositivitycount($year, $month, $value->id, 5, $division);
					}

					else if($type == 3){
						$pos_a = $n->GetNationalResultbyEntrypoint($year, $month, $value->id, 2, $division);
						$neg_a = $n->GetNationalResultbyEntrypoint($year, $month, $value->id, 1, $division);
						$fail_a = $n->GetNationalResultbyEntrypoint($year, $month, $value->id, 3, $division);
						$rd_a = $n->GetNationalResultbyEntrypoint($year, $month, $value->id, 5, $division);
					}

					else if($type == 4){
						$pos_a = $n->OutcomesByAgeBand($year, $month, [$value->lower, $value->upper], 2, $division);
						$neg_a = $n->OutcomesByAgeBand($year, $month, [$value->lower, $value->upper], 1, $division);
					}


					// Loop through divisions i.e. counties, subcounties, partners and sites
					// for ($it=0; $it < $array_size; $it++) { 

					foreach ($neg_a as $key_row => $neg_row) {

						$pos = $this->checknull($pos_a->where($column, $neg_row->facility));
						$neg = $neg_row->totals;

						$data_array = array('pos' => $pos, 'neg' => $neg, 'dateupdated' => $today);

						if($type != 4){							

							$fail = $this->checknull($fail_a->where($column, $neg_row->facility));
							$rd = $this->checknull($rd_a->where($column, $neg_row->facility));

							$redraw = $fail + $rd;
							$tests = $pos + $neg +  $redraw;

							$data_array = array_merge($data_array, ['tests' => $tests, 'redraw' => $redraw]);
						}

						$wheres = ['year' => $year, 'month' => $month, 'facility' => $neg_row->facility, $table[2] => $value->id];

						$row = DB::table($table[0])->where($wheres)->first();

						if($row) DB::table($table[0])->where('ID', $row->ID)->update($data_array);
						else{
							$data_array = array_merge($data_array, $wheres);
							DB::table($table[0])->insert($data_array);	
						}	
					}
					// End of looping through divisions
				}
				//End of looping through months
			}
			// End of looping through ids of each table e.g. entry_points
			echo "\n Completed eid " . $table[0] . " update at " . date('d/m/Y h:i:s a', time());
		}
		// $this->mysqli->multi_query($update_statements);
    }

    // Will be used to enter data for yearly divisions except labs
    // Types: 1=county, 2=subcounty, 3=partner, 4=sites
    public function division_updator_yearly($year=null, $type=1, $column='county', $division='view_facilitys.county', $div_table='countys', $sum_table='county_summary_yearly'){

    	if($year == null){
    		$year = Date('Y');
    	}

    	$update_statements = "";
    	$updates = 0;

    	// Instantiate new object
    	$n = new EidDivision;

    	$today=date("Y-m-d");

    	$div_array;
    	$array_size = 0;

    	$divs = $data = DB::connection('eid_vl')
		->table($div_table)->select('id')->get();

		foreach ($divs as $key => $value) {
			$div_array[$key] = $value->id;
			$array_size++;
		}

		echo "\n Begin eid {$column} summary yearly update at " . date('d/m/Y h:i:s a', time());

		// Get collection instances of the data
    	$alltests_a = $n->CumulativeTestedSamples($year, $division, false);
    	$eqatests_a = $n->OverallEQATestedSamples($year, $division, false);

    	$tests_a = $n->OverallTestedSamples($year, $division, false);
		
		$patienttests_a = $n->OverallTestedPatients($year, false, $division, false);
		$patienttestsPOS_a = $n->OverallTestedPatients($year, true, $division, false);
		$received_a = $n->OverallReceivedSamples($year, $division, false);

		$firstdna_a = $n->getbypcr($year, 1, false, $division, false);
		$posrepeats_a = $n->getbypcr($year, 2, false, $division, false);
		$confirmdna_a = $n->getbypcr($year, 4, false, $division, false);

		$posrepeatsPOS_a = $n->getbypcr($year, 2, true, $division, false);
		$confirmdnaPOS_a = $n->getbypcr($year, 4, true, $division, false);


		$infantsless2m_a = 		$n->Gettestedsamplescountrange($year, 1, false, $division, false);
		$infantsless2mPOS_a = 	$n->Gettestedsamplescountrange($year, 1, true, $division, false);
		$infantsless2w_a =		$n->Gettestedsamplescountrange($year, 3, false, $division, false);
		$infantsless2wPOS_a =	$n->Gettestedsamplescountrange($year, 3, true, $division, false);
		$infantsless46w_a =		$n->Gettestedsamplescountrange($year, 4, false, $division, false);
		$infantsless46wPOS_a =	$n->Gettestedsamplescountrange($year, 4, true, $division, false);
		$infantsabove2m_a =		$n->Gettestedsamplescountrange($year, 2, false, $division, false);
		$infantsabove2mPOS_a = 	$n->Gettestedsamplescountrange($year, 2, true, $division, false);
		$adulttests_a =			$n->Gettestedsamplescountrange($year, 5, false, $division, false);
		$adulttestsPOS_a =		$n->Gettestedsamplescountrange($year, 5, true, $division, false);

		if($type == 4){
			$noage_a =			$n->Gettestedsamplescountrange($year, 0, false, $division, false);
			$noagePOS_a =		$n->Gettestedsamplescountrange($year, 0, true, $division, false);
		}
		

		$pos_a = $n->OverallTestedSamplesOutcomes($year, 2, 1, $division, false);
		$neg_a = $n->OverallTestedSamplesOutcomes($year, 1, 1, $division, false);

		$rpos_a = $n->OverallTestedSamplesOutcomes($year, 2, 2, $division, false);
		$rneg_a = $n->OverallTestedSamplesOutcomes($year, 1, 2, $division, false);

		$allpos_a = $n->OverallTestedSamplesOutcomes($year, 2, 0, $division, false);
		$allneg_a = $n->OverallTestedSamplesOutcomes($year, 1, 0, $division, false);


		$fail_a = $n->OverallTestedSamplesOutcomes($year, 3, 1, $division, false);
		$rd_a = $n->OverallTestedSamplesOutcomes($year, 5, 1, $division, false);
		$rdd_a = $n->OverallTestedSamplesOutcomes($year, 6, 1, $division, false);
		// $redraw=$fail + $rd + $rdd;

		$rej_a = $n->Getnationalrejectedsamples($year, $division, false);
		
		$v = "enrollment_status";
		$enrolled_a = $n->GetHEIFollowUpNational($year, 1, $v, $division, false);
		$ltfu_a = $n->GetHEIFollowUpNational($year, 2, $v, $division, false);
		$dead_a = $n->GetHEIFollowUpNational($year, 3, $v, $division, false);
		$adult_a = $n->GetHEIFollowUpNational($year, 4, $v, $division, false);
		$transout_a = $n->GetHEIFollowUpNational($year, 5, $v, $division, false);
		$other_a = $n->GetHEIFollowUpNational($year, 6, $v, $division, false);


		$v = 'hei_validation';
		$v_cp_a = $n->GetHEIFollowUpNational($year, 1, $v, $division, false); //confirmedpos	
		$v_ad_a = $n->GetHEIFollowUpNational($year, 2, $v, $division, false); //adult
		$v_vl_a = $n->GetHEIFollowUpNational($year, 3, $v, $division, false); //vl
		$v_rp_a = $n->GetHEIFollowUpNational($year, 4, $v, $division, false); //repeat
		$v_uf_a = $n->GetHEIFollowUpNational($year, 5, $v, $division, false); //unknownfacility		
		
		$sitesending_a = $n->GettotalEidsitesbytimeperiod($year, $division, false);
		$avgage_a = $n->Getoverallaverageage($year, $division, false);
		$medage_a = $n->Getoverallmedianage($year, $div_array, $division, $column, false);
		$medage_a = collect($medage_a);

		$tat = $n->get_tat($year, $division, false);		

		// Loop through divisions i.e. counties, subcounties, partners and sites
		for ($it=0; $it < $array_size; $it++) { 
			if($type == 3 && $div_array[$it] == 55 && $year < 2019) continue;
			$wheres = [$column => $div_array[$it]];

			$alltests = $this->checknull($alltests_a, $wheres);
			$received = $this->checknull($received_a, $wheres);

			if($alltests == 0 && $received == 0) continue;

			$eqatests = $this->checknull($eqatests_a, $wheres);

			$tests = $this->checknull($tests_a, $wheres);
			$patienttests = $this->checknull($patienttests_a, $wheres);
			$patienttestsPOS = $this->checknull($patienttestsPOS_a, $wheres);

			
			$firstdna = $this->checknull($firstdna_a, $wheres);
			$confirmdna = $this->checknull($confirmdna_a, $wheres);
			$posrepeats = $this->checknull($posrepeats_a, $wheres);
			$confirmdnaPOS = $this->checknull($confirmdnaPOS_a, $wheres);
			$posrepeatsPOS = $this->checknull($posrepeatsPOS_a, $wheres);
			$confimPOS = $confirmdnaPOS + $posrepeatsPOS;

			$infantsless2m = $this->checknull($infantsless2m_a, $wheres);
			$infantsless2mPOS = $this->checknull($infantsless2mPOS_a, $wheres);
			$infantsless2w = $this->checknull($infantsless2w_a, $wheres);
			$infantsless2wPOS = $this->checknull($infantsless2wPOS_a, $wheres);
			$infantsless46w = $this->checknull($infantsless46w_a, $wheres);
			$infantsless46wPOS = $this->checknull($infantsless46wPOS_a, $wheres);
			$infantsabove2m = $this->checknull($infantsabove2m_a, $wheres);
			$infantsabove2mPOS = $this->checknull($infantsabove2mPOS_a, $wheres);
			$adulttests = $this->checknull($adulttests_a, $wheres);
			$adulttestsPOS = $this->checknull($adulttestsPOS_a, $wheres);


			$pos = $this->checknull($pos_a, $wheres);
			$neg = $this->checknull($neg_a, $wheres);
			$fail = $this->checknull($fail_a, $wheres);
			$rd = $this->checknull($rd_a, $wheres);
			$rdd = $this->checknull($rdd_a, $wheres);
			$redraw = $fail + $rd + $rdd;

			$rpos = $this->checknull($rpos_a, $wheres);
			$rneg = $this->checknull($rneg_a, $wheres);

			$allpos = $this->checknull($allpos_a, $wheres);
			$allneg = $this->checknull($allneg_a, $wheres);

			$rej = $this->checknull($rej_a, $wheres);
			$enrolled = $this->checknull($enrolled_a, $wheres);
			$ltfu = $this->checknull($ltfu_a, $wheres);
			$dead = $this->checknull($dead_a, $wheres);
			$adult = $this->checknull($adult_a, $wheres);
			$transout = $this->checknull($transout_a, $wheres);
			$other = $this->checknull($other_a, $wheres);

			$v_cp = $this->checknull($v_cp_a, $wheres);
			$v_ad = $this->checknull($v_ad_a, $wheres);
			$v_vl = $this->checknull($v_vl_a, $wheres);
			$v_rp = $this->checknull($v_rp_a, $wheres);
			$v_uf = $this->checknull($v_uf_a, $wheres);

			$sitesending = $this->checknull($sitesending_a, $wheres);
			$avgage = $this->checknull($avgage_a, $wheres);
			$medage = $this->checkmedage($medage_a->where('division', $div_array[$it]));

			$tt = $this->check_tat($tat, $wheres);

			$data_array = array(
				'avgage' => $avgage,	'medage' => $medage,	'received' => $received,
				'alltests' => $alltests, 'eqatests' => $eqatests, 'tests' => $tests,
				'firstdna' => $firstdna, 'confirmdna' => $confirmdna, 'repeatspos' => $posrepeats,
				'confirmedPOs' => $confirmdnaPOS, 'repeatposPOS' => $posrepeatsPOS,
				'infantsless2m' => $infantsless2m,
				'infantsless2mPOs' => $infantsless2mPOS, 'infantsless2w' => $infantsless2w,
				'infantsless2wPOs' => $infantsless2wPOS, 'infantsabove2m' => $infantsabove2m,
				'infantsabove2mPOs' => $infantsabove2mPOS, 'adults' => $adulttests,
				'adultsPOs' => $adulttestsPOS, 'actualinfants' => $patienttests,
				'actualinfantsPOs' => $patienttestsPOS, 'pos' => $pos, 'neg' => $neg,
				'allpos' => $allpos, 'allneg' => $allneg, 'rpos' => $rpos, 'rneg' => $rneg,
				'redraw' => $redraw, 'rejected' => $rej, 'enrolled' => $enrolled, 'dead' => $dead,
				'ltfu' => $ltfu, 'adult' => $adult, 'transout' => $transout, 'other' => $other,
				'validation_confirmedpos' => $v_cp, 'validation_repeattest' => $v_ad,
				'validation_viralload' => $v_vl, 'validation_adult' => $v_rp,
				'validation_unknownsite' => $v_uf, 'sitessending' => $sitesending,
				'tat1' => $tt['tat1'], 'tat2' => $tt['tat2'], 'tat3' => $tt['tat3'], 'tat4' => $tt['tat4'],
				 'dateupdated' => $today
			);

			if ($type == 4) {
				$noage = $this->checknull($noage_a, $wheres);
				$noagePOS = $this->checknull($noagePOS_a, $wheres);
				$data_array = array_merge($data_array, ['noage' => $noage, 'noagePOS' => $noagePOS]);
			}

			DB::table($sum_table)->where('year', $year)->where($column, $div_array[$it])->update($data_array);

			// $update_statements .= $this->update_query($sum_table, $data_array, ['year' => $year, 'month' => $month, $column => $div_array[$it]]);
			// $updates++;

			// if($updates == 200){
			// 	$this->mysqli->multi_query($update_statements);
			// 	$update_statements = '';
			// 	$updates = 0;
			// }

		}
		// End of division loop
			
		// $this->mysqli->multi_query($update_statements);

		echo "\n Completed entry into eid {$column} summary yearly at " . date('d/m/Y h:i:s a', time());
	}

    public function update_counties($year = null){
    	return $this->division_updator($year, 1, 'county', 'county', 'countys', 'county_summary', 'county_agebreakdown', 'county_rejections');
    }

    public function update_subcounties($year = null){
    	return $this->division_updator($year, 2, 'subcounty', 'subcounty', 'districts', 'subcounty_summary', 'subcounty_agebreakdown', 'subcounty_rejections');
    }

    public function update_partners($year = null){
    	return $this->division_updator($year, 3, 'partner', 'partner', 'partners', 'ip_summary', 'ip_agebreakdown', 'ip_rejections');
    }

    public function update_facilities($year = null){
    	return $this->division_updator($year, 4, 'facility', 'facility', 'facilitys', 'site_summary', '', 'site_rejections');
    }


    public function update_counties_yearly($year = null){
    	return $this->division_updator_yearly($year, 1, 'county', 'county', 'countys', 'county_summary_yearly');
    }

    public function update_subcounties_yearly($year = null){
    	return $this->division_updator_yearly($year, 2, 'subcounty', 'subcounty', 'districts', 'subcounty_summary_yearly');
    }

    public function update_partners_yearly($year = null){
    	return $this->division_updator_yearly($year, 3, 'partner', 'partner', 'partners', 'ip_summary_yearly');
    }

    public function update_facilities_yearly($year = null){
    	return $this->division_updator_yearly($year, 4, 'facility', 'facility', 'facilitys', 'site_summary_yearly');
    }

    public function update_patients(){
    	echo "\n Begin entry into eid patients at " . date('d/m/Y h:i:s a', time()); 

    	// Instantiate new object
    	$n = new EidDivision;

    	echo $n->update_patients();

    	echo "\n Completed entry into eid patients at " . date('d/m/Y h:i:s a', time()); 
    }

    public function update_tat($year = null){
    	// Instantiate new object
    	$n = new EidNation;

    	if($year == null){
    		$year = Date('Y');
    	}

    	echo $n->update_tats($year);
    }

    public function update_confirmatory($year = null){
    	// Instantiate new object
    	$n = new EidNation;

    	if($year == null){
    		$year = Date('Y');
    	}

    	echo $n->confirmatory_report($year);
    }



    public function checknull($var, $wheres=[]){
    	foreach ($wheres as $key => $value) {
    		$var = $var->where($key, $value);
    	}
    	return $var->first()->totals ?? 0;
    }

    public function checknull_month($var, $month){
    	return $var->where('month', $month)->first()->totals ?? 0;
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

    public function checkmedage($var){
    	if($var->isEmpty()){
    		return 0;
    	}else{
    		$a = $var->first();
    		return $a['totals'];
    	}
    }



    private function get_table($division, $type){
    	$name;
    	if ($division == 0) {
    		switch ($type) {
    			case 1:
    				$name = array("national_iprophylaxis", "prophylaxis", "prophylaxis");
    				break;
    			case 2:
    				$name = array("national_mprophylaxis", "prophylaxis", "prophylaxis");
    				break;
    			case 3:
    				$name = array("national_entrypoint", "entry_points", "entrypoint");
    				break;
    			case 4:
    				$name = array("national_age_breakdown", "age_bands", "age_band_id");
    				break;
    			default:
    				break;
    		}
    	}
    	else if ($division == 1) {
    		switch ($type) {
    			case 1:
    				$name = array("county_iprophylaxis", "prophylaxis", "prophylaxis");
    				break;
    			case 2:
    				$name = array("county_mprophylaxis", "prophylaxis", "prophylaxis");
    				break;
    			case 3:
    				$name = array("county_entrypoint", "entry_points", "entrypoint");
    				break;
    			case 4:
    				$name = array("county_age_breakdown", "age_bands", "age_band_id");
    				break;
    			default:
    				break;
    		}
    	}

    	else if ($division == 2) {
    		switch ($type) {
    			case 1:
    				$name = array("subcounty_iprophylaxis", "prophylaxis", "prophylaxis");
    				break;
    			case 2:
    				$name = array("subcounty_mprophylaxis", "prophylaxis", "prophylaxis");
    				break;
    			case 3:
    				$name = array("subcounty_entrypoint", "entry_points", "entrypoint");
    				break;
    			case 4:
    				$name = array("subcounty_age_breakdown", "age_bands", "age_band_id");
    				break;
    			default:
    				break;
    		}
    	}

    	else if ($division == 3) {
    		switch ($type) {
    			case 1:
    				$name = array("ip_iprophylaxis", "prophylaxis", "prophylaxis");
    				break;
    			case 2:
    				$name = array("ip_mprophylaxis", "prophylaxis", "prophylaxis");
    				break;
    			case 3:
    				$name = array("ip_entrypoint", "entry_points", "entrypoint");
    				break;
    			case 4:
    				$name = array("ip_age_breakdown", "age_bands", "age_band_id");
    				break;
    			default:
    				break;
    		}
    	}

    	else if ($division == 4) {
    		switch ($type) {
    			case 1:
    				$name = array("site_iprophylaxis", "prophylaxis", "prophylaxis");
    				break;
    			case 2:
    				$name = array("site_mprophylaxis", "prophylaxis", "prophylaxis");
    				break;
    			case 3:
    				$name = array("site_entrypoint", "entry_points", "entrypoint");
    				break;
    			case 4:
    				$name = array("site_age_breakdown", "age_bands", "age_band_id");
    				break;
    			default:
    				break;
    		}
    	}

    	else if ($division == 6) {
    		switch ($type) {
    			case 1:
    				$name = array("site_iprophylaxis_poc", "prophylaxis", "prophylaxis");
    				break;
    			case 2:
    				$name = array("site_mprophylaxis_poc", "prophylaxis", "prophylaxis");
    				break;
    			case 3:
    				$name = array("site_entrypoint_poc", "entry_points", "entrypoint");
    				break;
    			case 4:
    				$name = array("site_age_breakdown_poc", "age_bands", "age_band_id");
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
