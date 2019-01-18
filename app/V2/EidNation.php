<?php

namespace App\V2;

use DB;
use App\V2\BaseModel;
use App\SampleView;

class EidNation
{
    //

    //national ALL tests EQA + INFANTS
	public function CumulativeTestedSamples($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->where('result', '>', 0)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->where('repeatt', 0)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get();

		return $data;
	}

	//national EQA tests
	public function OverallEQATestedSamples($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->where('result', '>', 0)
		->whereBetween('datetested', $date_range)
		->where('facility_id', 7148)
		->where('flag', 1)
		->where('repeatt', 0)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get();

		return $data;
	}

	//national tests
	public function OverallTestedSamples($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->whereIn('result', [1, 2])
		->whereBetween('datetested', $date_range)
		->where('repeatt', 0)
		->where('flag', 1)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;  
	}

	//national tests
	public function OverallTestedPatients($year, $pos=false, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleView::selectRaw("COUNT(DISTINCT patient_id) as totals, month(datetested) as month")
		->when(true, function($query) use ($pos){
			if($pos){
				return $query->where('result', 2);
			}
			else{
				return $query->whereIn('result', [1, 2]);
			}			
		})
		->whereBetween('age', [0.0001, 24])
		->whereIn('pcrtype', [1, 2, 3])
		->whereIn('result', [1, 2])
		->whereBetween('datetested', $date_range)
		->where('repeatt', 0)
		->where('flag', 1)
		->where('facility_id', '!=', 7148)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}

	//national tests
	public function OverallReceivedSamples($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleView::selectRaw("COUNT(id) as totals, month(datereceived) as month")
		->whereBetween('datereceived', $date_range)
		->whereRaw("(parentid=0 OR parentid IS NULL)")
		->where('flag', 1)
		->where('facility_id', '!=', 7148)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}

	//national false confirmatory
	public function false_confirmatory($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->where('facility_id', '!=', 7148)
		->where('previous_positive', 1)
		->whereBetween('datetested', $date_range)
		->where('repeatt', 0)
		->where('flag', 1)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}

