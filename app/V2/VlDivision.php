<?php

namespace App\V2;

use DB;
use App\V2\BaseModel;
use App\ViralsampleSynchView;
use App\ViralsampleView;

class VlDivision
{
    //Control Tests
	public function control_samples($year, $start_month){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = DB::connection('eid_vl_wr')
		->table('viralworksheets')
		->selectRaw("COUNT(*) as totals, lab_id as lab, month(daterun) as month")
		->whereBetween('daterun', $date_range)
		->groupBy('month', 'lab')
		->get();

		return $data;
	}

    //Calibration Tests
	public function calibration_samples($year, $start_month){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = DB::connection('eid_vl_wr')
		->table('viralworksheets')
		->selectRaw("COUNT(*) as totals, lab_id as lab, month(daterun) as month")
		->whereBetween('daterun', $date_range)
		->where('calibration', 1)
		->groupBy('month', 'lab')
		->get();

		return $data;
	}

    //Mapping
	public function lab_county_tests($year, $start_month, $division='county'){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month, lab")
		->when(true, function($query) use ($division){
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
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
	public function lab_mapping_sites($year, $start_month, $division='county'){
		$date_range = BaseModel::date_range($year, $start_month);

    	$data = ViralsampleSynchView::selectRaw("COUNT(DISTINCT facility_id) as totals, month(datetested) as month, lab")
		->when(true, function($query) use ($division){
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
			return $query->where('site_entry', 2);
		})
		->where('facility_id', '!=', 7148)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->groupBy('month', 'lab')
		->get();

		return $data;
	}

    //National rejections
	public function national_rejections($year, $start_month, $division='county', $rejected_reason){
		$date_range = BaseModel::date_range($year, $start_month);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datereceived) as month")
		->when(true, function($query) use ($division){
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
			return $query->where('site_entry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
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
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
			return $query->where('site_entry', 2);
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
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
			return $query->where('site_entry', 2);
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
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
			return $query->where('site_entry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
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
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
			return $query->where('site_entry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
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
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
			return $query->where('site_entry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
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
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
			return $query->where('site_entry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
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
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
			return $query->where('site_entry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
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
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
			return $query->where('site_entry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
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
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
			return $query->where('site_entry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
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
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
			return $query->where('site_entry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
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
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
			return $query->where('site_entry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
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
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
			return $query->where('site_entry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
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
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
			return $query->where('site_entry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
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
		->where('sampletype', $sampletype)
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
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
			return $query->where('site_entry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
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
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
			return $query->where('site_entry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
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
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
			return $query->where('site_entry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
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
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
			return $query->where('site_entry', 2);
		})
		->when($division, function($query) use ($division){
			if($division == "lab" || $division == "facility_id"){
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
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
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
		->groupBy('month')
		->get();

		return $data;
    }

    public function getallreceivediraloadsamplesbydash($year, $start_month, $division='county', $type, $param){

		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datereceived) as month")
		->when(true, function($query) use ($division){
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
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
		->groupBy('month')
		->get();

		return $data;
    }

    public function getallrejectedviraloadsamplesbydash($year, $start_month, $division='county', $type, $param){

		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datereceived) as month")
		->when(true, function($query) use ($division){
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
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
		->groupBy('month')
		->get();

		return $data;
    }

    public function GetNationalConfirmed2VLsbydash($year, $start_month, $division='county', $type, $param){

		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
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
		->groupBy('month')
		->get();

		return $data;
    }

    public function GetNationalConfirmedFailurebydash($year, $start_month, $division='county', $type, $param){

		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
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
		->groupBy('month')
		->get();

		return $data;
    }

    public function GetNationalBaselinebydash($year, $start_month, $division='county', $type, $param){

		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

    	$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
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
		->groupBy('month')
		->get();

		return $data;
    }

    public function GetNationalBaselineFailurebydash($year, $start_month, $division='county', $type, $param){

		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

    	$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
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
		->groupBy('month')
		->get();

		return $data;
    }

    public function getallrepeattviraloadsamplesbydash($year, $start_month, $division='county', $type, $param){

		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
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
		->groupBy('month')
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbytypedetailsbydash($year, $start_month, $division='county', $type, $param, $sampletype){

		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
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
		->where('sampletype', $sampletype)
		->where('flag', 1)
		->where('repeatt', 0)
		->groupBy('month')
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbygenderbydash($year, $start_month, $division='county', $type, $param, $sex, $nonsuppressed=false){

		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
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
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
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
		->groupBy('month')
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbyresultbydash($year, $start_month, $division='county', $type, $param, $result){

		$date_range = BaseModel::date_range($year, $start_month);
		$p = BaseModel::get_vlparams($type, $param);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($division){
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
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
		->groupBy('month')
		->get();

		return $data;
    }

    public function get_results_by_multiple_params($year, $start_month, $division='county', $params){

		$date_range = BaseModel::date_range($year, $start_month);

		$data = ViralsampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month, rcategory")
		->when(true, function($query) use ($division){
			if(!str_contains($division, 'poc')) return $query->addSelect($division)->groupBy($division);
			if($division == "site_poc") return $query->addSelect('facility', 'lab_id')->where('site_entry', 2)->groupBy('facility');
			return $query->where('site_entry', 2);
		})
		->whereNotIn('justification', [2, 10])
		->whereBetween('datetested', $date_range)
		->where($params)
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

    	// SELECT facility_id as facility, rcategory, count(*) as totals 
		// FROM 
		// (SELECT v.id, v.facility_id, v.rcategory 
		// FROM viralsamples_view v 
		// RIGHT JOIN 
		// (SELECT id, patient_id, max(datetested) as maxdate 
		// FROM viralsamples_view 
		// WHERE ( (year(datetested) = ? and month(datetested) > ?) || (year(datetested) = ? and month(datetested) < ?) ) 
		// AND patient != '' AND patient != 'null' AND patient is not null 
		// AND flag=1 AND repeatt=0 AND rcategory in (1, 2, 3, 4)
		// AND justification != 10 AND facility_id != 7148 
		// GROUP BY patient_id) gv 
		// ON v.id=gv.id) tb 
		// GROUP BY facility_id, rcategory 
		// ORDER BY facility_id, rcategory 


    	$r = $this->current_range();

    	$year = $r[0];
    	$prev_year = $r[1];
    	$month = $r[2];
    	$prev_month = $r[3];

    	$sql = 'SELECT facility_id as facility, rcategory, count(*) as totals ';
		$sql .= 'FROM ';
		$sql .= '(SELECT v.id, v.facility_id, v.rcategory ';
		$sql .= 'FROM viralsamples_view v ';
		$sql .= 'RIGHT JOIN ';
		$sql .= '(SELECT id, patient_id, max(datetested) as maxdate ';
		$sql .= 'FROM viralsamples_view ';
		$sql .= 'WHERE ( (year(datetested) = ? and month(datetested) > ?) || (year(datetested) = ? and month(datetested) < ?) ) ';
		$sql .= "AND patient != '' AND patient != 'null' AND patient is not null ";
		$sql .= 'AND flag=1 AND repeatt=0 AND rcategory in (1, 2, 3, 4) ';
		$sql .= 'AND justification != 10 AND facility_id != 7148 ';
		$sql .= 'GROUP BY patient_id) gv ';
		$sql .= 'ON v.id=gv.id) tb ';
		$sql .= 'GROUP BY facility_id, rcategory ';
		$sql .= 'ORDER BY facility_id, rcategory ';

		$data = DB::connection('eid_vl')->select($sql, [$prev_year, $prev_month, $year, $month]);

		return $data;
    }

    public function current_age_suppression($age, $suppression=true){
    	ini_set("memory_limit", "-1"); 

    	$r = $this->current_range();

    	$year = $r[0];
    	$prev_year = $r[1];
    	$month = $r[2];
    	$prev_month = $r[3];

    	$sql = 'SELECT facility_id as facility, count(*) as totals ';
		$sql .= 'FROM ';
		$sql .= '(SELECT v.id, v.facility_id, v.rcategory, v.age_category ';
		$sql .= 'FROM viralsamples_view v ';
		$sql .= 'RIGHT JOIN ';
		$sql .= '(SELECT id, patient_id, max(datetested) as maxdate ';
		$sql .= 'FROM viralsamples_view ';
		$sql .= 'WHERE ( (year(datetested) = ? and month(datetested) > ?) || (year(datetested) = ? and month(datetested) < ?) ) ';
		$sql .= "AND patient != '' AND patient != 'null' AND patient is not null ";
		$sql .= 'AND flag=1 AND repeatt=0 AND rcategory in (1, 2, 3, 4) ';
		$sql .= 'AND justification != 10 and facility_id != 7148 ';
		$sql .= 'GROUP BY patient_id) gv ';
		$sql .= 'ON v.id=gv.id) tb ';
		if($suppression){
			$sql .= 'WHERE rcategory in (1, 2) ';
		}
		else{
			$sql .= 'WHERE rcategory in (3, 4) ';
		}
		$sql .= 'AND age_category = ? ';
		$sql .= 'GROUP BY facility_id ';
		$sql .= 'ORDER BY facility_id';

		$data = DB::connection('eid_vl')->select($sql, [$prev_year, $prev_month, $year, $month, $age]);

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

    	$sql = 'SELECT facility_id as facility, count(*) as totals ';
		$sql .= 'FROM ';
		$sql .= '(SELECT v.id, v.facility_id, v.rcategory, v.sex ';
		$sql .= 'FROM viralsamples_view v ';
		$sql .= 'RIGHT JOIN ';
		$sql .= '(SELECT ID, patient_id, max(datetested) as maxdate ';
		$sql .= 'FROM viralsamples_view ';
		$sql .= 'WHERE ( (year(datetested) = ? and month(datetested) > ?) || (year(datetested) = ? and month(datetested) < ?) ) ';
		$sql .= "AND patient != '' AND patient != 'null' AND patient is not null ";
		$sql .= 'AND flag=1 AND repeatt=0 AND rcategory in (1, 2, 3, 4) ';
		$sql .= 'AND justification != 10 and facility_id != 7148 ';
		$sql .= 'GROUP BY patient_id) gv ';
		$sql .= 'ON v.id=gv.id) tb ';
		if($suppression){
			$sql .= 'WHERE rcategory in (1, 2) ';
		}
		else{
			$sql .= 'WHERE rcategory in (3, 4) ';
		}
		$sql .= 'AND sex = ? ';		
		$sql .= 'GROUP BY facility_id ';
		$sql .= 'ORDER BY facility_id ';

		$data = DB::connection('eid_vl')->select($sql, [$prev_year, $prev_month, $year, $month, $sex]);

		return collect($data);
    }

    public function current_datim_suppression($lower, $upper, $suppression=true)
    {
    	ini_set("memory_limit", "-1"); 

    	$r = $this->current_range();

    	$b = new BaseModel;

    	$sql = 'SELECT facility_id as facility, sex, count(*) as totals ';
		$sql .= 'FROM ';
		$sql .= '(SELECT v.id, v.facility_id, v.rcategory, v.sex, v.age ';
		$sql .= 'FROM viralsamples_view v ';
		$sql .= 'RIGHT JOIN ';
		$sql .= '(SELECT id, patient_id, max(datetested) as maxdate ';
		$sql .= 'FROM viralsamples_view ';
		$sql .= "WHERE datetested between '2017-10-01' and '2018-09-30' ";
		$sql .= "AND patient != '' AND patient != 'null' AND patient is not null ";
		$sql .= 'AND flag=1 AND repeatt=0 AND rcategory in (1, 2, 3, 4) ';
		$sql .= 'AND justification != 10 and facility_id != 7148 ';
		$sql .= 'GROUP BY patient_id) gv ';
		$sql .= 'ON v.id=gv.id) tb ';
		if($suppression){
			$sql .= 'WHERE rcategory in (1, 2) ';
		}
		else{
			$sql .= 'WHERE rcategory in (3, 4) ';
		}
		$sql .= "AND age >= {$lower} ";
		if($upper) $sql .= "AND age < {$upper} ";

		$sql .= 'GROUP BY facility_id, sex ';
		$sql .= 'ORDER BY facility_id, sex ';

		$data = DB::connection('eid_vl')->select($sql);

		return collect($data);
    }

    public function suppression_two($facility_id){
    	ini_set("memory_limit", "-1");

    	// SELECT facility_id as facility, rcategory, count(*) as totals 
		// FROM 
		// (SELECT v.id, v.facility_id, v.rcategory 
		// FROM viralsamples_view v 
		// RIGHT JOIN 
		// (SELECT id, patient_id, max(datetested) as maxdate 
		// FROM viralsamples_view 
		// WHERE ( (year(datetested) = ? and month(datetested) > ?) || (year(datetested) = ? and month(datetested) < ?) ) 
		// AND patient != '' AND patient != 'null' AND patient is not null 
		// AND flag=1 AND repeatt=0 AND rcategory in (1, 2, 3, 4)
		// AND justification != 10 AND facility_id != 7148 
		// GROUP BY patient_id) gv 
		// ON v.id=gv.id) tb 
		// GROUP BY facility_id, rcategory 
		// ORDER BY facility_id, rcategory 


    	$r = $this->current_range();

    	$year = $r[0];
    	$prev_year = $r[1];
    	$month = $r[2];
    	$prev_month = $r[3];

    	$sql = 'SELECT facility_id as facility, rcategory, count(*) as totals ';
		$sql .= 'FROM ';
		$sql .= '(SELECT v.id, v.facility_id, v.rcategory ';
		$sql .= 'FROM viralsamples_view v ';
		$sql .= 'RIGHT JOIN ';
		$sql .= '(SELECT id, patient_id, max(datetested) as maxdate ';
		$sql .= 'FROM viralsamples_view ';
		$sql .= 'WHERE ( (year(datetested) = ? and month(datetested) > ?) || (year(datetested) = ? and month(datetested) < ?) ) ';
		$sql .= "AND patient != '' AND patient != 'null' AND patient is not null ";
		$sql .= 'AND flag=1 AND repeatt=0 AND rcategory in (1, 2, 3, 4) ';
		$sql .= 'AND justification != 10 AND facility_id != 7148 ';
		$sql .= 'AND facility_id = ' . $facility_id . ' ';
		$sql .= 'GROUP BY patient_id) gv ';
		$sql .= 'ON v.id=gv.id) tb ';
		$sql .= 'GROUP BY facility_id, rcategory ';
		$sql .= 'ORDER BY facility_id, rcategory ';

		$data = DB::connection('eid_vl')->select($sql, [$prev_year, $prev_month, $year, $month]);

		return $data;
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

		$raw = "ID, patient, facility, datetested";		

		$data = ViralsampleView::where(['flag' => 1, 'repeatt' => 0, 'synched' => 0])->get();

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

			if ($value->justification == 2 && $value->datetested) {

				$d = ViralsampleView::where('patient_id', $value->patient_id)
				->where('datetested', '<', $value->datetested)
				->where('datetested', '<', $value->datetested)
				->whereIn('rcategory', [3, 4])
				->where('repeatt', 0)
				->where('flag', 1)
				->where('facility_id', '!=', 7148)
				->first();

				if($d == null){
					$update_array = array_merge($update_array, ['previous_nonsuppressed' => 1]);
				}
			}

			DB::connection('eid_vl_wr')->table('viralsamples')->where('id', $value->id)->update($update_array);
			$p++;
		}


		echo "\n {$p} vl patients synched.";
	}
}
