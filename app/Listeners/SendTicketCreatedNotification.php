<?php

namespace App\Listeners;

use App\Events\TicketCreated;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TicketCreatedNotification;
use Illuminate\Support\Facades\Log;

class SendTicketCreatedNotification
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
    public function handle(TicketCreated $event): void
    {
        Log::info("Listener SendTicketCreatedNotification : handeling event");
        $admins = User::role('admin')->get();
        Notification::send($admins, new TicketCreatedNotification($event->ticket));
        $event->ticket->user->notify(new TicketCreatedNotification($event->ticket, true));
    }
}
