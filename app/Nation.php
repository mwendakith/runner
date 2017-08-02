<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Nation extends Model
{
    //

    //national ALL tests EQA + INFANTS
	public function CumulativeTestedSamples($year)
	{
		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->where('result', '>', 0)
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('Flag', 1)
		->where('eqa', 0)
		->groupBy('month')
		->get();

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result > 0 AND YEAR(datetested)='$year' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))   AND Flag=1 AND eqa=0") or die(mysql_error());            


	}

	//national EQA tests
	public function OverallEQATestedSamples($year)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->where('result', '>', 0)
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('Flag', 1)
		->where('eqa', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result > 0 AND YEAR(datetested)='$year'  AND repeatt=0  AND Flag=1 AND eqa=1") or die(mysql_error());            

	}

	//national tests
	public function OverallTestedSamples($year)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->whereBetween('result', [1, 2])
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('repeatt', 0)
		->where('Flag', 1)
		->where('eqa', 0)
		->groupBy('month')
		->get();   

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result BETWEEN 1 AND 2 AND YEAR(datetested)='$year' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND repeatt=0  AND Flag=1 AND eqa=0") or die(mysql_error());            


	}

	//national tests
	public function OverallTestedPatients($year)
	{
		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(DISTINCT samples.patient,samples.facility) as totals, month(datetested) as month"))
		->leftJoin('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->whereBetween('samples.result', [1, 2])
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.repeatt', 0)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->groupBy('month')
		->get(); 

		// $sql=mysql_query("SELECT COUNT(DISTINCT samples.patient,samples.facility) as totals  FROM samples,view_facilitys WHERE samples.facility=view_facilitys.ID AND samples.result BETWEEN 1 AND 2 AND YEAR(samples.datetested)='$year' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND samples.repeatt=0  AND samples.Flag=1 AND samples.eqa=0") or die(mysql_error());            


	}

	//national tests
	public function OverallTestedPatientsPOS($year)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(DISTINCT samples.patient,samples.facility) as totals, month(datetested) as month"))
		->leftJoin('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->where('samples.result', 1)
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.repeatt', 0)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->groupBy('month')
		->get(); 

		// $sql=mysql_query("SELECT COUNT(DISTINCT samples.patient,samples.facility) as totals  FROM samples,view_facilitys WHERE samples.facility=view_facilitys.ID AND samples.result =2 AND YEAR(samples.datetested)='$year'  AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))  AND samples.repeatt=0  AND samples.Flag=1 AND samples.eqa=0") or die(mysql_error());            


	}

	//national tests
	public function OverallReceivedSamples($year)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datereceived) as month"))
		->where('samples.result', 1)
		->whereYear('datereceived', $year)
		->whereRaw("(samples.parentid=0 OR samples.parentid IS NULL)")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->groupBy('month')
		->get(); 

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE YEAR(datereceived)='$year'  AND ((samples.parentid=0)||(samples.parentid IS NULL))  AND Flag=1 AND eqa=0") or die(mysql_error());            


	}

	//national tests first pcr
	public function OveralldnafirstTestedSamples($year)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->whereBetween('samples.result', [1, 2])
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month')
		->get(); 

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result BETWEEN 1 AND 2 AND YEAR(datetested)='$year' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND repeatt=0  AND Flag=1 AND eqa=0") or die(mysql_error());           


	}

	//national tests confirmatory
	public function OveralldnasecondTestedSamples($year)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->where('samples.result', '>', 0)
		->whereYear('datetested', $year)
		->whereRaw("samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths'")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month')
		->get(); 

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result > 0 AND YEAR(datetested)='$year' AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND repeatt=0  AND Flag=1 AND eqa=0") or die(mysql_error());   

	}

	//national tests confirmatory
	public function OveralldnasecondTestedSamplesPOS($year)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->where('samples.result', 2)
		->whereYear('datetested', $year)
		->whereRaw("samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths'")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month')
		->get(); 

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result =2 AND YEAR(datetested)='$year' AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND repeatt=0  AND Flag=1 AND eqa=0") or die(mysql_error());        
  
	}

	//national tests confirmatory
	public function OverallPosRepeatsTestedSamples($year)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->where('samples.result', '>', 0)
		->whereYear('datetested', $year)
		->whereRaw("samples.receivedstatus=3 AND samples.reason_for_repeat!='Confirmatory PCR at 9 Mths' and reason_for_repeat !='Repeat For Rejection'")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month')
		->get(); 

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result > 0 AND YEAR(datetested)='$year' and receivedstatus=3 and reason_for_repeat !='Confirmatory PCR at 9 Mths'  and reason_for_repeat !='Repeat For Rejection' AND repeatt=0  AND Flag=1 AND eqa=0") or die(mysql_error());            

  
	}

	//national tests confirmatory
	public function OverallPosRepeatsTestedSamplesPOS($year)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->where('samples.result', 2)
		->whereYear('datetested', $year)
		->whereRaw("samples.receivedstatus=3 AND samples.reason_for_repeat!='Confirmatory PCR at 9 Mths' and reason_for_repeat !='Repeat For Rejection'")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month')
		->get(); 

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result =2 AND YEAR(datetested)='$year' and receivedstatus=3 and reason_for_repeat !='Confirmatory PCR at 9 Mths'  and reason_for_repeat !='Repeat For Rejection' AND repeatt=0  AND Flag=1 AND eqa=0") or die(mysql_error()); 
  
	}

	//samples for a particular range	
	public function Gettestedsamplescountrange($year, $age, $pos)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(patients.ID) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->whereBetween('patients.age', $age)
		->when($pos, function($query) use ($pos){
			if($pos){
				return $query->where('samples.result', 2);
			}
			else{
				return $query->whereBetween('samples.result', [1, 2]);				
			}				
		})
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month')
		->get(); 
  
	}


	//samples less than 2 months	
	public function Gettestedsamplescountlessthan2months($year)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(patients.ID) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->whereBetween('patients.age', [0.0001, 2])
		->whereBetween('samples.result', [1, 2])
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month')
		->get(); 

		$s="SELECT  count(patients.ID)  as numsamples FROM samples,patients WHERE samples.patientautoid =patients.autoID AND patients.age BETWEEN 0.0001 AND  2 AND samples.result BETWEEN 1 AND 2 AND YEAR(samples.datetested)='$yea'  AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND samples.repeatt=0 AND samples.Flag=1 and samples.eqa=0";          

  
	}

	//samples less than 2 months	
	public function Gettestedsamplescountlessthan2monthsPOS($year)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(patients.ID) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->whereBetween('patients.age', [0.0001, 2])
		->whereBetween('samples.result', 2)
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month')
		->get(); 

		$s="SELECT  count(patients.ID)  as numsamples FROM samples,patients WHERE samples.patientautoid =patients.autoID AND patients.age BETWEEN 0.0001 AND  2 AND samples.result =2 AND YEAR(samples.datetested)='$yea' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND samples.repeatt=0 AND samples.Flag=1 and samples.eqa=0";          

  
	}

	//samples less than 2 months	
	public function Gettestedsamplescountmorethan2months($year)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(patients.ID) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->whereBetween('patients.age', [2.0001, 18])
		->whereBetween('samples.result', [1, 2])
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month')
		->get(); 

		$s="SELECT  count(patients.ID)  as numsamples FROM samples,patients WHERE samples.patientautoid =patients.autoID AND patients.age BETWEEN 2.0001 AND  18 AND samples.result BETWEEN 1 AND 2 AND YEAR(samples.datetested)='$yea'  AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND samples.repeatt=0 AND samples.Flag=1 and samples.eqa=0";  
  
	}


	//samples less than 2 months	
	public function Gettestedsamplescountmorethan2monthsPOS($year)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(patients.ID) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->whereBetween('patients.age', [2.0001, 18])
		->where('samples.result', 2)
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month')
		->get(); 

		$s="SELECT  count(patients.ID)  as numsamples FROM samples,patients WHERE samples.patientautoid =patients.autoID AND patients.age BETWEEN 2.0001 AND  8800 AND samples.result = 2 AND YEAR(samples.datetested)='$yea'  AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND samples.repeatt=0 AND samples.Flag=1 and samples.eqa=0";          
   
  
	}


	//samples less than 2 weeks	
	public function Gettestedsamplescountlessthan2weeks($year)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(patients.ID) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->whereBetween('patients.age', [0.0001, 0.5])
		->whereBetween('samples.result', [1, 2])
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month')
		->get(); 

		$s="SELECT  count(patients.ID)  as numsamples FROM samples,patients WHERE samples.patientautoid =patients.autoID AND patients.age BETWEEN 0.0001 AND  0.5 AND samples.result BETWEEN 1 AND 2 AND YEAR(samples.datetested)='$yea'  AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND samples.repeatt=0 AND samples.Flag=1 and samples.eqa=0";          

  
	}

	//samples less than 2 weeks	
	public function Gettestedsamplescountlessthan2weeksPOS($year)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(patients.ID) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->whereBetween('patients.age', [0.0001, 0.5])
		->whereBetween('samples.result', 2)
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month')
		->get(); 

		$s="SELECT  count(patients.ID)  as numsamples FROM samples,patients WHERE samples.patientautoid =patients.autoID AND patients.age BETWEEN 0.0001 AND  0.5 AND samples.result =2 AND YEAR(samples.datetested)='$yea' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND samples.repeatt=0 AND samples.Flag=1 and samples.eqa=0";          

  
	}


	//samples less than 2 months	
	public function Gettestedsamplescount46weeks($year)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(patients.ID) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->whereBetween('patients.age', [1, 1.5])
		->whereBetween('samples.result', [1, 2])
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month')
		->get(); 

		$s="SELECT  count(patients.ID)  as numsamples FROM samples,patients WHERE samples.patientautoid =patients.autoID AND patients.age BETWEEN 1 AND  1.5 AND samples.result BETWEEN 1 AND 2 AND YEAR(samples.datetested)='$yea'  AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND samples.repeatt=0 AND samples.Flag=1 and samples.eqa=0";          

  
	}


	//samples less than 2 months	
	public function Gettestedsamplescount46weeksPOS($year)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(patients.ID) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->whereBetween('patients.age', [1, 1.5])
		->where('samples.result', 2)
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month')
		->get(); 

		$s="SELECT  count(patients.ID)  as numsamples FROM samples,patients WHERE samples.patientautoid =patients.autoID AND patients.age BETWEEN 1 AND  1.5 AND samples.result = 2 AND YEAR(samples.datetested)='$yea'  AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND samples.repeatt=0 AND samples.Flag=1 and samples.eqa=0";          

  
	}


	//samples adults	
	public function Gettestedsamplescountadults($year, $pos)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(patients.ID) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->where('patients.age', '>', 24)
		->when($pos, function($query) use ($pos){
			if($pos){
				return $query->where('samples.result', 2);
			}
			else{
				return $query->whereBetween('samples.result', [1, 2]);				
			}				
		})
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month')
		->get(); 

		$s="SELECT  count(patients.ID)  as numsamples FROM samples,patients WHERE samples.patientautoid =patients.autoID AND patients.age >24 AND samples.result BETWEEN 1 AND 2 AND YEAR(samples.datetested)='$yea' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND samples.repeatt=0 AND samples.Flag=1 and samples.eqa=0";          

  
	}

	//samples adults	
	public function GettestedsamplescountadultsPOS($year)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(patients.ID) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->where('patients.age', '>', 24)
		->where('samples.result', 2)
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month')
		->get(); 

		$s="SELECT  count(patients.ID)  as numsamples FROM samples,patients WHERE samples.patientautoid =patients.autoID AND patients.age >24 AND samples.result BETWEEN 1 AND 2 AND YEAR(samples.datetested)='$yea' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND samples.repeatt=0 AND samples.Flag=1 and samples.eqa=0";          

  
	}


	//national outcomes	
	public function OverallTestedSamplesOutcomes($year, $result_type)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->where('samples.result', $result_type)
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month')
		->get(); 

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result ='$resulttype' AND YEAR(datetested)='$year' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND repeatt=0  AND Flag=1 AND eqa=0") or die(mysql_error()); 

  
	}


	//national rejected	
	public function Getnationalrejectedsamples($year)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->whereYear('datetested', $year)
		->where('samples.receivedstatus', 2)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month')
		->get(); 

		// $s="SELECT COUNT(ID) as numsamples FROM samples WHERE receivedstatus = 2 AND YEAR(samples.datereceived)='$yea' AND samples.repeatt=0 AND samples.Flag=1 and samples.eqa=0";
  
	}


	//national patients HEI follow up or validation status
	public function GetHEIFollowUpNational($year, $estatus, $col="samples.enrollmentstatus")
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->where('samples.result', 2)
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where($col, $estatus)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month')
		->get(); 

		// $sql=mysql_query("SELECT samples.ID from samples where   YEAR(samples.datetested)='$currentyear' AND MONTH(samples.datetested)='$currentmonth'  and samples.repeatt=0 and samples.result =2 AND samples.flag=1 AND  samples.enrollmentstatus ='$estatus' and samples.eqa=0 AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) ") or die(mysql_error());

  
	}


	//national patients HEI validation status
	// public function GetHEIValidationNational($year, $estatus)
	// {

	// 	$data = DB::connection('eid')
	// 	->table('samples')
	// 	->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
	// 	->where('samples.result', 2)
	// 	->whereYear('datetested', $year)
	// 	->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
	// 	->where('samples.hei_validation', $estatus)
	// 	->where('samples.Flag', 1)
	// 	->where('samples.eqa', 0)
	// 	->where('samples.repeatt', 0)
	// 	->groupBy('month')
	// 	->get(); 

	// 	$sql=mysql_query("select samples.ID from samples where   YEAR(samples.datetested)='$currentyear' AND MONTH(samples.datetested)='$currentmonth'  and samples.repeatt=0 and samples.result =2 AND samples.flag=1 AND  samples.hei_validation ='$estatus' and samples.eqa=0 AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) ") or die(mysql_error());
  
	// }


	//national sites by period
	public function GettotalEIDsitesbytimeperiod($year)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(DISTINCT samples.facility) as totals, month(datereceived) as month"))
		->whereYear('datereceived', $year)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->groupBy('month')
		->get(); 

		// $strQuery=mysql_query("SELECT COUNT(DISTINCT samples.facility) as 'activesites' FROM $tb WHERE YEAR($tb.datereceived)='$currentyear'  AND MONTH($tb.datereceived)='$currentmonth' and $tb.eqa=0 and $tb.flag=1")or die(mysql_error());

  
	}

	// Average age	
	public function Getoverallaverageage($year)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("AVG(patients.age) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->where('patients.age', '>', 0)
		->where('patients.age', '<', 24)
		->where('samples.result', '>', 0)
		->whereYear('datetested', $year)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->groupBy('month')
		->get(); 

		// $s="SELECT AVG(patients.age) as averageage FROM samples,patients WHERE samples.patientauotid=patients.autoID AND patients.age < 24 and patients.age >0  AND samples.result >0  AND YEAR(samples.datetested)='$yea' AND samples.Flag=1 and samples.eqa=0";              
	}


	// Median age	
	public function Getoverallmedianage($year)
	{
		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("patients.age, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->where('patients.age', '>', 0)
		->where('patients.age', '<', 24)
		->where('samples.result', '>', 0)
		->whereYear('datetested', $year)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->get(); 

		// $s="SELECT patients.age  FROM samples,patients WHERE samples.patientautoid=patients.autoID AND patients.age < 24 and patients.age >0  AND samples.result >0  AND YEAR(samples.datetested)='$yea' AND samples.Flag=1 and samples.eqa=0";

		$return;

		for ($i=0; $i < 12; $i++) { 
			$subset = $data->where('month', $i);

			if($subset->isEmpty()){
				break;
			}
			else{
				$return[$i] = $subset->median('age');
			}

		}

		return $return;
		              
	}

	// infant proph nat summary
	public function Getinfantprophpositivitycount($year, $drug, $result_type)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(patients.AutoID) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->where('patients.prophylaxis', $drug)
		->where('samples.result', $result_type)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->whereYear('datetested', $year)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month')
		->get(); 

		// $prophQuery =mysql_query("SELECT count(patients.AutoID) as 'TotOutput'
  //           FROM samples,patients WHERE  samples.patientautoid=patients.autoID    AND patients.prophylaxis='$drug'   AND  (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))  AND    samples.result='$resulttype' AND YEAR(samples.datetested)='$yea' AND  samples.repeatt=0 AND samples.Flag=1 and samples.eqa=0  ") or die(mysql_error());
              
	}


	// mother proph nat summary
	public function Getinterventionspositivitycount($year, $drug, $result_type)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->join('mothers', 'patients.mother', '=', 'mothers.ID')
		->where('mothers.prophylaxis', $drug)
		->where('samples.result', $result_type)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->whereYear('datetested', $year)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month')
		->get(); 

		// $s="SELECT COUNT(samples.ID) as numtests from samples,patients,mothers where samples.patientautoid=patients.autoID AND patients.mother=mothers.ID AND samples.result='$resulttype' AND YEAR(samples.datetested)='$yea' and samples.repeatt=0 AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) and samples.eqa=0   AND samples.Flag=1 AND mothers.prophylaxis='$drug'";
              
	}

	// entry point national summary
	public function GetNationalResultbyEntrypoint($year, $entry_point, $result_type)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->join('mothers', 'patients.mother', '=', 'mothers.ID')
		->where('mothers.entry_point', $entry_point)
		->where('samples.result', $result_type)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->whereYear('datetested', $year)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month')
		->get(); 

		// $s="SELECT count(samples.ID) as numsamples
     // FROM samples,patients,mothers WHERE  samples.result ='$resulttype' AND YEAR(samples.datetested)='$yea'  AND samples.patientautoid=patients.autoID  AND patients.mother=mothers.ID AND mothers.entry_point='$entrypoint' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND samples.repeatt=0 AND samples.Flag=1 and samples.eqa=0";

              
	}


	//samples for a particular range	
	public function GetTestOutcomesbyAgeBand($year, $age, $result_type)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(patients.AutoID) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->whereBetween('patients.age', $age)
		->where('samples.result', $result_type)
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month')
		->get(); 

		// $prophQuery =mysql_query("SELECT count(patients.AutoID) as 'TotOutput'
            // FROM samples,patients WHERE  samples.patientautoid=patients.autoID    AND patients.age BETWEEN $fromage AND $endage  AND  (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))  AND    samples.result='$resultype' AND YEAR(samples.datetested)='$yea' AND  samples.repeatt=0 AND samples.Flag=1 and samples.eqa=0  ") or die(mysql_error());

  
	}

	// Average age	
	public function GetNatTATs($year)
	{
		$sql = "datediff(datereceived, datecollected) as tat1, datediff(datetested, datereceived) as tat2, datediff(datedispatched, datetested) as tat3, datediff(datedispatched, datecollected) as tat4, datecollected, datereceived, datetested, datedispatched, month(datetested) as month"

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw($sql))
		->whereYear('samples.datecollected', '>', 1980)
		->whereYear('samples.datereceived', '>', 1980)
		->whereYear('samples.datetested', '>', 1980)
		->whereYear('samples.datedispatched', '>', 1980)
		->whereColumn([
			['datecollected', '<=', 'datereceived'],
			['datereceived', '<=', 'datetested'],
			['datetested', '<=', 'datedispatched'],
			['datecollected', '<=', 'datedispatched']
		])
		->whereYear('datetested', $year)
		->where('samples.Flag', 1)
		->where('samples.repeatt', 0)
		->get(); 

		// $s="SELECT AVG(patients.age) as averageage FROM samples,patients WHERE samples.patientauotid=patients.autoID AND patients.age < 24 and patients.age >0  AND samples.result >0  AND YEAR(samples.datetested)='$yea' AND samples.Flag=1 and samples.eqa=0";              
	}



	





}
