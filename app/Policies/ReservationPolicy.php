<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReservationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Reservation $reservation): bool
    {
        return $user->id === $reservation->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    /**
     * Determine whether the user or admin can update the model.
     *
     * @param  \App\Models\User|\App\Models\Admin  $actor
     * @param  Reservation  $reservation
     */
    public function update($actor, Reservation $reservation): bool
    {
        // Jika Admin
        if ($actor instanceof \App\Models\Admin) {
            if ($actor->role === 'superadmin') {
                return true;
            }
            return $actor->instansi_id === $reservation->roomInfo->instansi_id;
        }
        // Jika User
        if ($actor instanceof \App\Models\User) {
            return false;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Reservation $reservation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Reservation $reservation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Reservation $reservation): bool
    {
        return false;
    }
}
