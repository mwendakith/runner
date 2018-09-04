<?php

namespace App\V2;

use Illuminate\Support\Facades\Mail;
use App\Mail\UpdateReport;
use App\Mail\InsertReport;
use Carbon\Carbon;

class BaseModel
{
    //

    public function getTotalHolidaysinMonth($month)
	{
		switch ($month) {
			case 0:
				$totalholidays=10;
				break;
			case 1:
				$totalholidays=1;
				break;
			case 4:
				$totalholidays=2;
				break;
			case 5:
				$totalholidays=1;
				break;
			case 6:
				$totalholidays=1;
				break;
			case 8:
				$totalholidays=1;
				break;
			case 10:
				$totalholidays=1;
				break;
			case 12:
				$totalholidays=3;
				break;
			default:
				$totalholidays=0;
				break;
		}
		return $totalholidays;

	}

	public function getWorkingDays($startDate,$endDate){


	    //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
	    //We add one to inlude both dates in the interval.
	    $days = (strtotime($endDate) - strtotime($startDate)) / 86400 + 1;

	    $no_full_weeks = floor($days / 7);

	    $no_remaining_days = fmod($days, 7);

	    //It will return 1 if it's Monday,.. ,7 for Sunday
	    $the_first_day_of_week = date("N",strtotime($startDate));

	    $the_last_day_of_week = date("N",strtotime($endDate));
	    // echo              $the_last_day_of_week;
	    //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
	    //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
	    if ($the_first_day_of_week <= $the_last_day_of_week){
	        if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
	        if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
	    }

	    else{
	        if ($the_first_day_of_week <= 6) {
	        //In the case when the interval falls in two weeks, there will be a Sunday for sure
	            $no_remaining_days--;
	        }
	    }

	    //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
		//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
	   $workingDays = $no_full_weeks * 5;
	    if ($no_remaining_days > 0 )
	    {
	      $workingDays += $no_remaining_days;
	    }

	    //We subtract the holidays
		/*    foreach($holidays as $holiday){
	        $time_stamp=strtotime($holiday);
	        //If the holiday doesn't fall in weekend
	        if (strtotime($startDate) <= $time_stamp && $time_stamp <= strtotime($endDate) && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
	            $workingDays--;
	    }*/

	    return $workingDays;
	} 

	public static function age_range($age){
		$age_b;
		switch ($age) {
			case 0:
				$age_b = array(0, 0);
				break;
			case 1:
				$age_b = array(0.0001, 2);
				break;
			case 2:
				$age_b = array(2.0001, 18);
				break;
			case 3:
				$age_b = array(0.0001, 0.5);
				break;
			case 4:
				$age_b = array(1, 1.5);
				break;
			case 5:
				$age_b = array(24, 1200);
				break;
			default:
				break;
		}
		return $age_b;
	}

	public static function age_band($age){
		$age_b;
		switch ($age) {
			case 1:
				$age_b = array(0.0001, 2);
				break;
			case 2:
				$age_b = array(2.0001, 8);
				break;
			case 3:
				$age_b = array(8.0001, 12);
				break;
			case 4:
				$age_b = array(12.0001, 18);
				break;
			case 5:
				$age_b = array(18.0001, 1200);
				break;
			case 6:
				$age_b = array(0, 0);
				break;
			case 9:
				$age_b = array(0.0001, 0.5);
				break;
			case 10:
				$age_b = array(0.50001, 1.5);
				break;
			case 11:
				$age_b = array(1.5001, 2);
				break;
			case 12:
				$age_b = array(2.0001, 6);
				break;
			case 13:
				$age_b = array(6.0001, 9);
				break;
			case 14:
				$age_b = array(9.0001, 12);
				break;
			default:
				break;
		}
		return $age_b;
	}

	public function get_days($start, $finish, $holidays){
		if($start == '0000-00-00' || $finish == '0000-00-00') return null;
		// $finish = date("d-m-Y",strtotime($finish));
		// $start = date("d-m-Y",strtotime($start));
		// $workingdays= $this->getWorkingDays($start, $finish);

		$s = Carbon::parse($start);
		$f = Carbon::parse($finish);
		$workingdays = $s->diffInWeekdays($f);

		$totaldays = $workingdays - $holidays;
		if ($totaldays < 1) $totaldays=1;
		return $totaldays;
	}

