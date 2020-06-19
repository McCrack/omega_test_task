<?php


namespace App\Task\Mining;

use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx as Writer;

use Illuminate\Support\Collection;

class Xlsx
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
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception|Exception
     */
    public function store(string $path)
    {
        $this->buildSheet();
        $this->writer->save($path);
    }

    /**
     * @throws Exception
     */
    protected function buildSheet(): void
    {
        $sheet = $this->spreadsheet->getActiveSheet();

        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(16);

        $row = 1;
        $row = $this->makeTitle($sheet, $row);
        $row = $this->TotalCustomers($sheet, $row);
        $row = $this->inactiveCustomers($sheet, $row);
        $row++;
        $row = $this->makeTariffs($sheet, $row);
        $row = $this->makeCustomers($sheet, $row);
    }

    /**
     * @param Worksheet $sheet
     * @param int $row
     * @return int
     * @throws Exception
     */
    private function makeTitle(Worksheet $sheet, int $row): int
    {
        $sheet->getRowDimension($row)->setRowHeight(25);
        $sheet->setCellValue("A{$row}", $this->data['company']);
        $sheet->mergeCells("A{$row}:B{$row}");
        $sheet->getStyle("A{$row}")->applyFromArray([
            'font' => [
                'bold' => true
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->setCellValue("C{$row}", $this->data['date']);
        $sheet->getStyle("C{$row}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getStyle("A{$row}:C{$row}")->applyFromArray([
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => [
                        'rgb' => '000000'
                    ]
                ],
            ],
        ]);

        return ++$row;
    }

    /**
     * @param Worksheet $sheet
     * @param int $row
     * @return int
     */
    private function totalCustomers(Worksheet $sheet, int $row): int
    {
        $sheet->setCellValue("A{$row}", "Total Customers:");
        $sheet->setCellValue("B{$row}", $this->data['total_customers']);
        $sheet->getStyle("B{$row}")->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        return ++$row;
    }

    /**
     * @param Worksheet $sheet
     * @param int $row
     * @return int
     */
    private function inactiveCustomers(Worksheet $sheet, int $row): int
    {
        $sheet->setCellValue("A{$row}", "Inactive Customers:");
        $sheet->setCellValue("B{$row}", $this->data['inactive_customers']);
        $sheet->getStyle("B{$row}")->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        return ++$row;
    }

    /**
     * @param Worksheet $sheet
     * @param int $row
     * @return int
     * @throws Exception
     */
    private function makeTariffs(Worksheet $sheet, int $row): int
    {
        $sheet->setCellValue("A{$row}", "Tariffs");
        $sheet->getRowDimension($row)->setRowHeight(20);
        $sheet->mergeCells("A{$row}:C{$row}");
        $sheet->getStyle("A{$row}")->applyFromArray([
            'font' => [
                'bold' => true
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);
        $row++;
        $sheet->setCellValue("A{$row}", "Tariff");
        $sheet->setCellValue("B{$row}", "Active customers");
        $sheet->getStyle("A{$row}:B{$row}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);
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
     * @throws Exception
     */
    private function makeCustomers(Worksheet $sheet, int $row): int
    {
        $sheet->setCellValue("A{$row}", "Customers");
        $sheet->getRowDimension($row)->setRowHeight(20);
        $sheet->mergeCells("A{$row}:C{$row}");
        $sheet->getStyle("A{$row}")->applyFromArray([
            'font' => [
                'bold' => true
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $row++;
        $sheet->setCellValue("A{$row}", "Tariff");
        $sheet->setCellValue("B{$row}", "Name");
        $sheet->setCellValue("C{$row}", "Phone");
        $sheet->getStyle("A{$row}:C{$row}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $row++;
        foreach ($this->data['customers'] as $customer) {
            $sheet->setCellValue("A{$row}", $customer->tariff);
            $sheet->setCellValue("B{$row}", $customer->name);
            $sheet->setCellValue("C{$row}", $customer->phone);
            $row++;
        }

        return ++$row;
    }
}
