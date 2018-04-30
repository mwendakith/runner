<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\BaseModel;

class EidPoc extends Model
{


	//national outcomes	
	public function OverallTestedSamplesOutcomes($year, $result_type, $monthly=true)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->where('samples.result', $result_type)
		->whereYear('datetested', $year)
		->where('samples.pcrtype', 1)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->where('samples.siteentry', 2)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result ='$resulttype' AND YEAR(datetested)='$year' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND repeatt=0  AND Flag=1 AND eqa=0") or die(mysql_error()); 

  
	}

	// Total number of batches
    public function GettotalbatchesPerlab($year, $monthly=true){
    	$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.batchno) as totals, month(datetested) as month"))
		->whereYear('datetested', $year)
		->whereRaw("(samples.parentid=0  OR samples.parentid IS NULL)")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.siteentry', 2)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get();

		return $data;

		// $sql = "SELECT COUNT(DISTINCT(batchno)) as totals  FROM samples WHERE labtestedin='$lab' AND  YEAR(datereceived)='$year'  AND ((samples.parentid=0)||(samples.parentid IS NULL))  AND Flag=1 AND eqa=0";
    }

    //national sites by period
	public function GettotalEIDsitesbytimeperiod($year, $monthly=true)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(DISTINCT samples.facility) as totals, month(datereceived) as month"))
		->whereYear('datereceived', $year)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.siteentry', 2)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;

		// $strQuery=mysql_query("SELECT COUNT(DISTINCT samples.facility) as 'activesites' FROM $tb WHERE YEAR($tb.datereceived)='$currentyear'  AND MONTH($tb.datereceived)='$currentmonth' and $tb.eqa=0 and $tb.flag=1")or die(mysql_error());

  
	}

	// Tat
	public function get_tat($year, $monthly=true)
	{
		$sql = "AVG(tat1) AS tat1, AVG(tat2) AS tat2, AVG(tat3) AS tat3, AVG(tat4) AS tat4, month(datetested) as month";

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw($sql))
		->whereYear('samples.datecollected', '>', 1980)
		->whereColumn([
			['datecollected', '<=', 'datereceived'],
			['datereceived', '<=', 'datetested'],
			['datetested', '<=', 'datedispatched']
		])
		->whereYear('samples.datecollected', '>', 1980)
		->whereYear('samples.datereceived', '>', 1980)
		->whereYear('samples.datetested', '>', 1980)
		->whereYear('samples.datedispatched', '>', 1980)
		->whereYear('datetested', $year)
		->where('samples.Flag', 1)
		->where('samples.repeatt', 0)
		->where('samples.siteentry', 2)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;
	}

	//national tests
	public function OverallReceivedSamples($year, $monthly=true)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datereceived) as month"))
		->whereYear('datereceived', $year)
		->whereRaw("(samples.parentid=0 OR samples.parentid IS NULL)")
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.siteentry', 2)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE YEAR(datereceived)='$year'  AND ((samples.parentid=0)||(samples.parentid IS NULL))  AND Flag=1 AND eqa=0") or die(mysql_error());            


	}

	//national tests
	public function OverallTestedSamples($year, $monthly=true)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->whereBetween('result', [1, 2])
		->whereYear('datetested', $year)
		->where('repeatt', 0)
		->where('Flag', 1)
		->where('eqa', 0)
		->where('samples.siteentry', 2)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;  

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result BETWEEN 1 AND 2 AND YEAR(datetested)='$year' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection')) AND repeatt=0  AND Flag=1 AND eqa=0") or die(mysql_error());            


	}

	//national ALL tests EQA + INFANTS
	public function CumulativeTestedSamples($year, $monthly=true)
	{
		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->where('result', '>', 0)
		->whereYear('datetested', $year)
		->where('Flag', 1)
		->where('eqa', 0)
		->where('repeatt', 0)
		->where('samples.siteentry', 2)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get();

		return $data;

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result > 0 AND YEAR(datetested)='$year' AND (samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))   AND Flag=1 AND eqa=0") or die(mysql_error());            


	}

	//national EQA tests
	public function OverallEQATestedSamples($year, $monthly=true)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->where('result', '>', 0)
		->whereYear('datetested', $year)
		->whereRaw("(samples.receivedstatus=1  OR (samples.receivedstatus=3  and  samples.reason_for_repeat='Repeat For Rejection'))")
		->where('Flag', 1)
		->where('eqa', 1)
		->where('repeatt', 0)
		->where('samples.siteentry', 2)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get();

		return $data;

		// $sql=mysql_query("SELECT COUNT(samples.ID) as totals  FROM samples WHERE result > 0 AND YEAR(datetested)='$year'  AND repeatt=0  AND Flag=1 AND eqa=1") or die(mysql_error());            

	}

	public function getbypcr($year, $pcr=1, $pos=false, $monthly=true){

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datetested) as month"))
		->when(true, function($query) use ($pos){
			if($pos){
				return $query->where('samples.result', 2);
			}
			else{
				return $query->whereBetween('samples.result', [1, 2]);
			}			
		})
		->whereYear('datetested', $year)
		->where('samples.pcrtype', $pcr)
		->where('samples.siteentry', 2)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;
	}

	//national rejected	
	public function Getnationalrejectedsamples($year, $monthly=true)
	{

		$data = DB::connection('eid')
		->table('samples')
		->select(DB::raw("COUNT(samples.ID) as totals, month(datereceived) as month"))
		->whereYear('datereceived', $year)
		->where('samples.receivedstatus', 2)
		->where('samples.Flag', 1)
		->where('samples.eqa', 0)
		->where('samples.repeatt', 0)
		->where('samples.siteentry', 2)
		->when($monthly, function($query) use ($monthly){
			if($monthly){
				return $query->groupBy('month');
			}			
		})
		->get(); 

		return $data;

		// $s="SELECT COUNT(ID) as numsamples FROM samples WHERE receivedstatus = 2 AND YEAR(samples.datereceived)='$yea' AND samples.repeatt=0 AND samples.Flag=1 and samples.eqa=0";
  
	}

}