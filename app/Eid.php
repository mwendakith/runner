<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\EidNation;
use App\EidDivision;
use DB;

class Eid extends Model
{
    //
    public function update_nation($year = null){
    	if($year == null){
    		$year = Date('Y');
    	}
    	// Instantiate new object
    	$n = new EidNation;

    	$today=date("Y-m-d");

    	echo "\n Begin eid nation update at " . date('d/m/Y h:i:s a', time());

    	// Get collection instances of the data
    	$alltests_a = $n->CumulativeTestedSamples($year);
    	$eqatests_a = $n->OverallEQATestedSamples($year);

    	$tests_a = $n->OverallTestedSamples($year);
		
		$patienttests_a = $n->OverallTestedPatients($year);
		$patienttestsPOS_a = $n->OverallTestedPatientsPOS($year);
		$received_a = $n->OverallReceivedSamples($year);
			
		$firstdna_a = $n->OveralldnafirstTestedSamples($year);
		$confirmdna_a = $n->OveralldnasecondTestedSamples($year);
		$posrepeats_a = $n->OverallPosRepeatsTestedSamples($year);

		$confirmdnaPOS_a = $n->OveralldnasecondTestedSamplesPOS($year);
		$posrepeatsPOS_a = $n->OverallPosRepeatsTestedSamplesPOS($year);
		//$confimPOs =$confirmdnaPOS + $posrepeatsPOS;


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
		

		$pos_a = $n->OverallTestedSamplesOutcomes($year, 2);
		$neg_a = $n->OverallTestedSamplesOutcomes($year, 1);
		$fail_a = $n->OverallTestedSamplesOutcomes($year, 3);
		$rd_a = $n->OverallTestedSamplesOutcomes($year, 5);
		$rdd_a = $n->OverallTestedSamplesOutcomes($year, 6);
		// $redraw=$fail + $rd + $rdd;
		
		//echo $alltests;
		$rej_a = $n->Getnationalrejectedsamples($year);
		
		$enrolled_a = $n->GetHEIFollowUpNational($year, 1);
		$ltfu_a = $n->GetHEIFollowUpNational($year, 2);
		$dead_a = $n->GetHEIFollowUpNational($year, 3);
		$adult_a = $n->GetHEIFollowUpNational($year, 4);
		$transout_a = $n->GetHEIFollowUpNational($year, 5);
		$other_a = $n->GetHEIFollowUpNational($year, 6);


		$v = 'samples.hei_validation';
		$v_cp_a = $n->GetHEIFollowUpNational($year, 1, $v); //confirmedpos	
		$v_ad_a = $n->GetHEIFollowUpNational($year, 2, $v); //adult
		$v_vl_a = $n->GetHEIFollowUpNational($year, 3, $v); //vl
		$v_rp_a = $n->GetHEIFollowUpNational($year, 4, $v); //repeat
		$v_uf_a = $n->GetHEIFollowUpNational($year, 5, $v); //unknownfacility		
		
		$sitesending_a = $n->GettotalEIDsitesbytimeperiod($year);
		$avgage_a = $n->Getoverallaverageage($year);
		$medage_a = $n->Getoverallmedianage($year);

		$tat = $n->GetNatTATs($year);
		// $tat = collect($tat);

		$count = $pos_a->count();

		// Loop through the months and insert data into the national summary
		for ($i=0; $i < $count; $i++) { 
			$month = $i + 1;

			$alltests = $this->checknull($alltests_a->where('month', $month));
			$eqatests = $this->checknull($eqatests_a->where('month', $month));
			$tests = $this->checknull($tests_a->where('month', $month));
			$patienttests = $this->checknull($patienttests_a->where('month', $month));
			$patienttestsPOS = $this->checknull($patienttestsPOS_a->where('month', $month));

			$received = $this->checknull($received_a->where('month', $month));
			$firstdna = $this->checknull($firstdna_a->where('month', $month));
			$confirmdna = $this->checknull($confirmdna_a->where('month', $month));
			$posrepeats = $this->checknull($posrepeats_a->where('month', $month));
			$confirmdnaPOS = $this->checknull($confirmdnaPOS_a->where('month', $month));
			$posrepeatsPOS = $this->checknull($posrepeatsPOS_a->where('month', $month));
			$confimPOS = $confirmdnaPOS + $posrepeatsPOS;

			$infantsless2m = $this->checknull($infantsless2m_a->where('month', $month));
			$infantsless2mPOS = $this->checknull($infantsless2mPOS_a->where('month', $month));
			$infantsless2w = $this->checknull($infantsless2w_a->where('month', $month));
			$infantsless2wPOS = $this->checknull($infantsless2wPOS_a->where('month', $month));
			$infantsless46w = $this->checknull($infantsless46w_a->where('month', $month));
			$infantsless46wPOS = $this->checknull($infantsless46wPOS_a->where('month', $month));
			$infantsabove2m = $this->checknull($infantsabove2m_a->where('month', $month));
			$infantsabove2mPOS = $this->checknull($infantsabove2mPOS_a->where('month', $month));
			$adulttests = $this->checknull($adulttests_a->where('month', $month));
			$adulttestsPOS = $this->checknull($adulttestsPOS_a->where('month', $month));


			$pos = $this->checknull($pos_a->where('month', $month));
			$neg = $this->checknull($neg_a->where('month', $month));
			$fail = $this->checknull($fail_a->where('month', $month));
			$rd = $this->checknull($rd_a->where('month', $month));
			$rdd = $this->checknull($rdd_a->where('month', $month));
			$redraw = $fail + $rd + $rdd;

			$rej = $this->checknull($rej_a->where('month', $month));
			$enrolled = $this->checknull($enrolled_a->where('month', $month));
			$ltfu = $this->checknull($ltfu_a->where('month', $month));
			$dead = $this->checknull($dead_a->where('month', $month));
			$adult = $this->checknull($adult_a->where('month', $month));
			$transout = $this->checknull($transout_a->where('month', $month));
			$other = $this->checknull($other_a->where('month', $month));

			$v_cp = $this->checknull($v_cp_a->where('month', $month));
			$v_ad = $this->checknull($v_ad_a->where('month', $month));
			$v_vl = $this->checknull($v_vl_a->where('month', $month));
			$v_rp = $this->checknull($v_rp_a->where('month', $month));
			$v_uf = $this->checknull($v_uf_a->where('month', $month));

			$sitesending = $this->checknull($sitesending_a->where('month', $month));
			$avgage = $this->checknull($avgage_a->where('month', $month));
			$medage = $medage_a[$i];

			$tt = $tat[$i];			

			$data_array = array(
				'avgage' => $avgage,	'medage' => $medage,	'received' => $received,
				'alltests' => $alltests, 'eqatests' => $eqatests, 'tests' => $tests,
				'firstdna' => $firstdna, 'confirmdna' => $confirmdna, 'repeatspos' => $posrepeats,
				'confirmedPOs' => $confirmdnaPOS, 'repeatposPOS' => $posrepeatsPOS,
				'infantsless2m' => $infantsless2m,
				'infantsless2mPOs' => $infantsless2mPOS, 'infantsless2w' => $infantsless2w,
				'infantsless2mPOs' => $infantsless2wPOS, 'infantsabove2m' => $infantsabove2m,
				'infantsabove2mPOs' => $infantsabove2mPOS, 'adults' => $adulttests,
				'adultsPOs' => $adulttestsPOS, 'actualinfants' => $patienttests,
				'actualinfantsPOs' => $patienttestsPOS, 'pos' => $pos, 'neg' => $neg,
				'redraw' => $redraw, 'rejected' => $rej, 'enrolled' => $enrolled, 'dead' => $dead,
				'ltfu' => $ltfu, 'adult' => $adult, 'transout' => $transout, 'other' => $other,
				'validation_confirmedpos' => $v_cp, 'validation_repeattest' => $v_ad,
				'validation_viralload' => $v_vl, 'validation_adult' => $v_rp,
				'validation_unknownsite' => $v_uf, 'sitessending' => $sitesending,
				'tat1' => $tt['tat1'], 'tat2' => $tt['tat2'], 'tat3' => $tt['tat3'], 
				'tat4' => $tt['tat4'], 'dateupdated' => $today
			);

			DB::table('national_summary')->where('year', $year)->where('month', $month)->update($data_array);

			// $sql = "UPDATE national_summary set avgage='$avgage', medage='$medage',received='$received' , alltests ='$alltests' , eqatests ='$eqatests' , tests ='$tests',firstdna='$firstdna',confirmdna='$confirmdna',repeatspos ='$posrepeats',confirmedPOs ='$confimPOS',infantsless2m='$infantsless2m'  ,infantsless2mPOs ='$infantsless2mPOS' ,infantsless2w='$infantsless2w'  ,infantsless2wPOs ='$infantsless2wPOS',infants4to6w='$infantsless46w'  ,infants4to6wPOs ='$infantsless46wPOS',infantsabove2m='$infantsabove2m', infantsabove2mPOs ='$infantsabove2mPOS',adults ='$adulttests',adultsPOs ='$adulttestsPOS' ,actualinfants ='$patienttests', actualinfantsPOs ='$patienttestsPOS',pos ='$pos',neg ='$neg',redraw='$redraw',rejected='$rej',enrolled='$enrolled',dead='$dead',ltfu='$ltfu',adult='$adult',transout='$transout',	 other='$other' ,validation_confirmedpos ='$v_cp',validation_repeattest='$v_ad',validation_viralload='$v_vl',validation_adult='$v_rp',validation_unknownsite='$v_uf',sitessending ='$sitesending', tat1='$t1', tat2='$t2', tat3='$t3', tat4='$t4',sorted=15   WHERE month='$month' AND year='$year' ";

			


		}
		// End of for loop

		echo "\n Completed entry into eid national summary at " . date('d/m/Y h:i:s a', time());

		// Set the following to null in order to free memory
		$alltests_a = $eqatests_a = $tests_a = $patienttests_a = $patienttestsPOS_a = $received_a = $firstdna_a = $confirmdna_a = $posrepeats_a = $confirmdnaPOS_a = $posrepeatsPOS_a = $infantsless2m_a = $infantsless2mPOS_a = $infantsless2w_a = $infantsless2wPOS_a = $infantsless46w_a = $infantsless46wPOS_a = $infantsabove2m_a = $infantsabove2mPOS_a = $adulttests_a = $adulttestsPOS_a = $pos_a = $neg_a = $fail_a = $rd_a = $rdd_a = $rej_a = $enrolled_a = $ltfu_a = $dead_a = $adult_a = $transout_a = $other_a = $v_cp_a = $v_ad_a = $v_vl_a = $v_rp_a = $v_uf_a = $sitesending_a = $avgage_a = $medage_a = $tat = null;

		echo "\n Begin eid nation age breakdown update at " . date('d/m/Y h:i:s a', time());

		// Get national age_breakdown
		$age1pos_a = $n->GetTestOutcomesbyAgeBand($year, 1, 2);
		$age1neg_a = $n->GetTestOutcomesbyAgeBand($year, 1, 1);
		$age2pos_a = $n->GetTestOutcomesbyAgeBand($year, 2, 2);
		$age2neg_a = $n->GetTestOutcomesbyAgeBand($year, 2, 1);
		$age3pos_a = $n->GetTestOutcomesbyAgeBand($year, 3, 2);
		$age3neg_a = $n->GetTestOutcomesbyAgeBand($year, 3, 1);
		$age4pos_a = $n->GetTestOutcomesbyAgeBand($year, 4, 2);
		$age4neg_a = $n->GetTestOutcomesbyAgeBand($year, 4, 1);
		$age5pos = 0;
		$age5neg = 0;
		$age6pos_a = $n->GetTestOutcomesbyAgeBand($year, 6, 2);
		$age6neg_a = $n->GetTestOutcomesbyAgeBand($year, 6, 1);
		
		$age9pos_a = $n->GetTestOutcomesbyAgeBand($year, 9, 2);
		$age9neg_a = $n->GetTestOutcomesbyAgeBand($year, 9, 1);
		$age10pos_a = $n->GetTestOutcomesbyAgeBand($year, 10, 2);
		$age10neg_a = $n->GetTestOutcomesbyAgeBand($year, 10, 1);
		$age11pos_a = $n->GetTestOutcomesbyAgeBand($year, 11, 2);
		$age11neg_a = $n->GetTestOutcomesbyAgeBand($year, 11, 1);
		$age12pos_a = $n->GetTestOutcomesbyAgeBand($year, 12, 2);
		$age12neg_a = $n->GetTestOutcomesbyAgeBand($year, 12, 1);
		$age13pos_a = $n->GetTestOutcomesbyAgeBand($year, 13, 2);
		$age13neg_a = $n->GetTestOutcomesbyAgeBand($year, 13, 1);
		$age14pos_a = $n->GetTestOutcomesbyAgeBand($year, 14, 2);
		$age14neg_a = $n->GetTestOutcomesbyAgeBand($year, 14, 1);

		// Loop through the months and insert data into the national agebreakdown
		for ($i=0; $i < $count; $i++) { 
			$month = $i + 1;

			$age1pos = $this->checknull($age1pos_a->where('month', $month));
			$age1neg = $this->checknull($age1neg_a->where('month', $month));
			$age2pos = $this->checknull($age2pos_a->where('month', $month));
			$age2neg = $this->checknull($age2neg_a->where('month', $month));
			$age3pos = $this->checknull($age3pos_a->where('month', $month));
			$age3neg = $this->checknull($age3neg_a->where('month', $month));
			$age4pos = $this->checknull($age4pos_a->where('month', $month));
			$age4neg = $this->checknull($age4neg_a->where('month', $month));

			$age6pos = $this->checknull($age6pos_a->where('month', $month));
			$age6neg = $this->checknull($age6neg_a->where('month', $month));

			$age9pos = $this->checknull($age9pos_a->where('month', $month));
			$age9neg = $this->checknull($age9neg_a->where('month', $month));
			$age10pos = $this->checknull($age10pos_a->where('month', $month));
			$age10neg = $this->checknull($age10neg_a->where('month', $month));
			$age11pos = $this->checknull($age11pos_a->where('month', $month));
			$age11neg = $this->checknull($age11neg_a->where('month', $month));
			$age12pos = $this->checknull($age12pos_a->where('month', $month));
			$age12neg = $this->checknull($age12neg_a->where('month', $month));
			$age13pos = $this->checknull($age13pos_a->where('month', $month));
			$age13neg = $this->checknull($age13neg_a->where('month', $month));
			$age14pos = $this->checknull($age14pos_a->where('month', $month));
			$age14neg = $this->checknull($age14neg_a->where('month', $month));


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

			DB::table('national_agebreakdown')->where('year', $year)->where('month', $month)->update($data_array);

			// $sql = "UPDATE national_agebreakdown set sixweekspos='$age1pos', sixweeksneg='$age1neg', sevento3mpos='$age2pos', sevento3mneg='$age2neg'	,threemto9mpos='$age3pos',threemto9mneg='$age3neg',ninemto18mpos='$age4pos',ninemto18mneg='$age4neg',above18mpos='$age5pos',above18mneg='$age5neg',nodatapos='$age6pos',nodataneg='$age6neg', less2wpos='$age9pos',less2wneg='$age9neg',twoto6wpos='$age10pos',twoto6wneg='$age10neg',sixto8wpos='$age11pos',sixto8wneg='$age11neg',sixmonthpos='$age12pos',sixmonthneg='$age12neg',ninemonthpos='$age13pos',ninemonthneg='$age13neg',twelvemonthpos='$age14pos',twelvemonthneg='$age14neg',sorted=9 WHERE month='$month' AND year='$year'";

		}
		// End of for loop

		echo "\n Completed entry into eid national age breakdown at " . date('d/m/Y h:i:s a', time());


		echo "\n Begin eid nation infant regimen update at " . date('d/m/Y h:i:s a', time());

		// Start of infant regimen
		$iregimen = $data = DB::connection('eid')
		->table('prophylaxis')->select('ID')->where('ptype', 2)->get();

		// Loop through infant regimen
		foreach ($iregimen as $key => $value) {
			$ipos_a = $n->Getinfantprophpositivitycount($year, $value->ID, 2);
			$ineg_a = $n->Getinfantprophpositivitycount($year, $value->ID, 1);
			$ifail_a = $n->Getinfantprophpositivitycount($year, $value->ID, 3);
			$ird_a = $n->Getinfantprophpositivitycount($year, $value->ID, 5);

			// Loop through each month and update iprophylaxis
			for ($i=0; $i < $count; $i++) { 
				$month = $i + 1;

				$ipos = $this->checknull($ipos_a->where('month', $month));
				$ineg = $this->checknull($ineg_a->where('month', $month));
				$ifail = $this->checknull($ifail_a->where('month', $month));
				$ird = $this->checknull($ird_a->where('month', $month));

				$iredraw = $ifail + $ird;
				$itests = $ipos + $ineg +  $iredraw;

				$data_array = array(
					'tests' => $itests, 'pos' => $ipos, 'neg' => $ineg, 'redraw' => $iredraw,
					'dateupdated' => $today
				);

				DB::table('national_iprophylaxis')->where('year', $year)->where('month', $month)->where('prophylaxis', $value->ID)->update($data_array);

				// $sql = "UPDATE national_iprophylaxis set tests='$itests', pos='$ipos', neg='$ineg', redraw='$iredraw',sorted=9 WHERE prophylaxis='$iaArray[$irow]' AND month='$month' AND year='$year'";

			}
			
		}
		// End of infant regimen

		echo "\n Completed entry into eid national infant regimen at " . date('d/m/Y h:i:s a', time());

		echo "\n Begin eid nation mother regimen update at " . date('d/m/Y h:i:s a', time());

		// Start of mother regimen
		$mregimen = $data = DB::connection('eid')
		->table('prophylaxis')->select('ID')->where('ptype', 1)->get();

		// Loop through mother regimen
		foreach ($mregimen as $key => $value) {
			$mpos_a = $n->Getinterventionspositivitycount($year, $value->ID, 2);
			$mneg_a = $n->Getinterventionspositivitycount($year, $value->ID, 1);
			$mfail_a = $n->Getinterventionspositivitycount($year, $value->ID, 3);
			$mrd_a = $n->Getinterventionspositivitycount($year, $value->ID, 5);

			// Loop through each month and update mprophylaxis
			for ($i=0; $i < $count; $i++) { 
				$month = $i + 1;

				$mpos = $this->checknull($mpos_a->where('month', $month));
				$mneg = $this->checknull($mneg_a->where('month', $month));
				$mfail = $this->checknull($mfail_a->where('month', $month));
				$mrd = $this->checknull($mrd_a->where('month', $month));

				$mredraw=$mfail + $mrd;
				$tests=$mpos + $mneg +  $mredraw;

				$data_array = array(
					'tests' => $itests, 'pos' => $mpos, 'neg' => $mneg, 'redraw' => $mredraw,
					'dateupdated' => $today
				);

				DB::table('national_mprophylaxis')->where('year', $year)->where('month', $month)->where('prophylaxis', $value->ID)->update($data_array);

				// $sql = "UPDATE national_mprophylaxis set tests='$tests', pos='$mpos', neg='$mneg', redraw='$mredraw',sorted=9 WHERE prophylaxis='$maArray[$mrow]' AND  month='$month' AND year='$year'  ";

			}
			
		}
		// End of mother regimen

		echo "\n Completed entry into eid national mother regimen " . date('d/m/Y h:i:s a', time());

		// Start of entrypoints
		$entrypoints = $data = DB::connection('eid')
		->table('entry_points')->select('ID')->get();

		// Loop through entrypoints
		foreach ($entrypoints as $key => $value) {
			$epos_a = $n->GetNationalResultbyEntrypoint($year, $value->ID, 2);
			$eneg_a = $n->GetNationalResultbyEntrypoint($year, $value->ID, 1);
			$efail_a = $n->GetNationalResultbyEntrypoint($year, $value->ID, 3);
			$erd_a = $n->GetNationalResultbyEntrypoint($year, $value->ID, 5);

			// Loop through each month and update entrypoints
			for ($i=0; $i < $count; $i++) { 
				$month = $i + 1;

				$epos = $this->checknull($epos_a->where('month', $month));
				$eneg = $this->checknull($eneg_a->where('month', $month));
				$efail = $this->checknull($efail_a->where('month', $month));
				$erd = $this->checknull($erd_a->where('month', $month));

				$eredraw = $efail + $erd;
				$etests = $epos + $eneg +  $eredraw;

				$data_array = array(
					'tests' => $etests, 'pos' => $epos, 'neg' => $eneg, 'redraw' => $eredraw,
					'dateupdated' => $today
				);

				DB::table('national_entrypoint')->where('year', $year)->where('month', $month)->where('entrypoint', $value->ID)->update($data_array);

				// $sql = "UPDATE national_entrypoint set tests='$etests', pos='$epos', neg='$eneg', redraw='$eredraw',sorted=9 WHERE entrypoint='$aArray[$row]' AND month='$month' AND year='$year'  ";

			}
			
		}
		// End of entrypoints

		echo "\n Completed entry into eid national entrypoints at " . date('d/m/Y h:i:s a', time());

		// End of national function
    }

