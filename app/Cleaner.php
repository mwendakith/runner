<?php

namespace App;

use DB;

class Cleaner
{

	public function clean_apidb()
	{
		$indices = DB::table('INFORMATION_SCHEMA.STATISTICS')
			->select(['table_name', 'index_name'])
			->where('TABLE_SCHEMA', 'apidb')
			->where('INDEX_NAME', '!=', 'PRIMARY')
			->where('NON_UNIQUE', 1)
			->where('SEQ_IN_INDEX', 1)
			->where('TABLE_NAME', 'NOT LIKE', 'a%')
			->whereNotIn('COLUMN_NAME', ['county', 'subcounty', 'partner', 'facility', 'lab', 'prophylaxis', 'entrypoint', 'age_band_id', 'rejected_reason', 'age', 'gender', 'regimen', 'sampletype', 'justification', 'pmtcttype'])
			->get();

		foreach ($indices as $key => $ind) {
			// $sql = "DROP INDEX `" . $ind->index_name . "` ON " . $ind->table_name . ";";
			$sql = "ALTER TABLE " . $ind->table_name . " DROP INDEX `" . $ind->index_name . "`;";
			DB::statement($sql);
		}
	}

	public function create_eid_indices()
	{
		$levels = [null, 'county', 'subcounty', 'partner', 'facility', 'lab'];
		$matches = [
			'rejections' => 'rejected_reason',
			'i_regimens' => 'prophylaxis',
			'm_regimens' => 'prophylaxis',
			'entrypoints' => 'entrypoint',
			'ages' => 'age_band_id',

		];
		// $i_regimens = ['national', 'county', 'subcounty', 'ip'];

		$summaries = ['national_summary', 'county_summary', 'subcounty_summary', 'ip_summary', 'site_summary', 'lab_summary'];
		$yearlies = ['national_summary_yearly', 'county_summary_yearly', 'subcounty_summary_yearly', 'ip_summary_yearly', 'site_summary_yearly'];

		// $rejections = ['national_rejections', 'county_rejections', 'subcounty_rejections', 'ip_rejections', 'site_rejections', 'lab_rejections'];
		// $i_regimens = ['national_iprophylaxis', 'county_iprophylaxis', 'subcounty_iprophylaxis', 'ip_iprophylaxis'];
		// $m_regimens = ['national_mprophylaxis', 'county_mprophylaxis', 'subcounty_mprophylaxis', 'ip_mprophylaxis'];
		// $entrypoints = ['national_entrypoint', 'county_entrypoint', 'subcounty_entrypoint', 'ip_entrypoint'];
		// $ages = ['national_age_breakdown', 'county_age_breakdown', 'subcounty_age_breakdown', 'ip_age_breakdown'];

		$similar = [
			'rejections' => ['national_rejections', 'county_rejections', 'subcounty_rejections', 'ip_rejections', 'site_rejections', 'lab_rejections'],
			'i_regimens' => ['national_iprophylaxis', 'county_iprophylaxis', 'subcounty_iprophylaxis', 'ip_iprophylaxis'],
			'm_regimens' => ['national_mprophylaxis', 'county_mprophylaxis', 'subcounty_mprophylaxis', 'ip_mprophylaxis'],
			'entrypoints' => ['national_entrypoint', 'county_entrypoint', 'subcounty_entrypoint', 'ip_entrypoint'],
			'ages' => ['national_age_breakdown', 'county_age_breakdown', 'subcounty_age_breakdown', 'ip_age_breakdown'],
		];

		foreach ($summaries as $key => $value) {
			$sql = "CREATE INDEX eid_{$value} ON {$value}(";
			if($levels[$key]) $sql .= $levels[$key] . ", ";
			$sql .= "year, month);";
			DB::statement($sql);

			// execute
			$sql = "CREATE INDEX eid_{$value}_year ON {$value}(year, month);";
			DB::statement($sql);
		}

		foreach ($yearlies as $key => $value) {
			$sql = "CREATE INDEX eid_{$value} ON {$value}(";
			if($levels[$key]) $sql .= $levels[$key] . ", ";
			$sql .= "year);";
			DB::statement($sql);

			// execute
			$sql = "CREATE INDEX eid_index_year ON {$value}(year);";
			DB::statement($sql);
		}

		foreach ($similar as $match => $tables) {
			foreach ($tables as $key => $value) {
				$column = $matches[$match]; 

				$sql = "CREATE INDEX eid_{$value}_year ON {$value}(";
				if($levels[$key]) $sql .= $levels[$key] . ", ";
				$sql .= "year, month);";
				DB::statement($sql);
				
				$sql = "CREATE INDEX eid_{$column}_year ON {$value}({$column}, year, month);";
				DB::statement($sql);
			}
		}		
	}

