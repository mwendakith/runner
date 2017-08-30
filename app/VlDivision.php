<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\BaseModel;

class VlDivision extends Model
{
    //

    public function getalltestedviraloadsamples($year, $division='view_facilitys.county'){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->whereYear('datetested', $year)
		->whereBetween('viralsamples.rcategory', [1, 4])
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getallactualpatients($year, $division='view_facilitys.county'){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.patient,viralsamples.facility) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->whereYear('datetested', $year)
		->whereBetween('viralsamples.rcategory', [1, 4])
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getallreceivediraloadsamples($year, $division='view_facilitys.county'){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datereceived) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->whereYear('datereceived', $year)
		->whereRaw("((parentid=0) || (parentid IS NULL))")
		->where('viralsamples.Flag', 1)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getallrejectedviraloadsamples($year, $division='view_facilitys.county'){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datereceived) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->whereYear('datereceived', $year)
		->where('viralsamples.receivedstatus', 2)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function GetSupportedfacilitysFORViralLoad($year, $division='view_facilitys.county'){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.facility) as totals, month(datereceived) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->whereYear('datereceived', $year)
		->where('viralsamples.facility', '!=', 0)
		->where('viralsamples.Flag', 1)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function GetNationalConfirmed2VLs($year, $division='view_facilitys.county'){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->whereYear('datetested', $year)
		->whereBetween('viralsamples.rcategory', [1, 4])
		->whereBetween('viralsamples.sampletype', [1, 4])
		->where('viralsamples.justification', 2)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function GetNationalConfirmedFailure($year, $division='view_facilitys.county'){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->whereYear('datetested', $year)
		->whereBetween('viralsamples.rcategory', [3, 4])
		->whereBetween('viralsamples.sampletype', [1, 4])
		->where('viralsamples.justification', 2)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function GetNationalBaseline($year, $division='view_facilitys.county'){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->whereYear('datetested', $year)
		->whereBetween('viralsamples.rcategory', [1, 4])
		->whereBetween('viralsamples.sampletype', [1, 4])
		->where('viralsamples.justification', 10)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function GetNationalBaselineFailure($year, $division='view_facilitys.county'){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->whereYear('datetested', $year)
		->whereBetween('viralsamples.rcategory', [3, 4])
		->whereBetween('viralsamples.sampletype', [1, 4])
		->where('viralsamples.justification', 10)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getallrepeattviraloadsamples($year, $division='view_facilitys.county'){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->whereYear('datetested', $year)
		->where('viralsamples.receivedstatus', 3)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbytypedetails($year, $division='view_facilitys.county', $sampletype){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->whereYear('datetested', $year)
		->whereRaw("(viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection'))")
		->whereBetween('viralsamples.rcategory', [1, 4])
		->where('viralsamples.sampletype', $sampletype)
		->where('viralsamples.justification', '!=', 2)
		->where('viralsamples.justification', '!=', 10)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getalltestedviraloadbygender($year, $division='view_facilitys.county', $sex){

    	$b = new BaseModel;
		$gender = $b->get_gender($sex);

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID')
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->whereYear('datetested', $year)
		->whereRaw("(viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection'))")
		->whereBetween('viralsamples.rcategory', [1, 4])
		->where('viralpatients.gender', $gender)
		->where('viralsamples.justification', '!=', 2)
		->where('viralsamples.justification', '!=', 10)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getalltestedviraloadsbyage($year, $division='view_facilitys.county', $age, $all=false){

    	$b = new BaseModel;
		$age_band = $b->get_vlage($age);

		$age_column = 'viralsamples.age2';
		$sql = "COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month";

		if($age < 6){
			$age_column = 'viralsamples.age';
		}

		if($all){
			$sql = "COUNT(viralsamples.ID) as totals, month(datetested) as month";
		}

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->whereYear('datetested', $year)
		->whereRaw("(viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection'))")
		->whereBetween('viralsamples.rcategory', [1, 4])
		->where($age_column, $age)
		->where('viralsamples.justification', '!=', 2)
		->where('viralsamples.justification', '!=', 10)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getalltestedviraloadsbyresult($year, $division='view_facilitys.county', $result){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->whereYear('datetested', $year)
		->whereRaw("(viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection'))")
		->where('viralsamples.rcategory', $result)
		->where('viralsamples.justification', '!=', 2)
		->where('viralsamples.justification', '!=', 10)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function GetNatTATs($year, $div_array, $division='view_facilitys.county', $col='county')
	{
		// $sql = "datediff(datereceived, datecollected) as tat1, datediff(datetested, datereceived) as tat2, datediff(datedispatched, datetested) as tat3, datediff(datedispatched, datecollected) as tat4, datecollected, datereceived, datetested, datedispatched, month(datetested) as month";
		$sql = "datecollected, datereceived, datetested, datedispatched, month(datetested) as month";
		ini_set("memory_limit", "-1");

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw($sql))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->whereYear('viralsamples.datecollected', '>', 1980)
		->whereYear('viralsamples.datereceived', '>', 1980)
		->whereYear('viralsamples.datetested', '>', 1980)
		->whereYear('viralsamples.datedispatched', '>', 1980)
		->whereColumn([
			['datecollected', '<=', 'datereceived'],
			['datereceived', '<=', 'datetested'],
			['datetested', '<=', 'datedispatched']
		])
		->whereYear('datetested', $year)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->get(); 



		$return;
		$b = new BaseModel;

		$place = 0;

		for ($i=0; $i < 12; $i++) { 
			$month = $i + 1;

			for ($iterator=0; $iterator < count($div_array); $iterator++) { 
				$c = $div_array[$iterator];
				
				$d = $data->where('month', $month)->where($col, $c);

				if($d->isEmpty()){
					$return[$place]['tat1'] = 0;
					$return[$place]['tat2'] = 0;
					$return[$place]['tat3'] = 0;
					$return[$place]['tat4'] = 0;
					$return[$place]['division'] = $c;
					$return[$place]['month'] = $month;
					continue;
				}

				$tat1 = $tat2 = $tat3 = $tat4 = 0;
				$rows = $d->count();

				$holidays = $b->getTotalHolidaysinMonth($month);

				foreach ($d as $key => $value) {
					
					$tat1 += $b->get_days($value->datecollected, $value->datereceived, $holidays);
					$tat2 += $b->get_days($value->datereceived, $value->datetested, $holidays);
					$tat3 += $b->get_days($value->datetested, $value->datedispatched, $holidays);
					$tat4 += $b->get_days($value->datecollected, $value->datedispatched, $holidays);

				}

				$return[$place]['tat1'] = floor($tat1 / $rows);
				$return[$place]['tat2'] = floor($tat2 / $rows);
				$return[$place]['tat3'] = floor($tat3 / $rows);
				$return[$place]['tat4'] = floor($tat4 / $rows);
				$return[$place]['county'] = $c;
				$return[$place]['month'] = $month;

				$place++;

			}


		}

		return $return;
	}

	public function get_tat($year, $division='view_facilitys.county')
    	$sql = "AVG(tat1) AS tat1, AVG(tat2) AS tat2, AVG(tat3) AS tat3, AVG(tat4) AS tat4, month(datetested) as month";

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw($sql))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->whereYear('viralsamples.datecollected', '>', 1980)
		->whereYear('viralsamples.datereceived', '>', 1980)
		->whereYear('viralsamples.datetested', '>', 1980)
		->whereYear('viralsamples.datedispatched', '>', 1980)
		->whereColumn([
			['datecollected', '<=', 'datereceived'],
			['datereceived', '<=', 'datetested'],
			['datetested', '<=', 'datedispatched']
		])
		->whereYear('datetested', $year)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get(); 

		return $data;
	}



    public function compare_alltestedviralloadsamples(){
    	$age = "select count(ID)  as numsamples from viralsamples where  MONTH(datetested)='$month' and YEAR(datetested)='$year' AND (viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection')) AND viralsamples.justification !=2 AND repeatt=0 and Flag=1 AND $colmn ='$age' and viralsamples.rcategory BETWEEN 1 AND 4";

    	$gender = "select count(viralsamples.ID)  as numsamples from viralsamples, viralpatients where  viralsamples.patientid=viralpatients.AutoID AND MONTH(datetested)='$month' AND (viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection')) AND viralsamples.justification !=2 and YEAR(datetested)='$year' AND repeatt=0 and Flag=1 AND viralpatients.gender='$gender' and viralsamples.rcategory BETWEEN 1 AND 4";

    	$regimen = "select count(ID)  as numsamples from viralsamples where  MONTH(datetested)='$month' and YEAR(datetested)='$year' AND repeatt=0 and Flag=1 AND prophylaxis='$regimen' and (viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection')) AND viralsamples.justification !=2";

    	$justification = "select count(ID)  as numsamples from viralsamples where  MONTH(datetested)='$month' and YEAR(datetested)='$year' AND repeatt=0 and Flag=1 AND justification='$justification'";

    	$sampletype = "select count(ID)  as numsamples from viralsamples where  MONTH(datetested)='$month' and YEAR(datetested)='$year' AND repeatt=0 and Flag=1 AND sampletype BETWEEN '$stype' AND '$ttype'";
    }

    public function getalltestedviraloadsamplesbydash($year, $division='view_facilitys.county', $type, $param){

		$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
				->whereRaw("(viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection'))")
				->where('viralsamples.justification', '!=', 2)
				->where('viralsamples.justification', '!=', 10)
				->whereBetween('viralsamples.rcategory', [1, 4]);
			}				
		})
		->when($type, function($query) use ($type, $param, $p){
			if($type == 5 && $param == 3){
				return $query->where($p['column'], '>', ($p['param']-1) );
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->whereYear('datetested', $year)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getallreceivediraloadsamplesbydash($year, $division='view_facilitys.county', $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datereceived) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datereceived', $year)
		->whereRaw("((parentid=0) || (parentid IS NULL))")
		->when($type, function($query) use ($type, $param, $p){
			if($type == 5 && $param == 3){
				return $query->where($p['column'], '>', ($p['param']-1) );
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.Flag', 1)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getallrejectedviraloadsamplesbydash($year, $division='view_facilitys.county', $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datereceived) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datereceived', $year)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 5 && $param == 3){
				return $query->where($p['column'], '>', ($p['param']-1) );
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.receivedstatus', 2)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function GetNationalConfirmed2VLsbydash($year, $division='view_facilitys.county', $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datetested', $year)
		->whereBetween('viralsamples.sampletype', [1, 4])
		->when($type, function($query) use ($type, $param, $p){
			if($type == 5 && $param == 3){
				return $query->where($p['column'], '>', ($p['param']-1) );
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.justification', 2)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function GetNationalConfirmedFailurebydash($year, $division='view_facilitys.county', $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datetested', $year)
		->whereBetween('viralsamples.sampletype', [1, 4])
		->whereBetween('viralsamples.rcategory', [3, 4])
		->when($type, function($query) use ($type, $param, $p){
			if($type == 5 && $param == 3){
				return $query->where($p['column'], '>', ($p['param']-1) );
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.justification', 2)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getallrepeattviraloadsamplesbydash($year, $division='view_facilitys.county', $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datetested', $year)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 5 && $param == 3){
				return $query->where($p['column'], '>', ($p['param']-1) );
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.receivedstatus', 3)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbytypedetailsbydash($year, $division='view_facilitys.county', $type, $param, $sampletype){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
				->whereRaw("(viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection'))")
				->where('viralsamples.justification', '!=', 2)
				->where('viralsamples.justification', '!=', 10)
				->whereBetween('viralsamples.rcategory', [1, 4]);
			}				
		})
		->whereYear('datetested', $year)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 5 && $param == 3){
				return $query->where($p['column'], '>', ($p['param']-1) );
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.sampletype', $sampletype)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbygenderbydash($year, $division='view_facilitys.county', $type, $param, $gender){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);
		$sex = $b->get_gender($gender);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID')
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
				->whereRaw("(viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection'))")
				->where('viralsamples.justification', '!=', 2)
				->where('viralsamples.justification', '!=', 10)
				->whereBetween('viralsamples.rcategory', [1, 4]);
			}				
		})
		->whereYear('datetested', $year)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 5 && $param == 3){
				return $query->where($p['column'], '>', ($p['param']-1) );
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralpatients.gender', $sex)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbyagebydash($year, $division='view_facilitys.county', $type, $param, $age){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$age_column = 'viralsamples.age2';

		if($age < 6){
			$age_column = 'viralsamples.age';
		}

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
				->whereRaw("(viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection'))")
				->where('viralsamples.justification', '!=', 2)
				->where('viralsamples.justification', '!=', 10)
				->whereBetween('viralsamples.rcategory', [1, 4]);
			}				
		})
		->whereYear('datetested', $year)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 5 && $param == 3){
				return $query->where($p['column'], '>', ($p['param']-1) );
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where($age_column, $age)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbyresultbydash($year, $division='view_facilitys.county', $type, $param, $result){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
				->whereRaw("(viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection'))")
				->where('viralsamples.justification', '!=', 2)
				->where('viralsamples.justification', '!=', 10);
			}				
		})
		->whereYear('datetested', $year)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 5 && $param == 3){
				return $query->where($p['column'], '>', ($p['param']-1) );
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.rcategory', $result)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function update_patients(){
		$sql = "viralsamples.ID, viralsamples.patient, viralsamples.batchno, view_facilitys.name, view_facilitys.facilitycode, view_facilitys.DHIScode, viralpatients.age, viralpatients.gender, viralpatients.prophylaxis, viralsamples.justification, viralsamples.datecollected, viralsamples.receivedstatus, viralsamples.sampletype, viralsamples.rejectedreason, viralsamples.reason_for_repeat, viralsamples.datereceived, viralsamples.datetested, viralsamples.result, viralsamples.datedispatched, viralsamples.labtestedin, month(datetested) as month";

		$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw($sql))
		->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID')
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->where('viralsamples.synched', 0)
		->get();

		$today=date('Y-m-d');

		$b = new BaseModel;

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
				'datedispatched' => $value->datedispatched, 'labtestedin' => $value->labtestedin
			);

			DB::table('patients')->insert($data_array);

			$holidays = $b->getTotalHolidaysinMonth($value->month);

			$tat1 = $b->get_days($value->datecollected, $value->datereceived, $holidays);
			$tat2 = $b->get_days($value->datereceived, $value->datetested, $holidays);
			$tat3 = $b->get_days($value->datetested, $value->datedispatched, $holidays);
			$tat4 = $b->get_days($value->datecollected, $value->datedispatched, $holidays);

			$update_array = array('synched' => 0, 'datesynched' => $today, 'tat1' => $tat1, 'tat2' => $tat2, 'tat3' => $tat3, 'tat4' => $tat4);

			DB::connection('vl')->table('viralsamples')->where('ID', $value->ID)->update($update_array);
		}
	}
}
