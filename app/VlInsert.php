<?php

namespace App;
use DB;

class VlInsert
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

    	echo "\n Begin vl summary insert at " . date('d/m/Y h:i:s a', time());

    	$counties = DB::table('countys')->select('id')->orderBy('id')->get();
    	$subcounties = DB::table('districts')->select('id')->orderBy('id')->get();
    	$partners = DB::table('partners')->select('id')->orderBy('id')->get();
    	$labs = DB::table('labs')->select('id')->orderBy('id')->get();
    	$sites = DB::table('facilitys')->select('id')->orderBy('id')->get();

    	$data_array=null;
    	$i=0;

		$data_array[$i] = array('year' => $year, 'month' => $month);
		$i++;

		DB::table('vl_national_summary')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($counties as $k => $val) {
			$data_array[$i] = array('year' => $year, 'month' => $month, 'county' => $val->id);
			$i++;
		}
		DB::table('vl_county_summary')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($subcounties as $k => $val) {
			$data_array[$i] = array('year' => $year, 'month' => $month, 'subcounty' => $val->id);
			$i++;
		}
		DB::table('vl_subcounty_summary')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($partners as $k => $val) {
			$data_array[$i] = array('year' => $year, 'month' => $month, 'partner' => $val->id);
			$i++;
		}
		DB::table('vl_partner_summary')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($labs as $k => $val) {
			$data_array[$i] = array('year' => $year, 'month' => $month, 'lab' => $val->id);
			$i++;
		}
        // POC row 
        $data_array[$i] = array('year' => $year, 'month' => $month, 'lab' => 11);
		DB::table('vl_lab_summary')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($sites as $k => $val) {
			$data_array[$i] = array('year' => $year, 'month' => $month, 'facility' => $val->id);
			$i++;
			if ($i == 150) {
				DB::table('vl_site_summary')->insert($data_array);
				$data_array=null;
		    	$i=0;
			}
		}
		DB::table('vl_site_summary')->insert($data_array);

		echo "\n Completed vl summary insert at " . date('d/m/Y h:i:s a', time());

		$this->inserter($year, $month);
		$this->insert_lab_mapping($year, $month);
        $this->inserter_age_gender($year, $month);
    }

    public function inserter($year=null, $month=null)
    {
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
    	for ($iterator=1; $iterator < 8; $iterator++) { 
    		$national = $this->get_table(0, $iterator);
    		$county = $this->get_table(1, $iterator);
    		$subcounty = $this->get_table(2, $iterator);
    		$partner = $this->get_table(3, $iterator);
    		$site = $this->get_table(4, $iterator);

    		$table_name = $national[1];
    		$column_name = $national[2];

			$reasons = $data = DB::connection('eid_vl')
			->table($table_name)->select('id')
			->when($iterator, function($query) use ($iterator){
                if($iterator == 1 || $iterator == 6) return $query->where('subid', 1);
                if($iterator == 5) return $query->where('flag', 1);
			})
			->get();

			echo "\n Begin vl {$table_name} insert at " . date('d/m/Y h:i:s a', time());

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

                    if($i == 150){
                        DB::table($county[0])->insert($data_array);
                        $data_array=null;
                        $i=0;
                    }
				}
			}
			if($data_array) DB::table($county[0])->insert($data_array);

			// Subcounty Insert
			$data_array=null;
	    	$i=0;

			foreach ($reasons as $key => $value) {
				foreach ($subcounties as $k => $val) {
					$data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value->id, 'subcounty' => $val->id);
					$i++;

					if($i == 150){
						DB::table($subcounty[0])->insert($data_array);
						$data_array=null;
				    	$i=0;
					}
				}
			}
			if($data_array) DB::table($subcounty[0])->insert($data_array);

			// Partner Insert
			$data_array=null;
	    	$i=0;

			foreach ($reasons as $key => $value) {
				foreach ($partners as $k => $val) {
                    $data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value->id, 'partner' => $val->id);
					$i++;

                    if($i == 150){
                        DB::table($partner[0])->insert($data_array);
                        $data_array=null;
                        $i=0;
                    }
				}
			}
			if($data_array) DB::table($partner[0])->insert($data_array);

			// Lab Insert
	    	if($iterator == 7){

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
				if($data_array) DB::table($lab[0])->insert($data_array);

			}


			// Facility Insert
			$data_array=null;
	    	$i=0;
			
			foreach ($reasons as $key => $value) {
				foreach ($sites as $k => $val) {
					$data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value->id, 'facility' => $val->id);
					$i++;

					if($i == 150){
						DB::table($site[0])->insert($data_array);
						$data_array=null;
				    	$i=0;
					}
				}
			}

			if($data_array) DB::table($site[0])->insert($data_array);
			$data_array=null;
	    	$i=0;
			

			echo "\n Completed vl {$table_name} insert at " . date('d/m/Y h:i:s a', time());

    	}
    }

    public function inserter_age_gender($year=null, $month=null)
    {
        if($year == null){
            $year = Date('Y');
        }
        if($month == null){
            $month = Date('m');
        }
        ini_set("memory_limit", "-1");

        $age_gender_tables = ['vl_national_age_gender', 'vl_county_age_gender', 'vl_subcounty_age_gender', 'vl_partner_age_gender', 'vl_site_age_gender'];

        // Get List of Divisions
        $counties = DB::table('countys')->select('id')->orderBy('id')->get();
        $subcounties = DB::table('districts')->select('id')->orderBy('id')->get();
        $partners = DB::table('partners')->select('id')->orderBy('id')->get();
        // $labs = DB::table('labs')->select('id')->orderBy('id')->get();
        $sites = DB::table('facilitys')->select('id')->orderBy('id')->get();

        $ages = DB::connection('eid_vl')->table('agecategory')->where('subid', 1)->get();
        $genders = DB::connection('eid_vl')->table('gender')->get();

        echo "\n Begin vl age gender insert at " . date('d/m/Y h:i:s a', time());

        // National Insert
        $data_array=null;
        $i=0;

        foreach ($ages as $age) {
            foreach ($genders as $gender) {
                $data_array[$i] = ['year' => $year, 'month' => $month, 'age' => $age->id, 'gender' => $gender->id];
                $i++;
            }
        }
        DB::table('vl_national_age_gender')->insert($data_array);


        // County Insert
        $data_array=null;
        $i=0;

        foreach ($ages as $age) {
            foreach ($genders as $gender) {
                foreach ($counties as $k => $val) {
                    $data_array[$i] = ['year' => $year, 'month' => $month, 'age' => $age->id, 'gender' => $gender->id, 'county' => $val->id];
                    $i++;

                    if($i == 150){
                        DB::table('vl_county_age_gender')->insert($data_array);
                        $data_array=null;
                        $i=0;
                    }
                }
            }
        }
        if($data_array) DB::table('vl_county_age_gender')->insert($data_array);

        // Subcounty Insert
        $data_array=null;
        $i=0;

        foreach ($ages as $age) {
            foreach ($genders as $gender) {
                foreach ($subcounties as $k => $val) {
                    $data_array[$i] = ['year' => $year, 'month' => $month, 'age' => $age->id, 'gender' => $gender->id, 'subcounty' => $val->id];
                    $i++;

                    if($i == 150){
                        DB::table('vl_subcounty_age_gender')->insert($data_array);
                        $data_array=null;
                        $i=0;
                    }
                }
            }
        }
        if($data_array) DB::table('vl_subcounty_age_gender')->insert($data_array);

        // Partner Insert
        $data_array=null;
        $i=0;

        foreach ($ages as $age) {
            foreach ($genders as $gender) {
                foreach ($partners as $k => $val) {
                    $data_array[$i] = ['year' => $year, 'month' => $month, 'age' => $age->id, 'gender' => $gender->id, 'partner' => $val->id];
                    $i++;

                    if($i == 150){
                        DB::table('vl_partner_age_gender')->insert($data_array);
                        $data_array=null;
                        $i=0;
                    }
                }
            }
        }
        if($data_array) DB::table('vl_partner_age_gender')->insert($data_array);


        // Facility Insert
        $data_array=null;
        $i=0;
        
        foreach ($ages as $age) {
            foreach ($genders as $gender) {
                foreach ($sites as $k => $val) {
                    $data_array[$i] = ['year' => $year, 'month' => $month, 'age' => $age->id, 'gender' => $gender->id, 'facility' => $val->id];
                    $i++;

                    if($i == 150){
                        DB::table('vl_site_age_gender')->insert($data_array);
                        $data_array=null;
                        $i=0;
                    }
                }
            }
        }

        if($data_array) DB::table('vl_site_age_gender')->insert($data_array);        

        echo "\n Completed vl age gender insert at " . date('d/m/Y h:i:s a', time());
    }

    public function insert_ag_rows($year=null)
    {
        if(!$year) $year = date('Y');
        for ($month=1; $month < 13; $month++) { 
            if($year == date('Y') && $month == date('m')) break;
            $this->inserter_age_gender($year, $month);
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

        echo "\n Begin vl summary lab mapping at " . date('d/m/Y h:i:s a', time());

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
        	DB::table('vl_lab_mapping')->insert($data_array);
	    	$data_array=null;
	    	$i=0;
        }
        echo "\n Completed vl summary lab mapping at " . date('d/m/Y h:i:s a', time());
    }


    public function insert_missing_rows($division=4, $year=null)
    {
        if(!$year) $year = Date('Y');
        $tables = $this->get_table($division, 0);
        if(!$tables) die();

        $data_array=null;
        $i=0;

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
                if ($i == 150) {
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

        for ($iterator=1; $iterator < 8; $iterator++) { 

            $newtables = $this->get_table($division, $iterator);

            // Table being inserted into
            $table_name = $newtables[0];
            $column_name = $newtables[2];

            $vars = $data = DB::table($newtables[1])
            ->select('id')
            ->when($iterator, function($query) use ($iterator){
                if($iterator == 1 || $iterator == 6){
                    return $query->where('subid', 1);
                }               
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

                        $data_array[$i] = array('year' => $year, 'month' => $month, $tables[2] => $mrow->id, $column_name => $row->id);
                        $i++;

                        if ($i == 150) {
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


    // private function summary_tables

    private function get_table($division, $type){
    	$name;
    	if ($division == 0) {
    		switch ($type) {
    			case 1:
    				$name = array("vl_national_age", "agecategory", "age");
    				break;
    			case 2:
    				$name = array("vl_national_gender", "gender", "gender");
    				break;
    			case 3:
    				$name = array("vl_national_prophylaxis", "viralregimen", "regimen");
    				break;
    			case 4:
    				$name = array("vl_national_sampletype", "viralsampletypedetails", "sampletype");
    				break;
    			case 5:
    				$name = array("vl_national_justification", "viraljustifications", "justification");
    				break;
    			case 6:
    				$name = array("vl_national_pmtct", "viralpmtcttype", "pmtcttype");
    				break;
    			case 7:
    				$name = array("vl_national_rejections", "viralrejectedreasons", "rejected_reason");
    				break;
    			default:
    				break;
    		}
    	}
    	else if ($division == 1) {
    		switch ($type) {
    			case 1:
    				$name = array("vl_county_age", "agecategory", "age");
    				break;
    			case 2:
    				$name = array("vl_county_gender", "gender", "gender");
    				break;
    			case 3:
    				$name = array("vl_county_prophylaxis", "viralregimen", "regimen");
    				break;
    			case 4:
    				$name = array("vl_county_sampletype", "viralsampletypedetails", "sampletype");
    				break;
    			case 5:
    				$name = array("vl_county_justification", "viraljustifications", "justification");
    				break;
    			case 6:
    				$name = array("vl_county_pmtct", "viralpmtcttype", "pmtcttype");
    				break;
    			case 7:
    				$name = array("vl_county_rejections", "viralrejectedreasons", "rejected_reason");
    				break;
    			default:
    				break;
    		}
    	}

    	else if ($division == 2) {
    		switch ($type) {
                case 0:
                    $name = array("vl_subcounty_summary", "districts", "subcounty");
                    break;
    			case 1:
    				$name = array("vl_subcounty_age", "agecategory", "age");
    				break;
    			case 2:
    				$name = array("vl_subcounty_gender", "gender", "gender");
    				break;
    			case 3:
    				$name = array("vl_subcounty_prophylaxis", "viralregimen", "regimen");
    				break;
    			case 4:
    				$name = array("vl_subcounty_sampletype", "viralsampletypedetails", "sampletype");
    				break;
    			case 5:
    				$name = array("vl_subcounty_justification", "viraljustifications", "justification");
    				break;
    			case 6:
    				$name = array("vl_subcounty_pmtct", "viralpmtcttype", "pmtcttype");
    				break;
    			case 7:
    				$name = array("vl_subcounty_rejections", "viralrejectedreasons", "rejected_reason");
    				break;
    			default:
    				break;
    		}
    	}

    	else if ($division == 3) {
    		switch ($type) {
                case 0:
                    $name = array("vl_partner_summary", "partners", "partner");
                    break;
    			case 1:
    				$name = array("vl_partner_age", "agecategory", "age");
    				break;
    			case 2:
    				$name = array("vl_partner_gender", "gender", "gender");
    				break;
    			case 3:
    				$name = array("vl_partner_prophylaxis", "viralregimen", "regimen");
    				break;
    			case 4:
    				$name = array("vl_partner_sampletype", "viralsampletypedetails", "sampletype");
    				break;
    			case 5:
    				$name = array("vl_partner_justification", "viraljustifications", "justification");
    				break;
    			case 6:
    				$name = array("vl_partner_pmtct", "viralpmtcttype", "pmtcttype");
    				break;
    			case 7:
    				$name = array("vl_partner_rejections", "viralrejectedreasons", "rejected_reason");
    				break;
    			default:
    				break;
    		}
    	}

    	else if ($division == 4) {
    		switch ($type) {
                case 0:
                    $name = array("vl_site_summary", "facilitys", "facility");
                    break;
    			case 1:
    				$name = array("vl_site_age", "agecategory", "age");
    				break;
    			case 2:
    				$name = array("vl_site_gender", "gender", "gender");
    				break;
    			case 3:
    				$name = array("vl_site_prophylaxis", "viralregimen", "regimen");
    				break;
    			case 4:
    				$name = array("vl_site_sampletype", "viralsampletypedetails", "sampletype");
    				break;
    			case 5:
    				$name = array("vl_site_justification", "viraljustifications", "justification");
    				break;
    			case 6:
    				$name = array("vl_site_pmtct", "viralpmtcttype", "pmtcttype");
    				break;
    			case 7:
    				$name = array("vl_site_rejections", "viralrejectedreasons", "rejected_reason");
    				break;
    			default:
    				break;
    		}
    	}

    	else if ($division == 5){
    		switch ($type) {
    			case 7:
    				$name = array("vl_lab_rejections", "viralrejectedreasons", "rejected_reason");
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
        $counties = DB::connection('eid_vl')->table('countys')->select('id')->orderBy('id')->get();
        $subcounties = DB::connection('eid_vl')->table('districts')->select('id')->orderBy('id')->get();
        $partners = DB::connection('eid_vl')->table('partners')->select('id')->orderBy('id')->get();
        $labs = DB::connection('eid_vl')->table('labs')->select('id')->orderBy('id')->get();
        $sites = DB::connection('eid_vl')->table('facilitys')->select('id')->orderBy('id')->get();

        // Iterate through classes of tables
        for ($iterator=5; $iterator < 6; $iterator++) { 
            $national = $this->get_table(0, $iterator);
            $county = $this->get_table(1, $iterator);
            $subcounty = $this->get_table(2, $iterator);
            $partner = $this->get_table(3, $iterator);
            $site = $this->get_table(4, $iterator);

            $table_name = $national[1];
            $column_name = $national[2];

            $reasons = $data = DB::connection('eid_vl')
            ->table($table_name)->select('id')
            ->where('id', '>', 6)
            ->get();

            echo "\n Begin vl {$table_name} insert at " . date('d/m/Y h:i:s a', time());

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
                }
            }
            DB::table($partner[0])->insert($data_array);

            // Lab Insert
            if($iterator == 7){

                $data_array=null;
                $i=0;

                foreach ($reasons as $key => $value) {
                    foreach ($labs as $k => $val) {
                        $data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value->id, 'lab' => $val->id);
                        $i++;
                    }
                }
                $lab = $this->get_table(5, $iterator);
                DB::table($lab[0])->insert($data_array);

            }


            // Facility Insert
            $data_array=null;
            $i=0;
            
            foreach ($reasons as $key => $value) {
                foreach ($sites as $k => $val) {
                    $data_array[$i] = array('year' => $year, 'month' => $month, $column_name => $value->id, 'facility' => $val->id);
                    $i++;

                    if($i == 150){
                        DB::table($site[0])->insert($data_array);
                        $data_array=null;
                        $i=0;
                    }
                }
            }

            DB::table($site[0])->insert($data_array);
            $data_array=null;
            $i=0;
            

            echo "\n Completed vl {$table_name} insert at " . date('d/m/Y h:i:s a', time());

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
        DB::table('vl_subcounty_summary')->insert($data_array);

        // Iterate through classes of tables
        for ($iterator=1; $iterator < 8; $iterator++) { 
            $national = $this->get_table(0, $iterator);
            $county = $this->get_table(1, $iterator);
            $subcounty = $this->get_table(2, $iterator);
            $partner = $this->get_table(3, $iterator);
            $site = $this->get_table(4, $iterator);

            $table_name = $national[1];
            $column_name = $national[2];

            $reasons = $data = DB::connection('eid_vl')
            ->table($table_name)->select('id')
            ->when($iterator, function($query) use ($iterator){
                if($iterator == 1 || $iterator == 6){
                    return $query->where('subid', 1);
                }               
            })
            ->get();

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
        DB::table('vl_partner_summary')->insert($data_array);

        // Iterate through classes of tables
        for ($iterator=1; $iterator < 8; $iterator++) { 
            $national = $this->get_table(0, $iterator);
            $county = $this->get_table(1, $iterator);
            $subcounty = $this->get_table(2, $iterator);
            $partner = $this->get_table(3, $iterator);
            $site = $this->get_table(4, $iterator);

            $table_name = $national[1];
            $column_name = $national[2];

            $reasons = $data = DB::connection('eid_vl')
            ->table($table_name)->select('id')
            ->when($iterator, function($query) use ($iterator){
                if($iterator == 1 || $iterator == 6){
                    return $query->where('subid', 1);
                }               
            })
            ->get();

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

    	echo "\n Begin vl rejection insert at " . date('d/m/Y h:i:s a', time());

    	$reasons = DB::connection('eid_vl')->table('viralrejectedreasons')->select('id')->orderBy('id')->get();
    	$counties = DB::connection('eid_vl')->table('countys')->select('id')->orderBy('id')->get();
    	$subcounties = DB::connection('eid_vl')->table('districts')->select('id')->orderBy('id')->get();
    	$partners = DB::connection('eid_vl')->table('partners')->select('id')->orderBy('id')->get();
    	$labs = DB::connection('eid_vl')->table('labs')->select('id')->orderBy('id')->get();
    	$sites = DB::connection('eid_vl')->table('facilitys')->select('id')->orderBy('id')->get();

    	$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->id);
			$i++;
		}
		DB::table('vl_national_rejections')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($counties as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->id, 'county' => $val->id);
				$i++;
			}
		}
		DB::table('vl_county_rejections')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($subcounties as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->id, 'subcounty' => $val->id);
				$i++;
			}
		}
		DB::table('vl_subcounty_rejections')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($partners as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->id, 'partner' => $val->id);
				$i++;
			}
		}
		DB::table('vl_partner_rejections')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($labs as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->id, 'lab' => $val->id);
				$i++;
			}
		}
		DB::table('vl_lab_rejections')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($sites as $k => $val) {
			foreach ($reasons as $key => $value) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'rejected_reason' => $value->id, 'facility' => $val->id);
				$i++;
			}
			DB::table('vl_site_rejections')->insert($data_array);
			$data_array=null;
	    	$i=0;
		}
		

		echo "\n Completed vl rejection insert at " . date('d/m/Y h:i:s a', time());
    }

    public function pmtct($year=null, $month=null){
    	if($year == null){
    		$year = Date('Y');
    	}
    	if($month == null){
    		$month = Date('m');
    	}
		ini_set("memory_limit", "-1");

    	echo "\n Begin vl pmtct insert at " . date('d/m/Y h:i:s a', time());

    	$reasons = DB::connection('eid_vl')->table('viralpmtcttype')->select('id')->where('subid', 1)->orderBy('id')->get();
    	$counties = DB::connection('eid_vl')->table('countys')->select('id')->orderBy('id')->get();
    	$subcounties = DB::connection('eid_vl')->table('districts')->select('id')->orderBy('id')->get();
    	$partners = DB::connection('eid_vl')->table('partners')->select('id')->orderBy('id')->get();
    	$labs = DB::connection('eid_vl')->table('labs')->select('id')->orderBy('id')->get();
    	$sites = DB::connection('eid_vl')->table('facilitys')->select('id')->orderBy('id')->get();

    	$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			$data_array[$i] = array('year' => $year, 'month' => $month, 'pmtcttype' => $value->id);
			$i++;
		}
		DB::table('vl_national_pmtct')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($counties as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'pmtcttype' => $value->id, 'county' => $val->id);
				$i++;
			}
		}
		DB::table('vl_county_pmtct')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($subcounties as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'pmtcttype' => $value->id, 'subcounty' => $val->id);
				$i++;
			}
		}
		DB::table('vl_subcounty_pmtct')->insert($data_array);

		$data_array=null;
    	$i=0;

		foreach ($reasons as $key => $value) {
			foreach ($partners as $k => $val) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'pmtcttype' => $value->id, 'partner' => $val->id);
				$i++;
			}
		}
		DB::table('vl_partner_pmtct')->insert($data_array);

		// $data_array=null;
    	// $i=0;

		// foreach ($reasons as $key => $value) {
		// 	foreach ($labs as $k => $val) {
		// 		$data_array[$i] = array('year' => $year, 'month' => $month, 'pmtcttype' => $value->id, 'lab' => $val->id);
		// 		$i++;
		// 	}
		// }
		// DB::table('vl_lab_pmtct')->insert($data_array);

		$data_array=null;
    	$i=0;

		
		foreach ($sites as $k => $val) {
			foreach ($reasons as $key => $value) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'pmtcttype' => $value->id, 'facility' => $val->id);
				$i++;
			}
			DB::table('vl_site_pmtct')->insert($data_array);
			$data_array=null;
	    	$i=0;
		}
		

		echo "\n Completed vl pmtct insert at " . date('d/m/Y h:i:s a', time());
    }

    
}
