<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Enums\TaxStatus;
use App\Models\Employee;
use App\Models\Position;
use App\Models\SalaryRange;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enums\EmploymentStatus;
use App\Models\ResidenceAddress;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use sirajcse\UniqueIdGenerator\UniqueIdGenerator;
use App\Http\Requests\StoreResidenceAddressRequest;
use App\Http\Requests\UpdateResidenceAddressRequest;

class MyDataController extends Controller
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
        $employee = Employee::with([
            'user',
            'position',
            'salaryRange',
            'residence',
            'families',
            'leave',
            'leaveRequests',
        ])->whereBelongsTo($request->user())->first();

        if (isset($employee->last_contract_end)) {
            $remaining_contract = now()->diffInDays($employee->last_contract_end);
        } else {
            $remaining_contract = null;
        }

        return view('my-data.show', [
            'title' => "My Data",
            'employee' => $employee,
            'remaining_contract' => $remaining_contract,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create', [Employee::class, "my-data"])) {
            return back()->withInput()->with('warning', 'Not Authorized');
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
            'title' => 'Fill Data',
            'user' => $request->user(),
            'positions' => Position::all()->mapWithKeys( fn($item, $key) => [$item['id'] => $item['name'].' - '.$item['department']['name']] )->all(),
            'salaryRanges' => SalaryRange::all()->mapWithKeys( fn($item, $key) => [$item['id'] => $item['name'].' - '.$item['level']['name']] )->all(),
            'genders' => $gender,
            'statusPajak' => $statusPajak,
            'dateConfig' => $dateConfig,
            'lastContractEndDateConfig' => $lastContractEndDateConfig,
            'birthDateConfig' => $birthDateConfig,
            'employmentStatus' => $employment_status,
            'bloodTypes' => $bloodTypes,
            'breadcrumb' => 'my-data',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeeRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();
        $validated['nip'] = UniqueIdGenerator::generate([
            'table' => 'employees',
            'field' => 'nip',
            'length' => 7,
            'prefix' => 'JH.',
        ]);
        if($user->employee()->create($validated)){
            if ($user->employee()->leave()->create()) {
                return redirect()->route('my-data.index')->with('success', 'Your data has been saved successfully');
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        $employee = $user->employee;

        if ($request->user()->cannot('update', $employee)) {
            return redirectNotAuthorized('my-data');
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

        return view('employees.edit', [
            'title' => 'Edit My Data',
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
            'breadcrumb' => 'my-data'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployeeRequest $request)
    {
        $employee = $request->user()->employee;
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
            return redirectWithAlert('my-data', 'success', 'Employee Updated Successfully');
        }

        return back()->withInput()->with('danger', 'Failed to update employee');
    }

    //RESIDENCE
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createResidence(Request $request)
    {
        $employee = $request->user()->employee;
        if ($request->user()->cannot('create', [ResidenceAddress::class, $employee])){
            return back()->with('warning', 'Not Authorized');
        }

        if ($employee->residence != null){
            return back()->with('warning', $employee->name.' already had a residence address!');
        }

        return view('employees.create-residence', [
            'title' => 'Add Residence Address',
            'employee' => $employee,
            'breadcrumb' => 'my-data'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreResidenceAddressRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function storeResidence(StoreResidenceAddressRequest $request)
    {
        $employee = $request->user()->employee;
        $validated = $request->validated();
        if($employee->residence != null){
            return redirect()->route('my-data.index')->with('warning', 'You already have a residence address!');
        }
        $employee->residence()->create($validated);

        return redirect()->route('my-data.index')->with('success', 'Residence Address added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ResidenceAddress  $residenceAddress
     * @return \Illuminate\Http\Response
     */
    public function editResidence(Request $request)
    {
        $employee = $request->user()->employee;
        if ($request->user()->cannot('update', [$employee->residence, $employee])){
            return back()->with('warning', 'Not Authorized');
        }

        return view('employees.edit-residence', [
            'title' => 'Edit Residence Address',
            'employee' => $employee,
            'breadcrumb' => 'my-data',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateResidenceAddressRequest  $request
     * @param  \App\Models\ResidenceAddress  $residenceAddress
     * @return \Illuminate\Http\Response
     */
    public function updateResidence(UpdateResidenceAddressRequest $request)
    {
        $employee = $request->user()->employee;
        $validated = $request->validated();
        if ($employee->residence()->update($validated)) {
            return redirect()->route('my-data.index')->with('success', 'Residence Address updated successfully');
        };
        return back()->withInput()->with('danger', 'Failed to update address');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroyResidence(Request $request)
    {
        $employee = $request->user()->employee;
        if ($request->user()->cannot('delete', [$employee->residence, $employee])){
            return back()->with('warning', 'Not Authorized');
        }

        $employee->residence()->delete();
        return redirect()->route('my-data.index')->with('success', 'Residence deleted successfully');
    }
}
