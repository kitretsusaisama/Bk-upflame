<?php

namespace App\Policies;

use App\Domains\Booking\Models\Booking;
use App\Domain\Identity\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
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
        // Users can view their own bookings
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Domain\Identity\Models\User  $user
     * @param  \App\Domain\Booking\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Booking $booking)
    {
        // Users can view their own bookings or admins can view any booking in their tenant
        return $user->id === $booking->user_id || 
               ($user->hasRole('admin') && $user->tenant_id === $booking->tenant_id);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Domain\Identity\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // Any authenticated user can create bookings
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Domain\Identity\Models\User  $user
     * @param  \App\Domain\Booking\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Booking $booking)
    {
        // Users can update their own bookings if they have the right permissions
        // or admins can update any booking in their tenant
        return $user->id === $booking->user_id || 
               ($user->hasRole('admin') && $user->tenant_id === $booking->tenant_id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Domain\Identity\Models\User  $user
     * @param  \App\Domain\Booking\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Booking $booking)
    {
        // Users can delete their own bookings or admins can delete any booking in their tenant
        return $user->id === $booking->user_id || 
               ($user->hasRole('admin') && $user->tenant_id === $booking->tenant_id);
    }
}