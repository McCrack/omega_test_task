<?php


namespace App\Task\Mining;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Json
{
    /**
     * @var array
     */
    private array $data;

    /**
     * Json constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param $path
     */
    public function store($path): void
    {
        $json = json_encode($this->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($path, $json);
    }
}
