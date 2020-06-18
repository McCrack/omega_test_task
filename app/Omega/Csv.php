<?php


namespace App\Omega;


use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class Csv
{
    /**
     * @var Collection
     */
    private $data;

    /**
     * Csv constructor.
     * @param Collection $data
     */
    public function __construct(Collection $data)
    {
        $this->data = $data;


    }

    /**
     * @param $filename
     */
    public function store($filename)
    {
        Excel::store(
            new Tariffs($this->data),
            "csv/{$filename}.csv",
            'public'
        );
    }
}
