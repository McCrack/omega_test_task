<?php


namespace App\Omega;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Json
{
    /**
     * @var Collection
     */
    private Collection $data;

    /**
     * Json constructor.
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
        $json = json_encode($this->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        Storage::disk('public')->put("json/{$filename}.json", $json);
    }
}
