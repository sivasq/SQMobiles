<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;

class StockExport implements FromArray, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $bodyContents;
    protected $headings;

    public function __construct(array $bodyContents, $headings)
    {
        $this->bodyContents = $bodyContents;
        $this->headings = $headings;
    }

    public function array(): array
    {
        return $this->bodyContents;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function map($product): array
    {
        return [
            $product->brand_name . '-' . $product->product_name,
            $product->available_stock,
        ];
    }

    public function registerEvents(): array
    {
        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:B1')->getFont()->setSize(14)->setBold(14);
                $event->sheet->getDelegate()->getStyle('A4:B4')->getFont()->setSize(12)->setBold(12);
                $event->sheet->getDelegate()->setTitle('stock on ' . date('d-M-Y'));
                $event->sheet->getDelegate()->setMergeCells(['A1:B1', 'A2:B2']);
                $event->sheet->styleCells(
                    'A1:B2',
                    [
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]
                );
            },
        ];
    }

}
