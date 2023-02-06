<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Enums\LeaveType;
use Carbon\CarbonPeriod;
use App\Enums\LeaveStatus;
use Illuminate\Support\Str;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class LeaveApprovalController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->cannot('viewAny', [LeaveRequest::class, 'other-request'])) {
            return redirect()->route('home')->with('warning', 'Not authorized');
        }

        $employee_position = $user->employee->position;

        if ($user->can('view-all-leave-requests')) {
            $leave_request = LeaveRequest::filters(request(['search', 'status', 'type']))->paginate(15)->withQueryString();
        } else {
            $leave_request = LeaveRequest::whereHas('employee', function (Builder $query) use ($employee_position) {
                $query->whereHas('position', function (Builder $query) use ($employee_position) {
                    $query->where('department_id', $employee_position->department_id);
                });
            })->filters(request(['search', 'status', 'type']))->paginate(15)->withQueryString();
        }

        $leave_types = [];
        foreach (LeaveType::cases() as $item) {
        $leave_types[$item->value] = Str::headline($item->name);
        }

        $statuses = [];
        foreach (LeaveStatus::cases() as $item) {
        $statuses[$item->value] = Str::headline($item->name);
        }

        $title = 'Department Leave Requests';
        if($user->can('view-all-leave-requests')){
            $title = 'All Leave Requests';
        }

        return view('leave-requests-approval.index', [
            'title' => $title,
            'leave_requests' => $leave_request,
            'leave_types' => $leave_types,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\LeaveRequest  $leaveRequest
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, LeaveRequest $leaveRequest)
    {
        if ($request->user()->cannot('view', $leaveRequest)) {
            return redirect()->route('approve-leave-requests.index')->with('warning', 'Not authorized to look');
        }

        $days = 1;
        if (isset($leaveRequest->end_date)) {
            $period = CarbonPeriod::create($leaveRequest->start_date, $leaveRequest->end_date);
            $days = $period->count();
        }

        return view('leave-requests-approval.show', [
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
        if ($request->user()->cannot('approve', $leaveRequest)) {
            return redirect()->route('approve-leave-requests.index')->with('warning', 'Not Authorized');
        }

        if ($leaveRequest->status != LeaveStatus::WaitingApproval){
            $statuses = collect(LeaveStatus::cases())->where('value', '!=', 'waiting');
        } else {
            $statuses = collect(LeaveStatus::cases());
        }
        $leave_statuses = [];
        foreach ($statuses as $item) {
            $leave_statuses[$item->value] = Str::headline($item->name);
        }
        
        $dateConfig = [
            'format' => 'YYYY-MM-DD',
            'minDate' => "js:moment().add(1, 'day')",
        ];

        $days = 1;
        if (isset($leaveRequest->end_date)) {
            $period = CarbonPeriod::create($leaveRequest->start_date, $leaveRequest->end_date);
            $days = $period->count();
        }

        return view('leave-requests-approval.edit', [
            'title' => 'Edit Request for Leave',
            'leaveRequest' => $leaveRequest,
            'statuses' => $leave_statuses,
            'dateConfig' => $dateConfig,
            'days' => $days,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LeaveRequest  $leaveRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        if ($request->user()->cannot('approve', $leaveRequest)) {
            return redirect()->route('approve-leave-requests.index')->with('warning', 'Not Authorized');
        }
        
        $rules = [
            'status' => 'required',
            'note_from_approver' => 'nullable|string',
        ];

        if ($request->start_date != $leaveRequest->start_date) {
            $rules['start_date'] = 'required|date';
        }

        if ($request->end_date != $leaveRequest->end_date) {
            $rules['end_date'] = 'required|date';
        }

        $validated = $request->validate($rules);
        $validated['approved_by'] = $request->user()->id;
        if ($leaveRequest->update($validated)){
            return redirect()->route('approve-leave-requests.index')->with('success', 'Leave Request Updated Successfully');
        }
        return redirect()->route('approve-leave-requests.index')->with('danger', 'Failed to Leave Request');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LeaveRequest  $leaveRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, LeaveRequest $leaveRequest)
    {
        if ($request->user()->cannot('delete', $leaveRequest)){
            return back()->with('warning', 'Not Authorized');
        }

        $leaveRequest->delete();
        return redirect()->route('approve-leave-requests.index')->with('success', 'Leave Requests deleted successfuly');
    }
}
