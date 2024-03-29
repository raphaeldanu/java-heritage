<?php

namespace App\Policies;

use App\Models\TrainingMenu;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrainingMenuPolicy
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
        return $user->can('view-all-training-menus');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TrainingMenu  $trainingMenu
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, TrainingMenu $trainingMenu)
    {
        return $user->can('view-training-menus');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create-training-menus');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TrainingMenu  $trainingMenu
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, TrainingMenu $trainingMenu)
    {
        return $user->can('update-training-menus');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TrainingMenu  $trainingMenu
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, TrainingMenu $trainingMenu)
    {
        return $user->can('delete-training-menus');
    }
}
