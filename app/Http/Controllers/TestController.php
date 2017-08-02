<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EidNation;
use App\EidDivision;
use DB;

class TestController extends Controller
{
    //

    public function test(){
    	$t = new EidDivision;

	    // $r = $t->GetNatTATs(2016);

	    $counties = $data = DB::connection('eid')
		->table('countys')->select('ID')->get();

		$a;

		foreach ($counties as $key => $value) {
			$a[$key] = $value->ID;
		}

		$r = $t->GetNatTATs(2016, 'view_facilitys.county', 'county', $a);

		$r = collect($r);



	    $d = $r->where('month', 4)->where('county', 10);

	    dd($d);

	    // foreach ($r as $key => $value) {
	    // 	echo $value->county . ' ' . $value->totals;
	    // }

    }

    


}
