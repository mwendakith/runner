<?php

namespace App\V2;

use DB;
use App\V2\BaseModel;
use App\SampleSynchView;

class EidDivision
{
    //Control Tests
	public function control_samples($year)
	{
		$date_range = BaseModel::date_range($year);

    	$data = DB::connection('eid')
		->table('worksheets_eid')
		->selectRaw("COUNT(*) as totals, lab, month(daterun) as month")
		->whereBetween('daterun', $date_range)
		->groupBy('month', 'lab')
		->get();

		return $data;
	}

    //Mapping
	public function lab_county_tests($year, $division='county')
	{
		$date_range = BaseModel::date_range($year);

    	$data = SampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month, lab")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
		->where('facility_id', '!=', 7148)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->groupBy('month', 'lab')
		->get();

		return $data;
	}
	
    //Mapping
	public function lab_mapping_sites($year, $division='county')
	{
		$date_range = BaseModel::date_range($year);

    	$data = SampleSynchView::selectRaw("COUNT(DISTINCT facility_id) as totals, month(datetested) as month, lab")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
		->where('facility_id', '!=', 7148)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->groupBy('month', 'lab')
		->get();

		return $data;
	}

    // Total number of batches
    public function GettotalbatchesPerlab($year, $division='county', $monthly=true)
    {
		$date_range = BaseModel::date_range($year);

    	$data = SampleSynchView::selectRaw("COUNT(DISTINCT batch_id) as totals, month(datetested) as month")
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
		->whereBetween('datetested', $date_range)
		->whereRaw("(parentid=0  OR parentid IS NULL)")
		->where('flag', 1)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get();

		return $data;
    }

