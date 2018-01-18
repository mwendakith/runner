<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class EidInsert extends Model
{
    //

    public function summary($year=null, $month=null){
    	if($year == null){
    		$year = Date('Y');
    	}
    	if($month == null){
    		$month = Date('m');
    	}
		ini_set("memory_limit", "-1");

    	echo "\n Begin eid summary insert at " . date('d/m/Y h:i:s a', time());

    	$counties = DB::connection('eid')->table('countys')->select('ID')->orderBy('ID')->get();
    	$subcounties = DB::connection('eid')->table('districts')->select('ID')->orderBy('ID')->get();
    	$partners = DB::connection('eid')->table('partners')->select('ID')->orderBy('ID')->get();
    	$labs = DB::connection('eid')->table('labs')->select('ID')->orderBy('ID')->get();
    	$sites = DB::connection('eid')->table('facilitys')->select('ID')->orderBy('ID')->get();

    	$data_array=null;
    	$i=0;

		$data_array[$i] = array('year' => $year, 'month' => $month);
		$i++;

		DB::table('national_summary')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($counties as $k => $val) {
			$data_array[$i] = array('year' => $year, 'month' => $month, 'county' => $val->ID);
			$i++;
		}
		DB::table('county_summary')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($subcounties as $k => $val) {
			$data_array[$i] = array('year' => $year, 'month' => $month, 'subcounty' => $val->ID);
			$i++;
		}
		DB::table('subcounty_summary')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($partners as $k => $val) {
			$data_array[$i] = array('year' => $year, 'month' => $month, 'partner' => $val->ID);
			$i++;
		}
		DB::table('ip_summary')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($labs as $k => $val) {
			$data_array[$i] = array('year' => $year, 'month' => $month, 'lab' => $val->ID);
			$i++;
		}
		// POC row
		$data_array[$i] = array('year' => $year, 'month' => $month, 'lab' => 11);
		DB::table('lab_summary')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($sites as $k => $val) {
			$data_array[$i] = array('year' => $year, 'month' => $month, 'facility' => $val->ID);
			$i++;
			if ($i == 100) {
				DB::table('site_summary')->insert($data_array);
				$data_array=null;
		    	$i=0;
			}
		}
		DB::table('site_summary')->insert($data_array);

		echo "\n Completed eid summary insert at " . date('d/m/Y h:i:s a', time());

		$this->inserter($year, $month);
		$this->insert_lab_mapping($year, $month);
    }

    public function summary_yearly($year=null){
    	if($year == null){
    		$year = Date('Y');
    	}

		ini_set("memory_limit", "-1");

    	echo "\n Begin eid summary yearly insert at " . date('d/m/Y h:i:s a', time());

    	$counties = DB::connection('eid')->table('countys')->select('ID')->orderBy('ID')->get();
    	$subcounties = DB::connection('eid')->table('districts')->select('ID')->orderBy('ID')->get();
    	$partners = DB::connection('eid')->table('partners')->select('ID')->orderBy('ID')->get();
    	$labs = DB::connection('eid')->table('labs')->select('ID')->orderBy('ID')->get();
    	$sites = DB::connection('eid')->table('facilitys')->select('ID')->orderBy('ID')->get();

    	$data_array=null;
    	$i=0;

		$data_array[$i] = array('year' => $year);
		$i++;

		DB::table('national_summary_yearly')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($counties as $k => $val) {
			$data_array[$i] = array('year' => $year, 'county' => $val->ID);
			$i++;
		}
		DB::table('county_summary_yearly')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($subcounties as $k => $val) {
			$data_array[$i] = array('year' => $year, 'subcounty' => $val->ID);
			$i++;
		}
		DB::table('subcounty_summary_yearly')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($partners as $k => $val) {
			$data_array[$i] = array('year' => $year, 'partner' => $val->ID);
			$i++;
		}
		DB::table('ip_summary_yearly')->insert($data_array);

		// $data_array=null;
    	// $i=0;

		// foreach ($labs as $k => $val) {
		// 	$data_array[$i] = array('year' => $year, 'lab' => $val->ID);
		// 	$i++;
		// }
		// $data_array[$i] = array('year' => $year, 'lab' => 11);
		// DB::table('lab_summary_yearly')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($sites as $k => $val) {
			$data_array[$i] = array('year' => $year, 'facility' => $val->ID);
			$i++;
			if ($i == 100) {
				DB::table('site_summary_yearly')->insert($data_array);
				$data_array=null;
		    	$i=0;
			}
		}
		DB::table('site_summary_yearly')->insert($data_array);

		echo "\n Completed eid summary yearly insert at " . date('d/m/Y h:i:s a', time());
    }

    public function inserter($year=null, $month=null){
    	if($year == null){
    		$year = Date('Y');
    	}
    	if($month == null){
    		$month = Date('m');
    	}
		ini_set("memory_limit", "-1");

		// Get List of Divisions
    	$counties = DB::connection('eid')->table('countys')->select('ID')->orderBy('ID')->get();
    	$subcounties = DB::connection('eid')->table('districts')->select('ID')->orderBy('ID')->get();
    	$partners = DB::connection('eid')->table('partners')->select('ID')->orderBy('ID')->get();
    	$labs = DB::connection('eid')->table('labs')->select('ID')->orderBy('ID')->get();
    	$sites = DB::connection('eid')->table('facilitys')->select('ID')->orderBy('ID')->get();

    	// Iterate through classes of tables
    	for ($iterator=1; $iterator < 3; $iterator++) { 
    		$national = $this->get_table(0, $iterator);
    		$county = $this->get_table(1, $iterator);
    		$subcounty = $this->get_table(2, $iterator);
    		$partner = $this->get_table(3, $iterator);

    		$table_name = $national[1];
    		$column_name = $national[2];

			$reasons = $data = DB::connection('eid')
			->table($table_name)
			->select('ID')
			->when($iterator, function($query) use ($iterator){
				if($iterator == 1){
					return $query->where('ptype', 2);
				}	
				if($iterator == 2){
					return $query->where('ptype', 1);
				}							
			})
			->get();

			echo "\n Begin eid {$table_name} insert at " . date('d/m/Y h:i:s a', time());

			// National Insert
	    	$data_array=null;
	    	$i=0;

			foreach ($reasons as $key => $value) {
				$data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value->ID);
				$i++;
			}
			DB::table($national[0])->insert($data_array);


			// County Insert
			$data_array=null;
	    	$i=0;

			foreach ($reasons as $key => $value) {
				foreach ($counties as $k => $val) {
					$data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value->ID, 'county' => $val->ID);
					$i++;
				}
			}
			DB::table($county[0])->insert($data_array);

			// Subcounty Insert
			$data_array=null;
	    	$i=0;

			foreach ($reasons as $key => $value) {
				foreach ($subcounties as $k => $val) {
					$data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value->ID, 'subcounty' => $val->ID);
					$i++;
				}
			}
			DB::table($subcounty[0])->insert($data_array);

			// Partner Insert
			$data_array=null;
	    	$i=0;

			foreach ($reasons as $key => $value) {
				foreach ($partners as $k => $val) {
					$data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value->ID, 'partner' => $val->ID);
					$i++;
				}
			}
			DB::table($partner[0])->insert($data_array);

			// Lab Insert
	    	if($iterator == 5){

				$data_array=null;
		    	$i=0;

				foreach ($reasons as $key => $value) {
					foreach ($labs as $k => $val) {
						$data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value->ID, 'lab' => $val->ID);
						$i++;
					}
				}
				$lab = $this->get_table(5, $iterator);
				DB::table($lab[0])->insert($data_array);

			


				// Facility Insert
				$data_array=null;
		    	$i=0;
		    	
				$site = $this->get_table(4, $iterator);
				
				foreach ($reasons as $key => $value) {
					foreach ($sites as $k => $val) {
						$data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value->ID, 'facility' => $val->ID);
						$i++;

						if($i == 100){
							DB::table($site[0])->insert($data_array);
							$data_array=null;
					    	$i=0;
						}
					}
				}
				DB::table($site[0])->insert($data_array);
				$data_array=null;
		    	$i=0;

		    }
			

			echo "\n Completed eid {$table_name} insert at " . date('d/m/Y h:i:s a', time());
    	}
    }

    public function insert_lab_mapping($year=null, $month=null){
        if($year == null){
            $year = Date('Y');
        }
        if($month == null){
            $month = Date('m');
        }
        ini_set("memory_limit", "-1");

        echo "\n Begin eid lab mapping insert at " . date('d/m/Y h:i:s a', time());

        // Get List of Divisions
        $counties = DB::table('countys')->select('ID')->orderBy('ID')->get();
        $labs = DB::table('labs')->select('ID')->orderBy('ID')->get();

        // Lab Insert
    	$data_array=null;
    	$i=0;

        foreach ($labs as $key => $value) {
        	foreach ($counties as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'lab' => $value->ID, 
					'county' => $val->ID);
				$i++;
        	}
        	DB::table('lab_mapping')->insert($data_array);
	    	$data_array=null;
	    	$i=0;
        }
        echo "\n Completed eid lab mapping insert at " . date('d/m/Y h:i:s a', time());
    }

    private function get_table($division, $type){
    	$name;
    	if ($division == 0) {
    		switch ($type) {
    			case 1:
    				$name = array("national_iprophylaxis", "prophylaxis", "prophylaxis");
    				break;
    			case 2:
    				$name = array("national_mprophylaxis", "prophylaxis", "prophylaxis");
    				break;
    			case 3:
    				$name = array("national_entrypoint", "entry_points", "entrypoint");
    				break;
    			case 4:
    				$name = array("national_age_breakdown", "age_bands", "age_band_id");
    				break;
    			case 5:
    				$name = array("national_rejections", "rejectedreasons", "rejected_reason");
    				break;
    			default:
    				break;
    		}
    	}
    	else if ($division == 1) {
    		switch ($type) {
    			case 1:
    				$name = array("county_iprophylaxis", "prophylaxis", "prophylaxis");
    				break;
    			case 2:
    				$name = array("county_mprophylaxis", "prophylaxis", "prophylaxis");
    				break;
    			case 3:
    				$name = array("county_entrypoint", "entry_points", "entrypoint");
    				break;
    			case 4:
    				$name = array("county_age_breakdown", "age_bands", "age_band_id");
    				break;
    			case 5:
    				$name = array("county_rejections", "rejectedreasons", "rejected_reason");
    				break;
    			default:
    				break;
    		}
    	}

    	else if ($division == 2) {
    		switch ($type) {
    			case 1:
    				$name = array("subcounty_iprophylaxis", "prophylaxis", "prophylaxis");
    				break;
    			case 2:
    				$name = array("subcounty_mprophylaxis", "prophylaxis", "prophylaxis");
    				break;
    			case 3:
    				$name = array("subcounty_entrypoint", "entry_points", "entrypoint");
    				break;
    			case 4:
    				$name = array("subcounty_age_breakdown", "age_bands", "age_band_id");
    				break;
    			case 5:
    				$name = array("subcounty_rejections", "rejectedreasons", "rejected_reason");
    				break;
    			default:
    				break;
    		}
    	}

    	else if ($division == 3) {
    		switch ($type) {
    			case 1:
    				$name = array("ip_iprophylaxis", "prophylaxis", "prophylaxis");
    				break;
    			case 2:
    				$name = array("ip_mprophylaxis", "prophylaxis", "prophylaxis");
    				break;
    			case 3:
    				$name = array("ip_entrypoint", "entry_points", "entrypoint");
    				break;
    			case 4:
    				$name = array("ip_age_breakdown", "age_bands", "age_band_id");
    				break;
    			case 5:
    				$name = array("ip_rejections", "rejectedreasons", "rejected_reason");
    				break;
    			default:
    				break;
    		}
    	}

    	else if ($division == 4) {
    		switch ($type) {
    			case 5:
    				$name = array("site_rejections", "rejectedreasons", "rejected_reason");
    				break;
    			default:
    				break;
    		}
    	}

    	else if ($division == 5) {
    		switch ($type) {
    			case 5:
    				$name = array("lab_rejections", "rejectedreasons", "rejected_reason");
    				break;
    			default:
    				break;
    		}
    	}

    	return $name;
    }


    public function rejections($year=null, $month=null){
    	if($year == null){
    		$year = Date('Y');
    	}
    	if($month == null){
    		$month = Date('m');
    	}
		ini_set("memory_limit", "-1");

    	echo "\n Begin eid rejection insert at " . date('d/m/Y h:i:s a', time());

    	$reasons =  DB::connection('eid')->table('rejectedreasons')->select('ID')->orderBy('ID')->get();
    	$counties =  DB::connection('eid')->table('countys')->select('ID')->orderBy('ID')->get();
    	$subcounties =  DB::connection('eid')->table('districts')->select('ID')->orderBy('ID')->get();
    	$partners =  DB::connection('eid')->table('partners')->select('ID')->orderBy('ID')->get();
    	$labs = DB::connection('eid')->table('labs')->select('ID')->orderBy('ID')->get();
    	$sites = DB::connection('eid')->table('facilitys')->select('ID')->orderBy('ID')->get();

    	$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->ID);
			$i++;
		}
		DB::table('national_rejections')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($counties as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->ID, 'county' => $val->ID);
				$i++;
			}
		}
		DB::table('county_rejections')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($subcounties as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->ID, 'subcounty' => $val->ID);
				$i++;
			}
		}
		DB::table('subcounty_rejections')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($partners as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->ID, 'partner' => $val->ID);
				$i++;
			}
		}
		DB::table('ip_rejections')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($labs as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->ID, 'lab' => $val->ID);
				$i++;
			}
		}
		DB::table('lab_rejections')->insert($data_array);

		// echo "\n Completed eid else rejection insert at " . date('d/m/Y h:i:s a', time());

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($sites as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->ID, 'facility' => $val->ID);
				$i++;
			}
			DB::table('site_rejections')->insert($data_array);
			$data_array=null;
	    	$i=0;
		}
		

		echo "\n Completed eid rejection insert at " . date('d/m/Y h:i:s a', time());
    }

    public function age_breakdown($year=null, $month=null){
    	if($year == null){
    		$year = Date('Y');
    	}
    	if($month == null){
    		$month = Date('m');
    	}
		ini_set("memory_limit", "-1");

    	echo "\n Begin eid age insert at " . date('d/m/Y h:i:s a', time());

    	$reasons =  DB::connection('eid')->table('age_bands')->select('ID')->orderBy('ID')->get();
    	$counties =  DB::connection('eid')->table('countys')->select('ID')->orderBy('ID')->get();
    	$subcounties =  DB::connection('eid')->table('districts')->select('ID')->orderBy('ID')->get();
    	$partners =  DB::connection('eid')->table('partners')->select('ID')->orderBy('ID')->get();
    	// $labs = DB::connection('eid')->table('labs')->select('ID')->orderBy('ID')->get();
    	// $sites = DB::connection('eid')->table('facilitys')->select('ID')->orderBy('ID')->get();

    	$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			$data_array[$i] = array('year' => $year, 'month' => $month, 'age_band_id' => $value->ID);
			$i++;
		}
		DB::table('national_age_breakdown')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($counties as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'age_band_id' => $value->ID, 'county' => $val->ID);
				$i++;
			}
		}
		DB::table('county_age_breakdown')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($subcounties as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'age_band_id' => $value->ID, 'subcounty' => $val->ID);
				$i++;
			}
		}
		DB::table('subcounty_age_breakdown')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($partners as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'age_band_id' => $value->ID, 'partner' => $val->ID);
				$i++;
			}
		}
		DB::table('ip_age_breakdown')->insert($data_array);

		
		// $data_array=null;
    	// $i=0;

		// foreach ($reasons as $key => $value) {
		// 	foreach ($sites as $k => $val) {
		// 		$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->ID, 'facility' => $val->ID);
		// 		$i++;
		// 	}
		// 	DB::table('site_rejections')->insert($data_array);
			// $data_array=null;
	    	// $i=0;
		// }
		

		echo "\n Completed eid age insert at " . date('d/m/Y h:i:s a', time());
    }



}
