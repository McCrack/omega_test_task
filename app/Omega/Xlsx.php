<?php


namespace App\Omega;


use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class Xlsx
{
    /**
     * @var Collection
     */
    private Collection $data;

    /**
     * Xlsx constructor.
     * @param Collection $data
     */
    public function __construct(Collection $data)
    {
        $this->data = collect($data['customers']);

    }

    /**
     * @param $filename
     */
    public function store($filename)
    {
        Excel::store(
            new Tariffs($this->data),
            "xlsx/{$filename}.xlsx",
            'public'
        );
    }
}
