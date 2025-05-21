<?php

namespace App\Listeners;

use App\Events\TicketRestored;
use Illuminate\Support\Facades\Log;
use App\Notifications\TicketRestoredNotification;

class SendTicketRestoredNotification
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
    public function handle(TicketRestored $event): void
    {
        Log::info('Ticket restored: ' . $event->ticket->id);

        $event->ticket->user->notify(
            new TicketRestoredNotification($event->ticket)
        );

        if($event->ticket->assigned_to &&
            $event->ticket->assigned_to !== $event->ticket->user_id
        ){
            $event->ticket->assignedTo->notify(
                new TicketRestoredNotification($event->ticket)
            );
        }
    }
}
