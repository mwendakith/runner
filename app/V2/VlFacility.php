<?php

namespace App\V2;

use DB;
use App\V2\BaseModel;
use App\ViralsampleSynchView;

class VlFacility
{

	// Callbacks
	public function get_callback($division, $date_range)
	{
		return function($query) use($division, $date_range){
			if($division == 'partner_id'){
				$query->join('partner_facilities', 'viralsample_synch_view.facility_id', '=', 'partner_facilities.facility_id')
					->whereRaw("((start_date <= '{$date_range[0]}' AND end_date >= '{$date_range[1]}') OR 
						(start_date <= '{$date_range[0]}' AND end_date IS NULL) )");
			}
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->orderBy($division, 'asc')->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->orderBy('facility', 'asc')->groupBy('facility');
			return $query->where('site_entry', 2);
		};
	}

	public function get_eqa_callback($division)
	{
		return function($query) use($division){
			if($division == "lab" || $division == "facility"){
				return $query->where('facility', '!=', 7148);
			}
		};
	}

	public function get_sampletype_dbs_callback($type, $param, $p)
	{
		return function($query) use ($type, $param, $p){
			/*if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}*/	
			return $query->where($p['column'], $p['param']);	
		};
	}

	public function get_justification_callback($type)
	{
		return function($query) use ($type){
			if($type < 4){
				return $query->whereNotIn('justification', [2, 10])->whereIn('rcategory', [1, 2, 3, 4]);
			}
			if($type == 4){
				return $query->whereIn('rcategory', [1, 2, 3, 4]);
			}					
		};
	}



    //Control Tests
	public function control_samples($year, $month){
		$date_range = BaseModel::date_range_month($year, $month);

    	$data = DB::connection('eid_vl_wr')
		->table('viralworksheets')
		->selectRaw("COUNT(*) as totals, lab_id as lab")
		->whereBetween('daterun', $date_range)
		->groupBy('lab')
		->orderBy('lab', 'asc')
		->get();

		return $data;
	}

    //Calibration Tests
	public function calibration_samples($year, $month){
		$date_range = BaseModel::date_range_month($year, $month);

    	$data = DB::connection('eid_vl_wr')
		->table('viralworksheets')
		->selectRaw("COUNT(*) as totals, lab_id as lab")
		->whereBetween('daterun', $date_range)
		->where('calibration', 1)
		->groupBy('lab')
		->orderBy('lab', 'asc')
		->get();

		return $data;
	}

