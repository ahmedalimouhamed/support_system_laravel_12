<?php

namespace App\Listeners;

use App\Events\TicketAssigned;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Notifications\TicketAssignedNotification;

class SendTicketAssignedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TicketAssigned $event): void
    {
        Log::info("Listener SendTicketAssignedNotification : handeling event");
        if($event->ticket->assigned_to){
            $assignedTo = User::find($event->ticket->assigned_to);
            if($assignedTo){
                $assignedTo->notify(
                    new TicketAssignedNotification($event->ticket)
                );
            }
        }

        if($event->previousAgentId){
            $previousAgent = User::find($event->previousAgentId);
            $previousAgent->notify(
                new TicketAssignedNotification($event->ticket, false)
            );
        }
    }
}
