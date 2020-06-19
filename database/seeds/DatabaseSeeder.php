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
        factory(App\Company::class, 5)->create();
        factory(App\Tariff::class, 25)->create();
        factory(App\Customer::class, 1000)->create();

        $customers = Customer::all();

        foreach ($customers as $customer) {
            $tariffId = rand(1, 25);
            $tariff = Tariff::find($tariffId);
            if ($tariff) {
                $customer->tariffs()->attach($tariff);
            }
        }
    }
}

