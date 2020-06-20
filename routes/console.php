<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('companies', function () {
    $companies = (new App\Task\Repository)->getCompaniesList();
    $this->comment(
        implode("\n", $companies)
    );
})->describe('Show Companies list');

Artisan::command('customers:count {company_name} {--active} {--inactive}', function (string $company_name, $active, $inactive) {
    print "{$company_name}: ";
    $repository = (new App\Task\Repository)
        ->company($company_name)
        ->customers();
    if ($active) {
        $repository->active();
    } elseif($inactive) {
        $repository->active(false);
    }

    $this->comment("{$repository->count()} customers");
})->describe('The number of customers of the company');

Artisan::command('customers:get {company_name} {--active} {--inactive}', function (string $company_name, $active, $inactive) {
    $repository = (new App\Task\Repository)
        ->company($company_name)
        ->customers();
    if ($active) {
        $repository->active();
    } elseif($inactive) {
        $repository->active(false);
    }
    $this->comment(
        $repository->get()->implode("name", "\n")
    );
})->describe('Company customer list');

Artisan::command(
    'customers:tariffs {company_name} {--active} {--inactive}',
    function (string $company_name, $active, $inactive) {
        $repository = (new App\Task\Repository)
            ->company($company_name)
            ->customersWithTariffs();
        if ($active) {
            $repository->active();
        } elseif ($inactive) {
            $repository->active(false);
        }

        $this->comment(
            $repository->get()->map(function($item){
                return [
                    'value' => "{$item->tariff} | {$item->name} [{$item->phone}]"
                ];
            })->implode('value', "\n")
        );

})->describe('Company customer list');

Artisan::command('tariffs:customers {company_name}', function (string $company_name) {
    $tariffs = (new App\Task\Repository)
        ->company($company_name)
        ->tariffsWithActiveCustomers()
        ->get();

    $this->comment(
        $tariffs->map(function($item){
            return [
                'value' => "{$item->tariff} | {$item->active_customers}"
            ];
        })->implode('value', "\n")
    );
})->describe('Company customer list');

