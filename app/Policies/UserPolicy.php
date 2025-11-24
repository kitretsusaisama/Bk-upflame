<?php

namespace App\Policies;

use App\Domain\Identity\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Domain\Identity\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        // Users can view other users in their tenant
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Domain\Identity\Models\User  $user
     * @param  \App\Domain\Identity\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model)
    {
        // Users can view their own profile or users in their tenant
        return $user->tenant_id === $model->tenant_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Domain\Identity\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // Only admins can create users
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Domain\Identity\Models\User  $user
     * @param  \App\Domain\Identity\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model)
    {
        // Users can update their own profile or admins can update any user in their tenant
        return $user->id === $model->id || 
               ($user->hasRole('admin') && $user->tenant_id === $model->tenant_id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Domain\Identity\Models\User  $user
     * @param  \App\Domain\Identity\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model)
    {
        // Only admins can delete users and they can't delete themselves
        return $user->hasRole('admin') && 
               $user->tenant_id === $model->tenant_id && 
               $user->id !== $model->id;
    }
}