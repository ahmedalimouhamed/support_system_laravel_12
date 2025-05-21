<?php

namespace App\Policies;

use App\Models\TicketResponse;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketResponsePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TicketResponse $response): bool
    {
        return $user->hasRole('admin') ||
            $response->ticket->user_id === $user->id ||
            $response->ticket->assigned_to === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TicketResponse $response): bool
    {
        return $user->id === $response->ticket->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TicketResponse $response): bool
    {
        return $user->hasRole('admin') || $user->id === $response->ticket->user_id;
    }
}
