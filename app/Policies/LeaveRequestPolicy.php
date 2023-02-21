<?php

namespace App\Policies;

use App\Enums\LeaveStatus;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaveRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @param  string $mode
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user, string $mode)
    {
        if ($mode == 'my-request') {
            return isset($user->employee);
        } elseif ($mode == 'other-request') {
            return $user->can('view-all-leave-requests') or $user->can('view-leave-requests');
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LeaveRequest  $leaveRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, LeaveRequest $leaveRequest)
    {
        if ($user->can('view-leave-requests') or $leaveRequest->employee->user_id == $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return isset($user->employee);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LeaveRequest  $leaveRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, LeaveRequest $leaveRequest)
    {
        return $leaveRequest->employee->user_id == $user->id and $leaveRequest->status == LeaveStatus::WaitingApproval;
    }

    /**
     * Determine whether the user can approve the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LeaveRequest  $leaveRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function approve(User $user, LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status != LeaveStatus::WaitingApproval){
            return false;
        }
        if ($user->id == $leaveRequest->employee->user_id){
            return false;
        }
        
        if ($user->can('approve-all-leave-requests')) {
            return true;
        }

        if ($user->can('approve-leave-requests')) {
            return $user->employee->position->department_id == $leaveRequest->employee->position->department_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LeaveRequest  $leaveRequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, LeaveRequest $leaveRequest)
    {
        if ($user->can('delete-leave-requests')){
            return true;
        }

        return $leaveRequest->status == LeaveStatus::WaitingApproval and $user->id == $leaveRequest->employee->user_id;
    }
}
