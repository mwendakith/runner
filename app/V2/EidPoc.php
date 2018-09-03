<?php

namespace App\V2;

use DB;
use App\V2\BaseModel;
use App\SampleSynchView;

class EidPoc
{

	//national outcomes	
	public function OverallTestedSamplesOutcomes($year, $result_type, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data =SampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->where('result', $result_type)
		->whereBetween('datetested', $date_range)
		->where('pcrtype', 1)
		->where('flag', 1)
		->where('eqa', 0)
		->where('repeatt', 0)
		->where('siteentry', 2)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}

	// Total number of batches
    public function GettotalbatchesPerlab($year, $monthly=true)
    {
		$date_range = BaseModel::date_range($year);

    	$data =SampleSynchView::selectRaw("COUNT(batch_id) as totals, month(datetested) as month")
		->whereBetween('datetested', $date_range)
		->whereRaw("(parentid=0  OR parentid IS NULL)")
		->where('flag', 1)
		->where('eqa', 0)
		->where('siteentry', 2)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get();

		return $data;
    }

    //national sites by period
	public function GettotalEIDsitesbytimeperiod($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data =SampleSynchView::selectRaw("COUNT(DISTINCT facility) as totals, month(datereceived) as month")
		->whereBetween('datereceived', $date_range)
		->where('flag', 1)
		->where('eqa', 0)
		->where('siteentry', 2)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}

	// Tat
	public function get_tat($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$sql = "AVG(tat1) AS tat1, AVG(tat2) AS tat2, AVG(tat3) AS tat3, AVG(tat4) AS tat4, month(datetested) as month";

		$data =SampleSynchView::selectRaw($sql)
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
		->where('siteentry', 2)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}

	//national tests
	public function OverallReceivedSamples($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data =SampleSynchView::selectRaw("COUNT(id) as totals, month(datereceived) as month")
		->whereBetween('datereceived', $date_range)
		->whereRaw("(parentid=0 OR parentid IS NULL)")
		->where('flag', 1)
		->where('eqa', 0)
		->where('siteentry', 2)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}

	//national tests
	public function OverallTestedSamples($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data =SampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->whereBetween('result', [1, 2])
		->whereBetween('datetested', $date_range)
		->where('repeatt', 0)
		->where('flag', 1)
		->where('eqa', 0)
		->where('siteentry', 2)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}

	//national ALL tests EQA + INFANTS
	public function CumulativeTestedSamples($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data =SampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->where('result', '>', 0)
		->whereBetween('datetested', $date_range)
		->where('flag', 1)
		->where('eqa', 0)
		->where('repeatt', 0)
		->where('siteentry', 2)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get();

		return $data;
	}

	//national EQA tests
	public function OverallEQATestedSamples($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data =SampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->where('result', '>', 0)
		->whereBetween('datetested', $date_range)
		->whereRaw("(receivedstatus=1  OR (receivedstatus=3  and  reason_for_repeat='Repeat For Rejection'))")
		->where('flag', 1)
		->where('eqa', 1)
		->where('repeatt', 0)
		->where('siteentry', 2)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get();

		return $data;
	}

	public function getbypcr($year, $pcrtype=1, $pos=false, $monthly=true)
	{
		$date_range = BaseModel::date_range($year); 

		$data =SampleSynchView::selectRaw("COUNT(id) as totals, month(datetested) as month")
		->when(true, function($query) use ($pos){
			if($pos){
				return $query->where('result', 2);
			}
			else{
				return $query->whereBetween('result', [1, 2]);
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
		->where('siteentry', 2)
		->where('flag', 1)
		->where('eqa', 0)
		->where('repeatt', 0)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get();

		return $data;
	}

	//national rejected	
	public function Getnationalrejectedsamples($year, $monthly=true)
	{
		$date_range = BaseModel::date_range($year);

		$data =SampleSynchView::selectRaw("COUNT(id) as totals, month(datereceived) as month")
		->whereBetween('datereceived', $date_range)
		->where('receivedstatus', 2)
		->where('flag', 1)
		->where('eqa', 0)
		->where('repeatt', 0)
		->where('siteentry', 2)
		->when($monthly, function($query){
			return $query->groupBy('month');			
		})
		->get(); 

		return $data;
	}

}