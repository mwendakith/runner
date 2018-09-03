<?php

namespace App\V2;

use DB;
use App\V2\BaseModel;
use App\ViralsampleView;

class VlNation
{
    
	//National rejections
	public function national_rejections($year, $start_month){
		$date_range = BaseModel::date_range($year, $start_month);

		$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datereceived) as month, rejectedreason")
		->where('receivedstatus', 2)
		->whereBetween('datereceived', $date_range)
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month', 'rejectedreason')
		->get(); 

		return $data;
	}

    //
    public function getalltestedviraloadsamples($year, $start_month){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->groupBy('month')
		->get();

		return $data;
    }

    public function getallactualpatients($year, $start_month){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleView::selectRaw("COUNT(DISTINCT patient_id) as totals, month(datetested) as month")
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [1, 2, 3, 4])
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		return $data;
    }

    public function getallreceivediraloadsamples($year, $start_month){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datereceived) as month")
		->whereBetween('datereceived', $date_range)
		->whereRaw("((parentid=0) || (parentid IS NULL))")
		->where('flag', 1)
		->groupBy('month')
		->get();

		return $data;
    }

    public function getallrejectedviraloadsamples($year, $start_month){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datereceived) as month")
		->whereBetween('datereceived', $date_range)
		->whereRaw("((parentid=0) || (parentid IS NULL))")
		->where('receivedstatus', 2)
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		return $data;
    }

    public function GetSupportedfacilitysFORViralLoad($year, $start_month){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleView::selectRaw("COUNT(DISTINCT facility_id) as totals, month(datereceived) as month")
		->whereBetween('datereceived', $date_range)
		->whereRaw("((parentid=0) || (parentid IS NULL))")
		->where('facility_id', '!=', 0)
		->where('flag', 1)
		->groupBy('month')
		->get();

		return $data;
    }

    public function GetNationalConfirmed2VLs($year, $start_month){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [1, 2, 3, 4])
		->whereIn('sampletype', [1, 2, 3, 4])
		->where('justification', 2)
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		return $data;
    }

    public function GetNationalConfirmedFailure($year, $start_month){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [3, 4])
		->whereIn('sampletype', [1, 2, 3, 4])
		->where('justification', 2)
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		return $data;
    }
    

    public function false_confirmatory($year, $start_month){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->where('facility_id', '!=', 7148)
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [1, 2, 3, 4])
		->whereIn('sampletype', [1, 2, 3, 4])
		->where('previous_nonsuppressed', 1)
		->where('justification', 2)
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		return $data;
    }

    public function GetNationalBaseline($year, $start_month){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [1, 2, 3, 4])
		->where('justification', 10)
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		return $data;
    }

    public function GetNationalBaselineFailure($year, $start_month){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [3, 4])
		->where('justification', 10)
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		return $data;
    }


    public function getallrepeattviraloadsamples($year, $start_month){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->whereBetween('datetested', $date_range)
		->where('receivedstatus', 3)
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbytypedetails($year, $start_month, $routine=true){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datetested) as month, sampletype")
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [1, 2, 3, 4])
		->when($routine, function($query) use ($routine){
			return $query
			->where('justification', '!=', 2)
			->where('justification', '!=', 10);
		})
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month', 'sampletype')
		->get();

		return $data;
    }

    public function getalltestedviraloadbygender($year, $start_month, $sex){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datetested) as month, sex")
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [1, 2, 3, 4])
		->whereNotIn('justification', [2, 10])
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month', 'sex')
		->get();

		return $data;
    }

    public function getalltestedviraloadsbyage($year, $start_month, $all=false){
		$date_range = BaseModel::date_range($year, $start_month);

		if($all){
			$sql = "COUNT(id) as totals, month(datetested) as month";
		}

		$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datetested) as month, age_category")
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [1, 2, 3, 4])
		->whereNotIn('justification', [2, 10])
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month', 'age_category')
		->get();

		return $data;
    }

    public function getalltestedviraloadsbyresult($year, $start_month){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datetested) as month, rcategory")
		->whereBetween('datetested', $date_range)
		// ->when($result, function($query) use ($result){
		// 	if($result != 5){
		// 		return $query->whereNotIn('justification', [2, 10]);
		// 	}
		// })
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month', 'rcategory')
		->get();

		return $data;
    }

    public function get_tat($year, $start_month){
		$date_range = BaseModel::date_range($year, $start_month);

    	$sql = "AVG(tat1) AS tat1, AVG(tat2) AS tat2, AVG(tat3) AS tat3, AVG(tat4) AS tat4, month(datetested) as month";

		$data = ViralsampleView::selectRaw($sql)
		->whereColumn([
			['datecollected', '<=', 'datereceived'],
			['datereceived', '<=', 'datetested'],
			['datetested', '<=', 'datedispatched']
		])
		->whereBetween('datetested', $date_range)
		// ->whereYear('datecollected', '>', 1980)
		// ->whereYear('datereceived', '>', 1980)
		// ->whereYear('datedispatched', '>', 1980)
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get(); 

		return $data;
    }

    public function getalltestedviraloadsamplesbydash($year, $start_month, $type, $param){
		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

    	$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
		        ->whereNotIn('justification', [2, 10])
				->whereIn('rcategory', [1, 2, 3, 4]);
			}
			if($type == 4){
				return $query->whereIn('rcategory', [1, 2, 3, 4]);
			}					
		})
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		return $data;
    }

    public function getallreceivediraloadsamplesbydash($year, $start_month, $type, $param){
		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datereceived) as month")
		->whereBetween('datereceived', $date_range)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->whereRaw("((parentid=0) || (parentid IS NULL))")
		->where('flag', 1)
		->groupBy('month')
		->get();

		return $data;
    }

    public function getallrejectedviraloadsamplesbydash($year, $start_month, $type, $param){
		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datereceived) as month")
		->whereBetween('datereceived', $date_range)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('receivedstatus', 2)
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month', $p['column'])
		->get();

		return $data;
    }

    public function GetNationalConfirmed2VLsbydash($year, $start_month, $type, $param){
		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->whereBetween('datetested', $date_range)
		->whereIn('sampletype', [1, 2, 3, 4])
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('justification', 2)
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		return $data;
    }

    public function GetNationalConfirmedFailurebydash($year, $start_month, $type, $param){
		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->whereBetween('datetested', $date_range)
		->whereIn('sampletype', [1, 2, 3, 4])
		->whereIn('rcategory', [3, 4])
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('justification', 2)
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		return $data;
    }

    public function GetNationalBaselinebydash($year, $start_month, $type, $param){
		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

    	$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->whereBetween('datetested', $date_range)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->whereIn('rcategory', [1, 2, 3, 4])
		->whereIn('sampletype', [1, 2, 3, 4])
		->where('justification', 10)
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		return $data;
    }

    public function GetNationalBaselineFailurebydash($year, $start_month, $type, $param){
		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

    	$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->whereBetween('datetested', $date_range)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->whereIn('rcategory', [3, 4])
		->whereIn('sampletype', [1, 2, 3, 4])
		->where('justification', 10)
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		return $data;
    }

    public function getallrepeattviraloadsamplesbydash($year, $start_month, $type, $param){
		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->whereBetween('datetested', $date_range)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('receivedstatus', 3)
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbytypedetailsbydash($year, $start_month, $type, $param, $sampletype=null){
		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datetested) as month, sampletype")
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
		        ->whereNotIn('justification', [2, 10])
				->whereIn('rcategory', [1, 2, 3, 4]);
			}				
		})
		->whereBetween('datetested', $date_range)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month', 'sampletype')
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbygenderbydash($year, $start_month, $type, $param, $nonsuppressed=false){
		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datetested) as month, sex")
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
		        ->whereNotIn('justification', [2, 10])
				->whereIn('rcategory', [1, 2, 3, 4]);
			}
			if($type == 4){
				return $query->whereIn('rcategory', [1, 2, 3, 4]);
			}					
		})
		->when($nonsuppressed, function($query) use ($nonsuppressed){
			return $query->whereIn('rcategory', [3, 4]);
		})
		->whereBetween('datetested', $date_range)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month', 'sex')
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbyagebydash($year, $start_month, $type, $param, $nonsuppressed=false){
		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datetested) as month, age_category")
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
		        ->whereNotIn('justification', [2, 10])
				->whereIn('rcategory', [1, 2, 3, 4]);
			}
			if($type == 4){
				return $query->whereIn('rcategory', [1, 2, 3, 4]);
			}					
		})
		->whereBetween('datetested', $date_range)
		->when($nonsuppressed, function($query) use ($nonsuppressed){
			return $query->whereIn('rcategory', [3, 4]);
		})
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month', 'age_category')
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbyresultbydash($year, $start_month, $type, $param, $result){

		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleView::selectRaw("COUNT(id) as totals, month(datetested) as month, rcategory")
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
		        ->whereNotIn('justification', [2, 10]);
			}				
		})
		->whereBetween('datetested', $date_range)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month', 'rcategory')
		->get();

		return $data;
    }


    // Update samples tats	
	public function update_tats($year)
	{
		// $date_range = BaseModel::date_range($year, $start_month);
		// $sql = "datediff(datereceived, datecollected) as tat1, datediff(datetested, datereceived) as tat2, datediff(datedispatched, datetested) as tat3, datediff(datedispatched, datecollected) as tat4, datecollected, datereceived, datetested, datedispatched, month(datetested) as month";
		
		$sql = "ID, datecollected, datereceived, datetested, datedispatched, month(datetested) as month";
		$b = new BaseModel;
		
		ini_set("memory_limit", "-1");
		 
		echo "\n Begin vl samples tat update for {$year} at " . date('d/m/Y h:i:s a', time());

		for($month=1; $month<13; $month++){

			echo "\n Begin vl samples tat update for {$year} {$month} at " . date('d/m/Y h:i:s a', time());

			$data = DB::connection('vl')
			->table('viralsamples')
			->selectRaw($sql)
			->whereYear('datecollected', '>', 1980)
			->whereYear('datereceived', '>', 1980)
			->whereYear('datetested', '>', 1980)
			->whereYear('datedispatched', '>', 1980)
			->whereColumn([
				['datecollected', '<=', 'datereceived'],
				['datereceived', '<=', 'datetested'],
				['datetested', '<=', 'datedispatched']
			])
			->whereYear('datetested', $year)
			->whereMonth('datetested', $month)
			->where('flag', 1)
			->where('repeatt', 0)
			->get(); 

			if($data->isEmpty()){
				continue;
			}	

			$holidays = $b->getTotalHolidaysinMonth($month);	

			foreach ($data as $key => $value) {

				$tat1 = $b->get_days($value->datecollected, $value->datereceived, $holidays);
				$tat2 = $b->get_days($value->datereceived, $value->datetested, $holidays);
				$tat3 = $b->get_days($value->datetested, $value->datedispatched, $holidays);
				// $tat4 = $b->get_days($value->datecollected, $value->datedispatched, $holidays);
				$tat4 = $tat1 + $tat2 + $tat3;

				$update_array = array('tat1' => $tat1, 'tat2' => $tat2, 'tat3' => $tat3, 'tat4' => $tat4);

				DB::connection('vl_wr')->table('viralsamples')->where('ID', $value->ID)->update($update_array);

			}
			echo "\n Completed vl samples tat update for {$year} {$month} at " . date('d/m/Y h:i:s a', time());

		}
		echo "\n Completed vl samples tat update for {$year} at " . date('d/m/Y h:i:s a', time());
		
	}

	public function confirmatory_report($year){

    	$raw = "ID, patient, facility, datetested";

		echo "\n Begin vl samples confirmatory update for {$year} at " . date('d/m/Y h:i:s a', time());

    	$data = DB::connection('vl')
		->table("viralsamples")
		->selectRaw($raw)
		->orderBy('facility_id', 'desc')
		->whereYear('datetested', $year)
		->where('justification', 2)
		->where('repeatt', 0)
		->where('flag', 1)
		->where('previous_nonsuppressed', 0)
		->where('facility_id', '!=', 7148)
		->get();

		$i = 0;
		$result = null;

		foreach ($data as $sample) {

	    	$d = DB::connection('vl')
			->table("viralsamples")
			->selectRaw($raw)
			->where('facility_id', $sample->facility)
			->where('patient', $sample->patient)
			->whereDate('datetested', '<', $sample->datetested)
			->whereIn('rcategory', [3, 4])
			->where('repeatt', 0)
			->where('flag', 1)
			->where('facility_id', '!=', 7148)
			->first();

			if($d == null){
				DB::connection('vl_wr')->table('viralsamples')->where('ID', $sample->ID)->update(['previous_nonsuppressed' => 1]);
			}
		}
		echo "\n Completed vl samples confirmatory update for {$year} at " . date('d/m/Y h:i:s a', time());
    }



	public function confirmatory_v(){

    	$raw = "ID, patient, facility, datetested";

		echo "\n Begin vl samples confirmatory update at " . date('d/m/Y h:i:s a', time());


		// $sql = "
		// 	SELECT id, concat(patient,'-',facility) as new_id
		// 	FROM viralsamples WHERE new_id IN
		// 		(
		// 			SELECT concat(patient,'-',facility)
		// 		)

		// ";

		$sql = "
			SELECT ID, patient, facility, justification, count(*) as my_count
			FROM viralsamples
			WHERE justification=2 AND previous_nonsuppressed=0
			GROUP BY patient, facility
			HAVING my_count=1
		";

		$data = DB::connection('vl')->select($sql);

		$i = 0;
		$result = null;

		foreach ($data as $sample) {

			DB::connection('vl_wr')->table('viralsamples')->where('ID', $sample->ID)->update(['previous_nonsuppressed' => 1]);
		}
		echo "\n Completed vl samples confirmatory update at " . date('d/m/Y h:i:s a', time());
    }
}
