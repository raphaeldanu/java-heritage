<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Enums\LeaveType;
use App\Enums\LeaveStatus;
use Illuminate\Support\Str;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLeaveRequestRequest;
use App\Http\Requests\UpdateLeaveRequestRequest;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class LeaveRequestController extends Controller
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
        if ($user->cannot('viewAny', [LeaveRequest::class, 'my-request'])) {
            return redirect()->route('home')->with('danger', 'Please fill your data first');
        }

        $employee = $user->employee;

        $leave_request = LeaveRequest::whereBelongsTo($employee)->filters(request(['search', 'status', 'type']))->paginate(15)->withQueryString();

        $leave_types = [];
        foreach (LeaveType::cases() as $item) {
        $leave_types[$item->value] = Str::headline($item->name);
        }

        $statuses = [];
        foreach (LeaveStatus::cases() as $item) {
        $statuses[$item->value] = Str::headline($item->name);
        }

        return view('leave-requests.index', [
            'title' => 'Leave Request',
            'leave_requests' => $leave_request,
            'leave_types' => $leave_types,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create', LeaveRequest::class)) {
            return redirectWithAlert('home', 'warning', 'Please Complete Your Data First!');
        }

        if($request->user()->employee->gender == Gender::Male){
            $except = 'melahirkan';
        } else {
            $except = 'kelahiran_anak';
        }

        $types = collect(LeaveType::cases())->where('value', '!=', $except);
        $leave_types = [];
        foreach ($types as $item) {
        $leave_types[$item->value] = Str::headline($item->name);
        }
        
        $dateConfig = [
            'format' => 'YYYY-MM-DD',
            'minDate' => "js:moment().add(1, 'day')",
        ];

        return view('leave-requests.create', [
            'title' => 'Request for Leave',
            'types' => $leave_types,
            'dateConfig' => $dateConfig,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLeaveRequestRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLeaveRequestRequest $request)
    {
        $validated = $request->validated();
        $validated['employee_id'] = $request->user()->employee->id;
        $validated['status'] = LeaveStatus::WaitingApproval;
        if ($request->leave_type == 'annual'){
            $employee = $request->user()->employee;
            if (is_null($request->end_date)){
                $days = 1;
            } else {
                $start = Carbon::parse($request->start_date);
                $end = Carbon::parse($request->end_date);
                $period = CarbonPeriod::create($start, $end);
                $days = $period->count();
            }
            
            if ($employee->leave->annual < $days) {
                return back()->withInput();
            } else {
                $leave = $employee->leave;
                $leave->annual = $leave->annual - $days;
                $leave->save();
            }
        }
        $newLeaveRequest = LeaveRequest::create($validated);
        if (!$newLeaveRequest) {
            return back()->withInput()->with('danger', 'Failed to make new leave request');
        }
        return redirectWithAlert('leave-requests', 'success', 'Leave Request Added Successfuly!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LeaveRequest  $leaveRequest
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, LeaveRequest $leaveRequest)
    {
        if ($request->user()->cannot('view', $leaveRequest)) {
            return redirect()->route('leave-requests.index')->with('warning', 'Not authorized to look');
        }

        $days = 1;
        if (isset($leaveRequest->end_date)) {
            $period = CarbonPeriod::create($leaveRequest->start_date, $leaveRequest->end_date);
            $days = $period->count();
        }

        return view('leave-requests.show', [
            'title' => 'Leave Request Detail',
            'leaveRequest' => $leaveRequest,
            'days' => $days,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LeaveRequest  $leaveRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, LeaveRequest $leaveRequest)
    {
        if ($request->user()->cannot('update', $leaveRequest)) {
            return redirect()->route('leave-requests.index')->with('warning', 'Not Authorized');
        }

        if($request->user()->employee->gender == Gender::Male){
            $except = 'melahirkan';
        } else {
            $except = 'kelahiran_anak';
        }

        $types = collect(LeaveType::cases())->where('value', '!=', $except);
        $leave_types = [];
        foreach ($types as $item) {
        $leave_types[$item->value] = Str::headline($item->name);
        }
        
        $dateConfig = [
            'format' => 'YYYY-MM-DD',
            'minDate' => "js:moment().add(1, 'day')",
        ];

        return view('leave-requests.edit', [
            'title' => 'Edit Request for Leave',
            'leaveRequest' => $leaveRequest,
            'types' => $leave_types,
            'dateConfig' => $dateConfig,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLeaveRequestRequest  $request
     * @param  \App\Models\LeaveRequest  $leaveRequest
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLeaveRequestRequest $request, LeaveRequest $leaveRequest)
    {
        if ($request->user()->cannot('update', $leaveRequest)) {
            return redirect()->route('leave-requests.index')->with('warning', 'This request has been approved');
        }

        $validated = $request->validated();
        $leaveRequest->update($validated);

        return redirectWithAlert('leave-requests', 'success', 'Your leave request changed successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LeaveRequest  $leaveRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, LeaveRequest $leaveRequest)
    {
        if ($request->user()->cannot('delete', $leaveRequest)) {
            return back()->with('danger', 'You are not authorized to delete this request');
        }

        $leaveRequest->delete();
        return redirectWithAlert('leave-requests', 'success', 'Leave Request deleted successfully');
    }
}
