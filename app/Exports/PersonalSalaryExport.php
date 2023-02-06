<?php

namespace App\Exports;

use App\Models\Salary;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PersonalSalaryExport implements FromQuery, WithStyles, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    protected $salary_id;

    public function __construct(int $salary_id)
    {
        $this->salary_id = $salary_id;
    }

    public function query()
    {
        return Salary::query()->where('id', $this->salary_id);
    }

    public function headings(): array
    {
        $salary = Salary::find($this->salary_id);
        return [
            [
                'LAPORAN GAJI + SERVICE KARYAWAN'
            ],
            [
                'JAVA HERITAGE HOTEL PURWOKERTO'
            ],
            [
                date_format($salary->month_and_year, 'F Y'),
            ],
            [
                'NIP',
                'Nama',
                'Department',
                'Gaji Pokok',
                'Hari Kerja',
                'Total Gaji',
                'Penambah',
                '',
                '',
                '',
                'Gaji Bruto',
                'THR',
                'Bruto 1 Tahun',
                'PPh21',
                '',
                '',
                '',
                '',
                '',
                '',
                'PPh21 Bulanan',
                'Potongan CUG',
                'Gaji Diterima'
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                'Service',
                'JKK',
                'JKM',
                'BPJS',
                '',
                '',
                '',
                'Biaya Jabatan',
                'JHT 1 Tahun',
                'Pensiun Karyawan 1 Tahun',
                'Netto 1 Tahun',
                'PTKP',
                'PKP',
                'PPh21',
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('A2:B2');
        $sheet->mergeCells('A3:B3');
        $sheet->mergeCells('A4:A5');
        $sheet->mergeCells('B4:B5');
        $sheet->mergeCells('C4:C5');
        $sheet->mergeCells('D4:D5');
        $sheet->mergeCells('E4:E5');
        $sheet->mergeCells('F4:F5');
        $sheet->mergeCells('G4:J4');
        $sheet->mergeCells('K4:K5');
        $sheet->mergeCells('L4:L5');
        $sheet->mergeCells('M4:M5');
        $sheet->mergeCells('N4:T4');
        $sheet->mergeCells('U4:U5');
        $sheet->mergeCells('V4:V5');
        $sheet->mergeCells('W4:W5');

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle('A4:W6')->applyFromArray($styleArray);
        $sheet->getStyle('A1:W6')->getFont()->setName('Tahoma');
        $sheet->getStyle('A1:W6')->getFont()->setSize(10);
        $sheet->getStyle('A1:W5')->getFont()->setBold(true);
        $sheet->getStyle('A4:W5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A4:W5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('D6')->getNumberFormat()->setFormatCode('Rp#,##0.00');
        $sheet->getStyle('F6:W6')->getNumberFormat()->setFormatCode('Rp#,##0.00');
    }

    /**
    * @var App\Models\Salary $salary
    */
    public function map($salary): array
    {
        return [
            $salary->employee->nip,
            $salary->employee->name,
            $salary->employee->department->name,
            $salary->employee->salaryRange->base_salary,
            $salary->actual_workdays,
            $salary->basic_salary,
            $salary->service,
            $salary->jkk,
            $salary->jkm,
            $salary->bpjs,
            $salary->gross_salary,
            $salary->thr,
            $salary->year_gross_salary,
            $salary->position_allowance,
            $salary->jht_one_year,
            $salary->pension_one_year,
            $salary->netto,
            $salary->ptkp->fee,
            $salary->pkp,
            $salary->pph,
            $salary->monthly_pph,
            $salary->cug_cut,
            $salary->salary_received,
        ];
    }
}
