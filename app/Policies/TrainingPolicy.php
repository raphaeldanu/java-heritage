<?php

namespace App\Policies;

use App\Models\Training;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrainingPolicy
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
        return $user->can('view-all-trainings');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Training  $training
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Training $training)
    {
        if ($training->employees->contains($user->employee->id)) {
            return true;
        }

        return $user->can('view-trainings');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create-trainings');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Training  $training
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Training $training)
    {
        return $user->can('update-trainings');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Training  $training
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Training $training)
    {
        return $user->can('delete-trainings');
    }
}
