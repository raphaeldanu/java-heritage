<?php

namespace App\Exports;

use App\Models\Employee;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlyEmployeeResignExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithDefaultStyles, ShouldAutoSize
{
    use Exportable;

    protected $month_and_year;

    public function __construct(Carbon $month_and_year)
    {
        $this->month_and_year = $month_and_year;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        $startdate = Carbon::parse($this->month_and_year);
        $startdate->day(1);

        $enddate = Carbon::parse($this->month_and_year);
        $enddate->endOfMonth();

        return Employee::query()->whereBetween('first_join', [$startdate, $enddate]);
    }

    public function headings(): array
    {
        return [
            ['EMPLOYEE TURN OVER',],
            ['HIRE',],
            [date_format($this->month_and_year, 'F Y')],
            [
                'Name',
                'Department',
                'Resign Date',
            ]
        ];
    }

    public function map($employee): array
    {
        return [
            $employee->name,
            $employee->department->name,
            date_format($employee->resign_date, 'd F Y'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('A2:B2');
        $sheet->mergeCells('A3:B3');

        
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle('A4:C9')->applyFromArray($styleArray);
        $sheet->getStyle('A1:C4')->getFont()->setBold(true);
    }

    public function defaultStyles(Style $defaultStyle)
    {
        return [
            'font' => [
                'name' => 'Tahoma',
                'size' => 10,
            ],
        ];
    }
}
