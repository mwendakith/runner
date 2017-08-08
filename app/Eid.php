<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\EidNation;
use App\EidDivision;

class Eid extends Model
{
    //
    public function update_nation($year = null){
    	if($year == null){
    		$year = Date('Y');
    	}
    	// Instantiate new object
    	$n = new EidNation;

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
		$infantsless2wPOS_a =		$n->Gettestedsamplescountrange($year, 3, true);
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

		$count = $pos_a->count();

		// Loop through the months and insert data into the national summary
		for ($i=0; $i < $count; $i++) { 
			$month = $i + 1;

			$alltests = $alltests_a->where('month', $month)->first()->totals;
			$eqatests = $eqatests_a->where('month', $month)->first()->totals;
			$tests = $tests_a->where('month', $month)->first()->totals;
			$patienttests = $patienttests_a->where('month', $month)->first()->totals;
			$patienttestsPOS = $patienttestsPOS_a->where('month', $month)->first()->totals;

			$received = $received_a->where('month', $month)->first()->totals;
			$firstdna = $firstdna_a->where('month', $month)->first()->totals;
			$confirmdna = $confirmdna_a->where('month', $month)->first()->totals;
			$posrepeats = $posrepeats_a->where('month', $month)->first()->totals;
			$confirmdnaPOS = $confirmdnaPOS_a->where('month', $month)->first()->totals;
			$posrepeatsPOS = $posrepeatsPOS_a->where('month', $month)->first()->totals;
			$confimPOs =$confirmdnaPOS + $posrepeatsPOS;

			$infantsless2m = $infantsless2m_a->where('month', $month)->first()->totals;
			$infantsless2mPOS = $infantsless2mPOS_a->where('month', $month)->first()->totals;
			$infantsless2w = $infantsless2w_a->where('month', $month)->first()->totals;
			$infantsless2wPOS = $infantsless2wPOS_a->where('month', $month)->first()->totals;
			$infantsless46w = $infantsless46w_a->where('month', $month)->first()->totals;
			$infantsless46wPOS = $infantsless46wPOS_a->where('month', $month)->first()->totals;
			$infantsabove2m = $infantsabove2m_a->where('month', $month)->first()->totals;
			$infantsabove2mPOS = $infantsabove2mPOS_a->where('month', $month)->first()->totals;
			$adulttests = $adulttests_a->where('month', $month)->first()->totals;
			$adulttestsPOS = $adulttestsPOS_a->where('month', $month)->first()->totals;


			$pos = $pos_a->where('month', $month)->first()->totals;
			$neg = $neg_a->where('month', $month)->first()->totals;
			$fail = $fail_a->where('month', $month)->first()->totals;
			$rd = $rd_a->where('month', $month)->first()->totals;
			$rdd = $rdd_a->where('month', $month)->first()->totals;
			$redraw = $fail + $rd + $rdd;

			$rej = $rej_a->where('month', $month)->first()->totals;
			$enrolled = $enrolled_a->where('month', $month)->first()->totals;
			$ltfu = $ltfu_a->where('month', $month)->first()->totals;
			$dead = $dead_a->where('month', $month)->first()->totals;
			$adult = $adult_a->where('month', $month)->first()->totals;
			$transout = $transout_a->where('month', $month)->first()->totals;
			$other = $other_a->where('month', $month)->first()->totals;

			$v_cp = $v_cp_a->where('month', $month)->first()->totals;
			$v_ad = $v_ad_a->where('month', $month)->first()->totals;
			$v_vl = $v_vl_a->where('month', $month)->first()->totals;
			$v_rp = $v_rp_a->where('month', $month)->first()->totals;
			$v_uf = $v_uf_a->where('month', $month)->first()->totals;

			$sitesending = $sitesending_a->where('month', $month)->first()->totals;
			$avgage = $avgage_a->where('month', $month)->first()->totals;
			$medage = $medage_a[$i];

			$tt = $tat[$i];

			$sql = "UPDATE national_summary set avgage='$avgage', medage='$medage',received='$received' , alltests ='$alltests' , eqatests ='$eqatests' , tests ='$tests',firstdna='$firstdna',confirmdna='$confirmdna',repeatspos ='$posrepeats',confirmedPOs ='$confimPOS',infantsless2m='$infantsless2m'  ,infantsless2mPOs ='$infantsless2mPOS' ,infantsless2w='$infantsless2w'  ,infantsless2wPOs ='$infantsless2wPOS',infants4to6w='$infantsless46w'  ,infants4to6wPOs ='$infantsless46wPOS',infantsabove2m='$infantsabove2m', infantsabove2mPOs ='$infantsabove2mPOS',adults ='$adulttests',adultsPOs ='$adulttestsPOS' ,actualinfants ='$patienttests', actualinfantsPOs ='$patienttestsPOS',pos ='$pos',neg ='$neg',redraw='$redraw',rejected='$rej',enrolled='$enrolled',dead='$dead',ltfu='$ltfu',adult='$adult',transout='$transout',	 other='$other' ,validation_confirmedpos ='$v_cp',validation_repeattest='$v_ad',validation_viralload='$v_vl',validation_adult='$v_rp',validation_unknownsite='$v_uf',sitessending ='$sitesending', tat1='$t1', tat2='$t2', tat3='$t3', tat4='$t4',sorted=15   WHERE month='$month' AND year='$year' ";

			


		}
		// End of for loop


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

			$age1pos = $age1pos_a->where('month', $month)->first()->totals;
			$age1neg = $age1neg_a->where('month', $month)->first()->totals;
			$age2pos = $age1pos_a->where('month', $month)->first()->totals;
			$age2neg = $age1neg_a->where('month', $month)->first()->totals;
			$age3pos = $age1pos_a->where('month', $month)->first()->totals;
			$age3neg = $age1neg_a->where('month', $month)->first()->totals;
			$age4pos = $age1pos_a->where('month', $month)->first()->totals;
			$age4neg = $age1neg_a->where('month', $month)->first()->totals;

			$age6pos = $age1pos_a->where('month', $month)->first()->totals;
			$age6neg = $age1neg_a->where('month', $month)->first()->totals;

			$age9pos = $age1pos_a->where('month', $month)->first()->totals;
			$age9neg = $age1neg_a->where('month', $month)->first()->totals;
			$age10pos = $age1pos_a->where('month', $month)->first()->totals;
			$age10neg = $age1neg_a->where('month', $month)->first()->totals;
			$age11pos = $age1pos_a->where('month', $month)->first()->totals;
			$age11neg = $age1neg_a->where('month', $month)->first()->totals;
			$age12pos = $age1pos_a->where('month', $month)->first()->totals;
			$age12neg = $age1neg_a->where('month', $month)->first()->totals;
			$age13pos = $age1pos_a->where('month', $month)->first()->totals;
			$age13neg = $age1neg_a->where('month', $month)->first()->totals;
			$age14pos = $age1pos_a->where('month', $month)->first()->totals;
			$age14neg = $age1neg_a->where('month', $month)->first()->totals;



			$sql = "UPDATE national_agebreakdown set sixweekspos='$age1pos', sixweeksneg='$age1neg', sevento3mpos='$age2pos', sevento3mneg='$age2neg'	,threemto9mpos='$age3pos',threemto9mneg='$age3neg',ninemto18mpos='$age4pos',ninemto18mneg='$age4neg',above18mpos='$age5pos',above18mneg='$age5neg',nodatapos='$age6pos',nodataneg='$age6neg', less2wpos='$age9pos',less2wneg='$age9neg',twoto6wpos='$age10pos',twoto6wneg='$age10neg',sixto8wpos='$age11pos',sixto8wneg='$age11neg',sixmonthpos='$age12pos',sixmonthneg='$age12neg',ninemonthpos='$age13pos',ninemonthneg='$age13neg',twelvemonthpos='$age14pos',twelvemonthneg='$age14neg',sorted=9 WHERE month='$month' AND year='$year'";

		}
		// End of for loop


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

