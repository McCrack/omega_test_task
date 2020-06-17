<?php

namespace App\Omega;

use App\Company;
use App\Tariff;
use App\Customer;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Export
{
    private Company $company;

    /**
     * @param string $companyName
     * @return $this
     */
    public function company(string $companyName)
    {
        $this->company = Company::whereName($companyName)->first();

        return $this;
    }

    /**
     * @param bool|null $isActive
     * @return int
     */
    public function customersCount(bool $isActive = null): int
    {
        $builder = DB::table('customer_tariff')
            ->join('customers', 'customers.id', '=', 'customer_id')
            ->whereIn('tariff_id',
                $this->company
                    ->tariffs()
                    ->pluck('tariffs.id')
            );
        if (isset($isActive)) {
            if ($isActive) {
                $builder->where('is_active', '>', 0);
            } else {
                $builder->where('is_active', 0);
            }
        }
        return $builder->distinct('customer_id')->count();
    }

    /**
     * @return Collection
     */
    public function tariffs(): Collection
    {
        return DB::table('customer_tariff')
            ->select(DB::raw('tariffs.name AS tariff, COUNT(DISTINCT customer_id) AS customers'))
            ->join('customers', 'customers.id', '=', 'customer_id')
            ->join('tariffs', 'tariffs.id', '=', 'tariff_id')
            ->where('is_active', '>', 0)
            ->where('company_id', $this->company->id)
            ->groupBy('tariff_id')
            ->get();
    }

    /**
     * @param bool $isActive
     * @return Collection
     */
    public function customers(bool $isActive = null): Collection
    {
        $builder = DB::table('customer_tariff')
            ->select(
                DB::raw('tariffs.name AS tariff, CONCAT(customers.first_name, " ", customers.first_name) AS name'),
                'customers.phone',
                'is_active'
            )
            ->join('customers', 'customers.id', '=', 'customer_id')
            ->join('tariffs', 'tariffs.id', '=', 'tariff_id');
        if ($isActive) {
            $builder->where('is_active', '>', 0);
        } else {
            $builder->where('is_active', 0);
        }
        return $builder->get();
    }
}


/*
SELECT
	COUNT(DISTINCT customer_id) AS cnt
FROM
	customer_tariff
WHERE
    tariff_id IN (
	    SELECT
		    tariffs.id
	    FROM `companies`
	    JOIN `tariffs` ON `tariffs`.`company_id` = `companies`.`id`
        WHERE `companies`.`name` = 'Monahan-Marquardt'
    )
*/

/*
SELECT
	COUNT(DISTINCT customer_id) AS cnt
FROM
	customer_tariff
JOIN
	customers ON customers.id = customer_tariff.customer_id
WHERE
    is_active = 0
    AND tariff_id IN (
	    SELECT
		    tariffs.id
	    FROM `companies`
	    JOIN `tariffs` ON `tariffs`.`company_id` = `companies`.`id`
        WHERE `companies`.`name` = 'Monahan-Marquardt'
    )
*/

/*
SELECT
    tariffs.name AS tariff,
    COUNT(DISTINCT customer_id) AS customers
FROM
    `customer_tariff`
JOIN
    `customers` ON customers.id = customer_tariff.customer_id
JOIN
    `tariffs` ON tariffs.id = customer_tariff.tariff_id
JOIN
    `companies` ON companies.id = tariffs.company_id
WHERE
    companies.name = 'Monahan-Marquardt'
    AND customers.is_active > 0
GROUP BY tariff_id

*/

/*
SELECT
    CONCAT(customers.first_name, ' ', customers.first_name) AS name,
    customers.phone,
    tariffs.name as tariff
FROM
    `customer_tariff`
JOIN
    `customers` ON customers.id = customer_tariff.customer_id
JOIN
    `tariffs` ON tariffs.id = customer_tariff.tariff_id
WHERE
    customers.is_active > 0
*/
