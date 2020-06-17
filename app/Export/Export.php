<?php


namespace App\Export;


use App\Company;
use App\Tariffs;
use App\Customers;

class Export
{

	private $company;

	public function __contruct()
	{

	}
    /**
     *
     */
	public function company(string $companyName)
	{
		$this->company = Company::whereName($companyName)->first();

		return $this;
	}

	public function export()
	{

		//Customer::tariffs()
		foreach ($this->company->tariffs as $tariff) {
			echo $tariff->name . "\n";
		}
	}
}

/*
SELECT
    tariffs.name, count(customers.id) as customer_count
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
GROUP BY
    tariffs.name,
    customers.id
*/


/*
SELECT
    CONCAT(customers.first_name, ' ', customers.last_name) AS name,
    customers.phone,
    tariffs.name as tariff
FROM
    `customer_tariff`
JOIN
    `tariffs` ON tariffs.id = customer_tariff.tariff_id
JOIN
    `customers` ON customers.id = customer_tariff.customer_id
WHERE
    is_active > 0
*/
