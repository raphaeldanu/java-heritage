<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendancesImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    use Importable;

    private $employees;

    public function __construct() {
        $this->employees = Employee::with('attendances')->get();
    }

    protected function transformNip(int $nip)
    {
        if (substr($nip, 0, 1) == 1) {
            return 'HH.'.substr($nip, 1);
        } elseif (substr($nip, 0, 1) == 2) {
            return 'JH.'.substr($nip, 1);
        }
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        Validator::make($rows->toArray(), [
            '*.tanggal_scan' => 'required',
            '*.nip' => 'required',
            '*.in_out' => 'required',
            '*.mesin' => 'required'
        ])->validate();
        
        foreach ($rows as $row ) {
            $nip = $this->transformNip($row['nip']);
            $employee = $this->employees->where('nip', $nip)->first();
            $tgl_scan = Carbon::parse($row['tanggal_scan']);
            if ($employee->attendances->doesntContain('scan_datetime', $tgl_scan)){
                Attendance::create([
                    'employee_id' => $employee->id,
                    'scan_datetime' => $tgl_scan,
                    'in_out' => $row['in_out'],
                    'machine' => $row['mesin'],
                ]);
            }
        }
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