	public function create_vl_indices()
	{
		$levels = [null, 'county', 'subcounty', 'partner', 'facility', 'lab'];

		$summaries = ['vl_national_summary', 'vl_county_summary', 'vl_subcounty_summary', 'vl_partner_summary', 'vl_site_summary', 'vl_lab_summary'];

		$similar = [
			'rejected_reason' => ['vl_national_rejections', 'vl_county_rejections', 'vl_subcounty_rejections', 'vl_partner_rejections', 'vl_site_rejections', 'vl_lab_rejections'],
			'age' => ['vl_national_age', 'vl_county_age', 'vl_subcounty_age', 'vl_partner_age', 'vl_site_age'],
			'gender' => ['vl_national_gender', 'vl_county_gender', 'vl_subcounty_gender', 'vl_partner_gender', 'vl_site_gender'],
			'regimen' => ['vl_national_regimen', 'vl_county_regimen', 'vl_subcounty_regimen', 'vl_partner_regimen', 'vl_site_regimen'], 
			'sampletype' => ['vl_national_sampletype', 'vl_county_sampletype', 'vl_subcounty_sampletype', 'vl_partner_sampletype', 'vl_site_sampletype'],
			'justification' => ['vl_national_justification', 'vl_county_justification', 'vl_subcounty_justification', 'vl_partner_justification', 'vl_site_justification'],
			'pmtcttype' => ['vl_national_pmtct', 'vl_county_pmtct', 'vl_subcounty_pmtct', 'vl_partner_pmtct', 'vl_site_pmtct'],
			// '' => ['vl_national_', 'vl_county_', 'vl_subcounty_', 'vl_partner_', 'vl_site_'],
		];

		foreach ($summaries as $key => $value) {
			$sql = "CREATE INDEX vl_{$value} ON {$value}(";
			if($levels[$key]) $sql .= $levels[$key] . ", ";
			$sql .= "year, month);";
			DB::statement($sql);

			// execute
			$sql = "CREATE INDEX vl_{$value}_year ON {$value}(year, month);";
			DB::statement($sql);
		}

		foreach ($similar as $column => $tables) {
			foreach ($tables as $key => $value) {

				$sql = "CREATE INDEX vl_{$value}_year ON {$value}(";
				if($levels[$key]) $sql .= $levels[$key] . ", ";
				$sql .= "year, month);";
				DB::statement($sql);
				
				$sql = "CREATE INDEX vl_{$column}_year ON {$value}({$column}, year, month);";
				DB::statement($sql);
			}
		}		
	}

	// Delete duplicate rows from all vl site tables
	public static function clean_vl_sites($year = null)
	{
		if(!$year) $year = Date('Y');

		// When looping through sites array, the key will be the table name
		$sites = [
			// 'vl_site_summary' => null,
			'vl_site_rejections' => ['table' => 'viralrejectedreasons', 'column' => 'rejected_reason'],
			'vl_site_age' => ['table' => 'agecategory', 'column' => 'age', 'subid' => 1],
			'vl_site_gender' => ['table' => 'gender', 'column' => 'gender'],
			'vl_site_regimen' => ['table' => 'viralprophylaxis', 'column' => 'regimen'],
			'vl_site_sampletype' => ['table' => 'viralsampletypedetails', 'column' => 'sampletype'],
			'vl_site_justification' => ['table' => 'viraljustifications', 'column' => 'justification'],
			'vl_site_pmtct' => ['table' => 'viralpmtcttype', 'column' => 'pmtcttype', 'subid' => 1],
		];

		// Loop through months
		// This is for vl_site_summary
		for ($month=1; $month < 13; $month++) {
			if($year == Date('Y') && $month > Date('m')) break;
			$table_name = 'vl_site_summary'; 

			// Find where duplicate rows exist
			// Unique index is (year, month, facility)
			$duplicates = DB::table($table_name)
				->selectRaw("facility, count(facility) as my_count")
				->where(['year' => $year, 'month' => $month])
				->groupBy('facility')
				->having('my_count', '>', 1)
				->get();

			// If the duplicates result set is not empty, delete rows for all facilities in the result set for that particular month and year
			// These deleted rows will be recreated in vl_site_missing_rows() without duplicates
			if($duplicates->isNotEmpty()){
				$facilities = $duplicates->pluck('facility')->toArray();
				DB::table($table_name)
					->where(['year' => $year, 'month' => $month])
					->whereIn('facility', $facilities)
					->delete();					
			}		
		}

		// Loop through sites array
		foreach ($sites as $table_name => $value) {
			// Get result set for rejected reasons, pmtct ...
			$vars = DB::table($value['table'])
				->select('id')
				->when(isset($value['subid']), function($query){
					return $query->where('subid', 1);
				})
				->get();

			// Loop through the months
			for ($month=1; $month < 13; $month++) {
				if($year == Date('Y') && $month > Date('m')) break; 

				// Loop through rejected reasons, pmtct ...
				foreach ($vars as $row) {
					$duplicates = DB::table($table_name)
						->selectRaw("facility, count(facility) as my_count")
						->where(['year' => $year, 'month' => $month, $value['column'] => $row->id])
						->groupBy('facility')
						->having('my_count', '>', 1)
						->get();

					// If the duplicates result set is not empty, delete rows for all facilities in the result set for that particular month and year
					// These deleted rows will be recreated in vl_site_missing_rows() without duplicates

					if($duplicates->isNotEmpty()){
						$facilities = $duplicates->pluck('facility')->toArray();

						DB::table($table_name)
							->where(['year' => $year, 'month' => $month, $value['column'] => $row->id])
							->whereIn('facility', $facilities)
							->delete();
					}
				}
				// End of looping through rejected reasons, pmtct ...
			}
			// End of looping through months
		}
		// End of looping through sites_array
	}

