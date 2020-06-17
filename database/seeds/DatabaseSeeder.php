<?php

use Illuminate\Database\Seeder;

use App\Company;
use App\Customer;
use App\Tariff;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(CompanyTariffsSeeder::class);
        //$this->call(CompanyCustomersSeeder::class);

        
        $customers = Customer::all();

        foreach ($customers as $customer) {
            $tariffId = rand(1, 54);
            $tariff = Tariff::find($tariffId);
            if ($tariff) {
                $customer->tariffs()->attach($tariff);

                $company = Company::find($tariff->company_id);
                $customer->companies()->attach($company);
            }
        }
    }
}
