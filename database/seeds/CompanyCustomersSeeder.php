<?php

use Illuminate\Database\Seeder;

class CompanyCustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $records = [];

        for ($i = 0; $i < 100; $i++) {
        	$record[] = [
        		'company_id' => rand(1, 20),
        		'customer_id' => rand(1, 100),
        	];
        }

        \DB::table('company_customers')->insert($records);
    }
}
