<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\ResidenceAddress;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResidenceAddressPolicy
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
     * @param  \App\Models\ResidenceAddress  $residenceAddress
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ResidenceAddress $residenceAddress, Employee $employee)
    {
        if ($user->can('view-residence') or $employee->user_id == $user->id){
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
        if ($user->can('create-residence') or $employee->user_id == $user->id){
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ResidenceAddress  $residenceAddress
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ResidenceAddress $residenceAddress, Employee $employee)
    {
        if ($user->can('update-residence') or $employee->user_id == $user->id){
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ResidenceAddress  $residenceAddress
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ResidenceAddress $residenceAddress, Employee $employee)
    {
        if ($user->can('delete-residence') or $employee->user_id == $user->id){
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ResidenceAddress  $residenceAddress
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ResidenceAddress $residenceAddress)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ResidenceAddress  $residenceAddress
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ResidenceAddress $residenceAddress)
    {
        //
    }
}