	public static function vl_missing_site_rows($year = null)
	{
		if(!$year) $year = Date('Y');

		$sites = [
			// 'vl_site_summary' => null,
			'vl_site_rejections' => ['table' => 'viralrejectedreasons', 'column' => 'rejected_reason'],
			'vl_site_age' => ['table' => 'agecategory', 'column' => 'age', 'subid' => 1],
			'vl_site_gender' => ['table' => 'gender', 'column' => 'gender'],
			'vl_site_regimen' => ['table' => 'viralprophylaxis', 'column' => 'regimen'],
			'vl_site_sampletype' => ['table' => 'viralsampletypedetails', 'column' => 'sampletype'],
			'vl_site_justification' => ['table' => 'viraljustifications', 'column' => 'justification'],
			'vl_site_pmtct' => ['table' => 'viralpmtcttype', 'column' => 'pmtcttype', 'subid' => 1],
		];

		$data_array=null;
    	$i=0;

    	// Loop through months
		for ($month=1; $month < 13; $month++) {
			if($year == Date('Y') && $month > Date('m')) break; 
			$table_name = 'vl_site_summary';

			// Get list of facilities that do not have a row in the table for the particular month and year
			$mfacilities = DB::table('facilitys')
				->select('id')
				->whereRaw("id not in (SELECT facility FROM {$table_name} WHERE year={$year} AND month={$month} )")
				->get();

			if($mfacilities->isEmpty()) continue;

			// Iterate the facilities and add new row array into data array for insertion
			foreach ($mfacilities as $key => $fac) {

				$data_array[$i] = array('year' => $year, 'month' => $month, 'facility' => $fac->id);
				$i++;
				if ($i == 100) {
					DB::table($table_name)->insert($data_array);
					$data_array=null;
			    	$i=0;
				}				
			}
		}

		// Insert pending rows
		DB::table($table_name)->insert($data_array);
		$data_array=null;
		$i=0;

		// Insert missing rows in vl_site_suppression
		$table_name = 'vl_site_suppression';
		$mfacilities = DB::table('facilitys')
			->select('id')
			->whereRaw("id not in (SELECT facility FROM {$table_name} )")
			->get();

		if($mfacilities->isNotEmpty()){
			foreach ($mfacilities as $key => $fac) {
				$data_array[$i] = array('year' => $year, 'month' => $month, 'facility' => $fac->id);
				$i++;
			}
		}

		DB::table($table_name)->insert($data_array);
		$data_array=null;
		$i=0;


		// Iterate through vl site tables
		foreach ($sites as $table_name => $value) {
			// Get rejected reasons, genders, rejection reasons etc.
			$vars = DB::table($value['table'])
				->select('id')
				->when(isset($value['subid']), function($query){
					return $query->where('subid', 1);
				})
				->get();

			// Loop through months
			for ($month=1; $month < 13; $month++) {
				if($year == Date('Y') && $month > Date('m')) break; 

				// Iterate through rejected reasons, genders, pmtct types etc.
				foreach ($vars as $row) {

					// Get list of facilities that do not have a row for that particular combination of (year, month, {var}) where var is a rejected reason, gender, pmtct type etc.
					$mfacilities = DB::table('facilitys')
						->select('id')
						->whereRaw("id not in (SELECT facility FROM {$table_name} WHERE year={$year} AND month={$month} AND {$value['column']}={$row->id} )")
						->get();

					if($mfacilities->isEmpty()) continue;

					// For each facility add row array into the data array to be inserted
					foreach ($mfacilities as $key => $fac) {

						$data_array[$i] = array('year' => $year, 'month' => $month, 'facility' => $fac->id, $value['column'] => $row->id);
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

			DB::table($table_name)->insert($data_array);
			$data_array=null;
	    	$i=0;
		}
		// End of looping through vl site tables
	}


}