    //Mapping
	public function lab_county_tests($year, $month, $division='county'){
		$date_range = BaseModel::date_range_month($year, $month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals, lab")
		->when(true, $this->get_callback($division, $date_range))
		->where('facility', '!=', 7148)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->groupBy('lab')
		->orderBy('lab', 'asc')
		->get();

		return $data;
	}
	
    //Mapping
	public function lab_mapping_sites($year, $month, $division='county'){
		$date_range = BaseModel::date_range_month($year, $month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(DISTINCT facility) as totals, lab")
		->when(true, $this->get_callback($division, $date_range))
		->where('facility', '!=', 7148)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->groupBy('lab')
		->orderBy('lab', 'asc')
		->get();

		return $data;
	}

    //National rejections
	public function national_rejections($year, $month, $division='county', $rejected_reason){
		$date_range = BaseModel::date_range_month($year, $month);

		$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->when(true, $this->get_eqa_callback($division))
		->where('receivedstatus', 2)
		->where('rejectedreason', $rejected_reason)
		->whereBetween('datereceived', $date_range)
		->where('flag', 1)
		->where('repeatt', 0)
		->get(); 

		return $data;
	}

	//National eqa
	public function get_eqa_tests($year, $month, $division='county'){
		$date_range = BaseModel::date_range_month($year, $month);

		$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->where('facility', 7148)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->get(); 

		return $data;
	}
	

    public function getalltestedviraloadsamples($year, $month, $division='county'){
		$date_range = BaseModel::date_range_month($year, $month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->when($division, function($query) use ($division){
			if($division == "facility"){
				return $query->where('facility', '!=', 7148);
			}
		})
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->get();

		return $data;
    }

    public function getallactualpatients($year, $month, $division='county'){
		$date_range = BaseModel::date_range_month($year, $month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(DISTINCT patient_id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->when(true, $this->get_eqa_callback($division))
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [1, 2, 3, 4])
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function getallreceivediraloadsamples($year, $month, $division='county'){
		$date_range = BaseModel::date_range_month($year, $month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->when(true, $this->get_eqa_callback($division))
		->whereBetween('datereceived', $date_range)
		->whereRaw("((parentid=0) || (parentid IS NULL))")
		->where('flag', 1)
		->get();

		return $data;
    }

    public function getallrejectedviraloadsamples($year, $month, $division='county'){
		$date_range = BaseModel::date_range_month($year, $month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->when(true, $this->get_eqa_callback($division))
		->whereRaw("((parentid=0) || (parentid IS NULL))")
		->whereBetween('datereceived', $date_range)
		->where('receivedstatus', 2)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function GetSupportedfacilitysFORViralLoad($year, $month, $division='county'){
		$date_range = BaseModel::date_range_month($year, $month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(DISTINCT facility) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->when(true, $this->get_eqa_callback($division))
		->whereRaw("((parentid=0) || (parentid IS NULL))")
		->whereBetween('datereceived', $date_range)
		->where('facility', '!=', 0)
		->where('flag', 1)
		->get();

		return $data;
    } 

    public function GetNationalConfirmed2VLs($year, $month, $division='county'){
		$date_range = BaseModel::date_range_month($year, $month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->when(true, $this->get_eqa_callback($division))
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [1, 2, 3, 4])
		->whereIn('sampletype', [1, 2, 3, 4])
		->where('justification', 2)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }


    public function GetNationalConfirmedFailure($year, $month, $division='county'){
		$date_range = BaseModel::date_range_month($year, $month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->when(true, $this->get_eqa_callback($division))
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [3, 4])
		->whereIn('sampletype', [1, 2, 3, 4])
		->where('justification', 2)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }
    

    public function false_confirmatory($year, $month, $division='county'){
		$date_range = BaseModel::date_range_month($year, $month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->when(true, $this->get_eqa_callback($division))
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [1, 2, 3, 4])
		->whereIn('sampletype', [1, 2, 3, 4])
		->where('previous_nonsuppressed', 1)
		->where('justification', 2)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function GetNationalBaseline($year, $month, $division='county'){
		$date_range = BaseModel::date_range_month($year, $month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->when(true, $this->get_eqa_callback($division))
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [1, 2, 3, 4])
		->whereIn('sampletype', [1, 2, 3, 4])
		->where('justification', 10)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function GetNationalBaselineFailure($year, $month, $division='county'){
		$date_range = BaseModel::date_range_month($year, $month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->when(true, $this->get_eqa_callback($division))
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [3, 4])
		->whereIn('sampletype', [1, 2, 3, 4])
		->where('justification', 10)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function getallrepeattviraloadsamples($year, $month, $division='county'){
		$date_range = BaseModel::date_range_month($year, $month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->when(true, $this->get_eqa_callback($division))
		->whereBetween('datetested', $date_range)
		->where('receivedstatus', 3)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbytypedetails($year, $month, $division='county', $sampletype, $routine=true){
		$date_range = BaseModel::date_range_month($year, $month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->when(true, $this->get_eqa_callback($division))
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [1, 2, 3, 4])
		->when($routine, function($query) use ($routine){
			return $query
			->where('justification', '!=', 2)
			->where('justification', '!=', 10);
		})
		->where('sampletype', $sampletype)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function getalltestedviraloadbygender($year, $month, $division='county', $sex){
		$date_range = BaseModel::date_range_month($year, $month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->when(true, $this->get_eqa_callback($division))
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [1, 2, 3, 4])
		->where('sex', $sex)
		->whereNotIn('justification', [2, 10])
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function getalltestedviraloadsbyage($year, $month, $division='county', $age, $all=false){
		$date_range = BaseModel::date_range_month($year, $month);

		$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->when(true, $this->get_eqa_callback($division))
		->whereBetween('datetested', $date_range)
		->whereIn('rcategory', [1, 2, 3, 4])
		->where('age_category', $age)
		->whereNotIn('justification', [2, 10])
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function getalltestedviraloadsbyresult($year, $month, $division='county', $result){
		$date_range = BaseModel::date_range_month($year, $month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->when(true, $this->get_eqa_callback($division))
		->whereBetween('datetested', $date_range)
		->when($result, function($query) use ($result){
			if($result != 5){
				return $query->whereNotIn('justification', [2, 10]);
			}
		})
		->where('rcategory', $result)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

	public function get_tat($year, $month, $division='county'){
		$date_range = BaseModel::date_range_month($year, $month);

    	$sql = "AVG(tat1) AS tat1, AVG(tat2) AS tat2, AVG(tat3) AS tat3, AVG(tat4) AS tat4";

		$data = ViralsampleSynchView::selectRaw($sql)
		->when(true, $this->get_callback($division, $date_range))
		->when(true, $this->get_eqa_callback($division))
		->whereBetween('datetested', $date_range)
		->whereColumn([
			['datecollected', '<=', 'datereceived'],
			['datereceived', '<=', 'datetested'],
			['datetested', '<=', 'datedispatched']
		])
		->where('flag', 1)
		->where('repeatt', 0)
		->get(); 

		return $data;
	}
	










	

    public function getalltestedviraloadsamplesbydash($year, $month, $division='county', $type, $param){

		$date_range = BaseModel::date_range_month($year, $month);
		$p = BaseModel::get_vlparams($type, $param);

    	$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->when(true, $this->get_justification_callback($type))
		->when(true, $this->get_sampletype_dbs_callback($type, $param, $p))
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function getallreceivediraloadsamplesbydash($year, $month, $division='county', $type, $param){

		$date_range = BaseModel::date_range_month($year, $month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->whereBetween('datereceived', $date_range)
		->whereRaw("((parentid=0) || (parentid IS NULL))")
		->when(true, $this->get_sampletype_dbs_callback($type, $param, $p))
		->where('flag', 1)
		->get();

		return $data;
    }

    public function getallrejectedviraloadsamplesbydash($year, $month, $division='county', $type, $param){

		$date_range = BaseModel::date_range_month($year, $month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->whereBetween('datereceived', $date_range)
		->when(true, $this->get_sampletype_dbs_callback($type, $param, $p))
		->where('receivedstatus', 2)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function GetNationalConfirmed2VLsbydash($year, $month, $division='county', $type, $param){

		$date_range = BaseModel::date_range_month($year, $month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->whereBetween('datetested', $date_range)
		->whereIn('sampletype', [1, 2, 3, 4])
		->when(true, $this->get_sampletype_dbs_callback($type, $param, $p))
		->where('justification', 2)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function GetNationalConfirmedFailurebydash($year, $month, $division='county', $type, $param){

		$date_range = BaseModel::date_range_month($year, $month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->whereBetween('datetested', $date_range)
		->whereIn('sampletype', [1, 2, 3, 4])
		->whereIn('rcategory', [3, 4])
		->when(true, $this->get_sampletype_dbs_callback($type, $param, $p))
		->where('justification', 2)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function GetNationalBaselinebydash($year, $month, $division='county', $type, $param){

		$date_range = BaseModel::date_range_month($year, $month);
		$p = BaseModel::get_vlparams($type, $param);

    	$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->whereBetween('datetested', $date_range)
		->when(true, $this->get_sampletype_dbs_callback($type, $param, $p))
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

    	$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->whereBetween('datetested', $date_range)
		->when(true, $this->get_sampletype_dbs_callback($type, $param, $p))
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

		$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->whereBetween('datetested', $date_range)
		->when(true, $this->get_sampletype_dbs_callback($type, $param, $p))
		->where('receivedstatus', 3)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbytypedetailsbydash($year, $month, $division='county', $type, $param, $sampletype){

		$date_range = BaseModel::date_range_month($year, $month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->when(true, $this->get_justification_callback($type))
		->whereBetween('datetested', $date_range)
		->when(true, $this->get_sampletype_dbs_callback($type, $param, $p))
		->where('sampletype', $sampletype)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbygenderbydash($year, $month, $division='county', $type, $param, $sex){

		$date_range = BaseModel::date_range_month($year, $month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->when(true, $this->get_justification_callback($type))
		->whereBetween('datetested', $date_range)
		->when(true, $this->get_sampletype_dbs_callback($type, $param, $p))
		->where('sex', $sex)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbyagebydash($year, $month, $division='county', $type, $param, $age){

		$date_range = BaseModel::date_range_month($year, $month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->when(true, $this->get_justification_callback($type))
		->whereBetween('datetested', $date_range)
		->when(true, $this->get_sampletype_dbs_callback($type, $param, $p))
		->where('age_category', $age)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbyresultbydash($year, $month, $division='county', $type, $param, $result){

		$date_range = BaseModel::date_range_month($year, $month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals")
		->when(true, $this->get_callback($division, $date_range))
		->when(($type < 4), function($query){
			return $query->whereNotIn('justification', [2, 10]);			
		})
		->whereBetween('datetested', $date_range)
		->when(true, $this->get_sampletype_dbs_callback($type, $param, $p))
		->where('rcategory', $result)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }

    public function get_results_by_multiple_params($year, $month, $division='county', $params){

		$date_range = BaseModel::date_range_month($year, $month);

		$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals ")
		->when(true, $this->get_callback($division, $date_range))
		->whereNotIn('justification', [2, 10])
		->whereBetween('datetested', $date_range)
		->where($params)
		->where('flag', 1)
		->where('repeatt', 0)
		->get();

		return $data;
    }


    public function get_dhis_suppression($year, $month, $division='facility', $age_range, $suppressed){

		$date_range = BaseModel::date_range_month($year, $month);

		$data = ViralsampleSynchView::selectRaw("COUNT(viralsample_synch_view.id) as totals, sex")
		->when(true, $this->get_callback($division, $date_range))
		// ->whereNotIn('justification', [2, 10])
		->whereBetween('datetested', $date_range)
		->whereBetween('age', $age_range)
		->where('age', '>', 0)
		->where($params)
		->when(true, function($query) use ($suppressed){
			if($suppressed) return $query->whereIn('rcategory', [1,2]);
			return $query->whereIn('rcategory', [3,4]);
		})
		->where(['repeatt' => 0, 'flag' 1])
		->groupBy('sex')
		->get();

		return $data;
	}

	$dhis_sql = "
	CREATE TABLE `vl_site_dhis` (
		`ID` int(10) NOT NULL AUTO_INCREMENT,
		`dateupdated` date DEFAULT NULL,
		`month` int(10) DEFAULT '0',
		`year` int(10) DEFAULT '0',
		`facility` int(10) DEFAULT '0',
		`male_below_10_suppressed` int(14) DEFAULT NULL,
		`male_below_10_nonsuppressed` int(14) DEFAULT NULL,
		`male_below_15_suppressed` int(14) DEFAULT NULL,
		`male_below_15_nonsuppressed` int(14) DEFAULT NULL,
		`male_below_20_suppressed` int(14) DEFAULT NULL,
		`male_below_20_nonsuppressed` int(14) DEFAULT NULL,
		`male_below_25_suppressed` int(14) DEFAULT NULL,
		`male_below_25_nonsuppressed` int(14) DEFAULT NULL,
		`male_above_25_suppressed` int(14) DEFAULT NULL,
		`male_above_25_nonsuppressed` int(14) DEFAULT NULL,
		`female_below_10_suppressed` int(14) DEFAULT NULL,
		`female_below_10_nonsuppressed` int(14) DEFAULT NULL,
		`female_below_15_suppressed` int(14) DEFAULT NULL,
		`female_below_15_nonsuppressed` int(14) DEFAULT NULL,
		`female_below_20_suppressed` int(14) DEFAULT NULL,
		`female_below_20_nonsuppressed` int(14) DEFAULT NULL,
		`female_below_25_suppressed` int(14) DEFAULT NULL,
		`female_below_25_nonsuppressed` int(14) DEFAULT NULL,
		`female_above_25_suppressed` int(14) DEFAULT NULL,
		`female_above_25_nonsuppressed` int(14) DEFAULT NULL,
		PRIMARY KEY (`ID`),
		KEY `facility` (`facility`),
		KEY `vl_site_dhis` (`facility`,`year`,`month`),
		KEY `vl_site_dhis_year` (`year`,`month`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1
	";

}