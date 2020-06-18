<?php


namespace App\Omega;

use App\Company;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Repository
{
    private Builder $builder;

    /**
     * @return array
     */
    public function getCompaniesList(): array
    {
        return Company::all()->pluck('name')->toArray();
    }

    /**
     * @param string $companyName
     * @return Repository
     */
    public function customers(string $companyName): Repository
    {
        $this->builder = DB::table('customer_tariff')
            ->join('customers', 'customers.id', '=', 'customer_id')
            ->whereIn('tariff_id',
                Company::whereName($companyName)->first()
                    ->tariffs()
                    ->pluck('tariffs.id')
            )->distinct('customer_id');
        return $this;
    }
    /**
     * @param string $companyName
     * @return Repository
     */
    public function tariffs(string $companyName): Repository
    {
        $this->builder = DB::table('customer_tariff')
            ->select(
                'tariffs.name AS tariff',
                DB::raw('COUNT(DISTINCT customer_id) AS customers_count')
            )
            ->join('customers', 'customers.id', '=', 'customer_id')
            ->join('tariffs', 'tariffs.id', '=', 'tariff_id')
            ->where('is_active', '>', 0)
            ->where('company_id', Company::whereName($companyName)->first()->id)
            ->groupBy('tariff_id');
        return $this;
    }

    /**
     * @return Repository
     */
    public function getCustomers(): Repository
    {
        $this->builder = DB::table('customer_tariff')
            ->select(
                DB::raw('CONCAT(customers.first_name, " ", customers.first_name) AS name'),
                'customers.phone',
                'tariffs.name AS tariff'
            )
            ->join('customers', 'customers.id', '=', 'customer_id')
            ->join('tariffs', 'tariffs.id', '=', 'tariff_id');
        return $this;
    }

    /**
     * @param bool $isActive
     * @return Repository
     */
    public function active(bool $isActive = true): Repository
    {
        if ($isActive) {
            $this->builder->where('is_active', '>', 0);
        } else {
            $this->builder->where('is_active', 0);
        }
        return $this;
    }

    /**
     * @return Collection
     */
    public function get(): Collection
    {
        return $this->builder->get();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->builder->count();
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
