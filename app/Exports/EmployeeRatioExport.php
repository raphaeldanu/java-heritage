<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeeRatioExport implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings, WithStyles
{
    use Exportable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return [
            ['EMPLOYEE RATIO'],
            [ date_format(now(), 'd F Y') ],
            [
                'Department',
                'Permanent',
                '',
                'Contract',
                '',
                'Daily Worker',
                'Total',
            ],
            [
                '',
                'Male',
                'Female',
                'Male',
                'Female',
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('A2:B2');
        $sheet->mergeCells('A3:A4');
        $sheet->mergeCells('B3:C3');
        $sheet->mergeCells('D3:E3');
        $sheet->mergeCells('F3:F4');
        $sheet->mergeCells('G3:G4');
        

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle('A3:G13')->applyFromArray($styleArray);
        $sheet->getStyle('A1:D4')->getFont()->setBold(true);
    }

    public function map($row): array
    {
        return [
            $row['name'],
            $row['male_permanent'],
            $row['female_permanent'],
            $row['male_contract'],
            $row['female_contract'],
            $row['daily_worker'],
            $row['total'],
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return new Collection($this->data);
    }
}
