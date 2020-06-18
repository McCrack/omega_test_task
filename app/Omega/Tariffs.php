<?php


namespace App\Omega;


use Illuminate\Support\Collection;

use Maatwebsite\Excel\Concerns\FromCollection;

class Tariffs implements FromCollection
{
    /**
     * @var Collection
     */
    private Collection $data;

    /**
     * Tariffs constructor.
     * @param Collection $data
     */
    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return $this->data;
    }
}
