<?php

namespace App\Listeners;

use App\Events\TicketStatusChanged;
use App\Notifications\TicketStatusChangedNotification;
use Illuminate\Support\Facades\Log;

class SendTicketStatusChangedNotification
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
    public function handle(TicketStatusChanged $event): void
    {
        Log::info("Listener SendTicketStatusChangedNotification : handeling event");
        $event->ticket->user->notify(
            new TicketStatusChangedNotification(
                $event->ticket,
                $event->previousStatus
            )
        );

        if($event->ticket->assigned_to && $event->ticket->assignedTo !== $event->ticket->user_id){
            $event->ticket->assignedTo->notify(
                new TicketStatusChangedNotification(
                    $event->ticket,
                    $event->previousStatus
                )
            );
        }
    }
}
