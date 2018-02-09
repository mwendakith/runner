<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\BaseModel;

class VlTest extends Model
{

    public function getalltestedviraloadsamplesbydash($year, $month, $division='view_facilitys.county', $type, $param){

		$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(viralsamples.ID) as totals"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2 || $type == 6){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
				->where('viralsamples.justification', '!=', 2)
				->where('viralsamples.justification', '!=', 10)
				->whereBetween('viralsamples.rcategory', [1, 4]);
			}
			if($type == 4){
				return $query->whereBetween('viralsamples.rcategory', [1, 4]);
			}					
		})
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereBetween($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', $month)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy($division)
		->get();

		return $data;
    }

    public function getallreceivediraloadsamplesbydash($year, $month, $division='view_facilitys.county', $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2 || $type == 6){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datereceived', $year)
		->whereMonth('datereceived', $month)
		->whereRaw("((parentid=0) || (parentid IS NULL))")
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereBetween($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.Flag', 1)
		->groupBy($division)
		->get();

		return $data;
    }

    public function getallrejectedviraloadsamplesbydash($year, $month, $division='view_facilitys.county', $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2 || $type == 6){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datereceived', $year)
		->whereMonth('datereceived', $month)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereBetween($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.receivedstatus', 2)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy($division)
		->get();

		return $data;
    }

    public function GetNationalConfirmed2VLsbydash($year, $month, $division='view_facilitys.county', $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2 || $type == 6){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', $month)
		->whereBetween('viralsamples.sampletype', [1, 4])
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereBetween($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.justification', 2)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy($division)
		->get();

		return $data;
    }

    public function GetNationalConfirmedFailurebydash($year, $month, $division='view_facilitys.county', $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2 || $type == 6){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', $month)
		->whereBetween('viralsamples.sampletype', [1, 4])
		->whereBetween('viralsamples.rcategory', [3, 4])
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereBetween($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.justification', 2)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy($division)
		->get();

		return $data;
    }

    public function GetNationalBaselinebydash($year, $month, $division='view_facilitys.county', $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2 || $type == 6){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', $month)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereBetween($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->whereBetween('viralsamples.rcategory', [1, 4])
		->whereBetween('viralsamples.sampletype', [1, 4])
		->where('viralsamples.justification', 10)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy($division)
		->get();

		return $data;

    	// $sql = "select count(DISTINCT(ID))  as numsamples from viralsamples where viralsamples.justification=10 AND  MONTH(datetested)='$month' and YEAR(datetested)='$year'   AND viralsamples.sampletype BETWEEN 1 AND 4  AND repeatt=0 and Flag=1 and rcategory between 1 and 4";
    }

    public function GetNationalBaselineFailurebydash($year, $month, $division='view_facilitys.county', $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2 || $type == 6){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', $month)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereBetween($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->whereBetween('viralsamples.rcategory', [3, 4])
		->whereBetween('viralsamples.sampletype', [1, 4])
		->where('viralsamples.justification', 10)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy($division)
		->get();

		return $data;

    	// $sql = "select count(DISTINCT(ID))  as numsamples from viralsamples where viralsamples.justification=10 AND  MONTH(datetested)='$month' and YEAR(datetested)='$year' AND viralsamples.rcategory BETWEEN 3 AND 4  AND viralsamples.sampletype BETWEEN 1 AND 4  AND repeatt=0 and Flag=1";
    }

    public function getallrepeattviraloadsamplesbydash($year, $month, $division='view_facilitys.county', $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2 || $type == 6){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', $month)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereBetween($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.receivedstatus', 3)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy($division)
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbytypedetailsbydash($year, $month, $division='view_facilitys.county', $type, $param, $sampletype){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2 || $type == 6){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
				->where('viralsamples.justification', '!=', 2)
				->where('viralsamples.justification', '!=', 10)
				->whereBetween('viralsamples.rcategory', [1, 4]);
			}
			if($type == 4){
				return $query->whereBetween('viralsamples.rcategory', [1, 4]);
			}					
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', $month)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereBetween($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->when($sampletype, function($query) use ($sampletype){
			if($sampletype == 2){
				return $query->whereBetween('viralsamples.sampletype', [3, 4]);
			}
			else if($sampletype == 3){
				return $query->where('viralsamples.sampletype', 2);
			}
			else{
				return $query->where('viralsamples.sampletype', 1);
			}				
		})
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy($division)
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbygenderbydash($year, $month, $division='view_facilitys.county', $type, $param, $gender){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);
		$sex = $b->get_gender($gender);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID')
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
				->where('viralsamples.justification', '!=', 2)
				->where('viralsamples.justification', '!=', 10)
				->whereBetween('viralsamples.rcategory', [1, 4]);
			}
			if($type == 4){
				return $query->whereBetween('viralsamples.rcategory', [1, 4]);
			}					
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', $month)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereBetween($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralpatients.gender', $sex)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy($division)
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbyagebydash($year, $month, $division='view_facilitys.county', $type, $param, $age){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$age_column = 'viralsamples.age2';

		if($age < 6){
			$age_column = 'viralsamples.age';
		}

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2 || $type == 6){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
				->where('viralsamples.justification', '!=', 2)
				->where('viralsamples.justification', '!=', 10)
				->whereBetween('viralsamples.rcategory', [1, 4]);
			}
			if($type == 4){
				return $query->whereBetween('viralsamples.rcategory', [1, 4]);
			}					
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', $month)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereBetween($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where($age_column, $age)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy($division)
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbyresultbydash($year, $month, $division='view_facilitys.county', $type, $param, $result){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2 || $type == 6){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
				->where('viralsamples.justification', '!=', 2)
				->where('viralsamples.justification', '!=', 10);
			}				
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', $month)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereBetween($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.rcategory', $result)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy($division)
		->get();

		return $data;
    }
}
