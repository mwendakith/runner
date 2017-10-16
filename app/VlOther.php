<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class VlOther extends Model
{
    //

    public function update_all(){

    	echo "\n Begin viralload samples update at " . date('d/m/Y h:i:s a', time());

    	// Gender
    	DB::connection('vl')->table('viralpatients')->where('gender', 'N')->orWhere('gender', '')->orWhere('gender', 'U')->update(['gender' => 'No Data']);
    	DB::connection('vl')->table('viralpatients')->where('gender', 1)->update(['gender' => 'M']);
    	DB::connection('vl')->table('viralpatients')->where('gender', 2)->update(['gender' => 'F']);

    	// Justification
    	DB::connection('vl')->table('viralsamples')->where('justification', 0)->update(['justification' => 8]);

    	// Prophylaxis
    	DB::connection('vl')->table('viralsamples')->where('prophylaxis', 0)->update(['prophylaxis' => 16]);
    	DB::connection('vl')->table('viralpatients')->where('prophylaxis', 0)->update(['prophylaxis' => 16]);
    	DB::connection('vl')->table('viralsamples')->where('justification', 2)->where('receivedstatus', 1)->update(['receivedstatus' => 3, 'reason_for_repeat' => 'Repeat For Confirmatory VL Greater 1000 copies/Ml']);



    	// Age
    	DB::connection('vl')->table('viralsamples')
    	->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID')
    	->where('viralsamples.age', 0)->whereBetween('viralpatients.age', [0.0001, 4.9])
    	->update(['viralsamples.age' => 1]);

    	DB::connection('vl')->table('viralsamples')
    	->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID')
    	->where('viralsamples.age', 0)->whereBetween('viralpatients.age', [5, 9.9])
    	->update(['viralsamples.age' => 2]);

    	DB::connection('vl')->table('viralsamples')
    	->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID')
    	->where('viralsamples.age', 0)->whereBetween('viralpatients.age', [10, 14.9])
    	->update(['viralsamples.age' => 3]);

    	DB::connection('vl')->table('viralsamples')
    	->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID')
    	->where('viralsamples.age', 0)->whereBetween('viralpatients.age', [15, 17.9])
    	->update(['viralsamples.age' => 4]);

    	DB::connection('vl')->table('viralsamples')
    	->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID')
    	->where('viralsamples.age', 0)->where('viralpatients.age', '>', 17.9)
    	->update(['viralsamples.age' => 5]);

    	DB::connection('vl')->table('viralsamples')
    	->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID')
    	->where('viralsamples.age2', 0)->whereBetween('viralpatients.age', [0.000001, 1.99999])
    	->update(['viralsamples.age2' => 6]);

    	DB::connection('vl')->table('viralsamples')
    	->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID')
    	->where('viralsamples.age2', 0)->whereBetween('viralpatients.age', [2, 9.99999])
    	->update(['viralsamples.age2' => 7]);

    	DB::connection('vl')->table('viralsamples')
    	->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID')
    	->where('viralsamples.age2', 0)->whereBetween('viralpatients.age', [10, 14.99999])
    	->update(['viralsamples.age2' => 8]);

    	DB::connection('vl')->table('viralsamples')
    	->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID')
    	->where('viralsamples.age2', 0)->whereBetween('viralpatients.age', [15, 19.99999])
    	->update(['viralsamples.age2' => 9]);

    	DB::connection('vl')->table('viralsamples')
    	->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID')
    	->where('viralsamples.age2', 0)->whereBetween('viralpatients.age', [20, 24.99999])
    	->update(['viralsamples.age2' => 10]);

    	DB::connection('vl')->table('viralsamples')
    	->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID')
    	->where('viralsamples.age2', 0)->where('viralpatients.age', '>', 24.99999)
    	->update(['viralsamples.age2' => 11]);


    	$r1 = "(result='< LDL copies' OR result='Target not detected' 
    			OR result='< LDL copies' OR result='Not Detected' OR result='< LDL copies/ml' 
    			OR result='<LDL copies/ml')";

    	$r2 = "(result='<550' OR result='< 550 ' OR result='<150' OR result='<160'
    			 OR result='<75' OR result='<274' OR result='<400' OR result='< 400'
    			 OR result='<188' OR result='<218' OR result='<839' OR result='< 21'
    			 OR result='<40' OR result='<20' OR result='<218' OR result ='<1000')";

		$r4 = "(result='> 10000000' OR result='>10,000,000' OR result='>10000000' OR result='>10000000')";

		$r5 = "(result ='Failed' OR result ='Failed PREP_ABORT' OR result ='Failed Test'
				OR result ='Invalid' OR result ='Collect New Sample')";

		$rf = "(result='Failed Collect New sample' OR result='Collect New Sample' OR result='Failed Collect New sample')";

		$rc = "(result='Insufficient sample' OR result='Insufficient sample please collect new sample' OR result='Redraw New Sample' OR result='Failed False' OR result='collect new samp' OR result='collect new saple' OR result='insufficient' OR result='Failed Collect New sample' OR result='collect new sampl' OR result='collect new')";

    	// Result Categories
    	DB::connection('vl')->table('viralsamples')
    	->where('rcategory', 0)->where('result', '< LDL copies/ml')
    	->update(['rcategory' => 1]);

    	DB::connection('vl')->table('viralsamples')
    	->where('rcategory', 0)->where(DB::raw($r1))
    	->update(['result' => '< LDL copies/ml', 'rcategory' => 1]);

    	DB::connection('vl')->table('viralsamples')
    	->where('rcategory', 0)->where(DB::raw($r2))
    	->update(['rcategory' => 2]);

    	DB::connection('vl')->table('viralsamples')
    	->where('rcategory', 0)->whereBetween('result', [1, 1000])
    	->update(['rcategory' => 2]);

    	DB::connection('vl')->table('viralsamples')
    	->where('rcategory', 0)->where('result', '>1000')
    	->update(['rcategory' => 3]);

    	DB::connection('vl')->table('viralsamples')
    	->where('rcategory', 0)->whereBetween('result', [1001, 5000])
    	->update(['rcategory' => 3]);

    	DB::connection('vl')->table('viralsamples')
    	->where('rcategory', 0)->where(DB::raw($r4))
    	->update(['rcategory' => 4]);

    	DB::connection('vl')->table('viralsamples')
    	->where('rcategory', 0)->where('result', '>', '5000')
    	->update(['rcategory' => 4]);

    	DB::connection('vl')->table('viralsamples')
    	->where('rcategory', 0)->where(DB::raw($r5))
    	->update(['rcategory' => 5]);



    	DB::connection('vl')->table('viralsamples')
    	->where('rcategory', 0)->where('repeatt', 0)->where(DB::raw($rf))
    	->update(['rcategory' => 5, 'labcomment' => 'Failed Test']);

    	DB::connection('vl')->table('viralsamples')
    	->where('rcategory', 0)->where(DB::raw($rc))
    	->update(['rcategory' => 5, 'labcomment' => 'Failed Test', 'result' => 'Collect New Sample']);

    	DB::connection('vl')->table('viralsamples')
    	->where('rcategory', 0)->where('result', 'double entry')
    	->update(['rcategory' => 5, 'result' => '', 'datetested' => '', 'receivedstatus' => 2, 'rejectedreason' => 18]);

    	DB::connection('vl')->table('viralsamples')
    	->where('rcategory', 0)
    	->where(DB::raw("(result LIKE 'haemolysed%' OR result='Failed SAMPLECLOT')"))
    	->update(['rcategory' => 5, 'result' => '', 'datetested' => '', 'receivedstatus' => 2, 'rejectedreason' => 1]);

    	DB::connection('vl')->table('viralsamples')
    	->where('rcategory', 0)
    	->where(DB::raw("(result='no sample receivwd' OR result='not received')"))
    	->update(['rcategory' => 5, 'result' => '', 'datetested' => '', 'receivedstatus' => 2, 'rejectedreason' => 17]);

    	echo "\n Completed entry into viralload samples at " . date('d/m/Y h:i:s a', time());


    }

    public function bla(){
        echo "\n Begin eid {$column}_age_breakdown update at " . date('d/m/Y h:i:s a', time());

        $reasons = $data = DB::connection('eid')
        ->table('age_bands')->get();

        // Loop through age bands
        foreach ($reasons as $key => $value) {
            // Each age band has a lower and uppper limit which we pass as a param
            $pos_a = $n->OutcomesByAgeBand($year, [$value->lower, $value->upper], 2, $division);
            $neg_a = $n->OutcomesByAgeBand($year, [$value->lower, $value->upper], 1, $division);

            // Loop through the months and insert data into the national agebreakdown
            for ($i=0; $i < $count; $i++) { 
                $month = $i + 1;
                if($year == Date('Y') && $month > Date('m')){ break; }

                // Loop through divisions
                for ($it=0; $it < $array_size; $it++) {

                    $pos = $this->checknull($pos_a->where('month', $month)->where($column, $div_array[$it]));
                    $neg = $this->checknull($neg_a->where('month', $month)->where($column, $div_array[$it]));

                    $data_array = array(
                        'pos' => $pos, 'neg' => $neg 'dateupdated' => $today
                    );


                    if ($type==2) {
                        $column="subcounty";
                    }

                    DB::table($ageb_table)->where('year', $year)->where('month', $month)->where($column, $div_array[$it])->update($data_array);

                    $column = $column2;
                }
            }
        }
        // End of for loop

        echo "\n Completed entry into eid {$column}_age_breakdown at " . date('d/m/Y h:i:s a', time());





        echo "\n Begin eid nation_age_breakdown update at " . date('d/m/Y h:i:s a', time());

        $reasons = $data = DB::connection('eid')
        ->table('age_bands')->get();

        // Loop through age bands
        foreach ($reasons as $key => $value) {
            // Each age band has a lower and uppper limit which we pass as a param
            $pos_a = $n->OutcomesByAgeBand($year, [$value->lower, $value->upper], 2);
            $neg_a = $n->OutcomesByAgeBand($year, [$value->lower, $value->upper], 1);

            // Loop through the months and insert data into the national agebreakdown
            for ($i=0; $i < $count; $i++) { 
                $month = $i + 1;
                if($year == Date('Y') && $month > Date('m')){ break; }

                $pos = $this->checknull($pos_a->where('month', $month));
                $neg = $this->checknull($neg_a->where('month', $month));

                $data_array = array(
                    'pos' => $pos, 'neg' => $neg 'dateupdated' => $today
                );

                DB::table('national_age_breakdown')->where('year', $year)->where('month', $month)->update($data_array);
            }
        }
        // End of for loop

        echo "\n Completed entry into eid national_age_breakdown at " . date('d/m/Y h:i:s a', time());

    }
}
