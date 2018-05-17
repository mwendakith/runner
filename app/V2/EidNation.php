<?php

namespace App;

use DB;
use App\V2\BaseModel;

class EidNation
{
    //

    //national ALL tests EQA + INFANTS
	public function CumulativeTestedSamples($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

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
	}

	//national EQA tests
	public function OverallEQATestedSamples($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

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
	}

	//national tests
	public function OverallTestedSamples($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

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
	}

	//national tests
	public function OverallTestedPatients($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

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
	}

	//national tests
	public function OverallTestedPatientsPOS($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

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
	}

	//national tests
	public function OverallReceivedSamples($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

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
	}

	//national false confirmatory
	public function false_confirmatory($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

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

	public function getbypcr($year, $pcrtype=1, $pos=false, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

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
		$date_range = BaseModel::date_range($year);

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
	}

	//national tests confirmatory
	public function OveralldnasecondTestedSamples($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

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
	}

	//national tests confirmatory
	public function OveralldnasecondTestedSamplesPOS($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

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
	}

	//national tests confirmatory
	public function OverallPosRepeatsTestedSamples($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

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
	}

	//national tests confirmatory
	public function OverallPosRepeatsTestedSamplesPOS($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

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
	}

	//samples for a particular range	
	public function Gettestedsamplescountrange($year, $a, $pos=false, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);
		$age = BaseModel::age_range($a);

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
		$date_range = BaseModel::date_range($year);

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
	}


	//national rejected	
	public function Getnationalrejectedsamples($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

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
	}


	//national patients HEI follow up or validation status
	public function GetHEIFollowUpNational($year, $estatus, $col="samples.enrollmentstatus", $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

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
	}

	//national sites by period
	public function GettotalEIDsitesbytimeperiod($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

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
	}

	// Average age	
	public function Getoverallaverageage($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

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
	}


	// Median age	
	public function Getoverallmedianage($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

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
		$date_range = BaseModel::date_range($year);

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
	}


	// mother proph nat summary
	public function Getinterventionspositivitycount($year, $drug, $result_type, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

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
	}

	// entry point national summary
	public function GetNationalResultbyEntrypoint($year, $entry_point, $result_type, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

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
	}

	//samples for a particular range	
	public function OutcomesByAgeBand($year, $age_array, $result_type, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

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
	public function national_rejections($year, $rejected_reason, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

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
	public function get_tat($year, $monthly=true)
	{	
		$date_range = BaseModel::date_range($year);
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
		$date_range = BaseModel::date_range($year);
		
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

			DB::connection('eid')->table('samples')->where('ID', $value->ID)->update($update_array);

		}
		echo "\n Completed eid samples tat update for {$year} at " . date('d/m/Y h:i:s a', time());
	}

	public function confirmatory_report($year)
	{
		$date_range = BaseModel::date_range($year);

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
				DB::connection('eid')->table('samples')->where('ID', $sample->ID)->update(['previous_positive' => 1]);
			}
		}
		echo "\n Completed eid samples confirmatory update for {$year} at " . date('d/m/Y h:i:s a', time());
    }

	


}
