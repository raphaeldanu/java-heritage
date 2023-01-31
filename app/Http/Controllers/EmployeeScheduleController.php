<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EmployeeSchedule;
use App\Http\Requests\StoreEmployeeScheduleRequest;
use App\Http\Requests\UpdateEmployeeScheduleRequest;

class EmployeeScheduleController extends Controller
{
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

        if ($user->cannot('viewAll', EmployeeSchedule::class)) {
            $employeeOfUser = $user->employee;
    
            $employees = Employee::whereHas('position', fn($query) => $query->where('department_id', $employeeOfUser->position->id))->filters(request(['search', 'department_id']))->paginate(15)->withQueryString();
        } else {
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
        if ($user->cannot('viewAny', EmployeeSchedule::class) and $user->cannot('view', EmployeeSchedule::class)) {
            return redirect()->route('home')->with('warning', 'Not Authorized');
        } 

        $schedules = EmployeeSchedule::whereBelongsTo($employee)->orderBy('id', 'desc')->paginate(15);

        return view('schedules.show-per-employee', [
            'title' => 'Monthly Schedule of '. Str::before($employee->name, ' '),
            'schedules' => $schedules,
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
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEmployeeScheduleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeeScheduleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeSchedule  $employeeSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeSchedule $employeeSchedule)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeSchedule  $employeeSchedule
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeSchedule $employeeSchedule)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeSchedule  $employeeSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeSchedule $employeeSchedule)
    {
        //
    }
}
