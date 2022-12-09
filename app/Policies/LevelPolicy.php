<?php

namespace App\Policies;

use App\Models\Level;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LevelPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Level  $level
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Level $level)
    {
        if ($user->can('update-levels')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Level  $level
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Level $level)
    {
        if ($user->can('delete-levels')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Level  $level
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Level $level)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Level  $level
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Level $level)
    {
        //
    }
}