    //national ALL tests EQA + INFANTS
	public function CumulativeTestedSamples($year, $division='county', $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
				// return $query->where('facility_id', '!=', 7148);
			}
			else{
				return $query->where('repeatt', 0);
			}
		})
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
		->where('result', '>', 0)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get();

		return $data;
	}

	

	//national EQA tests
	public function OverallEQATestedSamples($year, $division='county', $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
				// return $query->where('facility_id', '!=', 7148);
			}
		})
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
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
	public function OverallTestedSamples($year, $division='county', $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
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
	public function OverallTestedPatients($year, $pos=false, $division='county', $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleSynchView::selectRaw("COUNT(DISTINCT patient_id) as totals, month(datetested) as month")
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
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
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}

	//national tests
	public function OverallReceivedSamples($year, $division='county', $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleSynchView::selectRaw("COUNT(id) as totals, month(datereceived) as month")
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
		->whereBetween('datereceived', $date_range)
		->whereRaw("(parentid=0 OR parentid IS NULL)")
		->where('flag', 1)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}

	//national false confirmatory
	public function false_confirmatory($year, $division='county', $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
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

	public function getbypcr($year, $pcrtype=1, $pos=false, $division='county', $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
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
		->where('flag', 1)
		->where('repeatt', 0)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}

	//samples for a particular range	
	public function Gettestedsamplescountrange($year, $a, $pos=false, $division='county', $monthly=true)
	{
		$date_range = BaseModel::date_range($year);
		$age = BaseModel::age_range($a);

		$data = SampleSynchView::selectRaw("COUNT(DISTINCT patient_id) as totals, month(datetested) as month")
		->whereBetween('age', $age)
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
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
		->where('repeatt', 0)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
  
	}

	//national outcomes	
	public function OverallTestedSamplesOutcomes($year, $result_type, $pcrtype, $division='county', $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
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
		->where('repeatt', 0)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}


	//national rejected	
	public function Getnationalrejectedsamples($year, $division='county', $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleSynchView::selectRaw("COUNT(id) as totals, month(datereceived) as month")
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
		->whereBetween('datereceived', $date_range)
		->whereRaw("(parentid=0 OR parentid IS NULL)")
		->where('receivedstatus', 2)
		->where('flag', 1)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}


	//national patients HEI follow up or validation status
	public function GetHEIFollowUpNational($year, $estatus, $col="enrollment_status", $division='county', $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleSynchView::selectRaw("COUNT(DISTINCT patient_id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
		->where('result', 2)
		->whereBetween('datetested', $date_range)
		->whereBetween('age', [0.0001, 24])
		->whereIn('pcrtype', [1, 2, 3])
		->where($col, $estatus)
		->where('flag', 1)
		->where('repeatt', 0)
		->when(true, function($query) use ($col){
			if($col == "enrollment_status"){
				return $query->where('hei_validation', 1);
			}			
		})
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}

	//national sites by period
	public function GettotalEidsitesbytimeperiod($year, $division='county', $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleSynchView::selectRaw("COUNT(DISTINCT facility_id) as totals, month(datereceived) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->whereBetween('datereceived', $date_range)
		->where('flag', 1)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}

	// Average age	
	public function Getoverallaverageage($year, $division='county', $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleSynchView::selectRaw("AVG(age) as totals, month(datetested) as month")
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
		->where('pcrtype', 1)
		->where('age', '>', 0)
		->where('age', '<', 24)
		->where('result', '>', 0)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;             
	}


	// Median age	
	public function Getoverallmedianage($year, $div_array, $division='county', $col='county', $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleSynchView::selectRaw("age, month(datetested) as month")
		->join('patients', 'patientautoid', '=', 'patient_id')
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
		->where('pcrtype', 1)
		->where('age', '>', 0)
		->where('age', '<', 24)
		->where('result', '>', 0)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->get(); 

		$return;

		$place = 0;

		if($monthly){

			for ($i=0; $i < 12; $i++) { 
				$month = $i + 1;

				for ($iterator=0; $iterator < count($div_array); $iterator++) { 
					$c = $div_array[$iterator];
					
					$d = $data->where('month', $month)->where($col, $c);

					if($d->isEmpty()){
						$return[$place]['totals'] = 0;
						$return[$place]['division'] = $c;
						$return[$place]['month'] = $month;
						continue;
					}

					$return[$place]['totals'] = $d->median('age');
					$return[$place]['division'] = $c;
					$return[$place]['month'] = $month;

					$place++;

				}


			}
		}

		else{
			for ($iterator=0; $iterator < count($div_array); $iterator++) { 
				$c = $div_array[$iterator];
				
				$d = $data->where($col, $c);

				if($d->isEmpty()){
					$return[$place]['totals'] = 0;
					$return[$place]['division'] = $c;
					continue;
				}

				$return[$place]['totals'] = $d->median('age');
				$return[$place]['division'] = $c;

				$place++;

			}
		}
		return $return;
	}

	// infant proph nat summary
	public function Getinfantprophpositivitycount($year, $drug, $result_type, $division='county', $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleSynchView::selectRaw("COUNT(patient_id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
		->where('regimen', $drug)
		->where('result', $result_type)
		->where('pcrtype', 1)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->where('repeatt', 0)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;              
	}


	// mother proph nat summary
	public function Getinterventionspositivitycount($year, $drug, $result_type, $division='county', $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
		->where('mother_prophylaxis', $drug)
		->where('result', $result_type)
		->where('pcrtype', 1)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->where('repeatt', 0)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}

	// entry point national summary
	public function GetNationalResultbyEntrypoint($year, $entry_point, $result_type, $division='county', $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
		->where('entry_point', $entry_point)
		->where('result', $result_type)
		->where('pcrtype', 1)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->where('repeatt', 0)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get();

		return $data;
	}

	//samples for a particular range	
	public function OutcomesByAgeBand($year, $age_array, $result_type, $division='county', $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleSynchView::selectRaw("COUNT(patient_id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
		->whereBetween('age', $age_array)
		->where('result', $result_type)
		->whereBetween('datetested', $date_range)
		->where('pcrtype', 1)
		->where('flag', 1)
		->where('repeatt', 0)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}

	//National rejections
	public function national_rejections($year, $rejected_reason, $division='county', $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data = SampleSynchView::selectRaw("COUNT(id) as totals, month(datereceived) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
		->where('receivedstatus', 2)
		->where('rejectedreason', $rejected_reason)
		->whereBetween('datereceived', $date_range)
		->where('flag', 1)
		->where('repeatt', 0)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}

	// Tat
	public function get_tat($year, $division='county', $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$sql = "AVG(tat1) AS tat1, AVG(tat2) AS tat2, AVG(tat3) AS tat3, AVG(tat4) AS tat4, month(datetested) as month";

		$data = SampleSynchView::selectRaw($sql)
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
		->whereColumn([
			['datecollected', '<=', 'datereceived'],
			['datereceived', '<=', 'datetested'],
			['datetested', '<=', 'datedispatched']
		])
		->whereYear('datecollected', '>', 1980)
		->whereYear('datereceived', '>', 1980)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->where('repeatt', 0)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}

	public function update_patients(){
		$sql = "id, patient, facility_id, batch_id, view_facilitys.name, view_facilitys.facilitycode, view_facilitys.DHIScode, age, gender, prophylaxis as infantproph, entry_point, feeding, prophylaxis, datecollected, receivedstatus, pcrtype, rejectedreason, reason_for_repeat, datereceived, datetested, result, datedispatched, lab, month(datetested) as month";

    	$raw = "id, patient, facility_id, datetested";

		ini_set("memory_limit", "-1");
		
		$data = SampleSynchView::selectRaw($sql)
		->where('flag', 1)
		->where('repeatt', 0)
		->where('synched', 0)
		->get();

		$today=date('Y-m-d');

		$b = new BaseModel;

		$p=0;

		foreach ($data as $key => $value) {

			$holidays = $b->getTotalHolidaysinMonth($value->month);

			$tat1 = $b->get_days($value->datecollected, $value->datereceived, $holidays);
			$tat2 = $b->get_days($value->datereceived, $value->datetested, $holidays);
			$tat3 = $b->get_days($value->datetested, $value->datedispatched, $holidays);
			$tat4 = $b->get_days($value->datecollected, $value->datedispatched, $holidays);
			// $tat4 = $tat1 + $tat2 + $tat3;

			$update_array = array('synched' => 1, 'datesynched' => $today, 'tat1' => $tat1, 'tat2' => $tat2, 'tat3' => $tat3, 'tat4' => $tat4);
			// $update_array = array('synched' => 1, 'datesynched' => $today);

			if($value->pcrtype == 4){

		    	$d = DB::connection('eid')
				->table("samples")
				->select(DB::raw($raw))
				->where('facility', $value->facility)
				->where('patient', $value->patient)
				->whereDate('datetested', '<', $value->datetested)
				->where('result', 1)
				->where('repeatt', 0)
				->where('flag', 1)
				->where('facility', '!=', 7148)
				->where('pcrtype', '<', 4)
				->first();

				if($d == null){
					$update_array = array_merge($update_array, ['previous_positive' => 1]);
				}
			}

			DB::connection('eid_wr')->table('samples')->where('id', $value->id)->update($update_array);
			$p++;
		}
		echo "\n {$p} eid patients synched.";
	}

	
}
