<?php


namespace App\Task;

use App\Company;

use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Repository
{
    private Builder $builder;
    private Company $company;

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
    public function company(string $companyName): Repository
    {
        $this->company = Company::whereName($companyName)->first();
        return $this;
    }

    /**
     * @return Repository
     * @throws Exception
     */
    public function customers(): Repository
    {
        if (isset($this->company)) {
            $this->builder = DB::table('customer_tariff')
                ->join('customers', 'customers.id', '=', 'customer_id')
                ->whereIn('tariff_id',
                    $this->company
                        ->tariffs()
                        ->pluck('tariffs.id')
                )->distinct('customer_id');
        } else {
            throw new Exception('Please indicate company');
        }
        return $this;
    }

    /**
     * @return Repository
     * @throws Exception
     */
    public function tariffsWithActiveCustomers(): Repository
    {
        if (isset($this->company)) {
            $this->builder = DB::table('customer_tariff')
                ->select(
                    'tariffs.name AS tariff',
                    DB::raw('COUNT(DISTINCT customer_id) AS active_customers')
                )
                ->join('customers', 'customers.id', '=', 'customer_id')
                ->join('tariffs', 'tariffs.id', '=', 'tariff_id')
                ->where('company_id', $this->company->id)
                ->groupBy('tariff_id');
        } else {
            throw new Exception('Please indicate company');
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function customersWithTariffs(): Repository
    {
        $this->builder = DB::table('customer_tariff')
            ->select(
                'is_active',
                DB::raw('CONCAT(customers.first_name, " ", customers.first_name) AS name'),
                'customers.phone',
                'tariffs.name AS tariff'
            )
            ->join('customers', 'customers.id', '=', 'customer_id')
            ->join('tariffs', 'tariffs.id', '=', 'tariff_id');
        if (isset($this->company)) {
            $this->builder
                ->join('companies', 'companies.id', '=', 'tariffs.company_id')
                ->where('company_id', $this->company->id);
        }
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
