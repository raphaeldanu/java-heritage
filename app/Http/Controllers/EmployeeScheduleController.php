<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EmployeeSchedule;
use App\Http\Requests\StoreEmployeeScheduleRequest;
use App\Http\Requests\UpdateEmployeeScheduleRequest;
use Carbon\Carbon;

class EmployeeScheduleController extends Controller
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
        $user = $request->user();
        if ($user->cannot('viewAny', EmployeeSchedule::class) and $user->cannot('view', EmployeeSchedule::class)) {
            return redirect()->route('home')->with('warning', 'Not Authorized');
        } 
        
        $employeeOfUser = $user->employee;
        $employees = Employee::whereHas('position', fn($query) => $query->where('department_id', $employeeOfUser->position->department_id))->filters(request(['search', 'department_id']))->paginate(15)->withQueryString();
        if ($user->can('view-all-schedules')) {
            $employees = Employee::filters(request(['search', 'department_id']))->paginate(15)->withQueryString();
        }

        $departments = Department::all()->mapWithKeys( fn($item, $key) => [$item['id'] => $item['name']] )->all();

        return view('schedules.index', [
            'title' => 'Employee Schedules',
            'employees' => $employees,
            'departments' => $departments,
        ]);
    }

    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeSchedule  $employeeSchedule
     * @return \Illuminate\Http\Response
     */
    public function showByEmployee(Request $request, Employee $employee)
    {
        $user = $request->user();
        if ($user->cannot('viewAny', EmployeeSchedule::class)) {
            return redirect()->route('home')->with('warning', 'Not Authorized');
        } 
        if ($user->cannot('view-schedules') and $user->employee->position->department_id != $employee->position->department_id) {
            return redirect()->route('schedules.index')->with('warning', 'Not Authorized');
        }

        $schedules = EmployeeSchedule::whereBelongsTo($employee)->orderBy('id', 'desc')->paginate(15);

        return view('schedules.show-per-employee', [
            'title' => 'Monthly Schedule of '. Str::before($employee->name, ' '),
            'schedules' => $schedules,
            'employee' => $employee
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Employee $employee)
    {
        if ($request->user()->cannot('create', EmployeeSchedule::class)) {
            return redirect()->route('schedules.index')->with('warning', 'Not Authorized');
        }

        $dateConfig = [
            'format' => 'YYYY-MM'
        ];

        return view('schedules.create', [
            'title' => 'Create Schedule for '. Str::before($employee->name, ' '),
            'employee' => $employee,
            'dateConfig' => $dateConfig,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEmployeeScheduleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeeScheduleRequest $request)
    {
        $validated = $request->validated();
        $validated['month_and_year'] = Carbon::parse($validated['month_and_year']);
        $newSchedule = EmployeeSchedule::create($validated);
        return redirect()->route('schedules.show-by-employee', ['employee' => $newSchedule->employee]) ;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeSchedule  $employeeSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, EmployeeSchedule $employeeSchedule)
    {
        if ($request->user()->cannot('view', $employeeSchedule)) {
            return redirect()->route('schedules.index')->with('warning', 'Not Authorized');
        }

        return view('schedules.show', [
            'title' => 'Schedule Details',
            'employee' => $employeeSchedule->employee,
            'schedule' => $employeeSchedule,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeSchedule  $employeeSchedule
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, EmployeeSchedule $employeeSchedule)
    {
        if ($request->user()->cannot('update', $employeeSchedule)) {
            return redirect()->route('schedules.show-by-employee', ['employee' => $employeeSchedule->employee])->with('warning', 'Not Authorized');
        }

        $dateConfig = [
            'format' => 'YYYY-MM'
        ];

        return view('schedules.edit', [
            'title' => 'Edit Schedule',
            'employeeSchedule' => $employeeSchedule,
            'dateConfig' => $dateConfig
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEmployeeScheduleRequest  $request
     * @param  \App\Models\EmployeeSchedule  $employeeSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployeeScheduleRequest $request, EmployeeSchedule $employeeSchedule)
    {
        $validated = $request->safe()->except('month_and_year');
        if (Carbon::parse($request->month_and_year) != $employeeSchedule->month_and_year) {
            $validated['month_and_year'] = Carbon::parse($request->month_and_year);
        }
        if($employeeSchedule->update($validated)){
            return redirect()->route('schedules.show-by-employee', ['employee' => $employeeSchedule->employee])->with('success', 'Schedules Updated Successfully');
        }
        return redirect()->route('schedules.show-by-employee', ['employee' => $employeeSchedule->employee])->with('danger', 'Failed to update employee schedule');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeSchedule  $employeeSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, EmployeeSchedule $employeeSchedule)
    {
        if ($request->user()->cannot('delete', $employeeSchedule)) {
            return redirect()->route('schedules.show-by-employee', ['employee' => $employeeSchedule->employee])->with('warning', 'Not Authorized');
        }

        $employee = $employeeSchedule->employee;
        if ($employeeSchedule->delete()){
            return redirect()->route('schedules.show-by-employee', ['employee' => $employee])->with('success', 'Schedules Deleted Successfully');
        }
        return redirect()->route('schedules.show-by-employee', ['employee' => $employee])->with('danger', 'Failed to update employee schedule');
    }
}
