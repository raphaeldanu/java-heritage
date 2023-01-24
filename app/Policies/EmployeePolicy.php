<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Employee $employee)
    {
        if ($user->can('view-employee-detail')) {
            return true;
        }

        if ($user->id == $employee->user_id) {
            return true;
        }
        return false;
    }
    
    /**
     * Determine whether the user can create the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, string $mode)
    {
        if ($mode == 'my-data') {
            if (isset($user->employee)) {
                return false;
            }
            return true;
        } elseif ($mode == 'employees'){
            if ($user->can('create-employees')) {
                return true;
            }
            return false;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Employee $employee)
    {
        if ($user->can('update-employees')) {
            return true;
        }

        if ($user->id == $employee->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Employee $employee)
    {
        if ($user->cannot('delete-employee')) {
            return false;
        }

        return true;
    }
}
