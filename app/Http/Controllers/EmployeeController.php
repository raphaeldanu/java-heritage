<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Enums\Gender;
use App\Models\Leave;
use App\Models\Level;
use App\Enums\TaxStatus;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Department;
use App\Models\SalaryRange;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enums\EmploymentStatus;
use Illuminate\Support\Facades\DB;
use App\Exports\EmployeeRatioExport;
use App\Exports\MonthlyDailyWorkerExport;
use App\Http\Requests\UpdateLeaveRequest;
use App\Exports\MonthlyEmployeeHireExport;
use App\Http\Requests\StoreEmployeeRequest;
use App\Exports\MonthlyEmployeeResignExport;
use App\Http\Requests\UpdateEmployeeRequest;
use sirajcse\UniqueIdGenerator\UniqueIdGenerator;
use App\Http\Requests\StoreResidenceAddressRequest;
use App\Http\Requests\UpdateResidenceAddressRequest;

class EmployeeController extends Controller
{
    /**
     * Construct the controller
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('view-employees')) {
            return redirectNotAuthorized('home');
        }

        return view('employees.index', [
            'levels' => Level::all(),
            'employees' => Employee::filters(request(['search', 'department_id', 'level_id']))->with('position', 'salaryRange')->orderByRaw('position_id IS NULL DESC')->orderByRaw('salary_range_id IS NULL DESC')->orderBy('id', 'ASC')->paginate(15)->withQueryString(),
            'departments' => Department::all(),
            'title' => 'Employees detail',
        ]);
    }

    /**
     * pick user to create employee.
     *
     * @return \Illuminate\Http\Response
     */
    public function pickUser(Request $request)
    {
        if ($request->user()->cannot('create-employees')) {
            return redirectNotAuthorized('employees');
        } 

        return view('employees.pick-user', [
            'title' => "Create Employee",
            'users' => User::doesntHave('employee')->filters(request(['search']))->paginate(15)->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, User $user)
    {
        if ($request->user()->cannot('create-employees')) {
            return redirectNotAuthorized('employees');
        }

        $taxStatus = TaxStatus::cases();

        $statusPajak = [];
        foreach ($taxStatus as $item) {
        $statusPajak[$item->value] = Str::headline($item->name);
        }

        $gender = [];
        foreach (Gender::cases() as $item) {
        $gender[$item->value] = $item->name;
        }

        $employment_status = [];
        foreach(EmploymentStatus::cases() as $item){
            $employment_status[$item->value] = $item->name;
        }

        $dateConfig = [
          'format' => 'YYYY-MM-DD'
        ];
        $lastContractEndDateConfig = [
          'format' => 'YYYY-MM-DD',
          'minDate' => "js:moment().add(1, 'month')",
        ];
        $birthDateConfig = [
          'format' => 'YYYY-MM-DD',
          'maxDate' => "js:moment().subtract(17, 'year')",
        ];
        $bloodTypes = [
            'A' => "A",
            'B' => "B",
            'AB' => "AB",
            'O' => "O",
        ];

        return view('employees.create', [
            'title' => 'Create New Employee',
            'user' => $user,
            'positions' => Position::all()->mapWithKeys( fn($item, $key) => [$item['id'] => $item['name'].' - '.$item['department']['name']] )->all(),
            'salaryRanges' => SalaryRange::all()->mapWithKeys( fn($item, $key) => [$item['id'] => $item['name'].' - '.$item['level']['name']] )->all(),
            'genders' => $gender,
            'statusPajak' => $statusPajak,
            'dateConfig' => $dateConfig,
            'lastContractEndDateConfig' => $lastContractEndDateConfig,
            'birthDateConfig' => $birthDateConfig,
            'employmentStatus' => $employment_status,
            'bloodTypes' => $bloodTypes,
            'breadcrumb' => 'employee',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEmployeeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeeRequest $request)
    {
        $validated = $request->validated();
        $validated['nip'] = UniqueIdGenerator::generate([
            'table' => 'employees',
            'field' => 'nip',
            'length' => 7,
            'prefix' => 'JH.',
        ]);

        $newEmployees = Employee::create($validated);
        $newEmployees->leave()->create();

        if (! $newEmployees) {
            return back()->withInput()->with('danger', 'Failed to save new position');
        }
        return redirectWithAlert('employees', 'success', 'New Employee Detail saved successfully');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Employee $employee)
    {
        if($request->user()->cannot('view', $employee)){
            return redirectNotAuthorized('employees');
        }
        
        $employee = Employee::with([
            'user',
            'position',
            'salaryRange',
            'residence',
            'families',
            'leave',
            
        ])->find($employee->id);

        if (isset($employee->last_contract_end)) {
            $remaining_contract = now()->diffInDays($employee->last_contract_end);
        } else {
            $remaining_contract = null;
        }

        return view('employees.show', [
            'title' => "Employee Details",
            'employee' => $employee,
            'remaining_contract' => $remaining_contract,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Employee $employee)
    {
        if ($request->user()->cannot('update', $employee)) {
            return redirectNotAuthorized('employees');
        }

        $taxStatus = TaxStatus::cases();

        $statusPajak = [];
        foreach ($taxStatus as $item) {
        $statusPajak[$item->value] = Str::headline($item->name);
        }

        $gender = [];
        foreach (Gender::cases() as $item) {
        $gender[$item->value] = $item->name;
        }

        $employment_status = [];
        foreach(EmploymentStatus::cases() as $item){
            $employment_status[$item->value] = $item->name;
        }

        $dateConfig = [
          'format' => 'YYYY-MM-DD'
        ];
        $lastContractEndDateConfig = [
          'format' => 'YYYY-MM-DD',
          'minDate' => "js:moment().add(1, 'month')",
        ];
        $birthDateConfig = [
          'format' => 'YYYY-MM-DD',
          'maxDate' => "js:moment().subtract(17, 'year')",
        ];
        $bloodTypes = [
            'A' => "A",
            'B' => "B",
            'AB' => "AB",
            'O' => "O",
        ];

        $employee = $employee->with(['user'])->find($employee->id);

        return view('employees.edit', [
            'title' => 'Edit Employee',
            'employee' => $employee,
            'positions' => Position::all()->mapWithKeys( fn($item, $key) => [$item['id'] => $item['name'].' - '.$item['department']['name']] )->all(),
            'salaryRanges' => SalaryRange::all()->mapWithKeys( fn($item, $key) => [$item['id'] => $item['name'].' - '.$item['level']['name']] )->all(),
            'genders' => $gender,
            'statusPajak' => $statusPajak,
            'dateConfig' => $dateConfig,
            'lastContractEndDateConfig' => $lastContractEndDateConfig,
            'birthDateConfig' => $birthDateConfig,
            'employmentStatus' => $employment_status,
            'bloodTypes' => $bloodTypes,
            'breadcrumb' => 'employee'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEmployeeRequest  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $uniqueColumn = [
            'nik',
            'bpjs_tk_number',
            'bpjs_kes_number',
            'npwp_number',
        ];
        $validated = $request->safe()->except($uniqueColumn);
        
        foreach($uniqueColumn as $item){
            if($employee->$item != $request->$item){
                $validated[$item] = $request->$item;
            }
        }

        if ($employee->employment_status != $request->employment_status and $request->employment_status == EmploymentStatus::Resigned){
            $validated['resign_date'] = DB::raw('CURRENT_TIMESTAMP');
        }

        if ($employee->employment_status != $request->employment_status and $employee->employment_status == EmploymentStatus::Resigned){
            $validated['resign_date'] = null;
        }

        if($employee->update($validated)){
            return redirectWithAlert('employees', 'success', 'Employee Updated Successfully');
        }

        return back()->withInput()->with('danger', 'Failed to update employee');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Illuminate\Http\Request $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Employee $employee)
    {
        if ($request->user()->cannot('delete-employees')) {
            return redirectNotAuthorized('employees');
        }

        if(!$employee->delete()){
            return redirectWithAlert('employees', 'danger', "Failed to delete employee detail");
        }

        return redirectWithAlert('employees', 'success', 'Employee detail deleted successfully');
    }

    public function exportTurnOver(Request $request)
    {
        if ($request->user()->cannot('export-employees')) {
            return redirectNotAuthorized('employees');
        }

        $types = [
            'hire' => "New Hire",
            'resign' => "Resign",
            'daily_worker' => "Daily Worker",
        ];

        $dateConfig = [
            'format' => 'YYYY-MM'
          ];

        return view('employees.pick-date-export', [
            'title' => 'Export Turn Over Employee',
            'types' => $types,
            'dateConfig' => $dateConfig,
        ]);
    }

    public function exportRatio(Request $request)
    {
        if ($request->user()->cannot('export-employees')) {
            return redirectNotAuthorized('employees');
        }

        $employee = Employee::where('employment_status', '!=', EmploymentStatus::Resigned)->get();
        $departments = Department::all();
        $data = [];
        foreach ($departments as $item) {
            $male_permanent = Employee::whereHas('position', fn($query) => $query->where('department_id', $item->id))->where('gender', Gender::Male)->where('employment_status', EmploymentStatus::Permanent)->count();
            $female_permanent = Employee::whereHas('position', fn($query) => $query->where('department_id', $item->id))->where('gender', Gender::Female)->where('employment_status', EmploymentStatus::Permanent)->count();
            $male_contract = Employee::whereHas('position', fn($query) => $query->where('department_id', $item->id))->where('gender', Gender::Male)->where('employment_status', EmploymentStatus::Contract)->count();
            $female_contract = Employee::whereHas('position', fn($query) => $query->where('department_id', $item->id))->where('gender', Gender::Female)->where('employment_status', EmploymentStatus::Contract)->count();
            $daily_worker = Employee::whereHas('position', fn($query) => $query->where('department_id', $item->id))->where('employment_status', EmploymentStatus::DailyWorker)->count();
            array_push($data, collect([
                'name' => $item->name,
                'male_permanent' => $male_permanent,
                'female_permanent' => $female_permanent,
                'male_contract' => $male_contract,
                'female_contract' => $female_contract,
                'daily_worker' => $daily_worker,
                'total' => $male_permanent+$female_permanent+$male_contract+$female_contract+$daily_worker,
            ]));
        }

        return (new EmployeeRatioExport(collect($data)))->download('employee-ratio.xlsx');
    }

    public function export(Request $request)
    {
        if ($request->user()->cannot('export-employees')) {
            return redirectNotAuthorized('employees');
        }
        
        $type = $request->type;
        $month_and_year = Carbon::parse($request->month_and_year);

        if ($type == 'hire'){
            return (new MonthlyEmployeeHireExport($month_and_year))->download('employee-hire.xlsx');
        } elseif ($type == 'resign'){
            return (new MonthlyEmployeeResignExport($month_and_year))->download('employee-resign.xlsx');
        } elseif ($type == 'daily_worker') {
            return (new MonthlyDailyWorkerExport($month_and_year))->download('daily-worker.xlsx');
        }
    }

    // LEAVE
    /**
     * Add leave to employee if not created yet.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addLeave(Request $request, Employee $employee)
    {
        if($request->user()->cannot('create-employees')){
            return redirect()->route('employees.show', ['employee' => $employee])->with('warning', 'Not Authorized');
        }

        if(is_null($employee->leave)){
            $employee->leave()->create([]);
        }

        return redirect()->route('employees.show', ['employee' => $employee])->with('success', 'Employee Leave is created successfully');
    }

    /**
     * Edit leave of employee.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editLeave(Request $request, Employee $employee, Leave $leave)
    {
        if($request->user()->cannot('update', $leave)){
            return redirect()->route('employees.show', ['employee' => $employee])->with('warning', 'Not Authorized');
        }

        return view('employees.edit-leave', [
            'title' => 'Edit Employee Leave',
            'leave' => $leave,
            'employee' => $employee,
        ]);
    }

    /**
     * Edit leave of employee.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateLeave(UpdateLeaveRequest $request, Employee $employee, Leave $leave)
    {
        if($request->user()->cannot('update', $leave)){
            return redirect()->route('employees.show', ['employee' => $employee])->with('warning', 'Not Authorized');
        }

        $validated = $request->validated();
        $leave->update($validated);
        return redirect()->route('employees.show', ['employee' => $employee]);
    }

    //RESIDENCE
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createResidence(Request $request, Employee $employee)
    {
        if ($request->user()->cannot('create', [ResidenceAddress::class, $employee])){
            return back()->with('warning', 'Not Authorized');
        }

        if ($employee->residence != null){
            return back()->with('warning', $employee->name.' already had a residence address!');
        }

        return view('employees.create-residence', [
            'title' => 'Add Residence Address',
            'employee' => $employee,
            'breadcrumb' => 'employee'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreResidenceAddressRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function storeResidence(StoreResidenceAddressRequest $request, Employee $employee)
    {
        $validated = $request->validated();
        if($employee->residence != null){
            return redirect()->route('employees.show', ['employee' => $employee])->with('warning', $employee->name.' already have a residence address!'); 
        }
        $employee->residence()->create($validated);
        $newResidence = $employee->residence;
        
        return redirect()->route('employees.show', ['employee' => $employee])->with('success', 'Residence Address added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ResidenceAddress  $residenceAddress
     * @return \Illuminate\Http\Response
     */
    public function editResidence(Request $request, Employee $employee)
    {
        if ($request->user()->cannot('update', [$employee->residence, $employee])){
            return back()->with('warning', 'Not Authorized');
        }

        return view('employees.edit-residence', [
            'title' => 'Edit Residence Address',
            'employee' => $employee,
            'breadcrumb' => 'employee',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateResidenceAddressRequest  $request
     * @param  \App\Models\ResidenceAddress  $residenceAddress
     * @return \Illuminate\Http\Response
     */
    public function updateResidence(UpdateResidenceAddressRequest $request, Employee $employee)
    {
        $validated = $request->validated();
        if (!$employee->residence()->update($validated)) {
            return back()->withInput()->with('danger', 'Failed to update address');
        };
        return redirect()->route('employees.show', ['employee' => $employee])->with('success', 'Residence Address updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroyResidence(Request $request, Employee $employee)
    {
        if ($request->user()->cannot('delete', [$employee->residence, $employee])){
            return back()->with('warning', 'Not Authorized');
        }

        $employee->residence()->delete();
        return redirect()->route('employees.show', ['employee' => $employee])->with('success', 'Residence deleted successfully');
    }
}
