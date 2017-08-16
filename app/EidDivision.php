<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\BaseModel;

class EidDivision extends Model
{
    //

    // Total number of batches
    public function GettotalbatchesPerlab($year, $division='view_facilitys.county'){
    	$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("COUNT(samples.batchno) as totals, month(datetested) as month"))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->whereYear('datetested', $year)
		->whereRaw("(samples.parentid=0  OR samples.parentid IS NULL)")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->groupBy('month', $division)
		->get();

		return $data;

		// $sql = "SELECT COUNT(DISTINCT(batchno)) as totals  FROM samples WHERE labtestedin='$lab' AND  YEAR(datereceived)='$year'  AND ((samples.parentid=0)||(samples.parentid IS NULL))  AND Flag=1 AND eqa=0";
    }

    //national ALL tests EQA + INFANTS
	public function CumulativeTestedSamples($year, $division='view_facilitys.county')
	{
		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->where('result', '>', 0)
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->groupBy('month', $division)
		->get();

		return $data;

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result > 0 AND YEAR(datetested)='$year' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))   AND Flag=1 AND eqa=0") or die(mysql_error()); 
	}

	

	//national EQA tests
	public function OverallEQATestedSamples($year, $division='view_facilitys.county')
	{

		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->where('result', '>', 0)
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.Flag', 1)
		->where('samples.eqa', 1)
		->where('samples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result > 0 AND YEAR(datetested)='$year'  AND repeatt=0  AND Flag=1 AND eqa=1") or die(mysql_error());            

	}

	//national tests
	public function OverallTestedSamples($year, $division='view_facilitys.county')
	{

		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->whereBetween('result', [1, 2])
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.repeatt', 0)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->groupBy('month', $division)
		->get(); 

		return $data;  

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result BETWEEN 1 AND 2 AND YEAR(datetested)='$year' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND repeatt=0  AND Flag=1 AND eqa=0") or die(mysql_error());            


	}

	//national tests
	public function OverallTestedPatients($year, $division='view_facilitys.county')
	{
		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("COUNT(DISTINCT samples.patient,samples.facility) as totals, month(datetested) as month"))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->whereBetween('samples.result', [1, 2])
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.repeatt', 0)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->groupBy('month', $division)
		->get(); 

		return $data;

		// $sql=mysql_query("SELECT COUNT(DISTINCT samples.patient,samples.facility) as totals  FROM samples,view_facilitys WHERE samples.facility=view_facilitys.ID AND samples.result BETWEEN 1 AND 2 AND YEAR(samples.datetested)='$year' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND samples.repeatt=0  AND samples.Flag=1 AND samples.eqa=0") or die(mysql_error());            


	}

	//national tests
	public function OverallTestedPatientsPOS($year, $division='view_facilitys.county')
	{

		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("COUNT(DISTINCT samples.patient,samples.facility) as totals, month(datetested) as month"))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->where('samples.result', 1)
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.repeatt', 0)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->groupBy('month', $division)
		->get(); 

		return $data;

		// $sql=mysql_query("SELECT COUNT(DISTINCT samples.patient,samples.facility) as totals  FROM samples,view_facilitys WHERE samples.facility=view_facilitys.ID AND samples.result =2 AND YEAR(samples.datetested)='$year'  AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))  AND samples.repeatt=0  AND samples.Flag=1 AND samples.eqa=0") or die(mysql_error());            


	}

	//national tests
	public function OverallReceivedSamples($year, $division='view_facilitys.county')
	{

		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("COUNT(samples.ID) as totals, month(datereceived) as month"))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->where('samples.result', 1)
		->whereYear('datereceived', $year)
		->whereRaw("(samples.parentid=0 OR samples.parentid IS NULL)")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->groupBy('month', $division)
		->get(); 

		return $data;

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE YEAR(datereceived)='$year'  AND ((samples.parentid=0)||(samples.parentid IS NULL))  AND Flag=1 AND eqa=0") or die(mysql_error());            


	}

	//national tests first pcr
	public function OveralldnafirstTestedSamples($year, $division='view_facilitys.county')
	{

		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->whereBetween('samples.result', [1, 2])
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month', $division)
		->get(); 

		return $data;

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result BETWEEN 1 AND 2 AND YEAR(datetested)='$year' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND repeatt=0  AND Flag=1 AND eqa=0") or die(mysql_error());           


	}

	//national tests confirmatory
	public function OveralldnasecondTestedSamples($year, $division='view_facilitys.county')
	{

		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->where('samples.result', '>', 0)
		->whereYear('datetested', $year)
		->whereRaw("samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths'")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month', $division)
		->get(); 

		return $data;

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result > 0 AND YEAR(datetested)='$year' AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND repeatt=0  AND Flag=1 AND eqa=0") or die(mysql_error());   

	}

	//national tests confirmatory
	public function OveralldnasecondTestedSamplesPOS($year, $division='view_facilitys.county')
	{

		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->where('samples.result', 2)
		->whereYear('datetested', $year)
		->whereRaw("samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths'")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month', $division)
		->get(); 

		return $data;

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result =2 AND YEAR(datetested)='$year' AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND repeatt=0  AND Flag=1 AND eqa=0") or die(mysql_error());        
  
	}

	//national tests confirmatory
	public function OverallPosRepeatsTestedSamples($year, $division='view_facilitys.county')
	{

		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->where('samples.result', '>', 0)
		->whereYear('datetested', $year)
		->whereRaw("samples.receivedstatus=3 AND samples.reason_for_repeat!='Confirmatory PCR at 9 Mths' and reason_for_repeat !='Repeat For Rejection'")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month', $division)
		->get(); 

		return $data;

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result > 0 AND YEAR(datetested)='$year' and receivedstatus=3 and reason_for_repeat !='Confirmatory PCR at 9 Mths'  and reason_for_repeat !='Repeat For Rejection' AND repeatt=0  AND Flag=1 AND eqa=0") or die(mysql_error());            

  
	}

	//national tests confirmatory
	public function OverallPosRepeatsTestedSamplesPOS($year, $division='view_facilitys.county')
	{

		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->where('samples.result', 2)
		->whereYear('datetested', $year)
		->whereRaw("samples.receivedstatus=3 AND samples.reason_for_repeat!='Confirmatory PCR at 9 Mths' and reason_for_repeat !='Repeat For Rejection'")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month', $division)
		->get(); 

		return $data;

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result =2 AND YEAR(datetested)='$year' and receivedstatus=3 and reason_for_repeat !='Confirmatory PCR at 9 Mths'  and reason_for_repeat !='Repeat For Rejection' AND repeatt=0  AND Flag=1 AND eqa=0") or die(mysql_error()); 
  
	}

	//samples for a particular range	
	public function Gettestedsamplescountrange($year, $a, $pos=false, $division='view_facilitys.county')
	{
		$b = new BaseModel;
		$age = $b->age_range($a);

		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("COUNT(patients.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
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
		->groupBy('month', $division)
		->get(); 

		return $data;
  
	}

	//national outcomes	
	public function OverallTestedSamplesOutcomes($year, $result_type, $division='view_facilitys.county')
	{

		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->where('samples.result', $result_type)
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month', $division)
		->get(); 

		return $data;

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result ='$resulttype' AND YEAR(datetested)='$year' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND repeatt=0  AND Flag=1 AND eqa=0") or die(mysql_error()); 

  
	}


	//national rejected	
	public function Getnationalrejectedsamples($year, $division='view_facilitys.county')
	{

		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->whereYear('datetested', $year)
		->where('samples.receivedstatus', 2)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month', $division)
		->get(); 

		return $data;

		// $s="SELECT COUNT(ID) as numsamples FROM samples WHERE receivedstatus = 2 AND YEAR(samples.datereceived)='$yea' AND samples.repeatt=0 AND samples.Flag=1 and samples.eqa=0";
  
	}


	//national patients HEI follow up or validation status
	public function GetHEIFollowUpNational($year, $estatus, $col="samples.enrollmentstatus", $division='view_facilitys.county')
	{

		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->where('samples.result', 2)
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where($col, $estatus)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month', $division)
		->get(); 

		return $data;

		// $sql=mysql_query("SELECT samples.ID from samples where   YEAR(samples.datetested)='$currentyear' AND MONTH(samples.datetested)='$currentmonth'  and samples.repeatt=0 and samples.result =2 AND samples.flag=1 AND  samples.enrollmentstatus ='$estatus' and samples.eqa=0 AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) ") or die(mysql_error());

  
	}

	//national sites by period
	public function GettotalEIDsitesbytimeperiod($year, $division='view_facilitys.county')
	{

		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("COUNT(DISTINCT samples.facility) as totals, month(datereceived) as month"))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->whereYear('datereceived', $year)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->groupBy('month', $division)
		->get(); 

		return $data;

		// $strQuery=mysql_query("SELECT COUNT(DISTINCT samples.facility) as 'activesites' FROM $tb WHERE YEAR($tb.datereceived)='$currentyear'  AND MONTH($tb.datereceived)='$currentmonth' and $tb.eqa=0 and $tb.flag=1")or die(mysql_error());

  
	}

	// Average age	
	public function Getoverallaverageage($year, $division='view_facilitys.county')
	{

		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("AVG(patients.age) as totals, month(datetested) as month"))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->where('patients.age', '>', 0)
		->where('patients.age', '<', 24)
		->where('samples.result', '>', 0)
		->whereYear('datetested', $year)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->groupBy('month', $division)
		->get(); 

		return $data;

		// $s="SELECT AVG(patients.age) as averageage FROM samples,patients WHERE samples.patientauotid=patients.autoID AND patients.age < 24 and patients.age >0  AND samples.result >0  AND YEAR(samples.datetested)='$yea' AND samples.Flag=1 and samples.eqa=0";              
	}


	// Median age	
	public function Getoverallmedianage($year, $div_array, $division='view_facilitys.county', $col='county')
	{
		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("patients.age, month(datetested) as month"))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->where('patients.age', '>', 0)
		->where('patients.age', '<', 24)
		->where('samples.result', '>', 0)
		->whereYear('datetested', $year)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->get(); 

		// return $data;

		// $s="SELECT patients.age  FROM samples,patients WHERE samples.patientautoid=patients.autoID AND patients.age < 24 and patients.age >0  AND samples.result >0  AND YEAR(samples.datetested)='$yea' AND samples.Flag=1 and samples.eqa=0";

		$return;

		$place = 0;

		for ($i=0; $i < 12; $i++) { 
			$month = $i + 1;

			for ($iterator=0; $iterator < count($div_array); $iterator++) { 
				$c = $div_array[$iterator];
				
				$d = $data->where('month', $month)->where($col, $c);

				if($d->isEmpty()){
					$return[$place]['totals'] = 0;
					$return[$place]['county'] = $c;
					$return[$place]['month'] = $month;
					continue;
				}

				$return[$place]['totals'] = $d->median('age');
				$return[$place]['county'] = $c;
				$return[$place]['month'] = $month;

				$place++;

			}


		}

		return $return;
		              
	}

	// infant proph nat summary
	public function Getinfantprophpositivitycount($year, $drug, $result_type, $division='view_facilitys.county')
	{

		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("COUNT(patients.AutoID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->where('patients.prophylaxis', $drug)
		->where('samples.result', $result_type)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->whereYear('datetested', $year)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month', $division)
		->get(); 

		return $data;

		// $prophQuery =mysql_query("SELECT count(patients.AutoID) as 'TotOutput'
  		//           FROM samples,patients WHERE  samples.patientautoid=patients.autoID    AND patients.prophylaxis='$drug'   AND  (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))  AND    samples.result='$resulttype' AND YEAR(samples.datetested)='$yea' AND  samples.repeatt=0 AND samples.Flag=1 and samples.eqa=0  ") or die(mysql_error());
              
	}


	// mother proph nat summary
	public function Getinterventionspositivitycount($year, $drug, $result_type, $division='view_facilitys.county')
	{

		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->join('mothers', 'patients.mother', '=', 'mothers.ID')
		->where('mothers.prophylaxis', $drug)
		->where('samples.result', $result_type)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->whereYear('datetested', $year)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month', $division)
		->get(); 

		return $data;

		// $s="SELECT COUNT(samples.ID) as numtests from samples,patients,mothers where samples.patientautoid=patients.autoID AND patients.mother=mothers.ID AND samples.result='$resulttype' AND YEAR(samples.datetested)='$yea' and samples.repeatt=0 AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) and samples.eqa=0   AND samples.Flag=1 AND mothers.prophylaxis='$drug'";
              
	}

	// entry point national summary
	public function GetNationalResultbyEntrypoint($year, $entry_point, $result_type, $division='view_facilitys.county')
	{

		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->join('mothers', 'patients.mother', '=', 'mothers.ID')
		->where('mothers.entry_point', $entry_point)
		->where('samples.result', $result_type)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->whereYear('datetested', $year)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data; 

		// $s="SELECT count(samples.ID) as numsamples
     // FROM samples,patients,mothers WHERE  samples.result ='$resulttype' AND YEAR(samples.datetested)='$yea'  AND samples.patientautoid=patients.autoID  AND patients.mother=mothers.ID AND mothers.entry_point='$entrypoint' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND samples.repeatt=0 AND samples.Flag=1 and samples.eqa=0";

              
	}


	//samples for a particular range	
	public function GetTestOutcomesbyAgeBand($year, $a, $result_type, $division='view_facilitys.county')
	{

		$b = new BaseModel;
		$age = $b->age_band($a);

		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("COUNT(patients.AutoID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->whereBetween('patients.age', $age)
		->where('samples.result', $result_type)
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->groupBy('month', $division)
		->get(); 

		return $data;

		// $prophQuery =mysql_query("SELECT count(patients.AutoID) as 'TotOutput'
            // FROM samples,patients WHERE  samples.patientautoid=patients.autoID    AND patients.age BETWEEN $fromage AND $endage  AND  (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))  AND    samples.result='$resultype' AND YEAR(samples.datetested)='$yea' AND  samples.repeatt=0 AND samples.Flag=1 and samples.eqa=0  ") or die(mysql_error());

  
	}

	// Average age	
	public function GetNatTATs($year, $div_array, $division='view_facilitys.county', $col='county')
	{
		// $sql = "datediff(datereceived, datecollected) as tat1, datediff(datetested, datereceived) as tat2, datediff(datedispatched, datetested) as tat3, datediff(datedispatched, datecollected) as tat4, datecollected, datereceived, datetested, datedispatched, month(datetested) as month";
		$sql = "datecollected, datereceived, datetested, datedispatched, month(datetested) as month";

		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw($sql))
		->join('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->whereYear('samples.datecollected', '>', 1980)
		->whereYear('samples.datereceived', '>', 1980)
		->whereYear('samples.datetested', '>', 1980)
		->whereYear('samples.datedispatched', '>', 1980)
		->whereColumn([
			['datecollected', '<=', 'datereceived'],
			['datereceived', '<=', 'datetested'],
			['datetested', '<=', 'datedispatched']
		])
		->whereYear('datetested', $year)
		->where('samples.Flag', 1)
		->where('samples.repeatt', 0)
		->get(); 



		$return;
		$b = new BaseModel;

		$place = 0;

		for ($i=0; $i < 12; $i++) { 
			$month = $i + 1;

			for ($iterator=0; $iterator < count($div_array); $iterator++) { 
				$c = $div_array[$iterator];
				
				$d = $data->where('month', $month)->where($col, $c);

				if($d->isEmpty()){
					$return[$place]['tat1'] = 0;
					$return[$place]['tat2'] = 0;
					$return[$place]['tat3'] = 0;
					$return[$place]['tat4'] = 0;
					$return[$place]['division'] = $c;
					$return[$place]['month'] = $month;
					continue;
				}

				$tat1 = $tat2 = $tat3 = $tat4 = 0;
				$rows = $d->count();

				$holidays = $b->getTotalHolidaysinMonth($month);

				foreach ($d as $key => $value) {
					
					$tat1 += $b->get_days($value->datecollected, $value->datereceived, $holidays);
					$tat2 += $b->get_days($value->datereceived, $value->datetested, $holidays);
					$tat3 += $b->get_days($value->datetested, $value->datedispatched, $holidays);
					$tat4 += $b->get_days($value->datecollected, $value->datedispatched, $holidays);

				}

				$return[$place]['tat1'] = floor($tat1 / $rows);
				$return[$place]['tat2'] = floor($tat2 / $rows);
				$return[$place]['tat3'] = floor($tat3 / $rows);
				$return[$place]['tat4'] = floor($tat4 / $rows);
				$return[$place]['county'] = $c;
				$return[$place]['month'] = $month;

				$place++;

			}


		}

		return $return;
	}

	
}
