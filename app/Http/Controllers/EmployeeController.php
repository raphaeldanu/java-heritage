<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\Gender;
use App\Models\Level;
use App\Enums\TaxStatus;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Department;
use App\Models\SalaryRange;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enums\EmploymentStatus;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use sirajcse\UniqueIdGenerator\UniqueIdGenerator;

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
            'employees' => Employee::filters(request(['search', 'department_id', 'level_id']))->with('position')->paginate(15)->withQueryString(),
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
}
