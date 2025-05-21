<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'agent', 'user']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('admin') || 
            $user->hasRole('agent') || 
            $user->id === $ticket->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('user');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('admin') || 
            $user->hasRole('agent') || 
            $user->id === $ticket->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function assign(User $user, Ticket $ticket): bool
    {
        return $user->hasAnyRole(['admin', 'agent']);
    }

    public function respond(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('admin') || 
            $user->assignrd_to === $user->id ||
            $ticket->user_id === $user->id;
    }

    public function restore(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('admin');
    }
    

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('admin');
    }
}
