<?php

namespace App\Http\Controllers;

use App\Exports\PersonalSalaryExport;
use Carbon\Carbon;
use App\Models\Salary;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSalaryRequest;
use App\Http\Requests\UpdateSalaryRequest;
use App\Models\Attendance;
use App\Models\Ptkp;
use Carbon\CarbonPeriod;

class SalaryController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', Salary::class)) {
            return redirectNotAuthorized('home');
        }

        $employees = Employee::filters(request(['search']))->orderBy('id', 'ASC')->paginate(15)->withQueryString();

        $departments = Department::all()->mapWithKeys( fn($item, $key) => [$item['id'] => $item['name']] )->all();

        return view('salaries.index', [
            'title' => "Salary",
            'employees' => $employees,
            'departments' => $departments,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function showByEmployee(Request $request, Employee $employee)
    {
        if ($request->user()->cannot('view-salaries')){
            return redirectNotAuthorized('salaries');
        }

        $salaries = Salary::whereBelongsTo($employee)->orderBy('id', 'desc')->paginate(15);

        return view('salaries.show-per-employee', [
            'title' => 'Salaries of '.Str::before($employee->name, ' '),
            'salaries' => $salaries,
            'employee' => $employee,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createByEmployee(Request $request, Employee $employee)
    {
        if ($request->user()->cannot('create', Salary::class)) {
            return redirect()->route('salaries.show-by-employee', ['employee' => $employee]);
        }

        $dateConfig = [
            'format' => 'YYYY-MM',
            'maxDate' => "js:moment().subtract(19, 'days')",
        ];

        return view('salaries.create-by-employee', [
            'title' => "Create Salary Roll for ".Str::before($employee->name, ' '),
            'employee' => $employee,
            'dateConfig' => $dateConfig,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSalaryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSalaryRequest $request)
    {
        $employee = Employee::with(['salaryRange', 'employeeSchedules'])->find($request->employee_id);
        $salary = Salary::whereBelongsTo($employee)->get();
        $month_and_year = Carbon::parse($request->month_and_year);
        if ($salary->contains('month_and_year', $month_and_year)){
            return redirect()->route('salaries.show-by-employee', ['employee' => $employee])->with('danger', "Salary roll for this date is already generated!");
        }
        $ptkp = Ptkp::where('tax_status', $employee->tax_status)->first();
        $schedules = $employee->employeeSchedules;
        $schedule = $schedules->where('month_and_year', $month_and_year)->first();

        $schedulesWorkdays = isset($schedule) ? $schedule->workdays ?? 28 : 28;
        $actual_workdays = $request->workdays > 28 ? 28 : $request->workdays;

        $base_salary = $employee->salaryRange->base_salary;
        $basic_salary = round(($base_salary / $schedulesWorkdays) * $actual_workdays, 0);
        $jkk = 0.0054 * $base_salary;
        $jkm = 0.003 * $base_salary;
        $bpjs = $base_salary > 12000000 ? 480000 : 0.04 * $base_salary;
        $service = $request->service ?? 0;

        $gross_salary = $basic_salary + $service + $jkk + $jkm + $bpjs;
        $thr = $request->thr ?? 0;
        $year_gross_salary = ($gross_salary * 12) + $thr;
        $position_allowance = $year_gross_salary * 0.05 > 6000000 ? 6000000 : round($year_gross_salary * 0.05, 0);
        $jht = $base_salary * 0.02;
        $jht_one_year = round($jht * 12, 2);
        $pension = $base_salary > 9077660 ? 9077660 * 0.01 : $base_salary * 0.01;
        $pension_one_year =  round($pension * 12, 2);
        $netto = $year_gross_salary - $position_allowance - $jht_one_year - $pension_one_year;

        $pkp = 0;
        $pph = 0;
        $monthly_pph = 0;
        if($netto > $ptkp->fee){
            $pkp = $netto - $ptkp->fee;
            $pph = round(countPph($pkp), 0);
            $monthly_pph = round($pph / 12, 0);
        }
        $cug_cut = $request->cug_cut ?? 0;
        $salary_received = round(($base_salary + $request->service) - ($jht + $pension + $cug_cut + $monthly_pph), 0);

        $data = [
            'employee_id' => $employee->id,
            'ptkp_id' => $ptkp->id,
            'month_and_year' => Carbon::parse($request->month_and_year),
            'actual_workdays' => $actual_workdays,
            'basic_salary' => $basic_salary,
            'service' => $service,
            'jkk' => $jkk,
            'jkm' => $jkm,
            'bpjs' => $bpjs,
            'gross_salary' => $gross_salary,
            'thr' => $thr,
            'year_gross_salary' => $year_gross_salary,
            'position_allowance' => $position_allowance,
            'jht_one_year' => $jht_one_year,
            'pension_one_year' => $pension_one_year,
            'netto' => $netto,
            'pkp' => $pkp,
            'pph' => $pph,
            'monthly_pph' => $monthly_pph,
            'cug_cut' => $cug_cut,
            'salary_received' => $salary_received,
        ];

        $newSalaryRoll = Salary::create($data);
        return redirect()->route('salaries.show-by-employee', ['employee' => $employee])->with('success', 'Salary Roll Generated Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Salary $salary)
    {
        if($request->user()->cannot('view', $salary)){
            return redirect()->route('salaries.index')->with('warning', 'Not Authorized');
        }

        return view('salaries.show', [
            'title' => "Salary Roll Detail",
            'salary' => $salary,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request, Salary $salary)
    {
        if($request->user()->cannot('view', $salary)){
            return redirect()->route('salaries.index')->with('warning', 'Not Authorized');
        }

        return (new PersonalSalaryExport($salary->id))->download('salary-roll.xlsx');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Salary $salary)
    {
        if($request->user()->cannot('delete', $salary)){
            return redirect()->route('salaries.show-by-employee', ['employee' => $salary->employee])->with('warning', 'Not Authorized');
        }

        $employee = $salary->employee;

        if ($salary->delete()) {
            return redirect()->route('salaries.show-by-employee', ['employee' => $employee])->with('success', 'Salary Roll deleted successfully');
        }
        return redirect()->route('salaries.show-by-employee', ['employee' => $employee])->with('danger', 'Failed to delete salary roll');
    }

    
    public function checkWorkdays(Request $request)
    {
        if (!isset($request['employee_id']) or !isset($request['month_and_year'])) {
            return redirect()->route('salaries.index')->with('danger', 'Your URL is not valid');
        }

        if ($request->user()->cannot('create', Salary::class)) {
            return redirect()->route('salaries.show-by-employee', ['employee' => $request['employee']])->with('warning', 'Not Authorized');
        }

        $employee = Employee::with(['employeeSchedules'])->find($request['employee_id']);
        $schedules = $employee->employeeSchedules;
        $month_and_year = Carbon::parse($request['month_and_year']);
        $schedule = $schedules->where('month_and_year', $month_and_year)->first();

        $beginning = Carbon::parse($request['month_and_year']);
        $beginning->subMonthsNoOverflow(1);
        $beginning->day = 21;
        
        $end = Carbon::parse($request['month_and_year']);
        $end->day = 20;
        
        $attendances = Attendance::whereBelongsTo($employee)->whereBetween('scan_datetime', [$beginning, $end])->pluck('scan_datetime');

        
        foreach ($attendances as $date) {
            $date->startOfDay();
        }

        $unique_attendances = $attendances->unique();
        $workdays = $unique_attendances->count();

        $dateConfig = [
            'format' => 'YYYY-MM',
            'maxDate' => "js:moment().subtract(19, 'days')",
        ];

        return view('salaries.validate-workdays', [
            'title' => "Validate Workdays of ".Str::before($employee->name, " "),
            'dateConfig' => $dateConfig,
            'month_and_year' => $month_and_year,
            'workdays' => $workdays,
            'employee' => $employee,
            'schedule' => $schedule,
        ]);
    }
}
