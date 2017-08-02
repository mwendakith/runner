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
    	$b = new BaseModel;
    	$n = new EidNation;

    	$alltests = $n->CumulativeTestedSamples($year);
    	$eqatests = $n->OverallEQATestedSamples($year);

    	$tests = $n->OverallTestedSamples($year);
		
		$patienttests = $n->OverallTestedPatients($year);
		$patienttestsPOS = $n->OverallTestedPatientsPOS($year);
		$received = $n->OverallReceivedSamples($year);
			
		$firstdna = $n->OveralldnafirstTestedSamples($year);
		$confirmdna = $n->OveralldnasecondTestedSamples($year);
		$posrepeats = $n->OverallPosRepeatsTestedSamples($year);

		$confirmdnaPOS = $n->OveralldnasecondTestedSamplesPOS($year);
		$posrepeatsPOS = $n->OverallPosRepeatsTestedSamplesPOS($year);
		//$confimPOS=$confirmdnaPOS + $posrepeatsPOS;


		$infantsless2m = 		$n->Gettestedsamplescountrange($year, 1);
		$infantsless2mPOS = 	$n->Gettestedsamplescountrange($year, 1, true);
		$infantsless2w =		$n->Gettestedsamplescountrange($year, 3);
		$infantsless2wPOS =		$n->Gettestedsamplescountrange($year, 3, true);
		$infantsless46w =		$n->Gettestedsamplescountrange($year, 4);
		$infantsless46wPOS =	$n->Gettestedsamplescountrange($year, 4, true);
		$infantsabove2m =		$n->Gettestedsamplescountrange($year, 2);
		$infantsabove2mPOS = 	$n->Gettestedsamplescountrange($year, 2, true);
		$adulttests =			$n->Gettestedsamplescountrange($year, 5);
		$adulttestsPOS =		$n->Gettestedsamplescountrange($year, 5, true);
		

		$pos = $n->OverallTestedSamplesOutcomes($year, 2);
		$neg = $n->OverallTestedSamplesOutcomes($year, 1);
		$fail = $n->OverallTestedSamplesOutcomes($year, 3);
		$rd = $n->OverallTestedSamplesOutcomes($year, 5);
		$rdd = $n->OverallTestedSamplesOutcomes($year, 6);
		$redraw=$fail + $rd + $rdd;
		
		//echo $alltests;
		$rej = $n->Getnationalrejectedsamples($year);
		
		$enrolled = $n->GetHEIFollowUpNational($year, 1);
		$ltfu = $n->GetHEIFollowUpNational($year, 2);
		$dead = $n->GetHEIFollowUpNational($year, 3);
		$adult = $n->GetHEIFollowUpNational($year, 4);
		$transout = $n->GetHEIFollowUpNational($year, 5);
		$other = $n->GetHEIFollowUpNational($year, 6);


		$v = 'samples.hei_validation';
		$v_cp = $n->GetHEIFollowUpNational($year, 1, $v); //confirmedpos	
		$v_ad = $n->GetHEIFollowUpNational($year, 2, $v); //adult
		$v_vl = $n->GetHEIFollowUpNational($year, 3, $v); //vl
		$v_rp = $n->GetHEIFollowUpNational($year, 4, $v); //repeat
		$v_uf = $n->GetHEIFollowUpNational($year, 5, $v); //unknownfacility		
		
		$sitesending = GettotalEIDsitesbytimeperiod($year,$month);
		$avgage=Getoverallaverageage($year,$month);
		$medage=Getoverallmedianage($year,$month);
		
		
		 $t1=GetNatTATs(1,$month,$year);
		 $t2=GetNatTATs(2,$month,$year);
		 $t3=GetNatTATs(3,$month,$year);
		 $t4=GetNatTATs(4,$month,$year);

    }
}
