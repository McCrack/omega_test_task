<?php


namespace App\Omega;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Export
{
    /**
     * @var string
     */
    private string $company;
    private Collection $data;

    /**
     * @param string $company
     * @return $this
     */
    public function company(string $company): Export
    {
        $this->company = $company;
        $this->makeCollection();
        return $this;
    }

    private function makeCollection()
    {
        $repository = new Repository();
        $this->data = new Collection([
            'company' => $this->company,
            'date' => date('M d, Y'),
            'total_customers' => $repository->customers($this->company)->count(),
            'not_active_customers' => $repository->customers($this->company)->active(false)->count(),
            'tariffs' => $repository->tariffs($this->company)->active()->get(),
            'customers' => $repository->getCustomers($this->company)->active()->get(),
        ]);
    }

    /**
     * @return string
     */
    public function store(): string
    {
        $fileName = $this->company . '-' . date('d-m-Y');

        if (Storage::disk('public')->missing('json')) {
            Storage::disk('public')->makeDirectory('json');
        }
        (new Json($this->data))->store($fileName);

        if (Storage::disk('public')->missing('xml')) {
            Storage::disk('public')->makeDirectory('xml');
        }
        //(new Xml($this->data))->store($fileName);
        /*
        if (Storage::disk('public')->missing('csv')) {
            Storage::disk('public')->makeDirectory('csv');
        }
        (new Csv($this->data))->store($fileName);
        */
        if (Storage::disk('public')->missing('xlsx')) {
            Storage::disk('public')->makeDirectory('xlsx');
        }
        (new Xlsx($this->data))->store($fileName);

        return "Done";
    }
}
