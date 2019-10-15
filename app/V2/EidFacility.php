<?php

namespace App\V2;

use DB;
use App\V2\BaseModel;
use App\SampleSynchView;
use App\SampleView;

class EidFacility
{


	// Callbacks
	public function get_callback($division, $date_range)
	{
		return function($query) use($division, $date_range){
			if($division == 'partner_id'){
				$query->join('partner_facilities', 'sample_synch_view.facility_id', '=', 'partner_facilities.facility_id')
					->whereRaw("((start_date <= {$date_range[0]} AND end_date >= {$date_range[1]}) OR 
						(start_date <= {$date_range[0]} AND end_date IS NULL) )");
			}
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
			return $query->where('site_entry', 2);
		};
	}

	public function get_eqa_callback($division)
	{
		return function($query) use($division){
			if($division == "lab" || $division == "facility"){
				return $query->where('facility_id', '!=', 7148);
			}
		};
	}




    //Control Tests
	public function control_samples($year, $month)
	{
		$date_range = BaseModel::date_range_month($year, $month);

    	$data = DB::connection('eid_vl_wr')
		->table('worksheets')
		->selectRaw("COUNT(*) as totals, lab_id as lab")
		->whereBetween('daterun', $date_range)
		->groupBy('lab')
		->get();

		return $data;
	}

    //Mapping
	public function lab_county_tests($year, $month, $division='county')
	{
		$date_range = BaseModel::date_range_month($year, $month);

    	$data = SampleSynchView::selectRaw("COUNT(sample_synch_view.id) as totals, lab")
		->when(true, $this->get_callback($division, $date_range))
		->where('facility_id', '!=', 7148)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->groupBy('lab')
		->get();

		return $data;
	}
	
    //Mapping
	public function lab_mapping_sites($year, $month, $division='county')
	{
		$date_range = BaseModel::date_range_month($year, $month);

    	$data = SampleSynchView::selectRaw("COUNT(DISTINCT facility_id) as totals, lab")
		->when(true, $this->get_callback($division, $date_range))
		->where('facility_id', '!=', 7148)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->groupBy('lab')
		->get();

		return $data;
	}

    // Total number of batches
    public function GettotalbatchesPerlab($year, $month, $division='county')
    {
		$date_range = BaseModel::date_range_month($year, $month);

    	$data = SampleSynchView::selectRaw("COUNT(DISTINCT batch_id) as totals")
		->when(true, $this->get_eqa_callback($division))
		->when(true, $this->get_callback($division, $date_range))
		->whereBetween('datetested', $date_range)
		->whereRaw("(parentid=0  OR parentid IS NULL)")
		->where('flag', 1)
		->get();

		return $data;
    }

    //national ALL tests EQA + INFANTS
	public function CumulativeTestedSamples($year, $month, $division='county')
	{
		$date_range = BaseModel::date_range_month($year, $month);

		$data = SampleSynchView::selectRaw("COUNT(sample_synch_view.id) as totals")
		->when(($division != "lab"), function($query){
			return $query->where('repeatt', 0);
		})
		->when(true, $this->get_callback($division, $date_range))
		->where('result', '>', 0)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->get();

		return $data;
	}

	

