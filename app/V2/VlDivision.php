<?php

namespace App\V2;

use DB;
use App\V2\BaseModel;
use App\ViralsampleSynchView;

class VlDivision
{
    //Control Tests
	public function control_samples($year, $start_month){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = DB::connection('vl')
		->table('worksheets_vl')
		->selectRaw("COUNT(*) as totals, lab, month(daterun) as month")
		->whereBetween('daterun', $date_range)
		->groupBy('month', 'lab')
		->get();

		return $data;
	}

    //Mapping
	public function lab_county_tests($year, $start_month, $division='county'){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month, lab_id"))
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
		})
		->where('facility_id', '!=', 7148)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->groupBy('month', 'lab_id')
		->get();

		return $data;
	}
	
    //Mapping
	public function lab_mapping_sites($year, $start_month, $division='county'){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(DISTINCT facility_id) as totals, month(datetested) as month, lab_id"))
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
		})
		->where('facility_id', '!=', 7148)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->groupBy('month', 'lab_id')
		->get();

		return $data;
	}

    //National rejections
	public function national_rejections($year, $start_month, $division='county', $rejected_reason){
		$date_range = BaseModel::date_range($year, $start_month);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datereceived) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab_id" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->where('receivedstatus', 2)
		->where('rejectedreason', $rejected_reason)
		->whereBetween('datereceived', $date_range)
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get(); 

		return $data;
	}

	//National eqa
	public function get_eqa_tests($year, $start_month, $division='county'){
		$date_range = BaseModel::date_range($year, $start_month);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
		})
		->where('facility', 7148)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->groupBy('month')
		->get(); 

		return $data;
	}
	

    public function getalltestedviraloadsamples($year, $start_month, $division='county'){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->groupBy('month')
		->get();

		return $data;
    }

    public function getallactualpatients($year, $start_month, $division='county'){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(DISTINCT patient_id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab_id" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [1, 2, 3, 4])
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		return $data;
    }

    public function getallreceivediraloadsamples($year, $start_month, $division='county'){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datereceived) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab_id" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->whereBetween('datereceived', $date_range)
		->whereRaw("((parentid=0) || (parentid IS NULL))")
		->where('flag', 1)
		->groupBy('month')
		->get();

		return $data;
    }

    public function getallrejectedviraloadsamples($year, $start_month, $division='county'){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datereceived) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab_id" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->whereRaw("((parentid=0) || (parentid IS NULL))")
		->whereBetween('datereceived', $date_range)
		->where('receivedstatus', 2)
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		return $data;
    }

    public function GetSupportedfacilitysFORViralLoad($year, $start_month, $division='county'){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(DISTINCT facility_id) as totals, month(datereceived) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab_id" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->whereRaw("((parentid=0) || (parentid IS NULL))")
		->whereBetween('datereceived', $date_range)
		->where('facility_id', '!=', 0)
		->where('flag', 1)
		->groupBy('month')
		->get();

		return $data;
    } 

    public function GetNationalConfirmed2VLs($year, $start_month, $division='county'){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab_id" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
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


    public function GetNationalConfirmedFailure($year, $start_month, $division='county'){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab_id" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
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
    

    public function false_confirmatory($year, $start_month, $division='county'){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab_id" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
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

    public function GetNationalBaseline($year, $start_month, $division='county'){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab_id" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [1, 2, 3, 4])
		->whereIn('sampletype', [1, 2, 3, 4])
		->where('justification', 10)
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		return $data;
    }

    public function GetNationalBaselineFailure($year, $start_month, $division='county'){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab_id" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [3, 4])
		->whereIn('sampletype', [1, 2, 3, 4])
		->where('justification', 10)
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		return $data;
    }

    public function getallrepeattviraloadsamples($year, $start_month, $division='county'){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab_id" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->whereBetween('datetested', $date_range)
		->where('receivedstatus', 3)
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbytypedetails($year, $start_month, $division='county', $sampletype, $routine=true){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab_id" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [1, 2, 3, 4])
		->when($routine, function($query) use ($routine){
			return $query
			->where('justification', '!=', 2)
			->where('justification', '!=', 10);
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
		->groupBy('month')
		->get();

		return $data;
    }

    public function getalltestedviraloadbygender($year, $start_month, $division='county', $sex){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab_id" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [1, 2, 3, 4])
		->where('sex', $sex)
		->whereNotIn('justification', [2, 10])
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		return $data;
    }

    public function getalltestedviraloadsbyage($year, $start_month, $division='county', $age, $all=false){
		$date_range = BaseModel::date_range($year, $start_month);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab_id" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [1, 2, 3, 4])
		->where('age_category', $age)
		->whereNotIn('justification', [2, 10])
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		return $data;
    }

    public function getalltestedviraloadsbyresult($year, $start_month, $division='county', $result){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab_id" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->whereBetween('datetested', $date_range)
		->when($result, function($query) use ($result){
			if($result != 5){
				return $query->whereNotIn('justification', [2, 10]);
			}
		})
		->where('rcategory', $result)
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		return $data;
    }

	public function get_tat($year, $start_month, $division='county'){
		$date_range = BaseModel::date_range($year, $start_month);

    	$sql = "AVG(tat1) AS tat1, AVG(tat2) AS tat2, AVG(tat3) AS tat3, AVG(tat4) AS tat4, month(datetested) as month";

		$data = ViralsampleSynchView::selectRaw($sql)
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab_id" || $division == "facility_id"){
				return $query->where('facility_id', '!=', 7148);
			}
		})
		->whereBetween('datetested', $date_range)
		->whereColumn([
			['datecollected', '<=', 'datereceived'],
			['datereceived', '<=', 'datetested'],
			['datetested', '<=', 'datedispatched']
		])
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get(); 

		return $data;
	}

    public function getalltestedviraloadsamplesbydash($year, $start_month, $division='county', $type, $param){

		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

    	$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
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
		->groupBy('month')
		->get();

		return $data;
    }

    public function getallreceivediraloadsamplesbydash($year, $start_month, $division='county', $type, $param){

		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datereceived) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
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
		->groupBy('month')
		->get();

		return $data;
    }

    public function getallrejectedviraloadsamplesbydash($year, $start_month, $division='county', $type, $param){

		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datereceived) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
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
		->groupBy('month')
		->get();

		return $data;
    }

    public function GetNationalConfirmed2VLsbydash($year, $start_month, $division='county', $type, $param){

		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
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
		->groupBy('month')
		->get();

		return $data;
    }

    public function GetNationalConfirmedFailurebydash($year, $start_month, $division='county', $type, $param){

		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
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
		->groupBy('month')
		->get();

		return $data;
    }

    public function GetNationalBaselinebydash($year, $start_month, $division='county', $type, $param){

		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

    	$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
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
		->groupBy('month')
		->get();

		return $data;
    }

    public function GetNationalBaselineFailurebydash($year, $start_month, $division='county', $type, $param){

		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

    	$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
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
		->groupBy('month')
		->get();

		return $data;
    }

    public function getallrepeattviraloadsamplesbydash($year, $start_month, $division='county', $type, $param){

		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
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
		->groupBy('month')
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbytypedetailsbydash($year, $start_month, $division='county', $type, $param, $sampletype){

		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
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
		->groupBy('month')
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbygenderbydash($year, $start_month, $division='county', $type, $param, $sex){

		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
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
		->groupBy('month')
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbyagebydash($year, $start_month, $division='county', $type, $param, $age){

		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
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
		->groupBy('month')
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbyresultbydash($year, $start_month, $division='county', $type, $param, $result){

		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if($division != "poc") return $query->addSelect($division)->groupBy($division);
			return $query->where('siteentry', 2);
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
		->groupBy('month')
		->get();

		return $data;
    }

    public function supp($year=null){

    	$sql = 'SELECT v.facility, v.rcategory, month(datetested) AS month ';
    	$sql .= 'FROM viralsamples v ';
    	$sql .= 'INNER JOIN ';
    	$sql .= '(SELECT ID, patient, facility, max(datetested) as maxdate ';
    	$sql .= 'FROM viralsamples ';
    	$sql .= 'WHERE year(datetested)={$year} ';
    	$sql .= 'AND flag=1 AND repeatt=0 AND rcategory between 1 and 4 ';
    	$sql .= 'GROUP BY patient, facility) gv ';
    	$sql .= 'ON v.ID=gv.ID AND gv.maxdate=v.datetested ';

		$newsql = 'SELECT tb.facility, tb.month, tb.rcategory, count(*) as tests ';
		$newsql .= 'FROM ';
		$newsql .= '(SELECT v.facility, v.rcategory, month(datetested) AS month ';
		$newsql .= 'FROM viralsamples v ';
		$newsql .= 'INNER JOIN ';
		$newsql .= '(SELECT ID, patient, facility, max(datetested) as maxdate ';
		$newsql .= 'FROM viralsamples ';
		$newsql .= 'WHERE year(datetested)={$year} ';
		$newsql .= 'AND flag=1 AND repeatt=0 AND rcategory between 1 and 4 ';
		$newsql .= 'GROUP BY patient, facility) gv ';
		$newsql .= 'ON v.ID=gv.ID AND gv.maxdate=v.datetested) tb ';
		$newsql .= 'GROUP BY tb.facility, tb.month, tb.rcategory ';
		$newsql .= 'ORDER BY tb.facility, tb.month, tb.rcategory ';

		// $data = DB::connection('vl')->select($newsql);

		// return $data;
    }

    public function suppression(){
    	ini_set("memory_limit", "-1");
    	// SELECT facility, rcategory, count(*) as totals
		// FROM
		// (SELECT v.ID, v.facility, v.rcategory 
		// FROM viralsamples v 
		// RIGHT JOIN 
		// (SELECT ID, patient, facility, max(datetested) as maxdate
		// FROM viralsamples
		// WHERE ( (year(datetested) = 2016 AND month(datetested) > 9) || (year(datetested) = 2017 AND month(datetested) < 10) ) 
		// AND flag=1 AND repeatt=0 AND rcategory between 1 AND 4 
		// AND justification != 10 AND facility != 7148
		// GROUP BY patient, facility) gv 
		// ON v.ID=gv.ID) tb
		// GROUP BY facility, rcategory 
		// ORDER BY facility, rcategory;

    	$r = $this->current_range();

    	$year = $r[0];
    	$prev_year = $r[1];
    	$month = $r[2];
    	$prev_month = $r[3];

    	$sql = 'SELECT facility, rcategory, count(*) as totals ';
		$sql .= 'FROM ';
		$sql .= '(SELECT v.ID, v.facility, v.rcategory ';
		$sql .= 'FROM viralsamples v ';
		$sql .= 'RIGHT JOIN ';
		$sql .= '(SELECT ID, patient, facility, max(datetested) as maxdate ';
		$sql .= 'FROM viralsamples ';
		$sql .= 'WHERE ( (year(datetested) = ? and month(datetested) > ?) || (year(datetested) = ? and month(datetested) < ?) ) ';
		$sql .= "AND patient != '' AND patient != 'null' AND patient is not null ";
		$sql .= 'AND flag=1 AND repeatt=0 AND rcategory between 1 AND 4 ';
		$sql .= 'AND justification != 10 AND facility != 7148 ';
		$sql .= 'GROUP BY patient, facility) gv ';
		$sql .= 'ON v.ID=gv.ID) tb ';
		$sql .= 'GROUP BY facility, rcategory ';
		$sql .= 'ORDER BY facility, rcategory ';

		$data = DB::connection('vl')->select($sql, [$prev_year, $prev_month, $year, $month]);

		return $data;
    }

    public function current_age_suppression($age, $suppression=true){
    	ini_set("memory_limit", "-1"); 

    	$r = $this->current_range();

    	$year = $r[0];
    	$prev_year = $r[1];
    	$month = $r[2];
    	$prev_month = $r[3];

    	$sql = 'SELECT facility, count(*) as totals ';
		$sql .= 'FROM ';
		$sql .= '(SELECT v.ID, v.facility, v.rcategory, v.age2 ';
		$sql .= 'FROM viralsamples v ';
		$sql .= 'RIGHT JOIN ';
		$sql .= '(SELECT ID, patient, facility, max(datetested) as maxdate ';
		$sql .= 'FROM viralsamples ';
		$sql .= 'WHERE ( (year(datetested) = ? and month(datetested) > ?) || (year(datetested) = ? and month(datetested) < ?) ) ';
		$sql .= "AND patient != '' AND patient != 'null' AND patient is not null ";
		$sql .= 'AND flag=1 AND repeatt=0 AND rcategory between 1 and 4 ';
		$sql .= 'AND justification != 10 and facility != 7148 ';
		$sql .= 'GROUP BY patient, facility) gv ';
		$sql .= 'ON v.ID=gv.ID) tb ';
		if($suppression){
			$sql .= 'WHERE rcategory between 1 and 2 ';
		}
		else{
			$sql .= 'WHERE rcategory between 3 and 4 ';
		}
		$sql .= 'AND age2 = ? ';
		$sql .= 'GROUP BY facility ';
		$sql .= 'ORDER BY facility';

		$data = DB::connection('vl')->select($sql, [$prev_year, $prev_month, $year, $month, $age]);

		return collect($data);
    }

    public function current_gender_suppression($sex, $suppression=true){
    	ini_set("memory_limit", "-1"); 

    	$r = $this->current_range();

    	$year = $r[0];
    	$prev_year = $r[1];
    	$month = $r[2];
    	$prev_month = $r[3];

    	$b = new BaseModel;
		$gender = $b->get_gender($sex);

    	$sql = 'SELECT facility, count(*) as totals ';
		$sql .= 'FROM ';
		$sql .= '(SELECT v.ID, v.facility, v.rcategory, viralpatients.gender ';
		$sql .= 'FROM viralsamples v JOIN viralpatients ON v.patientid=viralpatients.AutoID ';
		$sql .= 'RIGHT JOIN ';
		$sql .= '(SELECT ID, patient, facility, max(datetested) as maxdate ';
		$sql .= 'FROM viralsamples ';
		$sql .= 'WHERE ( (year(datetested) = ? and month(datetested) > ?) || (year(datetested) = ? and month(datetested) < ?) ) ';
		$sql .= "AND patient != '' AND patient != 'null' AND patient is not null ";
		$sql .= 'AND flag=1 AND repeatt=0 AND rcategory between 1 and 4 ';
		$sql .= 'AND justification != 10 and facility != 7148 ';
		$sql .= 'GROUP BY patient, facility) gv ';
		$sql .= 'ON v.ID=gv.ID) tb ';
		if($suppression){
			$sql .= 'WHERE rcategory between 1 and 2 ';
		}
		else{
			$sql .= 'WHERE rcategory between 3 and 4 ';
		}
		$sql .= 'AND gender = ? ';		
		$sql .= 'GROUP BY facility ';
		$sql .= 'ORDER BY facility ';

		$data = DB::connection('vl')->select($sql, [$prev_year, $prev_month, $year, $month, $gender]);
		// $data = DB::connection('vl')->select($sql, [$prev_year, $prev_month, $year, $month]);

		return collect($data);
    }

    private function current_range(){

    	$year = ((int) Date('Y'));
    	$prev_year = ((int) Date('Y')) - 1;
    	$month = ((int) Date('m'));
    	$prev_month = ((int) Date('m')) - 1;

    	// $year = 2017;
    	// $prev_year = 2016;
    	// $month = 10;
    	// $prev_month = 9;

    	return [$year, $prev_year, $month, $prev_month];
    }

    public function update_patients()
    {
    	ini_set("memory_limit", "-1");

		$sql = "ID, facility_id, patient, batchno, view_facilitys.name,
		 view_facilitys.facilitycode, view_facilitys.DHIScode, viralpatients.age, viralpatients.gender,
		  viralpatients.prophylaxis, justification, datecollected,
		   receivedstatus, sampletype, rejectedreason,
		    reason_for_repeat, datereceived, datetested,
		     result, datedispatched, lab_id, month(datetested) as month";

		$raw = "ID, patient, facility, datetested";		

		$data = ViralsampleSynchView::selectRaw($sql))
		->where('flag', 1)
		->where('repeatt', 0)
		->where('synched', 0)
		->get();

		$today=date('Y-m-d');

		$b = new BaseModel;

		$p=0;

		foreach ($data as $key => $value) {
			$data_array = array(
				'labid' => $value->ID, 'FacilityMFLcode' => $value->facilitycode, 
				'FacilityDHISCode' => $value->DHIScode, 'batchno' => $value->batchno,
				'patientID' => $value->patient, 'Age' => $value->age, 'Gender' => $value->gender,
				'Regimen' => $value->prophylaxis,	'datecollected' => $value->datecollected,
				'SampleType' => $value->sampletype, 'Justification' => $value->justification, 
				'receivedstatus' => $value->receivedstatus, 'result' => $value->result, 
				'rejectedreason' => $value->rejectedreason, 
				'reason_for_repeat' => $value->reason_for_repeat,
				'datereceived' => $value->datereceived, 'datetested' => $value->datetested,
				'datedispatched' => $value->datedispatched, 'lab_id' => $value->lab_id
			);

			// DB::table('patients')->insert($data_array);

			$holidays = $b->getTotalHolidaysinMonth($value->month);

			$tat1 = $b->get_days($value->datecollected, $value->datereceived, $holidays);
			$tat2 = $b->get_days($value->datereceived, $value->datetested, $holidays);
			$tat3 = $b->get_days($value->datetested, $value->datedispatched, $holidays);
			// $tat4 = $b->get_days($value->datecollected, $value->datedispatched, $holidays);
			$tat4 = $tat1 + $tat2 + $tat3;

			$update_array = array('synched' => 1, 'datesynched' => $today, 'tat1' => $tat1, 'tat2' => $tat2, 'tat3' => $tat3, 'tat4' => $tat4);

			if ($value->justification == 2) {

		    	$d = DB::connection('vl')
				->table("viralsamples")
				->selectRaw($raw))
				->where('facility', $value->facility)
				->where('patient', $value->patient)
				->whereDate('datetested', '<', $value->datetested)
				->whereIn('rcategory', [3, 4])
				->where('repeatt', 0)
				->where('flag', 1)
				->where('facility', '!=', 7148)
				->first();

				if($d == null){
					$update_array = array_merge($update_array, ['previous_nonsuppressed' => 1]);
				}
			}
			// $update_array = array('synched' => 0, 'datesynched' => $today);

			DB::connection('vl')->table('viralsamples')->where('ID', $value->ID)->update($update_array);
			$p++;
		}


		echo "\n {$p} vl patients synched.";
	}
}
