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

        // Sample Type
    	DB::connection('vl')->table('viralsamples')->where('sampletype', 5)->update(['sampletype' => 1]);

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

    // public function bla(){
    //     echo "\n Begin eid {$column}_age_breakdown update at " . date('d/m/Y h:i:s a', time());

    //     $reasons = $data = DB::connection('eid')
    //     ->table('age_bands')->get();

    //     // Loop through age bands
    //     foreach ($reasons as $key => $value) {
    //         // Each age band has a lower and uppper limit which we pass as a param
    //         $pos_a = $n->OutcomesByAgeBand($year, [$value->lower, $value->upper], 2, $division);
    //         $neg_a = $n->OutcomesByAgeBand($year, [$value->lower, $value->upper], 1, $division);

    //         // Loop through the months and insert data into the national agebreakdown
    //         for ($i=0; $i < $count; $i++) { 
    //             $month = $i + 1;
    //             if($year == Date('Y') && $month > Date('m')){ break; }

    //             // Loop through divisions
    //             for ($it=0; $it < $array_size; $it++) {

    //                 $pos = $this->checknull($pos_a->where('month', $month)->where($column, $div_array[$it]));
    //                 $neg = $this->checknull($neg_a->where('month', $month)->where($column, $div_array[$it]));

    //                 $data_array = array(
    //                     'pos' => $pos, 'neg' => $neg, 'dateupdated' => $today
    //                 );


    //                 if ($type==2) {
    //                     $column="subcounty";
    //                 }

    //                 DB::table($ageb_table)->where('year', $year)->where('month', $month)->where($column, $div_array[$it])->update($data_array);

    //                 $column = $column2;
    //             }
    //         }
    //     }
    //     // End of for loop

    //     echo "\n Completed entry into eid {$column}_age_breakdown at " . date('d/m/Y h:i:s a', time());





    //     echo "\n Begin eid nation_age_breakdown update at " . date('d/m/Y h:i:s a', time());

    //     $reasons = $data = DB::connection('eid')
    //     ->table('age_bands')->get();

    //     // Loop through age bands
    //     foreach ($reasons as $key => $value) {
    //         // Each age band has a lower and uppper limit which we pass as a param
    //         $pos_a = $n->OutcomesByAgeBand($year, [$value->lower, $value->upper], 2);
    //         $neg_a = $n->OutcomesByAgeBand($year, [$value->lower, $value->upper], 1);

    //         // Loop through the months and insert data into the national agebreakdown
    //         for ($i=0; $i < $count; $i++) { 
    //             $month = $i + 1;
    //             if($year == Date('Y') && $month > Date('m')){ break; }

    //             $pos = $this->checknull($pos_a->where('month', $month));
    //             $neg = $this->checknull($neg_a->where('month', $month));

    //             $data_array = array(
    //                 'pos' => $pos, 'neg' => $neg, 'dateupdated' => $today
    //             );

    //             DB::table('national_age_breakdown')->where('year', $year)->where('month', $month)->update($data_array);
    //         }
    //     }
    //     // End of for loop

    //     echo "\n Completed entry into eid national_age_breakdown at " . date('d/m/Y h:i:s a', time());

    // }

    public function update_suppression(){
        ini_set("memory_limit", "-1");

        echo "\n Begin entry into vl suppression at " . date('d/m/Y h:i:s a', time()); 

        // Instantiate new object
        $n = new VlDivision;

        $today=date('Y-m-d');

        $data = $n->suppression();

        $noage = $n->current_age_suppression(0);
        $less2 = $n->current_age_suppression(6);
        $less9 = $n->current_age_suppression(7);
        $less14 = $n->current_age_suppression(8);
        $less19 = $n->current_age_suppression(9);
        $less24 = $n->current_age_suppression(10);
        $over25 = $n->current_age_suppression(11);

        // $noage_ = $n->current_age_suppression(0, false);
        // $less2_ = $n->current_age_suppression(6, false);
        // $less9_ = $n->current_age_suppression(7, false);
        // $less14_ = $n->current_age_suppression(8, false);
        // $less19_ = $n->current_age_suppression(9, false);
        // $less24_ = $n->current_age_suppression(10, false);
        // $over25_ = $n->current_age_suppression(11, false);

        $male = $n->current_gender_suppression(1);  
        $female = $n->current_gender_suppression(2);  
        $nogender = $n->current_gender_suppression(3);

        // $male_ = $n->current_gender_suppression(1, false);  
        // $female_ = $n->current_gender_suppression(2, false);  
        // $nogender_ = $n->current_gender_suppression(3, false);   

        $divs = DB::connection('vl')
        ->table('facilitys')->select('ID', 'totalartmar')->get();

        $data = collect($data);


        foreach ($divs as $key => $value) {

            $suppressed = 
            (int) $this->checknull($data->where('facility', $value->ID)->where('rcategory', 1)) + 
            (int) $this->checknull($data->where('facility', $value->ID)->where('rcategory', 2));

            $nonsuppressed = 
            (int) $this->checknull($data->where('facility', $value->ID)->where('rcategory', 3)) + 
            (int) $this->checknull($data->where('facility', $value->ID)->where('rcategory', 4));

            $suppression=0;

            $tests =  ($suppressed + $nonsuppressed);
            $coverage = 0;

            if($tests == 0){
                continue;
                $suppression = 0;
            }
            else{

                $suppression = ($suppressed * 100) / $tests;

                if($value->totalartmar != null){
                    $coverage = ($tests * 100) / (int) $value->totalartmar ;
                }
                else{
                    $coverage = 100;
                }
            }

            $noage_sup =
            (int) $this->checknull($noage->where('facility', $value->ID)->where('rcategory', 1)) + 
            (int) $this->checknull($noage->where('facility', $value->ID)->where('rcategory', 2));

            $noage_nonsup = 
            (int) $this->checknull($noage->where('facility', $value->ID)->where('rcategory', 3)) + 
            (int) $this->checknull($noage->where('facility', $value->ID)->where('rcategory', 4));

            $less2_sup =
            (int) $this->checknull($less2->where('facility', $value->ID)->where('rcategory', 1)) + 
            (int) $this->checknull($less2->where('facility', $value->ID)->where('rcategory', 2));

            $less2_nonsup = 
            (int) $this->checknull($less2->where('facility', $value->ID)->where('rcategory', 3)) + 
            (int) $this->checknull($less2->where('facility', $value->ID)->where('rcategory', 4));

            $less9_sup =
            (int) $this->checknull($less9->where('facility', $value->ID)->where('rcategory', 1)) + 
            (int) $this->checknull($less9->where('facility', $value->ID)->where('rcategory', 2));

            $less9_nonsup = 
            (int) $this->checknull($less9->where('facility', $value->ID)->where('rcategory', 3)) + 
            (int) $this->checknull($less9->where('facility', $value->ID)->where('rcategory', 4));

            $less14_sup =
            (int) $this->checknull($less14->where('facility', $value->ID)->where('rcategory', 1)) + 
            (int) $this->checknull($less14->where('facility', $value->ID)->where('rcategory', 2));

            $less14_nonsup = 
            (int) $this->checknull($less14->where('facility', $value->ID)->where('rcategory', 3)) + 
            (int) $this->checknull($less14->where('facility', $value->ID)->where('rcategory', 4));

            $less19_sup =
            (int) $this->checknull($less19->where('facility', $value->ID)->where('rcategory', 1)) + 
            (int) $this->checknull($less19->where('facility', $value->ID)->where('rcategory', 2));

            $less19_nonsup = 
            (int) $this->checknull($less19->where('facility', $value->ID)->where('rcategory', 3)) + 
            (int) $this->checknull($less19->where('facility', $value->ID)->where('rcategory', 4));

            $less24_sup =
            (int) $this->checknull($less24->where('facility', $value->ID)->where('rcategory', 1)) + 
            (int) $this->checknull($less24->where('facility', $value->ID)->where('rcategory', 2));

            $less24_nonsup = 
            (int) $this->checknull($less24->where('facility', $value->ID)->where('rcategory', 3)) + 
            (int) $this->checknull($less24->where('facility', $value->ID)->where('rcategory', 4));

            $over25_sup =
            (int) $this->checknull($over25->where('facility', $value->ID)->where('rcategory', 1)) + 
            (int) $this->checknull($over25->where('facility', $value->ID)->where('rcategory', 2));

            $over25_nonsup = 
            (int) $this->checknull($over25->where('facility', $value->ID)->where('rcategory', 3)) + 
            (int) $this->checknull($over25->where('facility', $value->ID)->where('rcategory', 4));





            $male_sup =
            (int) $this->checknull($male->where('facility', $value->ID)->where('rcategory', 1)) + 
            (int) $this->checknull($male->where('facility', $value->ID)->where('rcategory', 2));

            $male_nonsup = 
            (int) $this->checknull($male->where('facility', $value->ID)->where('rcategory', 3)) + 
            (int) $this->checknull($male->where('facility', $value->ID)->where('rcategory', 4));

            $female_sup =
            (int) $this->checknull($female->where('facility', $value->ID)->where('rcategory', 1)) + 
            (int) $this->checknull($female->where('facility', $value->ID)->where('rcategory', 2));

            $female_nonsup = 
            (int) $this->checknull($female->where('facility', $value->ID)->where('rcategory', 3)) + 
            (int) $this->checknull($female->where('facility', $value->ID)->where('rcategory', 4));

            $nogender_sup =
            (int) $this->checknull($nogender->where('facility', $value->ID)->where('rcategory', 1)) + 
            (int) $this->checknull($nogender->where('facility', $value->ID)->where('rcategory', 2));

            $nogender_nonsup = 
            (int) $this->checknull($nogender->where('facility', $value->ID)->where('rcategory', 3)) + 
            (int) $this->checknull($nogender->where('facility', $value->ID)->where('rcategory', 4));


            // $data_array = array('facility' => $value->ID, 'dateupdated' => $today,
            // 'suppressed' => $suppressed, 'nonsuppressed' => $nonsuppressed, 'suppression' => $suppression);
            $data_array = array('dateupdated' => $today, 'suppressed' => $suppressed, 
                'nonsuppressed' => $nonsuppressed, 'suppression' => $suppression, 'coverage' => $coverage,

                'noage_suppressed' => $noage_sup, 'noage_nonsuppressed' => $noage_nonsup,
                'less2_suppressed' => $less2_sup, 'less2_nonsuppressed' => $less2_nonsup,
                'less9_suppressed' => $less9_sup, 'less9_nonsuppressed' => $less9_nonsup,
                'less14_suppressed' => $less14_sup, 'less14_nonsuppressed' => $less14_nonsup,
                'less19_suppressed' => $less19_sup, 'less19_nonsuppressed' => $less19_nonsup,
                'less24_suppressed' => $less24_sup, 'less24_nonsuppressed' => $less24_nonsup,
                'over25_suppressed' => $over25_sup, 'over25_nonsuppressed' => $over25_nonsup,

                'male_suppressed' => $male_sup, 'male_nonsuppressed' => $male_nonsup,
                'female_suppressed' => $female_sup, 'female_nonsuppressed' => $female_nonsup,
                'nogender_suppressed' => $nogender_sup, 'nogender_nonsuppressed' => $nogender_nonsup
            );

            // DB::table('vl_site_suppression')->insert($data_array);
            DB::table('vl_site_suppression')->where('facility', $value->ID)->update($data_array);
        }

        echo "\n Completed entry into vl suppression at " . date('d/m/Y h:i:s a', time()); 
    }
}