	//national EQA tests
	public function OverallEQATestedSamples($year, $month, $division='county')
	{
		$date_range = BaseModel::date_range_month($year, $month);

		$data = SampleSynchView::selectRaw("COUNT(sample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->where('result', '>', 0)
		->whereBetween('datetested', $date_range)
		->where('facility_id', 7148)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;  
	}

	//national tests
	public function OverallTestedSamples($year, $month, $division='county')
	{
		$date_range = BaseModel::date_range_month($year, $month);

		$data = SampleSynchView::selectRaw("COUNT(sample_synch_view.id) as totals")
		->when(true, $this->get_eqa_callback($division))
		->when(true, $this->get_callback($division, $date_range))
		->whereIn('result', [1, 2])
		->whereBetween('datetested', $date_range)
		->where('repeatt', 0)
		->where('flag', 1)
		->get(); 

		return $data; 
	}

	//national tests
	public function OverallTestedPatients($year, $month, $pos=false, $division='county')
	{
		$date_range = BaseModel::date_range_month($year, $month);

		$data = SampleSynchView::selectRaw("COUNT(DISTINCT patient_id) as totals")
		->when(true, $this->get_eqa_callback($division))
		->when(true, $this->get_callback($division, $date_range))
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
		->get(); 

		return $data;
	}

	//national tests
	public function OverallReceivedSamples($year, $month, $division='county')
	{
		$date_range = BaseModel::date_range_month($year, $month);

		$data = SampleSynchView::selectRaw("COUNT(sample_synch_view.id) as totals")
		->when(true, $this->get_eqa_callback($division))
		->when(true, $this->get_callback($division, $date_range))
		->whereBetween('datereceived', $date_range)
		->whereRaw("(parentid=0 OR parentid IS NULL)")
		->where('flag', 1)
		->get(); 

		return $data;
	}

	//national false confirmatory
	public function false_confirmatory($year, $month, $division='county')
	{
		$date_range = BaseModel::date_range_month($year, $month);

		$data = SampleSynchView::selectRaw("COUNT(sample_synch_view.id) as totals")
		->when(true, $this->get_eqa_callback($division))
		->when(true, $this->get_callback($division, $date_range))
		->where('previous_positive', 1)
		->whereBetween('datetested', $date_range)
		->where('repeatt', 0)
		->where('flag', 1)
		->get(); 

		return $data;
	}

	public function getbypcr($year, $month, $pcrtype=1, $pos=false, $division='county')
	{
		$date_range = BaseModel::date_range_month($year, $month);

		$data = SampleSynchView::selectRaw("COUNT(sample_synch_view.id) as totals")
		->when(true, $this->get_eqa_callback($division))
		->when(true, $this->get_callback($division, $date_range))
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
		->get(); 

		return $data;
	}

	//samples for a particular range	
	public function Gettestedsamplescountrange($year, $month, $a, $pos=false, $division='county')
	{
		$date_range = BaseModel::date_range_month($year, $month);
		$age = BaseModel::age_range($a);

		$data = SampleSynchView::selectRaw("COUNT(DISTINCT patient_id) as totals")
		->whereBetween('age', $age)
		->when(true, $this->get_callback($division, $date_range))
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
		->get(); 

		return $data;
  
	}

	//national outcomes	
	public function OverallTestedSamplesOutcomes($year, $month, $result_type, $pcrtype, $division='county')
	{
		$date_range = BaseModel::date_range_month($year, $month);

		$data = SampleSynchView::selectRaw("COUNT(sample_synch_view.id) as totals")
		->when(true, $this->get_eqa_callback($division))
		->when(true, $this->get_callback($division, $date_range))
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
		->get(); 

		return $data;
	}


	//national rejected	
	public function Getnationalrejectedsamples($year, $month, $division='county')
	{
		$date_range = BaseModel::date_range_month($year, $month);

		$data = SampleSynchView::selectRaw("COUNT(sample_synch_view.id) as totals")
		->when(true, $this->get_eqa_callback($division))
		->when(true, $this->get_callback($division, $date_range))
		->whereBetween('datereceived', $date_range)
		->whereRaw("(parentid=0 OR parentid IS NULL)")
		->where('receivedstatus', 2)
		->where('flag', 1)
		->get(); 

		return $data;
	}


	//national patients HEI follow up or validation status
	public function GetHEIFollowUpNational($year, $month, $estatus, $col="enrollment_status", $division='county')
	{
		$date_range = BaseModel::date_range_month($year, $month);

		$data = SampleSynchView::selectRaw("COUNT(DISTINCT patient_id) as totals")
		->when(true, $this->get_callback($division, $date_range))
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
		->get(); 

		return $data;
	}

	//national sites by period
	public function GettotalEidsitesbytimeperiod($year, $month, $division='county')
	{
		$date_range = BaseModel::date_range_month($year, $month);

		$data = SampleSynchView::selectRaw("COUNT(DISTINCT facility_id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->when(true, $this->get_eqa_callback($division))
		->whereBetween('datereceived', $date_range)
		->where('flag', 1)
		->get(); 

		return $data;
	}

	// Average age	
	public function Getoverallaverageage($year, $month, $division='county')
	{
		$date_range = BaseModel::date_range_month($year, $month);

		$data = SampleSynchView::selectRaw("AVG(age) as totals")
		->when(true, $this->get_eqa_callback($division))
		->when(true, $this->get_callback($division, $date_range))
		->where('pcrtype', 1)
		->where('age', '>', 0)
		->where('age', '<', 24)
		->where('result', '>', 0)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->get(); 

		return $data;             
	}


	// Median age	
	public function Getoverallmedianage($year, $month, $div_array, $division='county', $col='county')
	{
		$date_range = BaseModel::date_range_month($year, $month);

		$data = SampleSynchView::selectRaw("age")
		->when(true, $this->get_eqa_callback($division))
		->when(true, $this->get_callback($division, $date_range))
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
	public function Getinfantprophpositivitycount($year, $month, $drug, $result_type, $division='county')
	{
		$date_range = BaseModel::date_range_month($year, $month);

		$data = SampleSynchView::selectRaw("COUNT(patient_id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->where('regimen', $drug)
		->where('result', $result_type)
		->where('pcrtype', 1)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->where('repeatt', 0)
		->get(); 

		return $data;              
	}


	// mother proph nat summary
	public function Getinterventionspositivitycount($year, $month, $drug, $result_type, $division='county')
	{
		$date_range = BaseModel::date_range_month($year, $month);

		$data = SampleSynchView::selectRaw("COUNT(sample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->where('mother_prophylaxis', $drug)
		->where('result', $result_type)
		->where('pcrtype', 1)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->where('repeatt', 0)
		->get(); 

		return $data;
	}

	// entry point national summary
	public function GetNationalResultbyEntrypoint($year, $month, $entry_point, $result_type, $division='county')
	{
		$date_range = BaseModel::date_range_month($year, $month);

		$data = SampleSynchView::selectRaw("COUNT(sample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->where('entry_point', $entry_point)
		->where('result', $result_type)
		->where('pcrtype', 1)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
	}

	//samples for a particular range	
	public function OutcomesByAgeBand($year, $month, $age_array, $result_type, $division='county')
	{
		$date_range = BaseModel::date_range_month($year, $month);

		$data = SampleSynchView::selectRaw("COUNT(patient_id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->whereBetween('age', $age_array)
		->where('result', $result_type)
		->whereBetween('datetested', $date_range)
		->where('pcrtype', 1)
		->where('flag', 1)
		->where('repeatt', 0)
		->get(); 

		return $data;
	}

	//National rejections
	public function national_rejections($year, $month, $rejected_reason, $division='county')
	{
		$date_range = BaseModel::date_range_month($year, $month);

		$data = SampleSynchView::selectRaw("COUNT(sample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->where('receivedstatus', 2)
		->where('rejectedreason', $rejected_reason)
		->whereBetween('datereceived', $date_range)
		->where('flag', 1)
		->where('repeatt', 0)
		->get(); 

		return $data;
	}

	// Tat
	public function get_tat($year, $month, $division='county')
	{
		$date_range = BaseModel::date_range_month($year, $month);

		$sql = "AVG(tat1) AS tat1, AVG(tat2) AS tat2, AVG(tat3) AS tat3, AVG(tat4) AS tat4";

		$data = SampleSynchView::selectRaw($sql)
		->when(true, $this->get_callback($division, $date_range))
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
		->get(); 

		return $data;
	}

	public function update_patients(){

		ini_set("memory_limit", "-1");
		
		$data = SampleView::where(['flag' => 1, 'repeatt' => 0, 'synched' => 0])->get();

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

				$d = SampleView::where('patient_id', $value->patient_id)
				->where('datetested', '<', $value->datetested)
				->where('result', 1)
				->where('repeatt', 0)
				->where('flag', 1)
				->where('facility_id', '!=', 7148)
				->where('pcrtype', '<', 4)
				->first();

				if($d == null){
					$update_array = array_merge($update_array, ['previous_positive' => 1]);
				}
			}

			DB::connection('eid_vl_wr')->table('samples')->where('id', $value->id)->update($update_array);
			$p++;
		}
		echo "\n {$p} eid patients synched.";
	}

	
}