	public function get_vlage($age){
		$age_b;
		switch ($age) {
			case 0:
				$age_b = array(0, 0);
				break;
			case 1:
				$age_b = array(0.0001, 4.9);
				break;
			case 2:
				$age_b = array(5, 9.9);
				break;
			case 3:
				$age_b = array(10, 14.9);
				break;
			case 4:
				$age_b = array(15, 17.9);
				break;
			case 5:
				$age_b = array(18, 10000000);
				break;
			case 6:
				$age_b = array(0.0001, 1.9);
				break;
			case 7:
				$age_b = array(2, 9.9);
				break;
			case 8:
				$age_b = array(10, 14.9);
				break;
			case 9:
				$age_b = array(15, 19.9);
				break;
			case 10:
				$age_b = array(20, 24.9);
				break;
			case 11:
				$age_b = array(25, 100000);
				break;
			default:
				break;
		}
		return $age_b;
	}

	public static function get_vlparams($type, $param=null){
		$data;
		$param = (int) $param;

		// Type 1 for age
		if($type == 1){
			return array('column' => 'age_category', 'param' => $param);
		}

		// Type 2 for gender
		else if($type == 2){
			return array('column' => 'sex', 'param' => $param);
		}

		// Type 3 for regimen
		else if($type == 3){
			return array('column' => 'prophylaxis', 'param' => $param);
		}

		// Type 4 for sampletype
		else if ($type == 4) {
			if ($param == 2) {
				return array('column' => 'sampletype', 'param' => 3);
			}
			else if ($param == 3) {
				return array('column' => 'sampletype', 'param' => 2);
			}
			else{
				return array('column' => 'sampletype', 'param' => $param);
			}
			
		}

		// Type 5 for justification
		else if($type == 5){
			return array('column' => 'justification', 'param' => $param);
		}

		// Type 6 for pmtct
		else if($type == 6){
			return array('column' => 'pmtct', 'param' => $param);
		}
	}

	public static function date_range($year, $start_month=null)
	{
		$date_range[0] = ($year) . '-01-01';
		if($start_month){
			$start_month++;
			$month = $start_month;
			if($month < 10) $month = '0' . $month;
			$date_range[0] = ($year) . '-' . $month . '-01';
		}
		$date_range[1] = ($year) . '-12-31';
		return $date_range;
	}

	public static function date_range_month($year, $month=null)
	{
		if($month < 10) $month = '0' . $month;


		$date_range[0] = ($year) . '-' . $month . '-01';

		$d = Carbon::createFromFormat('Y-m-d', $date_range[0]);
		$d->addMonth();
		$d->subDay();

		$date_range[1] = $d->toDateString();
	}


    public function send_report(){
    	$mail_array = array('joelkith@gmail.com', 'tngugi@gmail.com', 'baksajoshua09@gmail.com');
    	Mail::to($mail_array)->send(new UpdateReport());

    	// $filePath = public_path('logs.txt');
    	// fclose(fopen($filePath, 'w'));
    }

    public function send_insert_report(){
    	$mail_array = array('joelkith@gmail.com', 'tngugi@gmail.com', 'baksajoshua09@gmail.com');
    	Mail::to($mail_array)->send(new InsertReport());
    }

    public function test_mail(){
    	// $mail_array = array('joelkith@gmail.com', 'tngugi@gmail.com', 'baksajoshua09@gmail.com');
    	$mail_array = array('joelkith@gmail.com');
    	$up = new UpdateReport;
    	Mail::to($mail_array)->send($up);
    }


    public function update_query($table, $data_array, $search_array)
    {
    	$sql = "UPDATE {$table} SET ";

    	foreach ($data_array as $key => $value) {
    		$sql .= "`{$key}` = '{$value}', ";
    	}

    	$sql = substr($sql, 0, -2);

    	$sql .= ' WHERE ';

    	foreach ($search_array as $key => $value) {
    		$sql .= "`{$key}` = '{$value}' AND ";
    	}

    	$sql = substr($sql, 0, -5);
    	$sql .= "; ";
    	return $sql;
    }

	



}
