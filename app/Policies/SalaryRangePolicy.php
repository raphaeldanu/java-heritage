<?php

namespace App\Policies;

use App\Models\SalaryRange;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalaryRangePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SalaryRange  $salaryRange
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, SalaryRange $salaryRange)
    {
        return $user->can('update-salary-ranges');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SalaryRange  $salaryRange
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, SalaryRange $salaryRange)
    {
        return $user->can('delete-salary-ranges');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SalaryRange  $salaryRange
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, SalaryRange $salaryRange)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SalaryRange  $salaryRange
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, SalaryRange $salaryRange)
    {
        //
    }
}
