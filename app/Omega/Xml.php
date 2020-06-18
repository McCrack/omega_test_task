<?php


namespace App\Omega;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Xml
{
    /**
     * @var Collection
     */
    private Collection $data;

    /**
     * Xml constructor.
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
        $xml = new \SimpleXMLElement('<root/>');
        foreach ($this->data as $item) {
            $tariff = $xml->addChild('tariff');
            $tariff->addChild('name', $item->tariff);
            $tariff->addChild('customer_count', $item->customers_count);
        }
        Storage::disk('public')->put("xml/{$filename}.xml", $xml->asXML());
    }
}
