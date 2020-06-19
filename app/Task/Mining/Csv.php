<?php


namespace App\Task\Mining;

use Illuminate\Support\Collection;

use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use PhpOffice\PhpSpreadsheet\Writer\Csv as Writer;

class Csv
{
    /**
     * @var array
     */
    private array $data;
    /**
     * @var Spreadsheet
     */
    private Spreadsheet $spreadsheet;
    /**
     * @var Writer
     */
    private Writer $writer;


    /**
     * Xlsx constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->spreadsheet = new Spreadsheet();
        $this->writer = new Writer($this->spreadsheet);
    }

    /**
     * @param $path
     */
    public function store(string $path)
    {
        $this->buildSheet();
        $this->writer->save($path);
    }

    /**
     */
    protected function buildSheet(): void
    {
        $sheet = $this->spreadsheet->getActiveSheet();


        $row = 1;
        $sheet->setCellValue("A{$row}", "Company");
        $sheet->setCellValue("B{$row}", $this->data['company']);
        $row++;
        $sheet->setCellValue("A{$row}", "Report date");
        $sheet->setCellValue("B{$row}", $this->data['date']);
        $row++;
        $sheet->setCellValue("A{$row}", "Total Customers:");
        $sheet->setCellValue("B{$row}", $this->data['total_customers']);
        $row++;
        $sheet->setCellValue("A{$row}", "Inactive Customers:");
        $sheet->setCellValue("B{$row}", $this->data['inactive_customers']);
        $row++;
        $row = $this->makeTariffs($sheet, $row);
        $row = $this->makeCustomers($sheet, $row);
    }


    /**
     * @param Worksheet $sheet
     * @param int $row
     * @return int
     */
    private function makeTariffs(Worksheet $sheet, int $row): int
    {
        $row++;
        $sheet->setCellValue("A{$row}", "Tariff");
        $sheet->setCellValue("B{$row}", "Active customers");
        $row++;
        foreach ($this->data['tariffs'] as $tariff) {
            $sheet->setCellValue("A{$row}", $tariff->tariff);
            $sheet->setCellValue("B{$row}", $tariff->active_customers);
            $row++;
        }
        return ++$row;
    }

    /**
     * @param Worksheet $sheet
     * @param int $row
     * @return int
     */
    private function makeCustomers(Worksheet $sheet, int $row): int
    {
        $sheet->setCellValue("A{$row}", "Name");
        $sheet->setCellValue("B{$row}", "Phone");
        $sheet->setCellValue("C{$row}", "Tariff");
        $row++;
        foreach ($this->data['customers'] as $customer) {
            $sheet->setCellValue("A{$row}", $customer->name);
            $sheet->setCellValue("B{$row}", $customer->phone);
            $sheet->setCellValue("C{$row}", $customer->tariff);
            $row++;
        }

        return ++$row;
    }
}
