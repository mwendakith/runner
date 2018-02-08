<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    //

    public function eid_agebreakdown(){


		// Set the following to null in order to free memory
		$alltests_a = $eqatests_a = $tests_a = $patienttests_a = $patienttestsPOS_a = $received_a = $firstdna_a = $confirmdna_a = $posrepeats_a = $confirmdnaPOS_a = $posrepeatsPOS_a = $infantsless2m_a = $infantsless2mPOS_a = $infantsless2w_a = $infantsless2wPOS_a = $infantsless46w_a = $infantsless46wPOS_a = $infantsabove2m_a = $infantsabove2mPOS_a = $adulttests_a = $adulttestsPOS_a = $pos_a = $neg_a = $fail_a = $rd_a = $rdd_a = $rej_a = $enrolled_a = $ltfu_a = $dead_a = $adult_a = $transout_a = $other_a = $v_cp_a = $v_ad_a = $v_vl_a = $v_rp_a = $v_uf_a = $sitesending_a = $avgage_a = $medage_a = $tat = null;

		// echo "\n Begin eid nation age breakdown update at " . date('d/m/Y h:i:s a', time());

		// Get national age_breakdown
		// $age1pos_a = $n->GetTestOutcomesbyAgeBand($year, 1, 2);
		// $age1neg_a = $n->GetTestOutcomesbyAgeBand($year, 1, 1);
		// $age2pos_a = $n->GetTestOutcomesbyAgeBand($year, 2, 2);
		// $age2neg_a = $n->GetTestOutcomesbyAgeBand($year, 2, 1);
		// $age3pos_a = $n->GetTestOutcomesbyAgeBand($year, 3, 2);
		// $age3neg_a = $n->GetTestOutcomesbyAgeBand($year, 3, 1);
		// $age4pos_a = $n->GetTestOutcomesbyAgeBand($year, 4, 2);
		// $age4neg_a = $n->GetTestOutcomesbyAgeBand($year, 4, 1);
		// $age5pos = 0;
		// $age5neg = 0;
		// $age6pos_a = $n->GetTestOutcomesbyAgeBand($year, 6, 2);
		// $age6neg_a = $n->GetTestOutcomesbyAgeBand($year, 6, 1);
		
		// $age9pos_a = $n->GetTestOutcomesbyAgeBand($year, 9, 2);
		// $age9neg_a = $n->GetTestOutcomesbyAgeBand($year, 9, 1);
		// $age10pos_a = $n->GetTestOutcomesbyAgeBand($year, 10, 2);
		// $age10neg_a = $n->GetTestOutcomesbyAgeBand($year, 10, 1);
		// $age11pos_a = $n->GetTestOutcomesbyAgeBand($year, 11, 2);
		// $age11neg_a = $n->GetTestOutcomesbyAgeBand($year, 11, 1);
		// $age12pos_a = $n->GetTestOutcomesbyAgeBand($year, 12, 2);
		// $age12neg_a = $n->GetTestOutcomesbyAgeBand($year, 12, 1);
		// $age13pos_a = $n->GetTestOutcomesbyAgeBand($year, 13, 2);
		// $age13neg_a = $n->GetTestOutcomesbyAgeBand($year, 13, 1);
		// $age14pos_a = $n->GetTestOutcomesbyAgeBand($year, 14, 2);
		// $age14neg_a = $n->GetTestOutcomesbyAgeBand($year, 14, 1);

		// // Loop through the months and insert data into the national agebreakdown
		// for ($i=0; $i < 12; $i++) { 
		// 	$month = $i + 1;
		// 	if($year == Date('Y') && $month > Date('m')){ break; }

		// 	$age1pos = $this->checknull($age1pos_a->where('month', $month));
		// 	$age1neg = $this->checknull($age1neg_a->where('month', $month));
		// 	$age2pos = $this->checknull($age2pos_a->where('month', $month));
		// 	$age2neg = $this->checknull($age2neg_a->where('month', $month));
		// 	$age3pos = $this->checknull($age3pos_a->where('month', $month));
		// 	$age3neg = $this->checknull($age3neg_a->where('month', $month));
		// 	$age4pos = $this->checknull($age4pos_a->where('month', $month));
		// 	$age4neg = $this->checknull($age4neg_a->where('month', $month));

		// 	$age6pos = $this->checknull($age6pos_a->where('month', $month));
		// 	$age6neg = $this->checknull($age6neg_a->where('month', $month));

		// 	$age9pos = $this->checknull($age9pos_a->where('month', $month));
		// 	$age9neg = $this->checknull($age9neg_a->where('month', $month));
		// 	$age10pos = $this->checknull($age10pos_a->where('month', $month));
		// 	$age10neg = $this->checknull($age10neg_a->where('month', $month));
		// 	$age11pos = $this->checknull($age11pos_a->where('month', $month));
		// 	$age11neg = $this->checknull($age11neg_a->where('month', $month));
		// 	$age12pos = $this->checknull($age12pos_a->where('month', $month));
		// 	$age12neg = $this->checknull($age12neg_a->where('month', $month));
		// 	$age13pos = $this->checknull($age13pos_a->where('month', $month));
		// 	$age13neg = $this->checknull($age13neg_a->where('month', $month));
		// 	$age14pos = $this->checknull($age14pos_a->where('month', $month));
		// 	$age14neg = $this->checknull($age14neg_a->where('month', $month));


		// 	$data_array = array(
		// 		'sixweekspos' => $age1pos, 'sixweeksneg' => $age1neg, 'sevento3mpos' => $age2pos,
		// 		'sevento3mneg' => $age2neg, 'threemto9mpos' => $age3pos, 
		// 		'threemto9mneg' => $age3neg, 'ninemto18mpos' => $age4pos,
		// 		'ninemto18mneg' => $age4neg, 'above18mpos' => $age5pos, 'above18mneg' => $age5neg,
		// 		'nodatapos' => $age6pos, 'nodataneg' => $age6neg, 'less2wpos' => $age9pos,
		// 		'less2wneg' => $age9neg, 'twoto6wpos' => $age10pos, 'twoto6wneg' => $age10neg,
		// 		'sixto8wpos' => $age11pos, 'sixto8wneg' => $age11neg, 'sixmonthpos' => $age12pos,
		// 		'sixmonthneg' => $age12neg, 'ninemonthpos' => $age13pos, 
		// 		'ninemonthneg' => $age13neg, 'twelvemonthpos' => $age14pos,
		// 		'twelvemonthneg' => $age14neg, 'dateupdated' => $today
		// 	);

		// 	DB::table('national_agebreakdown')->where('year', $year)->where('month', $month)->update($data_array);

			// $sql = "UPDATE national_agebreakdown set sixweekspos='$age1pos', sixweeksneg='$age1neg', sevento3mpos='$age2pos', sevento3mneg='$age2neg'	,threemto9mpos='$age3pos',threemto9mneg='$age3neg',ninemto18mpos='$age4pos',ninemto18mneg='$age4neg',above18mpos='$age5pos',above18mneg='$age5neg',nodatapos='$age6pos',nodataneg='$age6neg', less2wpos='$age9pos',less2wneg='$age9neg',twoto6wpos='$age10pos',twoto6wneg='$age10neg',sixto8wpos='$age11pos',sixto8wneg='$age11neg',sixmonthpos='$age12pos',sixmonthneg='$age12neg',ninemonthpos='$age13pos',ninemonthneg='$age13neg',twelvemonthpos='$age14pos',twelvemonthneg='$age14neg',sorted=9 WHERE month='$month' AND year='$year'";

		// }
		// End of for loop

		// echo "\n Completed entry into eid national age breakdown at " . date('d/m/Y h:i:s a', time());
    }

    public function division_age()
    {


		// echo "\n Begin entry into eid {$column} age breakdown at " . date('d/m/Y h:i:s a', time());
		// Get national age_breakdown
		// $age1pos_a = $n->GetTestOutcomesbyAgeBand($year, 1, 2, $division);
		// $age1neg_a = $n->GetTestOutcomesbyAgeBand($year, 1, 1, $division);
		// $age2pos_a = $n->GetTestOutcomesbyAgeBand($year, 2, 2, $division);
		// $age2neg_a = $n->GetTestOutcomesbyAgeBand($year, 2, 1, $division);
		// $age3pos_a = $n->GetTestOutcomesbyAgeBand($year, 3, 2, $division);
		// $age3neg_a = $n->GetTestOutcomesbyAgeBand($year, 3, 1, $division);
		// $age4pos_a = $n->GetTestOutcomesbyAgeBand($year, 4, 2, $division);
		// $age4neg_a = $n->GetTestOutcomesbyAgeBand($year, 4, 1, $division);
		// $age5pos = 0;
		// $age5neg = 0;
		// $age6pos_a = $n->GetTestOutcomesbyAgeBand($year, 6, 2, $division);
		// $age6neg_a = $n->GetTestOutcomesbyAgeBand($year, 6, 1, $division);
		
		// $age9pos_a = $n->GetTestOutcomesbyAgeBand($year, 9, 2, $division);
		// $age9neg_a = $n->GetTestOutcomesbyAgeBand($year, 9, 1, $division);
		// $age10pos_a = $n->GetTestOutcomesbyAgeBand($year, 10, 2, $division);
		// $age10neg_a = $n->GetTestOutcomesbyAgeBand($year, 10, 1, $division);
		// $age11pos_a = $n->GetTestOutcomesbyAgeBand($year, 11, 2, $division);
		// $age11neg_a = $n->GetTestOutcomesbyAgeBand($year, 11, 1, $division);
		// $age12pos_a = $n->GetTestOutcomesbyAgeBand($year, 12, 2, $division);
		// $age12neg_a = $n->GetTestOutcomesbyAgeBand($year, 12, 1, $division);
		// $age13pos_a = $n->GetTestOutcomesbyAgeBand($year, 13, 2, $division);
		// $age13neg_a = $n->GetTestOutcomesbyAgeBand($year, 13, 1, $division);
		// $age14pos_a = $n->GetTestOutcomesbyAgeBand($year, 14, 2, $division);
		// $age14neg_a = $n->GetTestOutcomesbyAgeBand($year, 14, 1, $division);

		// // Loop through the months and insert data into the national agebreakdown
		// for ($i=0; $i < 12; $i++) { 
		// 	$month = $i + 1;

		// 	if($year == Date('Y') && $month > Date('m')){ break; }

		// 	// Loop through divisions
		// 	for ($it=0; $it < $array_size; $it++) {
		// 		$age1pos = $this->checknull($age1pos_a->where('month', $month)->where($column, $div_array[$it]));
		// 		$age1neg = $this->checknull($age1neg_a->where('month', $month)->where($column, $div_array[$it]));
		// 		$age2pos = $this->checknull($age2pos_a->where('month', $month)->where($column, $div_array[$it]));
		// 		$age2neg = $this->checknull($age2neg_a->where('month', $month)->where($column, $div_array[$it]));
		// 		$age3pos = $this->checknull($age3pos_a->where('month', $month)->where($column, $div_array[$it]));
		// 		$age3neg = $this->checknull($age3neg_a->where('month', $month)->where($column, $div_array[$it]));
		// 		$age4pos = $this->checknull($age4pos_a->where('month', $month)->where($column, $div_array[$it]));
		// 		$age4neg = $this->checknull($age4neg_a->where('month', $month)->where($column, $div_array[$it]));

		// 		$age6pos = $this->checknull($age6pos_a->where('month', $month)->where($column, $div_array[$it]));
		// 		$age6neg = $this->checknull($age6neg_a->where('month', $month)->where($column, $div_array[$it]));

		// 		$age9pos = $this->checknull($age9pos_a->where('month', $month)->where($column, $div_array[$it]));
		// 		$age9neg = $this->checknull($age9neg_a->where('month', $month)->where($column, $div_array[$it]));
		// 		$age10pos = $this->checknull($age10pos_a->where('month', $month)->where($column, $div_array[$it]));
		// 		$age10neg = $this->checknull($age10neg_a->where('month', $month)->where($column, $div_array[$it]));
		// 		$age11pos = $this->checknull($age11pos_a->where('month', $month)->where($column, $div_array[$it]));
		// 		$age11neg = $this->checknull($age11neg_a->where('month', $month)->where($column, $div_array[$it]));
		// 		$age12pos = $this->checknull($age12pos_a->where('month', $month)->where($column, $div_array[$it]));
		// 		$age12neg = $this->checknull($age12neg_a->where('month', $month)->where($column, $div_array[$it]));
		// 		$age13pos = $this->checknull($age13pos_a->where('month', $month)->where($column, $div_array[$it]));
		// 		$age13neg = $this->checknull($age13neg_a->where('month', $month)->where($column, $div_array[$it]));
		// 		$age14pos = $this->checknull($age14pos_a->where('month', $month)->where($column, $div_array[$it]));
		// 		$age14neg = $this->checknull($age14neg_a->where('month', $month)->where($column, $div_array[$it]));


		// 		$data_array = array(
		// 			'sixweekspos' => $age1pos, 'sixweeksneg' => $age1neg, 'sevento3mpos' => $age2pos,
		// 			'sevento3mneg' => $age2neg, 'threemto9mpos' => $age3pos, 
		// 			'threemto9mneg' => $age3neg, 'ninemto18mpos' => $age4pos,
		// 			'ninemto18mneg' => $age4neg, 'above18mpos' => $age5pos, 'above18mneg' => $age5neg,
		// 			'nodatapos' => $age6pos, 'nodataneg' => $age6neg, 'less2wpos' => $age9pos,
		// 			'less2wneg' => $age9neg, 'twoto6wpos' => $age10pos, 'twoto6wneg' => $age10neg,
		// 			'sixto8wpos' => $age11pos, 'sixto8wneg' => $age11neg, 'sixmonthpos' => $age12pos,
		// 			'sixmonthneg' => $age12neg, 'ninemonthpos' => $age13pos, 
		// 			'ninemonthneg' => $age13neg, 'twelvemonthpos' => $age14pos,
		// 			'twelvemonthneg' => $age14neg, 'dateupdated' => $today
		// 		);
		// 		if ($type==2) {
		// 			$column="subcounty";
		// 		}

		// 		DB::table($age_table)->where('year', $year)->where('month', $month)->where($column, $div_array[$it])->update($data_array);
				
		// 		if ($type==2) {
		// 			$column = $column2;
		// 		}
		// 	}
		// 	// End of division loop
		// }
		// // End of months loop

		// echo "\n Completed entry into eid {$column} age breakdown at " . date('d/m/Y h:i:s a', time());
    }
}