	public function getbypcr($year, $pcrtype=1, $pos=false, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($pos){
			if($pos){
				return $query->where('result', 2);
			}
			else{
				return $query->whereIn('result', [1, 2]);
			}			
		})
		->whereBetween('datetested', $date_range)
		->when($pcrtype, function($query) use ($pcrtype){
			if($pcrtype == 2){
				return $query->whereIn('pcrtype', [2, 3]);
			}
			else{
				return $query->where('pcrtype', $pcrtype);
			}			
		})		
		// ->where('pcrtype', $pcrtype)
		->where('flag', 1)
		->where('facility_id', '!=', 7148)
		->where('repeatt', 0)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}

	//samples for a particular range	
	public function Gettestedsamplescountrange($year, $a, $pos=false, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);
		$age = BaseModel::age_range($a);

		$data = SampleView::selectRaw("COUNT(DISTINCT patient_id) as totals, month(datetested) as month")
		->whereBetween('age', $age)
		->when(true, function($query) use ($pos){
			if($pos){
				return $query->where('result', 2);
			}
			else{
				return $query->whereIn('result', [1, 2]);				
			}				
		})
		->when(true, function($query) use ($a){
			if($a != 5){
				return $query->where('pcrtype', 1);
			}
		})
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->where('facility_id', '!=', 7148)
		->where('repeatt', 0)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}

	//national outcomes	
	public function OverallTestedSamplesOutcomes($year, $result_type, $pcrtype, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->where('result', $result_type)
		->whereBetween('datetested', $date_range)
		->when($pcrtype, function($query) use ($pcrtype){
			if($pcrtype == 2){
				return $query->whereIn('pcrtype', [2, 3]);
			}
			else{
				return $query->where('pcrtype', $pcrtype);
			}			
		})		
		->where('flag', 1)
		->where('facility_id', '!=', 7148)
		->where('repeatt', 0)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}


	//national rejected	
	public function Getnationalrejectedsamples($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleView::selectRaw("COUNT(id) as totals, month(datereceived) as month")
		->whereBetween('datereceived', $date_range)
		->where('receivedstatus', 2)
		->whereRaw("(parentid=0 OR parentid IS NULL)")
		->where('flag', 1)
		->where('facility_id', '!=', 7148)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}


	//national patients HEI follow up or validation status
	public function GetHEIFollowUpNational($year, $col="enrollment_status", $monthly=true)
	{
		$date_range = BaseModel::date_range($year); 

		$data = SampleView::selectRaw("COUNT(DISTINCT patient_id) as totals, {$col}, month(datetested) as month")
		->where('result', 2)
		->whereBetween('datetested', $date_range)
		->whereBetween('age', [0.0001, 24])
		->whereIn('pcrtype', [1, 2, 3])
		->where('flag', 1)
		->where('facility_id', '!=', 7148)
		->where('repeatt', 0)
		->when(true, function($query) use ($col){
			if($col == "enrollment_status") return $query->where('hei_validation', 1);
		})
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->groupBy($col)
		->get(); 

		return $data;
	}

	//national sites by period
	public function GettotalEIDsitesbytimeperiod($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleView::selectRaw("COUNT(DISTINCT facility_id) as totals, month(datereceived) as month")
		->whereBetween('datereceived', $date_range)
		->where('flag', 1)
		->where('facility_id', '!=', 7148)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;  
	}

	// Average age	
	public function Getoverallaverageage($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleView::selectRaw("AVG(age) as totals, month(datetested) as month")
		->where('pcrtype', 1)
		->where('age', '>', 0)
		->where('age', '<', 24)
		->where('result', '>', 0)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->where('facility_id', '!=', 7148)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;           
	}


	// Median age	
	public function Getoverallmedianage($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleView::selectRaw("age, month(datetested) as month")
		->where('pcrtype', 1)
		->where('age', '>', 0)
		->where('age', '<', 24)
		->where('result', '>', 0)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->where('facility_id', '!=', 7148)
		->get(); 

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
	public function Getinfantprophpositivitycount($year, $drug, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleView::selectRaw("COUNT(patient_id) as totals, result, month(datetested) as month")
		->where('regimen', $drug)
		->where('pcrtype', 1)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->where('facility_id', '!=', 7148)
		->where('repeatt', 0)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->groupBy('result')
		->get(); 

		return $data;              
	}


	// mother proph nat summary
	public function Getinterventionspositivitycount($year, $drug, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleView::selectRaw("COUNT(id) as totals, result, month(datetested) as month")
		->where('mother_prophylaxis', $drug)
		->where('pcrtype', 1)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->where('facility_id', '!=', 7148)
		->where('repeatt', 0)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->groupBy('result')
		->get(); 

		return $data;
	}

	// entry point national summary
	public function GetNationalResultbyEntrypoint($year, $entry_point, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleView::selectRaw("COUNT(id) as totals, result, month(datetested) as month")
		->where('entry_point', $entry_point)
		->where('pcrtype', 1)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->where('facility_id', '!=', 7148)
		->where('repeatt', 0)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->groupBy('result')
		->get();

		return $data;               
	}

	//samples for a particular range	
	public function OutcomesByAgeBand($year, $age_array, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleView::selectRaw("COUNT(patient_id) as totals, result, month(datetested) as month")
		->whereBetween('age', $age_array)
		->whereBetween('datetested', $date_range)
		->where('pcrtype', 1)
		->where('flag', 1)
		->where('facility_id', '!=', 7148)
		->where('repeatt', 0)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->groupBy('result')
		->get(); 

		return $data;
	}

	//National rejections
	public function national_rejections($year, $rejected_reason, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleView::selectRaw("COUNT(id) as totals, month(datereceived) as month")
		->where('receivedstatus', 2)
		->where('rejectedreason', $rejected_reason)
		->whereBetween('datereceived', $date_range)
		->where('flag', 1)
		->where('facility_id', '!=', 7148)
		->where('repeatt', 0)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}

	// National Tat	
	public function get_tat($year, $monthly=true)
	{	
		$date_range = BaseModel::date_range($year);
		$sql = "AVG(tat1) AS tat1, AVG(tat2) AS tat2, AVG(tat3) AS tat3, AVG(tat4) AS tat4, month(datetested) as month";

		$data = SampleView::selectRaw($sql)
		->whereYear('datecollected', '>', 1980)
		->whereColumn([
			['datecollected', '<=', 'datereceived'],
			['datereceived', '<=', 'datetested'],
			['datetested', '<=', 'datedispatched']
		])
		->whereYear('datecollected', '>', 1980)
		->whereYear('datereceived', '>', 1980)
		->whereYear('datetested', '>', 1980)
		->whereYear('datedispatched', '>', 1980)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->where('repeatt', 0)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}

	// Update samples tats	
	public function update_tats($year)
	{
		$date_range = BaseModel::date_range($year);
		
		$sql = "id, datecollected, datereceived, datetested, datedispatched, month(datetested) as month";
		$b = new BaseModel;
		
		ini_set("memory_limit", "-1");

		echo "\n Begin eid samples tat update for {$year} at " . date('d/m/Y h:i:s a', time());

		$data = SampleView::selectRaw($sql)
		->where('datedispatched', '>', "2000-01-01")
		->whereColumn([
			['datecollected', '<=', 'datereceived'],
			['datereceived', '<=', 'datetested'],
			['datetested', '<=', 'datedispatched']
		])
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->where('repeatt', 0)
		->get(); 

		foreach ($data as $key => $value) {
			$holidays = $b->getTotalHolidaysinMonth($value->month);

			$tat1 = $b->get_days($value->datecollected, $value->datereceived, $holidays);
			$tat2 = $b->get_days($value->datereceived, $value->datetested, $holidays);
			$tat3 = $b->get_days($value->datetested, $value->datedispatched, $holidays);
			$tat4 = $b->get_days($value->datecollected, $value->datedispatched, $holidays);
			// $tat4 = $tat1 + $tat2 + $tat3;

			$update_array = array('tat1' => $tat1, 'tat2' => $tat2, 'tat3' => $tat3, 'tat4' => $tat4);

			DB::connection('eid_vl_wr')->table('samples')->where('id', $value->id)->update($update_array);

		}
		echo "\n Completed eid samples tat update for {$year} at " . date('d/m/Y h:i:s a', time());
	}

	public function confirmatory_report($year)
	{
		$date_range = BaseModel::date_range($year);

    	$raw = "ID, patient, facility_id, datetested";

    	$data = SampleView::selectRaw($raw)
		->orderBy('facility_id', 'desc')
		->whereBetween('datetested', $date_range)
		->where('pcrtype', 4)
		->where('repeatt', 0)
		->where('flag', 1)
		->where('facility_id', '!=', 7148)
		->get();

		echo "\n Begin eid samples confirmatory update for {$year} at " . date('d/m/Y h:i:s a', time());

		$i = 0;
		$result = null;

		foreach ($data as $sample) {

	    	$d = SampleView::selectRaw($raw)
			->where('facility_id', $sample->facility_id)
			->where('patient', $sample->patient)
			->whereDate('datetested', '<', $sample->datetested)
			->where('result', 1)
			->where('repeatt', 0)
			->where('flag', 1)
			->where('facility_id', '!=', 7148)
			->where('pcrtype', '<', 4)
			->first();

			if($d == null){
				DB::connection('eid')->table('samples')->where('ID', $sample->ID)->update(['previous_positive' => 1]);
			}
		}
		echo "\n Completed eid samples confirmatory update for {$year} at " . date('d/m/Y h:i:s a', time());
    }

	


}
