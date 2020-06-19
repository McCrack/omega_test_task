<?php


namespace App\Task\Mining;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Xml
{
    /**
     * @var Collection
     */
    private $data;

    private $xml;

    /**
     * Xml constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->xml = new \SimpleXMLElement('<root/>');
    }

    /**
     * @param $path
     */
    public function store($path): void
    {
        $this->buildXml();
        $this->xml->asXML($path);
    }

    private function buildXml()
    {
        $this->xml->addChild('company', $this->data['company']);
        $this->xml->addChild('report_date', $this->data['date']);
        $this->xml->addChild('total_customers', $this->data['total_customers']);
        $this->xml->addChild('inactive_customers', $this->data['inactive_customers']);

        $this->makeTariffs();
        $this->makeCustomers();
    }

    private function makeTariffs()
    {
        $tariffs = $this->xml->addChild('tariffs');
        foreach ($this->data['tariffs'] as $item) {
            $tariff = $tariffs->addChild('tariff');

            $tariff->addChild('name', $item->tariff);
            $tariff->addChild('active_customers', $item->active_customers);
        }
    }

    private function makeCustomers()
    {
        $customers = $this->xml->addChild('customers');
        foreach ($this->data['customers'] as $item) {
            $customer = $customers->addChild('customer');

            $customer->addChild('tariff', $item->tariff);
            $customer->addChild('name', $item->name);
            $customer->addChild('phone', $item->phone);
        }
    }
}
