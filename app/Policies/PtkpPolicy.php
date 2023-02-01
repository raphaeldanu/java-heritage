<?php

namespace App\Policies;

use App\Models\Ptkp;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PtkpPolicy
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
        return $user->can('view-all-ptkp');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ptkp  $ptkp
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Ptkp $ptkp)
    {
        return $user->can('view-ptkp');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create-ptkp');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ptkp  $ptkp
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Ptkp $ptkp)
    {
        return $user->can('update-ptkp');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ptkp  $ptkp
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Ptkp $ptkp)
    {
        return $user->can('delete-ptkp');
    }
}
