<?php

namespace App\V2;

use DB;
use App\V2\BaseModel;
use App\ViralsampleSynchView;

class VlFacility
{
	

    public function getalltestedviraloadsamplesbydash($year, $month, $division='county', $type, $param){

		$date_range = BaseModel::date_range_month($year, $month);
		$p = BaseModel::get_vlparams($type, $param);

    	$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
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
		->get();

		return $data;
    }

    public function getallreceivediraloadsamplesbydash($year, $month, $division='county', $type, $param){

		$date_range = BaseModel::date_range_month($year, $month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datereceived) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
		->whereBetween('datereceived', $date_range)
		->whereRaw("((parentid=0) || (parentid IS NULL))")
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('flag', 1)
		->get();

		return $data;
    }

    public function getallrejectedviraloadsamplesbydash($year, $month, $division='county', $type, $param){

		$date_range = BaseModel::date_range_month($year, $month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datereceived) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
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
		->get();

		return $data;
    }

    public function GetNationalConfirmed2VLsbydash($year, $month, $division='county', $type, $param){

		$date_range = BaseModel::date_range_month($year, $month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
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
		->get();

		return $data;
    }

    public function GetNationalConfirmedFailurebydash($year, $month, $division='county', $type, $param){

		$date_range = BaseModel::date_range_month($year, $month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
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
		->get();

		return $data;
    }

    public function GetNationalBaselinebydash($year, $month, $division='county', $type, $param){

		$date_range = BaseModel::date_range_month($year, $month);
		$p = BaseModel::get_vlparams($type, $param);

    	$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
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
		->whereIn('rcategory', [1, 2, 3, 4])
		->whereIn('sampletype', [1, 2, 3, 4])
		->where('justification', 10)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function GetNationalBaselineFailurebydash($year, $month, $division='county', $type, $param){

		$date_range = BaseModel::date_range_month($year, $month);
		$p = BaseModel::get_vlparams($type, $param);

    	$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
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
		->whereIn('rcategory', [3, 4])
		->whereIn('sampletype', [1, 2, 3, 4])
		->where('justification', 10)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function getallrepeattviraloadsamplesbydash($year, $month, $division='county', $type, $param){

		$date_range = BaseModel::date_range_month($year, $month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
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
		->where('receivedstatus', 3)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbytypedetailsbydash($year, $month, $division='county', $type, $param, $sampletype){

		$date_range = BaseModel::date_range_month($year, $month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
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
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->when($sampletype, function($query) use ($sampletype){
			if($sampletype == 2){
				return $query->whereIn('sampletype', [3, 4]);
			}
			else if($sampletype == 3){
				return $query->where('sampletype', 2);
			}
			else{
				return $query->where('sampletype', 1);
			}				
		})
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbygenderbydash($year, $month, $division='county', $type, $param, $sex){

		$date_range = BaseModel::date_range_month($year, $month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
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
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('sex', $sex)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbyagebydash($year, $month, $division='county', $type, $param, $age){

		$date_range = BaseModel::date_range_month($year, $month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
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
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('age_category', $age)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbyresultbydash($year, $month, $division='county', $type, $param, $result){

		$date_range = BaseModel::date_range_month($year, $month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('site_entry', 2);
		})
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
		->where('rcategory', $result)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

}