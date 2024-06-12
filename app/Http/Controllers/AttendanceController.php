<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Imports\AttendancesImport;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;

class AttendanceController extends Controller
{
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
        if ($request->user()->cannot('viewAny', Attendance::class)) {
            return redirectNotAuthorized('home');
        }

        $employees = Employee::has('attendances')
            ->filters(request(['search', 'department_id']))
            ->with('department')
            ->orderBy('id', 'ASC')
            ->paginate(15)
            ->withQueryString();

        $departments = Department::all()->mapWithKeys(fn ($item, $key) => [$item['id'] => $item['name']])->all();

        return view('attendances.index', [
            'title' => "Attendances History",
            'employees' => $employees,
            'departments' => $departments,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create', Attendance::class)) {
            return redirectNotAuthorized('attendances');
        }

        return view('attendances.create', [
            'title' => 'Import Attendances',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAttendanceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAttendanceRequest $request)
    {
        $import = new AttendancesImport;
        $import->import($request->file('attendance_files'));

        return redirect()->route('attendances.index')->with('success', 'Data imported');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function showByEmployee(Request $request, Employee $employee)
    {
        if ($request->user()->cannot('view-attendances')) {
            return redirectNotAuthorized('attendances');
        }

        $attendances = Attendance::whereBelongsTo($employee)
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('attendances.show-per-employee', [
            'title' => 'Attendances of ' . Str::before($employee->name, ' '),
            'attendances' => $attendances,
            'employee' => $employee,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function myIndex(Request $request)
    {
        $employee = $request->user()->employee;

        $attendances = Attendance::whereBelongsTo($employee)
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('attendances.my-index', [
            'title' => 'My Attendances',
            'attendances' => $attendances,
            'employee' => $employee,
        ]);
    }
}
