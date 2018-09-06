<?php

namespace App;
use DB;

class EidInsert
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

    	$counties = DB::table('countys')->select('id')->orderBy('id')->get();
    	$subcounties = DB::table('districts')->select('id')->orderBy('id')->get();
    	$partners = DB::table('partners')->select('id')->orderBy('id')->get();
    	$labs = DB::table('labs')->select('id')->orderBy('id')->get();
    	$sites = DB::table('facilitys')->select('id')->orderBy('id')->get();

    	$data_array=null;
    	$i=0;

		$data_array[$i] = array('year' => $year, 'month' => $month);
		$i++;

		DB::table('national_summary')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($counties as $k => $val) {
			$data_array[$i] = array('year' => $year, 'month' => $month, 'county' => $val->id);
			$i++;
		}
		DB::table('county_summary')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($subcounties as $k => $val) {
			$data_array[$i] = array('year' => $year, 'month' => $month, 'subcounty' => $val->id);
			$i++;
		}
		DB::table('subcounty_summary')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($partners as $k => $val) {
			$data_array[$i] = array('year' => $year, 'month' => $month, 'partner' => $val->id);
			$i++;
		}
		DB::table('ip_summary')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($labs as $k => $val) {
			$data_array[$i] = array('year' => $year, 'month' => $month, 'lab' => $val->id);
			$i++;
		}
		// POC row 
		$data_array[$i] = array('year' => $year, 'month' => $month, 'lab' => 11);
		DB::table('lab_summary')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($sites as $k => $val) {
			$data_array[$i] = array('year' => $year, 'month' => $month, 'facility' => $val->id);
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

    	$counties = DB::table('countys')->select('id')->orderBy('id')->get();
    	$subcounties = DB::table('districts')->select('id')->orderBy('id')->get();
    	$partners = DB::table('partners')->select('id')->orderBy('id')->get();
    	$labs = DB::table('labs')->select('id')->orderBy('id')->get();
    	$sites = DB::table('facilitys')->select('id')->orderBy('id')->get();

    	$data_array=null;
    	$i=0;

		$data_array[$i] = array('year' => $year);
		$i++;

		DB::table('national_summary_yearly')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($counties as $k => $val) {
			$data_array[$i] = array('year' => $year, 'county' => $val->id);
			$i++;
		}
		DB::table('county_summary_yearly')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($subcounties as $k => $val) {
			$data_array[$i] = array('year' => $year, 'subcounty' => $val->id);
			$i++;
		}
		DB::table('subcounty_summary_yearly')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($partners as $k => $val) {
			$data_array[$i] = array('year' => $year, 'partner' => $val->id);
			$i++;
		}
		DB::table('ip_summary_yearly')->insert($data_array);

		// $data_array=null;
    	// $i=0;

		// foreach ($labs as $k => $val) {
		// 	$data_array[$i] = array('year' => $year, 'lab' => $val->id);
		// 	$i++;
		// }
		// $data_array[$i] = array('year' => $year, 'lab' => 11);
		// DB::table('lab_summary_yearly')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($sites as $k => $val) {
			$data_array[$i] = array('year' => $year, 'facility' => $val->id);
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
    	$counties = DB::table('countys')->select('id')->orderBy('id')->get();
    	$subcounties = DB::table('districts')->select('id')->orderBy('id')->get();
    	$partners = DB::table('partners')->select('id')->orderBy('id')->get();
    	$labs = DB::table('labs')->select('id')->orderBy('id')->get();
    	$sites = DB::table('facilitys')->select('id')->orderBy('id')->get();

    	// Iterate through classes of tables
    	for ($iterator=1; $iterator < 6; $iterator++) { 
    		$national = $this->get_table(0, $iterator);
    		$county = $this->get_table(1, $iterator);
    		$subcounty = $this->get_table(2, $iterator);
    		$partner = $this->get_table(3, $iterator);

    		$table_name = $national[1];
    		$column_name = $national[2];

			$reasons = $data = DB::connection('eid')
			->table($table_name)
			->select('id')
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
				$data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value->id);
				$i++;
			}
			DB::table($national[0])->insert($data_array);


			// County Insert
			$data_array=null;
	    	$i=0;

			foreach ($reasons as $key => $value) {
				foreach ($counties as $k => $val) {
					$data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value->id, 'county' => $val->id);
					$i++;
				}
			}
			DB::table($county[0])->insert($data_array);

			// Subcounty Insert
			$data_array=null;
	    	$i=0;

			foreach ($reasons as $key => $value) {
				foreach ($subcounties as $k => $val) {
					$data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value->id, 'subcounty' => $val->id);
					$i++;

					if($i == 100){
						DB::table($subcounty[0])->insert($data_array);
						$data_array=null;
				    	$i=0;
					}
				}
			}
			DB::table($subcounty[0])->insert($data_array);

			// Partner Insert
			$data_array=null;
	    	$i=0;

			foreach ($reasons as $key => $value) {
				foreach ($partners as $k => $val) {
					$data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value->id, 'partner' => $val->id);
					$i++;

					if($i == 100){
						DB::table($partner[0])->insert($data_array);
						$data_array=null;
				    	$i=0;
					}
				}
			}
			DB::table($partner[0])->insert($data_array);

			// Lab Insert
	    	if($iterator == 5){

				$data_array=null;
		    	$i=0;

				foreach ($reasons as $key => $value) {
					foreach ($labs as $k => $val) {
						$data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value->id, 'lab' => $val->id);
						$i++;
					}
					// POC Rejection Row
					$data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value->id, 'lab' => 11);
					$i++;
				}
				$lab = $this->get_table(5, $iterator);
				DB::table($lab[0])->insert($data_array);

			


				// Facility Insert
				$data_array=null;
		    	$i=0;
		    	
				$site = $this->get_table(4, $iterator);
				
				foreach ($reasons as $key => $value) {
					foreach ($sites as $k => $val) {
						$data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value->id, 'facility' => $val->id);
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
        $counties = DB::table('countys')->select('id')->orderBy('id')->get();
        $labs = DB::table('labs')->select('id')->orderBy('id')->get();

        // Lab Insert
    	$data_array=null;
    	$i=0;

        foreach ($labs as $key => $value) {
        	foreach ($counties as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'lab' => $value->id, 
					'county' => $val->id);
				$i++;
        	}
        	DB::table('lab_mapping')->insert($data_array);
	    	$data_array=null;
	    	$i=0;
        }
        echo "\n Completed eid lab mapping insert at " . date('d/m/Y h:i:s a', time());
    }

    public function insert_missing_rows($division=4, $year=null)
    {
        if(!$year) $year = Date('Y');
        $tables = $this->get_table($division, 0);
        if(!$tables) die();

        $data_array=null;
        $i=0;

        // Summary Tables
        for ($month=1; $month < 13; $month++) { 
            if($year == Date('Y') && $month > Date('m')) break;

            $mrows = DB::table($tables[1])
                ->select('id')
                ->whereRaw("id not in (SELECT {$tables[2]} FROM {$tables[0]} WHERE year={$year} AND month={$month} )")
                ->get();

            if($mrows->isEmpty()) continue;

            // Iterate the facilities and add new row array into data array for insertion
            foreach ($mrows as $key => $mrow) {

                $data_array[$i] = array('year' => $year, 'month' => $month, $tables[2] => $mrow->id);
                $i++;
                if ($i == 100) {
                    DB::table($tables[0])->insert($data_array);
                    $data_array=null;
                    $i=0;
                }               
            }
        }

        // Insert pending rows
        if($data_array) DB::table($tables[0])->insert($data_array);
        $data_array=null;
        $i=0;

        // Summary yearly tables
        $mrows = DB::table($tables[1])
            ->select('id')
            ->whereRaw("id not in (SELECT {$tables[2]} FROM {$tables[3]} WHERE year={$year} )")
            ->get();

        // Iterate the facilities and add new row array into data array for insertion
        foreach ($mrows as $key => $mrow) {

            $data_array[$i] = array('year' => $year, $tables[2] => $mrow->id);
            $i++;
            if ($i == 100) {
                DB::table($tables[3])->insert($data_array);
                $data_array=null;
                $i=0;
            }               
        }

        // Insert pending rows
        if($data_array) DB::table($tables[3])->insert($data_array);
        $data_array=null;
        $i=0;


        for ($iterator=1; $iterator < 6; $iterator++) { 

            $newtables = $this->get_table($division, $iterator);

            if(!$newtables) continue;

            // Table being inserted into
            $table_name = $newtables[0];
            $column_name = $newtables[2];

            $vars = $data = DB::table($newtables[1])
            ->select('id')
            ->when($iterator, function($query) use ($iterator){
				if($iterator == 1) return $query->where('ptype', 2);
				if($iterator == 2) return $query->where('ptype', 1);
            })
            ->get();

            // Loop through months
            for ($month=1; $month < 13; $month++) {
                if($year == Date('Y') && $month > Date('m')) break; 

                // Iterate through rejected reasons, genders, pmtct types etc.
                foreach ($vars as $row) {

                    // Get list of facilities that do not have a row for that particular combination of (year, month, {var}) where var is a rejected reason, gender, pmtct type etc.
                    $mrows = DB::table($tables[1])
                        ->select('id')
                        ->whereRaw("id not in (SELECT {$tables[2]} FROM {$table_name} WHERE year={$year} AND month={$month} AND {$column_name}={$row->id} )")
                        ->get();

                    if($mrows->isEmpty()) continue;

                    // For each facility add row array into the data array to be inserted
                    foreach ($mrows as $key => $mrow) {

                        $data_array[$i] = array('year' => $year, 'month' => $month, 'facility' => $mrow->id, $value['column'] => $row->id);
                        $i++;

                        if ($i == 100) {
                            DB::table($table_name)->insert($data_array);
                            $data_array=null;
                            $i=0;
                        }               
                    }
                    // End of facility loop
                }
                // End of looping through rejected reasons, genders, pmtct types etc.
            }
            // End of looping through months

            if($data_array) DB::table($table_name)->insert($data_array);
            $data_array=null;
            $i=0;
        }
    }

    private function get_table($division, $type){
    	$name = null;
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
                case 0:
                    $name = array("subcounty_summary", "districts", "subcounty", "subcounty_summary_yearly");
                    break;
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
                case 0:
                    $name = array("ip_summary", "partners", "partner", "ip_summary_yearly");
                    break;
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
                case 0:
                    $name = array("site_summary", "facilitys", "facility", "ip_summary_yearly");
                    break;
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

    public function inserter_missing($year=null, $month=null){
        if($year == null){
            $year = Date('Y');
        }
        if($month == null){
            $month = Date('m');
        }
        ini_set("memory_limit", "-1");

        // Get List of Divisions
        $counties = DB::table('countys')->select('id')->orderBy('id')->get();
        $subcounties = DB::table('districts')->select('id')->orderBy('id')->get();
        $partners = DB::table('partners')->select('id')->orderBy('id')->get();
        $labs = DB::table('labs')->select('id')->orderBy('id')->get();
        $sites = DB::table('facilitys')->select('id')->orderBy('id')->get();

        // Iterate through classes of tables
        for ($iterator=2; $iterator < 3; $iterator++) { 
            $national = $this->get_table(0, $iterator);
            $county = $this->get_table(1, $iterator);
            $subcounty = $this->get_table(2, $iterator);
            $partner = $this->get_table(3, $iterator);

            $table_name = $national[1];
            $column_name = $national[2];

            $reasons = [25];

            echo "\n Begin eid {$table_name} insert at " . date('d/m/Y h:i:s a', time());

            // National Insert
            $data_array=null;
            $i=0;

            foreach ($reasons as $key => $value) {
                $data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value);
                $i++;
            }
            DB::table($national[0])->insert($data_array);


            // County Insert
            $data_array=null;
            $i=0;

            foreach ($reasons as $key => $value) {
                foreach ($counties as $k => $val) {
                    $data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value, 'county' => $val->id);
                    $i++;
                }
            }
            DB::table($county[0])->insert($data_array);

            // Subcounty Insert
            $data_array=null;
            $i=0;

            foreach ($reasons as $key => $value) {
                foreach ($subcounties as $k => $val) {
                    $data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value, 'subcounty' => $val->id);
                    $i++;

                    if($i == 100){
                        DB::table($subcounty[0])->insert($data_array);
                        $data_array=null;
                        $i=0;
                    }
                }
            }
            DB::table($subcounty[0])->insert($data_array);

            // Partner Insert
            $data_array=null;
            $i=0;

            foreach ($reasons as $key => $value) {
                foreach ($partners as $k => $val) {
                    $data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value, 'partner' => $val->id);
                    $i++;

                    if($i == 100){
                        DB::table($partner[0])->insert($data_array);
                        $data_array=null;
                        $i=0;
                    }
                }
            }
            DB::table($partner[0])->insert($data_array);

            // Lab Insert
            if($iterator == 5){

                $data_array=null;
                $i=0;

                foreach ($reasons as $key => $value) {
                    foreach ($labs as $k => $val) {
                        $data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value, 'lab' => $val->id);
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
                        $data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value, 'facility' => $val->id);
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




    public function insert_subcounty($year=null, $month=null, $sub=null){
    	if($year == null){
    		$year = Date('Y');
    	}
    	if($month == null){
    		$month = Date('m');
    	}

    	$data_array[0] = array('year' => $year, 'month' => $month, 'subcounty' => $sub);
    	DB::table('subcounty_summary')->insert($data_array);

    	// Iterate through classes of tables
    	for ($iterator=1; $iterator < 6; $iterator++) { 
    		$national = $this->get_table(0, $iterator);
    		$county = $this->get_table(1, $iterator);
    		$subcounty = $this->get_table(2, $iterator);
    		$partner = $this->get_table(3, $iterator);

    		$table_name = $national[1];
    		$column_name = $national[2];

			$reasons = $data = DB::connection('eid')
			->table($table_name)
			->select('id')
			->when($iterator, function($query) use ($iterator){
				if($iterator == 1){
					return $query->where('ptype', 2);
				}	
				if($iterator == 2){
					return $query->where('ptype', 1);
				}							
			})
			->get();// Subcounty Insert

			$data_array=null;
	    	$i=0;

			foreach ($reasons as $key => $value) {
				$data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value->id, 'subcounty' => $sub);
				$i++;
			}
			DB::table($subcounty[0])->insert($data_array);
		}
    }

    public function insert_partner($year=null, $month=null, $part=null){
    	if($year == null){
    		$year = Date('Y');
    	}
    	if($month == null){
    		$month = Date('m');
    	}

    	$data_array[0] = array('year' => $year, 'month' => $month, 'partner' => $part);
    	DB::table('ip_summary')->insert($data_array);

    	// Iterate through classes of tables
    	for ($iterator=1; $iterator < 6; $iterator++) { 
    		$national = $this->get_table(0, $iterator);
    		$county = $this->get_table(1, $iterator);
    		$subcounty = $this->get_table(2, $iterator);
    		$partner = $this->get_table(3, $iterator);

    		$table_name = $national[1];
    		$column_name = $national[2];

			$reasons = $data = DB::connection('eid')
			->table($table_name)
			->select('id')
			->when($iterator, function($query) use ($iterator){
				if($iterator == 1){
					return $query->where('ptype', 2);
				}	
				if($iterator == 2){
					return $query->where('ptype', 1);
				}							
			})
			->get();// Subcounty Insert

			$data_array=null;
	    	$i=0;

			foreach ($reasons as $key => $value) {
				$data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value->id, 'partner' => $part);
				$i++;
			}
			DB::table($partner[0])->insert($data_array);
		}
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

    	$reasons =  DB::connection('eid')->table('rejectedreasons')->select('id')->orderBy('id')->get();
    	$counties =  DB::connection('eid')->table('countys')->select('id')->orderBy('id')->get();
    	$subcounties =  DB::connection('eid')->table('districts')->select('id')->orderBy('id')->get();
    	$partners =  DB::connection('eid')->table('partners')->select('id')->orderBy('id')->get();
    	$labs = DB::connection('eid')->table('labs')->select('id')->orderBy('id')->get();
    	$sites = DB::connection('eid')->table('facilitys')->select('id')->orderBy('id')->get();

    	$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->id);
			$i++;
		}
		DB::table('national_rejections')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($counties as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->id, 'county' => $val->id);
				$i++;
			}
		}
		DB::table('county_rejections')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($subcounties as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->id, 'subcounty' => $val->id);
				$i++;
			}
		}
		DB::table('subcounty_rejections')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($partners as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->id, 'partner' => $val->id);
				$i++;
			}
		}
		DB::table('ip_rejections')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($labs as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->id, 'lab' => $val->id);
				$i++;
			}
		}
		DB::table('lab_rejections')->insert($data_array);

		// echo "\n Completed eid else rejection insert at " . date('d/m/Y h:i:s a', time());

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($sites as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->id, 'facility' => $val->id);
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

    	$reasons =  DB::connection('eid')->table('age_bands')->select('id')->orderBy('id')->get();
    	$counties =  DB::connection('eid')->table('countys')->select('id')->orderBy('id')->get();
    	$subcounties =  DB::connection('eid')->table('districts')->select('id')->orderBy('id')->get();
    	$partners =  DB::connection('eid')->table('partners')->select('id')->orderBy('id')->get();
    	// $labs = DB::connection('eid')->table('labs')->select('id')->orderBy('id')->get();
    	// $sites = DB::connection('eid')->table('facilitys')->select('id')->orderBy('id')->get();

    	$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			$data_array[$i] = array('year' => $year, 'month' => $month, 'age_band_id' => $value->id);
			$i++;
		}
		DB::table('national_age_breakdown')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($counties as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'age_band_id' => $value->id, 'county' => $val->id);
				$i++;
			}
		}
		DB::table('county_age_breakdown')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($subcounties as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'age_band_id' => $value->id, 'subcounty' => $val->id);
				$i++;
			}
		}
		DB::table('subcounty_age_breakdown')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($partners as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'age_band_id' => $value->id, 'partner' => $val->id);
				$i++;
			}
		}
		DB::table('ip_age_breakdown')->insert($data_array);

		
		// $data_array=null;
    	// $i=0;

		// foreach ($reasons as $key => $value) {
		// 	foreach ($sites as $k => $val) {
		// 		$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->id, 'facility' => $val->id);
		// 		$i++;
		// 	}
		// 	DB::table('site_rejections')->insert($data_array);
			// $data_array=null;
	    	// $i=0;
		// }
		

		echo "\n Completed eid age insert at " . date('d/m/Y h:i:s a', time());
    }



}
