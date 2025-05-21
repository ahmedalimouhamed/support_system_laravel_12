<?php

namespace App\Listeners;

use App\Events\ResponseAdded;
use App\Events\TicketStatusChanged;
use Illuminate\Support\Facades\Log;

class UpdateTicketStatusOnResponse
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
    public function handle(ResponseAdded $event): void
    {
        Log::info("Listener UpdateTicketStatusOnResponse : handeling event");
        $ticket = $event->response->ticket;

        if($event->response->user->hasRole('agent') && $ticket->status === 'open'){
            $previousStatus = $ticket->status;
            $ticket->status = 'in_progress';
            $ticket->save();

            event(new TicketStatusChanged($ticket, $previousStatus));
        }
    }
}
