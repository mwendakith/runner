<?php

use Illuminate\Database\Seeder;

class AgeBandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //        
        DB::table('age_bands')->insert([
		    ['id' => '0', 'name' => 'No Data', 'lower' => 0, 'upper' => 0, 'age_range_id' => 0, 'age_range' => 'No Data', 'lower_range' => 0, 'upper_range' => 0],


		    ['id' => '1', 'name' => '< 2 weeks', 'lower' => 0.0001, 'upper' => 0.5, 'age_range_id' => 1, 'age_range' => 'less 2 months', 'lower_range' => 0.0001, 'upper_range' => 2],
		    ['id' => '2', 'name' => '2-6 weeks', 'lower' => 0.5001, 'upper' => 1.5, 'age_range_id' => 1, 'age_range' => 'less 2 months', 'lower_range' => 0.0001, 'upper_range' => 2],
		    ['id' => '3', 'name' => '6-8 weeks', 'lower' => 1.5001, 'upper' => 2, 'age_range_id' => 1, 'age_range' => 'less 2 months', 'lower_range' => 0.0001, 'upper_range' => 2],


		    ['id' => '4', 'name' => '2-6 months', 'lower' => 2.0001, 'upper' => 6, 'age_range_id' => 2, 'age_range' => '2-9 months', 'lower_range' => 2.0001, 'upper_range' => 9],
		    ['id' => '5', 'name' => '6-9 months', 'lower' => 6.0001, 'upper' => 9, 'age_range_id' => 2, 'age_range' => '2-9 months', 'lower_range' => 2.0001, 'upper_range' => 9],


		    ['id' => '6', 'name' => '9-12 months', 'lower' => 9.0001, 'upper' => 12, 'age_range_id' => 3, 'age_range' => '9-12 months', 'lower_range' => 9.0001, 'upper_range' => 12],


		    ['id' => '7', 'name' => '12-18 months', 'lower' => 12.0001, 'upper' => 18, 'age_range_id' => 4, 'age_range' => '12-24 months', 'lower_range' => 12.0001, 'upper_range' => 24],
		    ['id' => '8', 'name' => '18-24 months', 'lower' => 18.0001, 'upper' => 24, 'age_range_id' => 4, 'age_range' => '12-24 months', 'lower_range' => 12.0001, 'upper_range' => 24],


		    ['id' => '9', 'name' => '24-36 months', 'lower' => 24.0001, 'upper' => 36, 'age_range_id' => 5, 'age_range' => 'Above 24 months', 'lower_range' => 24.0001, 'upper_range' => 1200],
		    ['id' => '10', 'name' => 'Above 36 months', 'lower' => 36.0001, 'upper' => 1200, 'age_range_id' => 5, 'age_range' => 'Above 24 months', 'lower_range' => 24.0001, 'upper_range' => 1200],

		]);
    }
}
