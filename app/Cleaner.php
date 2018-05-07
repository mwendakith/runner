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
			->where('TABLE_NAME', 'NOT LIKE', 'a%')
			->whereNotIn('COLUMN_NAME', ['county', 'subcounty', 'partner', 'facility', 'lab', 'prophylaxis', 'entrypoint', 'age_band_id', 'rejected_reason', 'age', 'gender', 'regimen', 'sampletype', 'justification', 'pmtcttype'])
			->get();

		foreach ($indices as $key => $ind) {
			$sql = "DROP INDEX " . $ind->index_name . " ON " . $ind->table_name . ";";
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
			'rejections' => ['national_rejections', 'county_rejections', 'subcounty_rejections', 'ip_rejections', 'site_rejections' 'lab_rejections',],
			'i_regimens' => ['national_iprophylaxis', 'county_iprophylaxis', 'subcounty_iprophylaxis', 'ip_iprophylaxis'],
			'm_regimens' => ['national_mprophylaxis', 'county_mprophylaxis', 'subcounty_mprophylaxis', 'ip_mprophylaxis'],
			'entrypoints' => ['national_entrypoint', 'county_entrypoint', 'subcounty_entrypoint', 'ip_entrypoint'],
			'ages' => ['national_age_breakdown', 'county_age_breakdown', 'subcounty_age_breakdown', 'ip_age_breakdown'],
		];

		foreach ($summaries as $key => $value) {
			$sql = "CREATE INDEX {$value} ON (";
			if($levels[$key]) $sql .= $levels[$key] . ", ";
			$sql .= "year, month);"
			DB::statement($sql);

			// execute
			$sql = "CREATE INDEX {$value}_year ON (year, month);";
			DB::statement($sql);
		}

		foreach ($yearlies as $key => $value) {
			$sql = "CREATE INDEX {$value} ON (";
			if($levels[$key]) $sql .= $levels[$key] . ", ";
			$sql .= "year);";
			DB::statement($sql);

			// execute
			$sql = "CREATE INDEX index_year ON (year);";
			DB::statement($sql);
		}

		foreach ($similar as $match => $tables) {
			foreach ($tables as $key => $value) {
				$column = $matches[$key];

				$sql = "CREATE INDEX {$value}_year ON (";
				if($levels[$key]) $sql .= $levels[$key] . ", ";
				$sql .= "year, month);";
				DB::statement($sql);
				
				$sql = "CREATE INDEX {$column}_year ON ({$column}, year, month);";
				DB::statement($sql);
			}
		}		
	}

	public function create_vl_indices()
	{
		$levels = [null, 'county', 'subcounty', 'partner', 'facility', 'lab'];

		$summaries = ['vl_national_summary', 'vl_county_summary', 'vl_subcounty_summary', 'vl_partner_summary', 'vl_site_summary', 'vl_lab_summary'];

		$similar = [
			'rejected_reason' => ['vl_national_rejections', 'vl_county_rejections', 'vl_subcounty_rejections', 'vl_partner_rejections', 'vl_site_rejections' 'vl_lab_rejections'],
			'age' => ['vl_national_age', 'vl_county_age', 'vl_subcounty_age', 'vl_partner_age', 'vl_site_age'],
			'gender' => ['vl_national_gender', 'vl_county_gender', 'vl_subcounty_gender', 'vl_partner_gender', 'vl_site_gender'],
			'viralprophylaxis' => ['vl_national_regimen', 'vl_county_regimen', 'vl_subcounty_regimen', 'vl_partner_regimen', 'vl_site_regimen'],
			'sampletype' => ['vl_national_sampletype', 'vl_county_sampletype', 'vl_subcounty_sampletype', 'vl_partner_sampletype', 'vl_site_sampletype'],
			'justification' => ['vl_national_justification', 'vl_county_justification', 'vl_subcounty_justification', 'vl_partner_justification', 'vl_site_justification'],
			'pmtcttype' => ['vl_national_pmtct', 'vl_county_pmtct', 'vl_subcounty_pmtct', 'vl_partner_pmtct', 'vl_site_pmtct'],
			// '' => ['vl_national_', 'vl_county_', 'vl_subcounty_', 'vl_partner_', 'vl_site_'],

		];

		foreach ($summaries as $key => $value) {
			$sql = "CREATE INDEX {$value} ON (";
			if($levels[$key]) $sql .= $levels[$key] . ", ";
			$sql .= "year, month);";
			DB::statement($sql);

			// execute
			$sql = "CREATE INDEX {$value}_year ON (year, month);";
			DB::statement($sql);
		}

		foreach ($similar as $column => $tables) {
			foreach ($tables as $key => $value) {

				$sql = "CREATE INDEX {$value}_year ON (";
				if($levels[$key]) $sql .= $levels[$key] . ", ";
				$sql .= "year, month);";
				DB::statement($sql);
				
				$sql = "CREATE INDEX {$column}_year ON ({$column}, year, month);";
				DB::statement($sql);
			}
		}		
	}


}
