<?php

use Illuminate\Database\Seeder;

class CompanyTariffsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $records = [];

        for ($i = 0; $i < 60; $i++) {
        	$record[] = [
        		'company_id' => rand(1, 20),
        		'tariff_id' => rand(1, 60),
        	];
        }

        \DB::table('company_tariffs')->insert($records);
    }
}