    public function update_nation_yearly($year = null){
    	if($year == null){
    		$year = Date('Y');
    	}
    	// Instantiate new object
    	$n = new EidNation;

    	$today=date("Y-m-d");

    	echo "\n Begin eid nation yearly update at " . date('d/m/Y h:i:s a', time());

    	// Get collection instances of the data
    	$alltests_a = $n->CumulativeTestedSamples($year, false);
    	$eqatests_a = $n->OverallEQATestedSamples($year, false);

    	$tests_a = $n->OverallTestedSamples($year, false);
		
		$patienttests_a = $n->OverallTestedPatients($year, false);
		$patienttestsPOS_a = $n->OverallTestedPatientsPOS($year, false);
		$received_a = $n->OverallReceivedSamples($year, false);
			
		$firstdna_a = $n->OveralldnafirstTestedSamples($year, false);
		$confirmdna_a = $n->OveralldnasecondTestedSamples($year, false);
		$posrepeats_a = $n->OverallPosRepeatsTestedSamples($year, false);

		$confirmdnaPOS_a = $n->OveralldnasecondTestedSamplesPOS($year, false);
		$posrepeatsPOS_a = $n->OverallPosRepeatsTestedSamplesPOS($year, false);
		//$confimPOs =$confirmdnaPOS + $posrepeatsPOS;


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
		

		$pos_a = $n->OverallTestedSamplesOutcomes($year, 2, false);
		$neg_a = $n->OverallTestedSamplesOutcomes($year, 1, false);
		$fail_a = $n->OverallTestedSamplesOutcomes($year, 3, false);
		$rd_a = $n->OverallTestedSamplesOutcomes($year, 5, false);
		$rdd_a = $n->OverallTestedSamplesOutcomes($year, 6, false);
		// $redraw=$fail + $rd + $rdd;
		
		//echo $alltests;
		$rej_a = $n->Getnationalrejectedsamples($year, false);
		
		$v = "samples.enrollmentstatus";
		$enrolled_a = $n->GetHEIFollowUpNational($year, 1, $v, false);
		$ltfu_a = $n->GetHEIFollowUpNational($year, 2, $v, false);
		$dead_a = $n->GetHEIFollowUpNational($year, 3, $v, false);
		$adult_a = $n->GetHEIFollowUpNational($year, 4, $v, false);
		$transout_a = $n->GetHEIFollowUpNational($year, 5, $v, false);
		$other_a = $n->GetHEIFollowUpNational($year, 6, $v, false);


		$v = 'samples.hei_validation';
		$v_cp_a = $n->GetHEIFollowUpNational($year, 1, $v, false); //confirmedpos	
		$v_ad_a = $n->GetHEIFollowUpNational($year, 2, $v, false); //adult
		$v_vl_a = $n->GetHEIFollowUpNational($year, 3, $v, false); //vl
		$v_rp_a = $n->GetHEIFollowUpNational($year, 4, $v, false); //repeat
		$v_uf_a = $n->GetHEIFollowUpNational($year, 5, $v, false); //unknownfacility		
		
		$sitesending_a = $n->GettotalEIDsitesbytimeperiod($year, false);
		$avgage_a = $n->Getoverallaverageage($year, false);
		$medage = $n->Getoverallmedianage($year, false);

		$tat = $n->GetNatTATs($year, false);
		// $tat = collect($tat);

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

		$rej = $this->checknull($rej_a);
		$enrolled = $this->checknull($enrolled_a);
		$ltfu = $this->checknull($ltfu_a);
		$dead = $this->checknull($dead_a);
		$adult = $this->checknull($adult_a);
		$transout = $this->checknull($transout_a);
		$other = $this->checknull($other_a);

		$v_cp = $this->checknull($v_cp_a);
		$v_ad = $this->checknull($v_ad_a);
		$v_vl = $this->checknull($v_vl_a);
		$v_rp = $this->checknull($v_rp_a);
		$v_uf = $this->checknull($v_uf_a);

		$sitesending = $this->checknull($sitesending_a);
		$avgage = $this->checknull($avgage_a);

		$tt = $tat[];			

		$data_array = array(
			'avgage' => $avgage,	'medage' => $medage,	'received' => $received,
			'alltests' => $alltests, 'eqatests' => $eqatests, 'tests' => $tests,
			'firstdna' => $firstdna, 'confirmdna' => $confirmdna, 'repeatspos' => $posrepeats,
			'confirmedPOs' => $confirmdnaPOS, 'repeatposPOS' => $posrepeatsPOS,
			'infantsless2m' => $infantsless2m,
			'infantsless2mPOs' => $infantsless2mPOS, 'infantsless2w' => $infantsless2w,
			'infantsless2mPOs' => $infantsless2wPOS, 'infantsabove2m' => $infantsabove2m,
			'infantsabove2mPOs' => $infantsabove2mPOS, 'adults' => $adulttests,
			'adultsPOs' => $adulttestsPOS, 'actualinfants' => $patienttests,
			'actualinfantsPOs' => $patienttestsPOS, 'pos' => $pos, 'neg' => $neg,
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

    	$today=date("Y-m-d");

    	$div_array;
    	$array_size = 0;

    	$divs = $data = DB::connection('eid')
		->table('labs')->select('ID')->get();

		foreach ($divs as $key => $value) {
			$div_array[$key] = $value->ID;
			$array_size++;
		}

		$division = "samples.labtestedin";
		$column = "labtestedin";

		$noofbatches= $n->GettotalbatchesPerlab($year, $division);

		$received_a = $n->OverallReceivedSamples($year, $division);
		$testedsamples = $n->OverallTestedSamples($year, $division);
		$alltestedsamples = $n->CumulativeTestedSamples($year, $division);
		$EQAtestedsamples = $n->OverallEQATestedSamples($year, $division);
		$confirmdna_a = $n->OveralldnasecondTestedSamples($year, $division);

		$posrepeats_a = $n->OverallPosRepeatsTestedSamples($year, $division);
		$rejectedsamples = $n->Getnationalrejectedsamples($year, $division);

		$pos_a = $n->OverallTestedSamplesOutcomes($year, 2, $division);
		$neg_a = $n->OverallTestedSamplesOutcomes($year, 1, $division);
		$fail_a = $n->OverallTestedSamplesOutcomes($year, 5, $division);
		$redraws_a = $n->OverallTestedSamplesOutcomes($year, 3, $division);
		
		$facilityssupported = $n->GettotalEIDsitesbytimeperiod($year, $division);

		$tat = $n->GetNatTATs($year, $div_array, $division, $column);
		$tat = collect($tat);

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
				
				$eqatests = $this->checknull($EQAtestedsamples->where('month', $month)->where($column, $div_array[$it]));

				$pos = $this->checknull($pos_a->where('month', $month)->where($column, $div_array[$it]));
				$neg = $this->checknull($neg_a->where('month', $month)->where($column, $div_array[$it]));
				$fail = $this->checknull($fail_a->where('month', $month)->where($column, $div_array[$it]));
				$redraws = $this->checknull($redraws_a->where('month', $month)->where($column, $div_array[$it]));
				$failed = $fail+$redraws;

				$batches = $this->checknull($noofbatches->where('month', $month)->where($column, $div_array[$it]));

				$rej = $this->checknull($rejectedsamples->where('month', $month)->where($column, $div_array[$it]));

				$sitesending = $this->checknull($facilityssupported->where('month', $month)->where($column, $div_array[$it]));

				$tt = $this->checktat($tat->where('month', $month)->where('division', $div_array[$it]));

				$data_array = array(
					'received' => $received, 'alltests' => $alltests, 'tests' => $tests,
					'confirmdna' => $confirmdna, 'eqatests' => $eqatests, 
					'repeatspos' => $posrepeats, 'pos' => $pos, 'neg' => $neg,
					'redraw' => $failed, 'batches' => $batches, 'rejected' => $rej,
					'sitessending' => $sitesending,
					'tat1' => $tt['tat1'], 'tat2' => $tt['tat2'], 'tat3' => $tt['tat3'], 
					'tat4' => $tt['tat4'], 'dateupdated' => $today
				);

				DB::table("lab_summary")->where('year', $year)->where('month', $month)->where("lab", $div_array[$it])->update($data_array);

				// $sql = "UPDATE lab_summary set received='$received',alltests='$alltestedsamples', tests='$testedsamples' ,confirmdna='$confirmdna',repeatspos='$posrepeats',  pos='$positives', neg='$negatives', redraw='$failed',eqatests='$EQAtestedsamples',batches='$noofbatches', rejected='$rejectedsamples', sitessending='$facilityssupported', tat1='$t1',tat2='$t2',tat3='$t3',tat4='$t4',sorted=15  WHERE lab='$maArray[$mrow]' AND  month='$month' AND year='$year'  ";

			}

		}
		echo "\n Completed eid lab update at " . date('d/m/Y h:i:s a', time());
    }


    // Will be used to enter data for divisions except labs
    // Types: 1=county, 2=subcounty, 3=partner, 4=sites
    public function division_updator($year=null, $type=1, $column='county', $division='view_facilitys.county', $div_table='countys', $sum_table='county_summary', $age_table='county_agebreakdown', $ir_table='county_iprophylaxis', $mr_table='county_mprophylaxis', $ent_table='county_entrypoint'){

    	if($year == null){
    		$year = Date('Y');
    	}

    	// Instantiate new object
    	$n = new EidDivision;

    	$today=date("Y-m-d");

    	$div_array;
    	$array_size = 0;

    	$divs = $data = DB::connection('eid')
		->table($div_table)->select('ID')->get();

		foreach ($divs as $key => $value) {
			$div_array[$key] = $value->ID;
			$array_size++;
		}

		echo "\n Begin {$column} update at " . date('d/m/Y h:i:s a', time());

		// Get collection instances of the data
    	$alltests_a = $n->CumulativeTestedSamples($year, $division);
    	$eqatests_a = $n->OverallEQATestedSamples($year, $division);

    	$tests_a = $n->OverallTestedSamples($year, $division);
		
		$patienttests_a = $n->OverallTestedPatients($year, $division);
		$patienttestsPOS_a = $n->OverallTestedPatientsPOS($year, $division);
		$received_a = $n->OverallReceivedSamples($year, $division);
			
		$firstdna_a = $n->OveralldnafirstTestedSamples($year, $division);
		$confirmdna_a = $n->OveralldnasecondTestedSamples($year, $division);
		$posrepeats_a = $n->OverallPosRepeatsTestedSamples($year, $division);

		$confirmdnaPOS_a = $n->OveralldnasecondTestedSamplesPOS($year, $division);
		$posrepeatsPOS_a = $n->OverallPosRepeatsTestedSamplesPOS($year, $division);
		//$confimPOs =$confirmdnaPOS + $posrepeatsPOS;


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
		

		$pos_a = $n->OverallTestedSamplesOutcomes($year, 2, $division);
		$neg_a = $n->OverallTestedSamplesOutcomes($year, 1, $division);
		$fail_a = $n->OverallTestedSamplesOutcomes($year, 3, $division);
		$rd_a = $n->OverallTestedSamplesOutcomes($year, 5, $division);
		$rdd_a = $n->OverallTestedSamplesOutcomes($year, 6, $division);
		// $redraw=$fail + $rd + $rdd;

		$rej_a = $n->Getnationalrejectedsamples($year, $division);
		
		$v = "samples.enrollmentstatus";
		$enrolled_a = $n->GetHEIFollowUpNational($year, 1, $v, $division);
		$ltfu_a = $n->GetHEIFollowUpNational($year, 2, $v, $division);
		$dead_a = $n->GetHEIFollowUpNational($year, 3, $v, $division);
		$adult_a = $n->GetHEIFollowUpNational($year, 4, $v, $division);
		$transout_a = $n->GetHEIFollowUpNational($year, 5, $v, $division);
		$other_a = $n->GetHEIFollowUpNational($year, 6, $v, $division);


		$v = 'samples.hei_validation';
		$v_cp_a = $n->GetHEIFollowUpNational($year, 1, $v, $division); //confirmedpos	
		$v_ad_a = $n->GetHEIFollowUpNational($year, 2, $v, $division); //adult
		$v_vl_a = $n->GetHEIFollowUpNational($year, 3, $v, $division); //vl
		$v_rp_a = $n->GetHEIFollowUpNational($year, 4, $v, $division); //repeat
		$v_uf_a = $n->GetHEIFollowUpNational($year, 5, $v, $division); //unknownfacility		
		
		$sitesending_a = $n->GettotalEIDsitesbytimeperiod($year, $division);
		$avgage_a = $n->Getoverallaverageage($year, $division);
		$medage_a = $n->Getoverallmedianage($year, $div_array, $division, $column);
		$medage_a = collect($medage_a);

		if($type != 3){
			$tat = $n->GetNatTATs($year, $div_array, $division, $column);
			$tat = collect($tat);
		}
		

		// Loop through the months and insert data into the division summary
		for ($i=0; $i < 12; $i++) { 
			$month = $i + 1;

			if($year == Date('Y') && $month > Date('m')){ break; }

			// Loop through divisions i.e. counties, subcounties, partners and sites
			for ($it=0; $it < $array_size; $it++) { 
				$alltests = $this->checknull($alltests_a->where('month', $month)->where($column, $div_array[$it]));
				$eqatests = $this->checknull($eqatests_a->where('month', $month)->where($column, $div_array[$it]));
				$tests = $this->checknull($tests_a->where('month', $month)->where($column, $div_array[$it]));
				$patienttests = $this->checknull($patienttests_a->where('month', $month)->where($column, $div_array[$it]));
				$patienttestsPOS = $this->checknull($patienttestsPOS_a->where('month', $month)->where($column, $div_array[$it]));

				$received = $this->checknull($received_a->where('month', $month)->where($column, $div_array[$it]));
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

				if($type != 3){
					$tt = $this->checktat($tat->where('month', $month)->where('division', $div_array[$it]));
				}

				$data_array = array(
					'avgage' => $avgage,	'medage' => $medage,	'received' => $received,
					'alltests' => $alltests, 'eqatests' => $eqatests, 'tests' => $tests,
					'firstdna' => $firstdna, 'confirmdna' => $confirmdna, 'repeatspos' => $posrepeats,
					'confirmedPOs' => $confirmdnaPOS, 'repeatposPOS' => $posrepeatsPOS,
					'infantsless2m' => $infantsless2m,
					'infantsless2mPOs' => $infantsless2mPOS, 'infantsless2w' => $infantsless2w,
					'infantsless2mPOs' => $infantsless2wPOS, 'infantsabove2m' => $infantsabove2m,
					'infantsabove2mPOs' => $infantsabove2mPOS, 'adults' => $adulttests,
					'adultsPOs' => $adulttestsPOS, 'actualinfants' => $patienttests,
					'actualinfantsPOs' => $patienttestsPOS, 'pos' => $pos, 'neg' => $neg,
					'redraw' => $redraw, 'rejected' => $rej, 'enrolled' => $enrolled, 'dead' => $dead,
					'ltfu' => $ltfu, 'adult' => $adult, 'transout' => $transout, 'other' => $other,
					'validation_confirmedpos' => $v_cp, 'validation_repeattest' => $v_ad,
					'validation_viralload' => $v_vl, 'validation_adult' => $v_rp,
					'validation_unknownsite' => $v_uf, 'sitessending' => $sitesending,
					 'dateupdated' => $today
				);

				if($type != 3){
					$turn = array('tat1' => $tt['tat1'], 'tat2' => $tt['tat2'], 'tat3' => $tt['tat3'], 'tat4' => $tt['tat4']);
					$data_array = array_merge($data_array, $turn);
				}

				DB::table($sum_table)->where('year', $year)->where('month', $month)->where($column, $div_array[$it])->update($data_array);

			}
			// End of division loop
			
		}
		// End of summary

		echo "\n Completed entry into eid {$column} summary at " . date('d/m/Y h:i:s a', time());

		if($type == 4){ 
			return "Success";
		}



		// Set the following to null in order to free memory
		$alltests_a = $eqatests_a = $tests_a = $patienttests_a = $patienttestsPOS_a = $received_a = $firstdna_a = $confirmdna_a = $posrepeats_a = $confirmdnaPOS_a = $posrepeatsPOS_a = $infantsless2m_a = $infantsless2mPOS_a = $infantsless2w_a = $infantsless2wPOS_a = $infantsless46w_a = $infantsless46wPOS_a = $infantsabove2m_a = $infantsabove2mPOS_a = $adulttests_a = $adulttestsPOS_a = $pos_a = $neg_a = $fail_a = $rd_a = $rdd_a = $rej_a = $enrolled_a = $ltfu_a = $dead_a = $adult_a = $transout_a = $other_a = $v_cp_a = $v_ad_a = $v_vl_a = $v_rp_a = $v_uf_a = $sitesending_a = $avgage_a = $medage_a = $tat = null;


		// Get national age_breakdown
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

				DB::table($age_table)->where('year', $year)->where('month', $month)->where($column, $div_array[$it])->update($data_array);
			}
			// End of division loop
		}
		// End of months loop

		echo "\n Completed entry into eid {$column} age breakdown at " . date('d/m/Y h:i:s a', time());


		// Start of infant regimen
		$iregimen = $data = DB::connection('eid')
		->table('prophylaxis')->select('ID')->where('ptype', 2)->get();

		// Loop through infant regimen
		foreach ($iregimen as $key => $value) {
			$ipos_a = $n->Getinfantprophpositivitycount($year, $value->ID, 2, $division);
			$ineg_a = $n->Getinfantprophpositivitycount($year, $value->ID, 1, $division);
			$ifail_a = $n->Getinfantprophpositivitycount($year, $value->ID, 3, $division);
			$ird_a = $n->Getinfantprophpositivitycount($year, $value->ID, 5, $division);

			// Loop through each month and update iprophylaxis
			for ($i=0; $i < 12; $i++) { 
				$month = $i + 1;

				if($year == Date('Y') && $month > Date('m')){ break; }

				// Loop through divisions
				for ($it=0; $it < $array_size; $it++) {

					$ipos = $this->checknull($ipos_a->where('month', $month)->where($column, $div_array[$it]));
					$ineg = $this->checknull($ineg_a->where('month', $month)->where($column, $div_array[$it]));
					$ifail = $this->checknull($ifail_a->where('month', $month)->where($column, $div_array[$it]));
					$ird = $this->checknull($ird_a->where('month', $month)->where($column, $div_array[$it]));

					$iredraw = $ifail + $ird;
					$itests = $ipos + $ineg +  $iredraw;

					$data_array = array(
						'tests' => $itests, 'pos' => $ipos, 'neg' => $ineg, 'redraw' => $iredraw,
						'dateupdated' => $today
					);

					DB::table($ir_table)->where('year', $year)->where('month', $month)->where($column, $div_array[$it])->where('prophylaxis', $value->ID)->update($data_array);

				}
				// End of loop through divisions
			}
			// End of loop through months			
		}
		// End of infant regimen

		echo "\n Completed entry into eid {$column} infant regimen at " . date('d/m/Y h:i:s a', time());

		// Start of mother regimen
		$mregimen = $data = DB::connection('eid')
		->table('prophylaxis')->select('ID')->where('ptype', 1)->get();

		// Loop through mother regimen
		foreach ($mregimen as $key => $value) {
			$mpos_a = $n->Getinterventionspositivitycount($year, $value->ID, 2, $division);
			$mneg_a = $n->Getinterventionspositivitycount($year, $value->ID, 1, $division);
			$mfail_a = $n->Getinterventionspositivitycount($year, $value->ID, 3, $division);
			$mrd_a = $n->Getinterventionspositivitycount($year, $value->ID, 5, $division);

			// Loop through each month and update mprophylaxis
			for ($i=0; $i < 12; $i++) { 
				$month = $i + 1;

				if($year == Date('Y') && $month > Date('m')){ break; }

				for ($it=0; $it < $array_size; $it++) {

					$mpos = $this->checknull($mpos_a->where('month', $month)->where($column, $div_array[$it]));
					$mneg = $this->checknull($mneg_a->where('month', $month)->where($column, $div_array[$it]));
					$mfail = $this->checknull($mfail_a->where('month', $month)->where($column, $div_array[$it]));
					$mrd = $this->checknull($mrd_a->where('month', $month)->where($column, $div_array[$it]));

					$mredraw=$mfail + $mrd;
					$tests=$mpos + $mneg +  $mredraw;

					$data_array = array(
						'tests' => $itests, 'pos' => $mpos, 'neg' => $mneg, 'redraw' => $mredraw,
						'dateupdated' => $today
					);

					DB::table($mr_table)->where('year', $year)->where('month', $month)->where($column, $div_array[$it])->where('prophylaxis', $value->ID)->update($data_array);

				}

			}
			
		}
		// End of mother regimen

		echo "\n Completed entry into eid {$column} mother regimen at " . date('d/m/Y h:i:s a', time());

		// Start of entrypoints
		$entrypoints = $data = DB::connection('eid')
		->table('entry_points')->select('ID')->get();

		// Loop through entrypoints
		foreach ($entrypoints as $key => $value) {
			$epos_a = $n->GetNationalResultbyEntrypoint($year, $value->ID, 2, $division);
			$eneg_a = $n->GetNationalResultbyEntrypoint($year, $value->ID, 1, $division);
			$efail_a = $n->GetNationalResultbyEntrypoint($year, $value->ID, 3, $division);
			$erd_a = $n->GetNationalResultbyEntrypoint($year, $value->ID, 5, $division);

			// Loop through each month and update entrypoints
			for ($i=0; $i < 12; $i++) { 
				$month = $i + 1;

				if($year == Date('Y') && $month > Date('m')){ break; }

				for ($it=0; $it < $array_size; $it++) {

					$epos = $this->checknull($epos_a->where('month', $month)->where($column, $div_array[$it]));
					$eneg = $this->checknull($eneg_a->where('month', $month)->where($column, $div_array[$it]));
					$efail = $this->checknull($efail_a->where('month', $month)->where($column, $div_array[$it]));
					$erd = $this->checknull($erd_a->where('month', $month)->where($column, $div_array[$it]));

					$eredraw = $efail + $erd;
					$etests = $epos + $eneg +  $eredraw;

					$data_array = array(
						'tests' => $etests, 'pos' => $epos, 'neg' => $eneg, 'redraw' => $eredraw,
						'dateupdated' => $today
					);

					DB::table($ent_table)->where('year', $year)->where('month', $month)->where($column, $div_array[$it])->where('entrypoint', $value->ID)->update($data_array);

				}

			}
			
		}
		// End of entrypoints

		echo "\n Completed entry into eid {$column} entrypoints at " . date('d/m/Y h:i:s a', time());

		// End of division updator
    }

    // Will be used to enter data for yearly divisions except labs
    // Types: 1=county, 2=subcounty, 3=partner, 4=sites
    public function division_updator_yearly($year=null, $type=1, $column='county', $division='view_facilitys.county', $div_table='countys', $sum_table='county_summary_yearly'){

    	if($year == null){
    		$year = Date('Y');
    	}

    	// Instantiate new object
    	$n = new EidDivision;

    	$today=date("Y-m-d");

    	$div_array;
    	$array_size = 0;

    	$divs = $data = DB::connection('eid')
		->table($div_table)->select('ID')->get();

		foreach ($divs as $key => $value) {
			$div_array[$key] = $value->ID;
			$array_size++;
		}

		echo "\n Begin eid {$column} summary yearly update at " . date('d/m/Y h:i:s a', time());

		// Get collection instances of the data
    	$alltests_a = $n->CumulativeTestedSamples($year, $division, false);
    	$eqatests_a = $n->OverallEQATestedSamples($year, $division, false);

    	$tests_a = $n->OverallTestedSamples($year, $division, false);
		
		$patienttests_a = $n->OverallTestedPatients($year, $division, false);
		$patienttestsPOS_a = $n->OverallTestedPatientsPOS($year, $division, false);
		$received_a = $n->OverallReceivedSamples($year, $division, false);
			
		$firstdna_a = $n->OveralldnafirstTestedSamples($year, $division, false);
		$confirmdna_a = $n->OveralldnasecondTestedSamples($year, $division, false);
		$posrepeats_a = $n->OverallPosRepeatsTestedSamples($year, $division, false);

		$confirmdnaPOS_a = $n->OveralldnasecondTestedSamplesPOS($year, $division, false);
		$posrepeatsPOS_a = $n->OverallPosRepeatsTestedSamplesPOS($year, $division, false);
		//$confimPOs =$confirmdnaPOS + $posrepeatsPOS;


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
		

		$pos_a = $n->OverallTestedSamplesOutcomes($year, 2, $division, false);
		$neg_a = $n->OverallTestedSamplesOutcomes($year, 1, $division, false);
		$fail_a = $n->OverallTestedSamplesOutcomes($year, 3, $division, false);
		$rd_a = $n->OverallTestedSamplesOutcomes($year, 5, $division, false);
		$rdd_a = $n->OverallTestedSamplesOutcomes($year, 6, $division, false);
		// $redraw=$fail + $rd + $rdd;

		$rej_a = $n->Getnationalrejectedsamples($year, $division, false);
		
		$v = "samples.enrollmentstatus";
		$enrolled_a = $n->GetHEIFollowUpNational($year, 1, $v, $division, false);
		$ltfu_a = $n->GetHEIFollowUpNational($year, 2, $v, $division, false);
		$dead_a = $n->GetHEIFollowUpNational($year, 3, $v, $division, false);
		$adult_a = $n->GetHEIFollowUpNational($year, 4, $v, $division, false);
		$transout_a = $n->GetHEIFollowUpNational($year, 5, $v, $division, false);
		$other_a = $n->GetHEIFollowUpNational($year, 6, $v, $division, false);


		$v = 'samples.hei_validation';
		$v_cp_a = $n->GetHEIFollowUpNational($year, 1, $v, $division, false); //confirmedpos	
		$v_ad_a = $n->GetHEIFollowUpNational($year, 2, $v, $division, false); //adult
		$v_vl_a = $n->GetHEIFollowUpNational($year, 3, $v, $division, false); //vl
		$v_rp_a = $n->GetHEIFollowUpNational($year, 4, $v, $division, false); //repeat
		$v_uf_a = $n->GetHEIFollowUpNational($year, 5, $v, $division, false); //unknownfacility		
		
		$sitesending_a = $n->GettotalEIDsitesbytimeperiod($year, $division, false);
		$avgage_a = $n->Getoverallaverageage($year, $division, false);
		$medage_a = $n->Getoverallmedianage($year, $div_array, $division, $column, false);
		$medage_a = collect($medage_a);

		$tat = $n->GetNatTATs($year, $div_array, $division, $column, false);
		$tat = collect($tat);
		

		// Loop through divisions i.e. counties, subcounties, partners and sites
		for ($it=0; $it < $array_size; $it++) { 
			$alltests = $this->checknull($alltests_a->where($column, $div_array[$it]));
			$eqatests = $this->checknull($eqatests_a->where($column, $div_array[$it]));
			$tests = $this->checknull($tests_a->where($column, $div_array[$it]));
			$patienttests = $this->checknull($patienttests_a->where($column, $div_array[$it]));
			$patienttestsPOS = $this->checknull($patienttestsPOS_a->where($column, $div_array[$it]));

			$received = $this->checknull($received_a->where($column, $div_array[$it]));
			$firstdna = $this->checknull($firstdna_a->where($column, $div_array[$it]));
			$confirmdna = $this->checknull($confirmdna_a->where($column, $div_array[$it]));
			$posrepeats = $this->checknull($posrepeats_a->where($column, $div_array[$it]));
			$confirmdnaPOS = $this->checknull($confirmdnaPOS_a->where($column, $div_array[$it]));
			$posrepeatsPOS = $this->checknull($posrepeatsPOS_a->where($column, $div_array[$it]));
			$confimPOS = $confirmdnaPOS + $posrepeatsPOS;

			$infantsless2m = $this->checknull($infantsless2m_a->where($column, $div_array[$it]));
			$infantsless2mPOS = $this->checknull($infantsless2mPOS_a->where($column, $div_array[$it]));
			$infantsless2w = $this->checknull($infantsless2w_a->where($column, $div_array[$it]));
			$infantsless2wPOS = $this->checknull($infantsless2wPOS_a->where($column, $div_array[$it]));
			$infantsless46w = $this->checknull($infantsless46w_a->where($column, $div_array[$it]));
			$infantsless46wPOS = $this->checknull($infantsless46wPOS_a->where($column, $div_array[$it]));
			$infantsabove2m = $this->checknull($infantsabove2m_a->where($column, $div_array[$it]));
			$infantsabove2mPOS = $this->checknull($infantsabove2mPOS_a->where($column, $div_array[$it]));
			$adulttests = $this->checknull($adulttests_a->where($column, $div_array[$it]));
			$adulttestsPOS = $this->checknull($adulttestsPOS_a->where($column, $div_array[$it]));


			$pos = $this->checknull($pos_a->where($column, $div_array[$it]));
			$neg = $this->checknull($neg_a->where($column, $div_array[$it]));
			$fail = $this->checknull($fail_a->where($column, $div_array[$it]));
			$rd = $this->checknull($rd_a->where($column, $div_array[$it]));
			$rdd = $this->checknull($rdd_a->where($column, $div_array[$it]));
			$redraw = $fail + $rd + $rdd;

			$rej = $this->checknull($rej_a->where($column, $div_array[$it]));
			$enrolled = $this->checknull($enrolled_a->where($column, $div_array[$it]));
			$ltfu = $this->checknull($ltfu_a->where($column, $div_array[$it]));
			$dead = $this->checknull($dead_a->where($column, $div_array[$it]));
			$adult = $this->checknull($adult_a->where($column, $div_array[$it]));
			$transout = $this->checknull($transout_a->where($column, $div_array[$it]));
			$other = $this->checknull($other_a->where($column, $div_array[$it]));

			$v_cp = $this->checknull($v_cp_a->where($column, $div_array[$it]));
			$v_ad = $this->checknull($v_ad_a->where($column, $div_array[$it]));
			$v_vl = $this->checknull($v_vl_a->where($column, $div_array[$it]));
			$v_rp = $this->checknull($v_rp_a->where($column, $div_array[$it]));
			$v_uf = $this->checknull($v_uf_a->where($column, $div_array[$it]));

			$sitesending = $this->checknull($sitesending_a->where($column, $div_array[$it]));
			$avgage = $this->checknull($avgage_a->where($column, $div_array[$it]));
			$medage = $this->checkmedage($medage_a->where('division', $div_array[$it]));

			$tt = $this->checktat($tat->where('division', $div_array[$it]));

			$data_array = array(
				'avgage' => $avgage,	'medage' => $medage,	'received' => $received,
				'alltests' => $alltests, 'eqatests' => $eqatests, 'tests' => $tests,
				'firstdna' => $firstdna, 'confirmdna' => $confirmdna, 'repeatspos' => $posrepeats,
				'confirmedPOs' => $confirmdnaPOS, 'repeatposPOS' => $posrepeatsPOS,
				'infantsless2m' => $infantsless2m,
				'infantsless2mPOs' => $infantsless2mPOS, 'infantsless2w' => $infantsless2w,
				'infantsless2mPOs' => $infantsless2wPOS, 'infantsabove2m' => $infantsabove2m,
				'infantsabove2mPOs' => $infantsabove2mPOS, 'adults' => $adulttests,
				'adultsPOs' => $adulttestsPOS, 'actualinfants' => $patienttests,
				'actualinfantsPOs' => $patienttestsPOS, 'pos' => $pos, 'neg' => $neg,
				'redraw' => $redraw, 'rejected' => $rej, 'enrolled' => $enrolled, 'dead' => $dead,
				'ltfu' => $ltfu, 'adult' => $adult, 'transout' => $transout, 'other' => $other,
				'validation_confirmedpos' => $v_cp, 'validation_repeattest' => $v_ad,
				'validation_viralload' => $v_vl, 'validation_adult' => $v_rp,
				'validation_unknownsite' => $v_uf, 'sitessending' => $sitesending,
				 'dateupdated' => $today
			);

			if($type != 3){
				$turn = array('tat1' => $tt['tat1'], 'tat2' => $tt['tat2'], 'tat3' => $tt['tat3'], 'tat4' => $tt['tat4']);
				$data_array = array_merge($data_array, $turn);
			}

			DB::table($sum_table)->where('year', $year)->where($column, $div_array[$it])->update($data_array);

		}
		// End of division loop
			

		echo "\n Completed entry into eid {$column} summary yearly at " . date('d/m/Y h:i:s a', time());
	}

    public function update_counties($year = null){
    	return $this->division_updator($year, 1, 'county', 'view_facilitys.county', 'countys', 'county_summary', 'county_agebreakdown', 'county_iprophylaxis', 'county_mprophylaxis', 'county_entrypoint');
    }

    public function update_subcounties($year = null){
    	return $this->division_updator($year, 2, 'subcounty', 'view_facilitys.subcounty', 'districts', 'subcounty_summary', 'subcounty_agebreakdown', 'subcounty_iprophylaxis', 'subcounty_mprophylaxis', 'subcounty_entrypoint');
    }

    public function update_partners($year = null){
    	return $this->division_updator($year, 3, 'partner', 'view_facilitys.partner', 'partners', 'ip_summary', 'ip_agebreakdown', 'ip_iprophylaxis', 'ip_mprophylaxis', 'ip_entrypoint');
    }

    public function update_facilities($year = null){
    	return $this->division_updator($year, 4, 'facility', 'samples.facility', 'facilitys', 'site_summary');
    }


    public function update_counties_yearly($year = null){
    	return $this->division_updator_yearly($year, 1, 'county', 'view_facilitys.county', 'countys', 'county_summary_yearly');
    }

    public function update_subcounties_yearly($year = null){
    	return $this->division_updator_yearly($year, 2, 'subcounty', 'view_facilitys.subcounty', 'districts', 'subcounty_summary_yearly');
    }

    public function update_partners_yearly($year = null){
    	return $this->division_updator_yearly($year, 3, 'partner', 'view_facilitys.partner', 'partners', 'ip_summary_yearly');
    }

    public function update_facilities_yearly($year = null){
    	return $this->division_updator_yearly($year, 4, 'facility', 'samples.facility', 'facilitys', 'site_summary_yearly');
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

    public function checkmedage($var){
    	if($var->isEmpty()){
    		return 0;
    	}else{
    		$a = $var->first();
    		return $a['totals'];
    	}
    }









   
}
