<?php

namespace App\Observers;

use App\Domain\Identity\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Domain\Identity\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        // Handle user creation
        // For example, create a user profile automatically
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Domain\Identity\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        // Handle user update
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Domain\Identity\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        // Handle user deletion
        // For example, delete associated profile and other related data
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Domain\Identity\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        // Handle user restoration
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Domain\Identity\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        // Handle force deletion
    }
}