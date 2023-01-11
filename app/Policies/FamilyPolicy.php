<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\Family;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FamilyPolicy
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
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Family  $family
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Family $family)
    {
        if($user->can('view-families') or $family->employee->user_id == $user->id){
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
    public function create(User $user, Employee $employee)
    {
        if($user->can('create-families') or $employee->user_id == $user->id){
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Family  $family
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Family $family)
    {
        if($user->can('update-families') or $family->employee->user_id == $user->id){
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Family  $family
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Family $family)
    {
        if($user->can('delete-families') or $family->employee->user_id == $user->id){
            return true;
        }

        return false;
    }
}
