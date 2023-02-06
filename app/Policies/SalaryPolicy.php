<?php

namespace App\Policies;

use App\Models\Salary;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalaryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('view-all-salaries');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Salary $salary)
    {
        return $user->can('view-salaries');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create-salaries');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Salary $salary)
    {
        return $user->can('update-salaries');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Salary $salary)
    {
        return $user->can('delete-salaries');
    }
}
