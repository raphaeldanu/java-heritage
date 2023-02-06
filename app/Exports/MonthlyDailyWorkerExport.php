<?php

namespace App\Exports;

use App\Enums\EmploymentStatus;
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

class MonthlyDailyWorkerExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithDefaultStyles, ShouldAutoSize
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

        return Employee::query()->where('employment_status', EmploymentStatus::DailyWorker)->whereBetween('last_contract_end', [$startdate, $enddate]);
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
                'Period',
            ],
            [
                '',
                '',
                'Start Date',
                'End Date',
            ],
        ];
    }

    public function map($employee): array
    {
        return [
            $employee->name,
            $employee->department->name,
            date_format($employee->last_contract_start, 'd F Y'),
            date_format($employee->last_contract_end, 'd F Y'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('A2:B2');
        $sheet->mergeCells('A3:B3');
        $sheet->mergeCells('C5:D5');

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle('A4:D10')->applyFromArray($styleArray);
        $sheet->getStyle('A1:D5')->getFont()->setBold(true);
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
