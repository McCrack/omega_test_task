<?php


namespace App\Task;

use App\Task\Mining\Xlsx;
use App\Task\Mining\Csv;
use App\Task\Mining\Xml;
use App\Task\Mining\Json;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Writer\Exception;

class Omega
{
    /**
     * @var Repository
     */
    private Repository $repository;

    public function __construct()
    {
        $this->repository = new Repository();
    }

    /**
     * @param string|null $companyName
     * @return string
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function export(string $companyName = null): string
    {
        $companies = isset($companyName)
            ? [$companyName]
            : $this->repository->getCompaniesList();
        foreach ($companies as $company) {
            $data = $this->makeCollection($company);
            $this->store($data);
        }
        return "Done";
    }

    /**
     * @param string $companyName
     * @return array
     * @throws \Exception
     */
    private function makeCollection(string $companyName): array
    {
        $this->repository->company($companyName);

        return [
            'company' => $companyName,
            'date' => date('d.m.Y'),
            'total_customers' => $this->repository->customers()->count(),
            'inactive_customers' => $this->repository->customers()->active(false)->count(),
            'tariffs' => $this->repository->tariffsWithActiveCustomers()->get(),
            'customers' => $this->repository->customersWithTariffs()->active()->get(),
        ];
    }

    /**
     * @param array $data
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function store(array $data): void
    {
        $filename = $data['company'] . '-' . $data['date'];

        /* Excel */
        if (Storage::disk('public')->missing('xlsx')) {
            Storage::disk('public')->makeDirectory('xlsx');
        }
        (new Xlsx($data))->store(
            storage_path("app/public/xlsx/{$filename}.xlsx")
        );

        /* CSV */
        if (Storage::disk('public')->missing('csv')) {
            Storage::disk('public')->makeDirectory('csv');
        }
        (new Csv($data))->store(
            storage_path("app/public/csv/{$filename}.csv")
        );

        /* JSON */
        if (Storage::disk('public')->missing('json')) {
            Storage::disk('public')->makeDirectory('json');
        }
        (new Json($data))->store(
            storage_path("app/public/json/{$filename}.json")
        );

        /* XML */
        if (Storage::disk('public')->missing('xml')) {
            Storage::disk('public')->makeDirectory('xml');
        }
        (new Xml($data))->store(
            storage_path("app/public/xml/{$filename}.xml")
        );
    }
}
