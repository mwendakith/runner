<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EidNation;
use App\EidDivision;
use App\Eid;
use DB;

class TestController extends Controller
{
    //

    public function test(){
    	// $t = new EidNation;
    	$t = new EidDivision;

	    // $r = $t->GetNatTATs(2016);

	    $counties = $data = DB::connection('eid')
		->table('countys')->select('ID')->get();

		$a;

		foreach ($counties as $key => $value) {
			$a[$key] = $value->ID;
		}

		$r = $t->GetNatTATs(2016, $a);
		// $r = $t->GetNatTATs(2016);

		$r = collect($r);



	    $d = $r->where('month', 1)->where('county', 1);
	    return $this->checknull($d);

	    dd($d);

	    // foreach ($r as $key => $value) {
	    // 	echo $value->county . ' ' . $value->totals;
	    // }

    }

    public function test2(){
    	$n = new Eid;
    	echo $n->update_nation(2016);
    }

    public function checknull($var){
    	if($var->isEmpty()){
    		return 0;
    	}else{
    		dd($var);
    	}
    }

    


}
