<?php

namespace App\Listeners;

use App\Events\ResponseAdded;
use App\Models\User;
use App\Notifications\TicketResponseAddedNotification;
use Illuminate\Support\Facades\Log;

class SendResponseAddedNotification
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
        Log::info("Listener SendResponseAddedNotification : handeling event");
        $response = $event->response;
        $ticket = $response->ticket;
        $responder = $response->user;

        $participations = $ticket->responses->pluck('user_id')
            ->push($ticket->user_id)
            ->unique()
            ->filter(fn($id) => $id !== $responder->id);

        User::whereIn('id', $participations)->get()->each(function ($user) use ($response) {
            $user->notify(new TicketResponseAddedNotification($response));
        });
    }
}
