<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\BaseModel;

class EidNation extends Model
{
    //

    //national ALL tests EQA + INFANTS
	public function CumulativeTestedSamples($year, $monthly=true)
	{
		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->where('result', '>', 0)
		->whereYear('datetested', $year)
		->where('Flag', 1)
		->where('repeatt', 0)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get();

		return $data;

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result > 0 AND YEAR(datetested)='$year' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))   AND Flag=1 AND eqa=0") or die(mysql_error());            


	}

	//national EQA tests
	public function OverallEQATestedSamples($year, $monthly=true)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->where('result', '>', 0)
		->whereYear('datetested', $year)
		->where('samples.facility', 7148)
		->where('Flag', 1)
		->where('repeatt', 0)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get();

		return $data;

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result > 0 AND YEAR(datetested)='$year'  AND repeatt=0  AND Flag=1 AND eqa=1") or die(mysql_error());            

	}

	//national tests
	public function OverallTestedSamples($year, $monthly=true)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->whereIn('result', [1, 2])
		->whereYear('datetested', $year)
		->where('repeatt', 0)
		->where('Flag', 1)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;  

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result BETWEEN 1 AND 2 AND YEAR(datetested)='$year' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND repeatt=0  AND Flag=1 AND eqa=0") or die(mysql_error());            


	}

	//national tests
	public function OverallTestedPatients($year, $monthly=true)
	{
		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(DISTINCT samples.patient,samples.facility) as totals, month(datetested) as month"))
		->leftJoin('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->whereBetween('patients.age', [0.0001, 24])
		->whereIn('samples.pcrtype', [1, 2, 3])
		->whereIn('samples.result', [1, 2])
		->whereYear('datetested', $year)
		->where('samples.repeatt', 0)
		->where('samples.Flag', 1)
		->where('samples.facility', '!=', 7148)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;

		// $sql=mysql_query("SELECT COUNT(DISTINCT samples.patient,samples.facility) as totals  FROM samples,view_facilitys WHERE samples.facility=view_facilitys.ID AND samples.result BETWEEN 1 AND 2 AND YEAR(samples.datetested)='$year' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND samples.repeatt=0  AND samples.Flag=1 AND samples.eqa=0") or die(mysql_error());            


	}

	//national tests
	public function OverallTestedPatientsPOS($year, $monthly=true)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(DISTINCT samples.patient,samples.facility) as totals, month(datetested) as month"))
		->leftJoin('view_facilitys', 'samples.facility', '=', 'view_facilitys.ID')
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->whereBetween('patients.age', [0.0001, 24])
		->where('samples.result', 2)
		->whereYear('datetested', $year)
		->whereIn('samples.pcrtype', [1, 2, 3])
		->where('samples.repeatt', 0)
		->where('samples.Flag', 1)
		->where('samples.facility', '!=', 7148)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;

		// $sql=mysql_query("SELECT COUNT(DISTINCT samples.patient,samples.facility) as totals  FROM samples,view_facilitys WHERE samples.facility=view_facilitys.ID AND samples.result =2 AND YEAR(samples.datetested)='$year'  AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))  AND samples.repeatt=0  AND samples.Flag=1 AND samples.eqa=0") or die(mysql_error());            


	}

	//national tests
	public function OverallReceivedSamples($year, $monthly=true)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datereceived) as month"))
		->whereYear('datereceived', $year)
		->whereRaw("(samples.parentid=0 OR samples.parentid IS NULL)")
		->where('samples.Flag', 1)
		->where('samples.facility', '!=', 7148)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE YEAR(datereceived)='$year'  AND ((samples.parentid=0)||(samples.parentid IS NULL))  AND Flag=1 AND eqa=0") or die(mysql_error());            


	}

	//national false confirmatory
	public function false_confirmatory($year, $monthly=true)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select($division, DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->where('samples.facility', '!=', 7148)
		->where('samples.previous_positive', 1)
		->whereYear('datetested', $year)
		->where('samples.repeatt', 0)
		->where('samples.Flag', 1)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;
	}

	public function getbypcr($year, $pcrtype=1, $pos=false, $monthly=true){

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->when(true, function($query) use ($pos){
			if($pos){
				return $query->where('samples.result', 2);
			}
			else{
				return $query->whereIn('samples.result', [1, 2]);
			}			
		})
		->whereYear('datetested', $year)
		->when($pcrtype, function($query) use ($pcrtype){
			if($pcrtype == 2){
				return $query->whereIn('samples.pcrtype', [2, 3]);
			}
			else{
				return $query->where('samples.pcrtype', $pcrtype);
			}			
		})		
		// ->where('samples.pcrtype', $pcrtype)
		->where('samples.Flag', 1)
		->where('samples.facility', '!=', 7148)
		->where('samples.repeatt', 0)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;
	}

	//national tests first pcr
	public function OveralldnafirstTestedSamples($year, $monthly=true)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->whereIn('samples.result', [1, 2])
		->whereYear('datetested', $year)
		->where('samples.pcrtype', 1)
		->where('samples.Flag', 1)
		->where('samples.facility', '!=', 7148)
		->where('samples.repeatt', 0)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result BETWEEN 1 AND 2 AND YEAR(datetested)='$year' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND repeatt=0  AND Flag=1 AND eqa=0") or die(mysql_error());           


	}

	//national tests confirmatory
	public function OveralldnasecondTestedSamples($year, $monthly=true)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->whereIn('samples.result', [1, 2])
		->whereYear('datetested', $year)
		->whereIn('samples.pcrtype', [2, 3])
		->where('samples.Flag', 1)
		->where('samples.facility', '!=', 7148)
		->where('samples.repeatt', 0)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result > 0 AND YEAR(datetested)='$year' AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND repeatt=0  AND Flag=1 AND eqa=0") or die(mysql_error());   

	}

	//national tests confirmatory
	public function OveralldnasecondTestedSamplesPOS($year, $monthly=true)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->where('samples.result', 2)
		->whereYear('datetested', $year)
		->whereIn('samples.pcrtype', [2, 3])
		->where('samples.Flag', 1)
		->where('samples.facility', '!=', 7148)
		->where('samples.repeatt', 0)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result =2 AND YEAR(datetested)='$year' AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND repeatt=0  AND Flag=1 AND eqa=0") or die(mysql_error());        
  
	}

	//national tests confirmatory
	public function OverallPosRepeatsTestedSamples($year, $monthly=true)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->whereIn('samples.result', [1, 2])
		->whereYear('datetested', $year)
		->where('samples.pcrtype', 4)
		->where('samples.Flag', 1)
		->where('samples.facility', '!=', 7148)
		->where('samples.repeatt', 0)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result > 0 AND YEAR(datetested)='$year' and receivedstatus=3 and reason_for_repeat !='Confirmatory PCR at 9 Mths'  and reason_for_repeat !='Repeat For Rejection' AND repeatt=0  AND Flag=1 AND eqa=0") or die(mysql_error());            

  
	}

	//national tests confirmatory
	public function OverallPosRepeatsTestedSamplesPOS($year, $monthly=true)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->where('samples.result', 2)
		->whereYear('datetested', $year)
		->where('samples.pcrtype', 4)
		->where('samples.Flag', 1)
		->where('samples.facility', '!=', 7148)
		->where('samples.repeatt', 0)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result =2 AND YEAR(datetested)='$year' and receivedstatus=3 and reason_for_repeat !='Confirmatory PCR at 9 Mths'  and reason_for_repeat !='Repeat For Rejection' AND repeatt=0  AND Flag=1 AND eqa=0") or die(mysql_error()); 
  
	}

	//samples for a particular range	
	public function Gettestedsamplescountrange($year, $a, $pos=false, $monthly=true)
	{
		$b = new BaseModel;
		$age = $b->age_range($a);

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(DISTINCT samples.patient,samples.facility) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->whereBetween('patients.age', $age)
		->when(true, function($query) use ($pos){
			if($pos){
				return $query->where('samples.result', 2);
			}
			else{
				return $query->whereIn('samples.result', [1, 2]);				
			}				
		})
		->when(true, function($query) use ($a){
			if($a != 5){
				return $query->where('samples.pcrtype', 1);
			}
		})
		->whereYear('datetested', $year)
		->where('samples.Flag', 1)
		->where('samples.facility', '!=', 7148)
		->where('samples.repeatt', 0)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;
  
	}

	//national outcomes	
	public function OverallTestedSamplesOutcomes($year, $result_type, $pcrtype, $monthly=true)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->where('samples.result', $result_type)
		->whereYear('datetested', $year)
		->when($pcrtype, function($query) use ($pcrtype){
			if($pcrtype == 2){
				return $query->whereIn('samples.pcrtype', [2, 3]);
			}
			else{
				return $query->where('samples.pcrtype', $pcrtype);
			}			
		})		
		->where('samples.Flag', 1)
		->where('samples.facility', '!=', 7148)
		->where('samples.repeatt', 0)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result ='$resulttype' AND YEAR(datetested)='$year' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND repeatt=0  AND Flag=1 AND eqa=0") or die(mysql_error()); 

  
	}


	//national rejected	
	public function Getnationalrejectedsamples($year, $monthly=true)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datereceived) as month"))
		->whereYear('datereceived', $year)
		->where('samples.receivedstatus', 2)
		->whereRaw("(samples.parentid=0 OR samples.parentid IS NULL)")
		->where('samples.Flag', 1)
		->where('samples.facility', '!=', 7148)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;

		// $s="SELECT COUNT(ID) as numsamples FROM samples WHERE receivedstatus = 2 AND YEAR(samples.datereceived)='$yea' AND samples.repeatt=0 AND samples.Flag=1 and samples.eqa=0";
  
	}


	//national patients HEI follow up or validation status
	public function GetHEIFollowUpNational($year, $estatus, $col="samples.enrollmentstatus", $monthly=true)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(DISTINCT samples.patient,samples.facility) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->where('samples.result', 2)
		->whereYear('datetested', $year)
		->whereBetween('patients.age', [0.0001, 24])
		->whereIn('samples.pcrtype', [1, 2, 3])
		->where($col, $estatus)
		->where('samples.Flag', 1)
		->where('samples.facility', '!=', 7148)
		->where('samples.repeatt', 0)
		->when(true, function($query) use ($col){
			if($col == "samples.enrollmentstatus"){
				return $query->where('hei_validation', 1);
			}			
		})
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;

		// $sql=mysql_query("SELECT samples.ID from samples where   YEAR(samples.datetested)='$currentyear' AND MONTH(samples.datetested)='$currentmonth'  and samples.repeatt=0 and samples.result =2 AND samples.flag=1 AND  samples.enrollmentstatus ='$estatus' and samples.eqa=0 AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) ") or die(mysql_error());

  
	}

	//national sites by period
	public function GettotalEIDsitesbytimeperiod($year, $monthly=true)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(DISTINCT samples.facility) as totals, month(datereceived) as month"))
		->whereYear('datereceived', $year)
		->where('samples.Flag', 1)
		->where('samples.facility', '!=', 7148)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;

		// $strQuery=mysql_query("SELECT COUNT(DISTINCT samples.facility) as 'activesites' FROM $tb WHERE YEAR($tb.datereceived)='$currentyear'  AND MONTH($tb.datereceived)='$currentmonth' and $tb.eqa=0 and $tb.flag=1")or die(mysql_error());

  
	}

	// Average age	
	public function Getoverallaverageage($year, $monthly=true)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("AVG(patients.age) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->where('samples.pcrtype', 1)
		->where('patients.age', '>', 0)
		->where('patients.age', '<', 24)
		->where('samples.result', '>', 0)
		->whereYear('datetested', $year)
		->where('samples.Flag', 1)
		->where('samples.facility', '!=', 7148)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;

		// $s="SELECT AVG(patients.age) as averageage FROM samples,patients WHERE samples.patientauotid=patients.autoID AND patients.age < 24 and patients.age >0  AND samples.result >0  AND YEAR(samples.datetested)='$yea' AND samples.Flag=1 and samples.eqa=0";              
	}


	// Median age	
	public function Getoverallmedianage($year, $monthly=true)
	{
		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("patients.age, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->where('samples.pcrtype', 1)
		->where('patients.age', '>', 0)
		->where('patients.age', '<', 24)
		->where('samples.result', '>', 0)
		->whereYear('datetested', $year)
		->where('samples.Flag', 1)
		->where('samples.facility', '!=', 7148)
		->get(); 


		// return $data;

		// $s="SELECT patients.age  FROM samples,patients WHERE samples.patientautoid=patients.autoID AND patients.age < 24 and patients.age >0  AND samples.result >0  AND YEAR(samples.datetested)='$yea' AND samples.Flag=1 and samples.eqa=0";

		$return;

		if($monthly){
			for ($i=0; $i < 12; $i++) { 
				$month = $i + 1;
				$subset = $data->where('month', $month);

				if($subset->isEmpty()){
					break;
				}
				else{
					$return[$i]['totals'] = $subset->median('age');
					$return[$i]['month'] = $subset->median('month');
				}

			}
		}
		else{
			$return = $data->median('age');
		}

		

		return $return;
		              
	}

	// infant proph nat summary
	public function Getinfantprophpositivitycount($year, $drug, $result_type, $monthly=true)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(patients.AutoID) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->where('patients.prophylaxis', $drug)
		->where('samples.result', $result_type)
		->where('samples.pcrtype', 1)
		->whereYear('datetested', $year)
		->where('samples.Flag', 1)
		->where('samples.facility', '!=', 7148)
		->where('samples.repeatt', 0)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;

		// $prophQuery =mysql_query("SELECT count(patients.AutoID) as 'TotOutput'
  		//           FROM samples,patients WHERE  samples.patientautoid=patients.autoID    AND patients.prophylaxis='$drug'   AND  (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))  AND    samples.result='$resulttype' AND YEAR(samples.datetested)='$yea' AND  samples.repeatt=0 AND samples.Flag=1 and samples.eqa=0  ") or die(mysql_error());
              
	}


	// mother proph nat summary
	public function Getinterventionspositivitycount($year, $drug, $result_type, $monthly=true)
	{
		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->join('mothers', 'patients.mother', '=', 'mothers.ID')
		->where('mothers.prophylaxis', $drug)
		->where('samples.result', $result_type)
		->where('samples.pcrtype', 1)
		->whereYear('datetested', $year)
		->where('samples.Flag', 1)
		->where('samples.facility', '!=', 7148)
		->where('samples.repeatt', 0)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;

		// $s="SELECT COUNT(samples.ID) as numtests from samples,patients,mothers where samples.patientautoid=patients.autoID AND patients.mother=mothers.ID AND samples.result='$resulttype' AND YEAR(samples.datetested)='$yea' and samples.repeatt=0 AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) and samples.eqa=0   AND samples.Flag=1 AND mothers.prophylaxis='$drug'";
              
	}

	// entry point national summary
	public function GetNationalResultbyEntrypoint($year, $entry_point, $result_type, $monthly=true)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->join('mothers', 'patients.mother', '=', 'mothers.ID')
		->where('mothers.entry_point', $entry_point)
		->where('samples.result', $result_type)
		->where('samples.pcrtype', 1)
		->whereYear('datetested', $year)
		->where('samples.Flag', 1)
		->where('samples.facility', '!=', 7148)
		->where('samples.repeatt', 0)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get();

		return $data; 

		// $s="SELECT count(samples.ID) as numsamples
     // FROM samples,patients,mothers WHERE  samples.result ='$resulttype' AND YEAR(samples.datetested)='$yea'  AND samples.patientautoid=patients.autoID  AND patients.mother=mothers.ID AND mothers.entry_point='$entrypoint' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND samples.repeatt=0 AND samples.Flag=1 and samples.eqa=0";

              
	}


	//samples for a particular range	
	public function GetTestOutcomesbyAgeBand($year, $a, $result_type, $monthly=true)
	{
		$b = new BaseModel;
		$age = $b->age_band($a);

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(patients.AutoID) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->whereBetween('patients.age', $age)
		->where('samples.result', $result_type)
		->whereYear('datetested', $year)
		->where('samples.pcrtype', 1)
		->where('samples.Flag', 1)
		->where('samples.facility', '!=', 7148)
		->where('samples.repeatt', 0)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;

		// $prophQuery =mysql_query("SELECT count(patients.AutoID) as 'TotOutput'
            // FROM samples,patients WHERE  samples.patientautoid=patients.autoID    AND patients.age BETWEEN $fromage AND $endage  AND  (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))  AND    samples.result='$resultype' AND YEAR(samples.datetested)='$yea' AND  samples.repeatt=0 AND samples.Flag=1 and samples.eqa=0  ") or die(mysql_error());

  
	}

	//samples for a particular range	
	public function OutcomesByAgeBand($year, $age_array, $result_type, $monthly=true)
	{
		// use eid_kemri2;
		// select COUNT(DISTINCT samples.patient,samples.facility) as totals
		// from samples
		// join patients on samples.patientautoid=patients.autoID
		// where result between 1 and 2 and year(datetested)=2017
		// and pcrtype=1 and flag=1 and eqa=0 and repeatt=0
		// and patients.age between 0.0001 and 2;

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(patients.AutoID) as totals, month(datetested) as month"))
		->join('patients', 'samples.patientautoid', '=', 'patients.autoID')
		->whereBetween('patients.age', $age_array)
		->where('samples.result', $result_type)
		->whereYear('datetested', $year)
		->where('samples.pcrtype', 1)
		->where('samples.Flag', 1)
		->where('samples.facility', '!=', 7148)
		->where('samples.repeatt', 0)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;
	}

	//National rejections
	public function national_rejections($year, $rejected_reason, $monthly=true){

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datereceived) as month"))
		->where('receivedstatus', 2)
		->where('rejectedreason', $rejected_reason)
		->whereYear('datereceived', $year)
		->where('samples.Flag', 1)
		->where('samples.facility', '!=', 7148)
		->where('samples.repeatt', 0)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;
	}


	// National Tat	
	public function GetNatTATs($year, $monthly=true)
	{
		// $sql = "datediff(datereceived, datecollected) as tat1, datediff(datetested, datereceived) as tat2, datediff(datedispatched, datetested) as tat3, datediff(datedispatched, datecollected) as tat4, datecollected, datereceived, datetested, datedispatched, month(datetested) as month";
		
		$sql = "datecollected, datereceived, datetested, datedispatched, month(datetested) as month";

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
			['datetested', '<=', 'datedispatched']
		])
		->whereYear('datetested', $year)
		->where('samples.Flag', 1)
		->where('samples.repeatt', 0)
		->get(); 

		// return $data;

		$return = null;
		$b = new BaseModel;

		if ($monthly) {
			for ($i=0; $i < 12; $i++) { 
				$month = $i + 1;
				$d = $data->where('month', $month);

				if($d->isEmpty()){
					break;
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

				$return[$i]['tat1'] = floor($tat1 / $rows);
				$return[$i]['tat2'] = floor($tat2 / $rows);
				$return[$i]['tat3'] = floor($tat3 / $rows);
				$return[$i]['tat4'] = floor($tat4 / $rows);
				$return[$i]['month'] = $month;

			}
		}

		else{
			$tat1 = $tat2 = $tat3 = $tat4 = 0;
			$rows = $data->count();

			foreach ($data as $key => $value) {
				$holidays = $b->getTotalHolidaysinMonth($value->month);

				$tat1 += $b->get_days($value->datecollected, $value->datereceived, $holidays);
				$tat2 += $b->get_days($value->datereceived, $value->datetested, $holidays);
				$tat3 += $b->get_days($value->datetested, $value->datedispatched, $holidays);
				$tat4 += $b->get_days($value->datecollected, $value->datedispatched, $holidays);

			}

			$return['tat1'] = floor($tat1 / $rows);
			$return['tat2'] = floor($tat2 / $rows);
			$return['tat3'] = floor($tat3 / $rows);
			$return['tat4'] = floor($tat4 / $rows);
		}
		

		return $return;

		              
	}

	// National Tat	
	public function get_tat($year, $monthly=true)
	{	
		$sql = "AVG(tat1) AS tat1, AVG(tat2) AS tat2, AVG(tat3) AS tat3, AVG(tat4) AS tat4, month(datetested) as month";

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw($sql))
		->whereYear('samples.datecollected', '>', 1980)
		->whereColumn([
			['datecollected', '<=', 'datereceived'],
			['datereceived', '<=', 'datetested'],
			['datetested', '<=', 'datedispatched']
		])
		->whereYear('samples.datecollected', '>', 1980)
		->whereYear('samples.datereceived', '>', 1980)
		->whereYear('samples.datetested', '>', 1980)
		->whereYear('samples.datedispatched', '>', 1980)
		->whereYear('datetested', $year)
		->where('samples.Flag', 1)
		->where('samples.repeatt', 0)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;
	}

	// Update samples tats	
	public function update_tats($year)
	{
		// $sql = "datediff(datereceived, datecollected) as tat1, datediff(datetested, datereceived) as tat2, datediff(datedispatched, datetested) as tat3, datediff(datedispatched, datecollected) as tat4, datecollected, datereceived, datetested, datedispatched, month(datetested) as month";
		
		$sql = "samples.ID, datecollected, datereceived, datetested, datedispatched, month(datetested) as month";
		$b = new BaseModel;


		echo "\n Begin eid samples tat update for {$year} at " . date('d/m/Y h:i:s a', time());

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
			['datetested', '<=', 'datedispatched']
		])
		->whereYear('datetested', $year)
		->where('samples.Flag', 1)
		->where('samples.repeatt', 0)
		->get(); 

		foreach ($data as $key => $value) {
			$holidays = $b->getTotalHolidaysinMonth($value->month);

			$tat1 = $b->get_days($value->datecollected, $value->datereceived, $holidays);
			$tat2 = $b->get_days($value->datereceived, $value->datetested, $holidays);
			$tat3 = $b->get_days($value->datetested, $value->datedispatched, $holidays);
			// $tat4 = $b->get_days($value->datecollected, $value->datedispatched, $holidays);
			$tat4 = $tat1 + $tat2 + $tat3;

			$update_array = array('tat1' => $tat1, 'tat2' => $tat2, 'tat3' => $tat3, 'tat4' => $tat4);

			DB::connection('eid_wr')->table('samples')->where('ID', $value->ID)->update($update_array);

		}
		echo "\n Completed eid samples tat update for {$year} at " . date('d/m/Y h:i:s a', time());
	}

	public function confirmatory_report($year){

    	$raw = "samples.ID, samples.patient, samples.facility, samples.datetested";

    	$data = DB::connection('eid')
		->table("samples")
		->select(DB::raw($raw))
		->orderBy('samples.facility', 'desc')
		->whereYear('datetested', $year)
		->where('pcrtype', 4)
		->where('samples.repeatt', 0)
		->where('samples.Flag', 1)
		->where('samples.facility', '!=', 7148)
		->get();

		echo "\n Begin eid samples confirmatory update for {$year} at " . date('d/m/Y h:i:s a', time());

		$i = 0;
		$result = null;

		foreach ($data as $sample) {

	    	$d = DB::connection('eid')
			->table("samples")
			->select(DB::raw($raw))
			->where('facility', $sample->facility)
			->where('patient', $sample->patient)
			->whereDate('datetested', '<', $sample->datetested)
			->where('result', 1)
			->where('repeatt', 0)
			->where('Flag', 1)
			->where('facility', '!=', 7148)
			->where('pcrtype', '<', 4)
			->first();

			if($d == null){
				DB::connection('eid_wr')->table('samples')->where('ID', $sample->ID)->update(['previous_positive' => 1]);
			}
		}
		echo "\n Completed eid samples confirmatory update for {$year} at " . date('d/m/Y h:i:s a', time());
    }

	


}