				$ipos = $ipos_a->where('month', $month)->first()->totals;
				$ineg = $ineg_a->where('month', $month)->first()->totals;
				$ifail = $ifail_a->where('month', $month)->first()->totals;
				$ird = $ird_a->where('month', $month)->first()->totals;

				$iredraw = $ifail + $ird;
				$itests = $ipos + $ineg +  $iredraw;

				$sql = "UPDATE national_iprophylaxis set tests='$itests', pos='$ipos', neg='$ineg', redraw='$iredraw',sorted=9 WHERE prophylaxis='$iaArray[$irow]' AND month='$month' AND year='$year'";

			}
			
		}
		// End of infant regimen

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

				$mpos = $mpos_a->where('month', $month)->first()->totals;
				$mneg = $mneg_a->where('month', $month)->first()->totals;
				$mfail = $mfail_a->where('month', $month)->first()->totals;
				$mrd = $mrd_a->where('month', $month)->first()->totals;

				$mredraw=$mfail + $mrd;
				$tests=$mpos + $mneg +  $mredraw;

				$sql = "UPDATE national_mprophylaxis set tests='$tests', pos='$mpos', neg='$mneg', redraw='$mredraw',sorted=9 WHERE prophylaxis='$maArray[$mrow]' AND  month='$month' AND year='$year'  ";

			}
			
		}
		// End of mother regimen

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

				$epos = $epos_a->where('month', $month)->first()->totals;
				$eneg = $eneg_a->where('month', $month)->first()->totals;
				$efail = $efail_a->where('month', $month)->first()->totals;
				$erd = $erd_a->where('month', $month)->first()->totals;

				$eredraw = $efail + $erd;
				$etests = $epos + $eneg +  $eredraw;

				$sql = "UPDATE national_entrypoint set tests='$etests', pos='$epos', neg='$eneg', redraw='$eredraw',sorted=9 WHERE entrypoint='$aArray[$row]' AND month='$month' AND year='$year'  ";

			}
			
		}
		// End of entrypoints

		// End of national function
    }







    // Will be used to enter data for divisions except labs
    // Types: 1=county, 2=subcounty, 3=partner, 4=sites
    public function division_updator($year=null, $type=1, $column='county', $division='view_facilitys.county', $div_table='countys', $sum_table='county_summary', $age_table='county_agebreakdown', $ir_table='county_iprophylaxis', $mr_table='county_mprophylaxis', $ent_table='county_entrypoint'){

    	if($year == null){
    		$year = Date('Y');
    	}

    	// Instantiate new object
    	$n = new EidDivision;
    	$div_array;
    	$array_size = 0;

    	$divs = $data = DB::connection('eid')
		->table($div_table)->select('ID')->get();

		foreach ($divs as $key => $value) {
			$div_array[$key] = $value->ID;
			$array_size++;
		}

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
		$infantsless2wPOS_a =		$n->Gettestedsamplescountrange($year, 3, true);
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
		$tat = collect($tat);
		

		// Loop through the months and insert data into the division summary
		for ($i=0; $i < 12; $i++) { 
			$month = $i + 1;

			for ($it=0; $it < $array_size; $it++) { 
				$alltests = $alltests_a->where('month', $month)->first()->totals;
				$eqatests = $eqatests_a->where('month', $month)->first()->totals;
				$tests = $tests_a->where('month', $month)->first()->totals;
				$patienttests = $patienttests_a->where('month', $month)->first()->totals;
				$patienttestsPOS = $patienttestsPOS_a->where('month', $month)->first()->totals;

				$received = $received_a->where('month', $month)->first()->totals;
				$firstdna = $firstdna_a->where('month', $month)->first()->totals;
				$confirmdna = $confirmdna_a->where('month', $month)->first()->totals;
				$posrepeats = $posrepeats_a->where('month', $month)->first()->totals;
				$confirmdnaPOS = $confirmdnaPOS_a->where('month', $month)->first()->totals;
				$posrepeatsPOS = $posrepeatsPOS_a->where('month', $month)->first()->totals;
				$confimPOs =$confirmdnaPOS + $posrepeatsPOS;

				$infantsless2m = $infantsless2m_a->where('month', $month)->first()->totals;
				$infantsless2mPOS = $infantsless2mPOS_a->where('month', $month)->first()->totals;
				$infantsless2w = $infantsless2w_a->where('month', $month)->first()->totals;
				$infantsless2wPOS = $infantsless2wPOS_a->where('month', $month)->first()->totals;
				$infantsless46w = $infantsless46w_a->where('month', $month)->first()->totals;
				$infantsless46wPOS = $infantsless46wPOS_a->where('month', $month)->first()->totals;
				$infantsabove2m = $infantsabove2m_a->where('month', $month)->first()->totals;
				$infantsabove2mPOS = $infantsabove2mPOS_a->where('month', $month)->first()->totals;
				$adulttests = $adulttests_a->where('month', $month)->first()->totals;
				$adulttestsPOS = $adulttestsPOS_a->where('month', $month)->first()->totals;


				$pos = $pos_a->where('month', $month)->first()->totals;
				$neg = $neg_a->where('month', $month)->first()->totals;
				$fail = $fail_a->where('month', $month)->first()->totals;
				$rd = $rd_a->where('month', $month)->first()->totals;
				$rdd = $rdd_a->where('month', $month)->first()->totals;
				$redraw = $fail + $rd + $rdd;

				$rej = $rej_a->where('month', $month)->first()->totals;
				$enrolled = $enrolled_a->where('month', $month)->first()->totals;
				$ltfu = $ltfu_a->where('month', $month)->first()->totals;
				$dead = $dead_a->where('month', $month)->first()->totals;
				$adult = $adult_a->where('month', $month)->first()->totals;
				$transout = $transout_a->where('month', $month)->first()->totals;
				$other = $other_a->where('month', $month)->first()->totals;

				$v_cp = $v_cp_a->where('month', $month)->first()->totals;
				$v_ad = $v_ad_a->where('month', $month)->first()->totals;
				$v_vl = $v_vl_a->where('month', $month)->first()->totals;
				$v_rp = $v_rp_a->where('month', $month)->first()->totals;
				$v_uf = $v_uf_a->where('month', $month)->first()->totals;

				$sitesending = $sitesending_a->where('month', $month)->first()->totals;
				$avgage = $avgage_a->where('month', $month)->first()->totals;
				$medage = $medage_a[$i];

				$tt = $tat->where('month', $month)->first();

			}
			

			$sql = "UPDATE national_summary set avgage='$avgage', medage='$medage',received='$received' , alltests ='$alltests' , eqatests ='$eqatests' , tests ='$tests',firstdna='$firstdna',confirmdna='$confirmdna',repeatspos ='$posrepeats',confirmedPOs ='$confimPOS',infantsless2m='$infantsless2m'  ,infantsless2mPOs ='$infantsless2mPOS' ,infantsless2w='$infantsless2w'  ,infantsless2wPOs ='$infantsless2wPOS',infants4to6w='$infantsless46w'  ,infants4to6wPOs ='$infantsless46wPOS',infantsabove2m='$infantsabove2m', infantsabove2mPOs ='$infantsabove2mPOS',adults ='$adulttests',adultsPOs ='$adulttestsPOS' ,actualinfants ='$patienttests', actualinfantsPOs ='$patienttestsPOS',pos ='$pos',neg ='$neg',redraw='$redraw',rejected='$rej',enrolled='$enrolled',dead='$dead',ltfu='$ltfu',adult='$adult',transout='$transout',	 other='$other' ,validation_confirmedpos ='$v_cp',validation_repeattest='$v_ad',validation_viralload='$v_vl',validation_adult='$v_rp',validation_unknownsite='$v_uf',sitessending ='$sitesending', tat1='$t1', tat2='$t2', tat3='$t3', tat4='$t4',sorted=15   WHERE month='$month' AND year='$year' ";

			


		}
		// End of for loop


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

			$age1pos = $age1pos_a->where('month', $month)->first()->totals;
			$age1neg = $age1neg_a->where('month', $month)->first()->totals;
			$age2pos = $age1pos_a->where('month', $month)->first()->totals;
			$age2neg = $age1neg_a->where('month', $month)->first()->totals;
			$age3pos = $age1pos_a->where('month', $month)->first()->totals;
			$age3neg = $age1neg_a->where('month', $month)->first()->totals;
			$age4pos = $age1pos_a->where('month', $month)->first()->totals;
			$age4neg = $age1neg_a->where('month', $month)->first()->totals;

			$age6pos = $age1pos_a->where('month', $month)->first()->totals;
			$age6neg = $age1neg_a->where('month', $month)->first()->totals;

			$age9pos = $age1pos_a->where('month', $month)->first()->totals;
			$age9neg = $age1neg_a->where('month', $month)->first()->totals;
			$age10pos = $age1pos_a->where('month', $month)->first()->totals;
			$age10neg = $age1neg_a->where('month', $month)->first()->totals;
			$age11pos = $age1pos_a->where('month', $month)->first()->totals;
			$age11neg = $age1neg_a->where('month', $month)->first()->totals;
			$age12pos = $age1pos_a->where('month', $month)->first()->totals;
			$age12neg = $age1neg_a->where('month', $month)->first()->totals;
			$age13pos = $age1pos_a->where('month', $month)->first()->totals;
			$age13neg = $age1neg_a->where('month', $month)->first()->totals;
			$age14pos = $age1pos_a->where('month', $month)->first()->totals;
			$age14neg = $age1neg_a->where('month', $month)->first()->totals;



			$sql = "UPDATE national_agebreakdown set sixweekspos='$age1pos', sixweeksneg='$age1neg', sevento3mpos='$age2pos', sevento3mneg='$age2neg'	,threemto9mpos='$age3pos',threemto9mneg='$age3neg',ninemto18mpos='$age4pos',ninemto18mneg='$age4neg',above18mpos='$age5pos',above18mneg='$age5neg',nodatapos='$age6pos',nodataneg='$age6neg', less2wpos='$age9pos',less2wneg='$age9neg',twoto6wpos='$age10pos',twoto6wneg='$age10neg',sixto8wpos='$age11pos',sixto8wneg='$age11neg',sixmonthpos='$age12pos',sixmonthneg='$age12neg',ninemonthpos='$age13pos',ninemonthneg='$age13neg',twelvemonthpos='$age14pos',twelvemonthneg='$age14neg',sorted=9 WHERE month='$month' AND year='$year'";

		}
		// End of for loop


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

				$ipos = $ipos_a->where('month', $month)->first()->totals;
				$ineg = $ineg_a->where('month', $month)->first()->totals;
				$ifail = $ifail_a->where('month', $month)->first()->totals;
				$ird = $ird_a->where('month', $month)->first()->totals;

				$iredraw = $ifail + $ird;
				$itests = $ipos + $ineg +  $iredraw;

				$sql = "UPDATE national_iprophylaxis set tests='$itests', pos='$ipos', neg='$ineg', redraw='$iredraw',sorted=9 WHERE prophylaxis='$iaArray[$irow]' AND month='$month' AND year='$year'";

			}
			
		}
		// End of infant regimen

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

				$mpos = $mpos_a->where('month', $month)->first()->totals;
				$mneg = $mneg_a->where('month', $month)->first()->totals;
				$mfail = $mfail_a->where('month', $month)->first()->totals;
				$mrd = $mrd_a->where('month', $month)->first()->totals;

				$mredraw=$mfail + $mrd;
				$tests=$mpos + $mneg +  $mredraw;

				$sql = "UPDATE national_mprophylaxis set tests='$tests', pos='$mpos', neg='$mneg', redraw='$mredraw',sorted=9 WHERE prophylaxis='$maArray[$mrow]' AND  month='$month' AND year='$year'  ";

			}
			
		}
		// End of mother regimen

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

				$epos = $epos_a->where('month', $month)->first()->totals;
				$eneg = $eneg_a->where('month', $month)->first()->totals;
				$efail = $efail_a->where('month', $month)->first()->totals;
				$erd = $erd_a->where('month', $month)->first()->totals;

				$eredraw = $efail + $erd;
				$etests = $epos + $eneg +  $eredraw;

				$sql = "UPDATE national_entrypoint set tests='$etests', pos='$epos', neg='$eneg', redraw='$eredraw',sorted=9 WHERE entrypoint='$aArray[$row]' AND month='$month' AND year='$year'  ";

			}
			
		}
		// End of entrypoints



    }
}
