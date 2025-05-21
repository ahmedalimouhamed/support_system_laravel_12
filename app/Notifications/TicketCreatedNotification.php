<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;

class TicketCreatedNotification extends Notification
{
    use Queueable;

    public $ticket;
    public $isOwner;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket, bool $isOwner = false)
    {
        $this->ticket = $ticket;
        $this->isOwner = $isOwner;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        Log::info("TicketCreatedNotification : toDatabase");
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_title' => $this->ticket->title,
            'message' => $this->isOwner 
                ? 'Votre ticket a été créé' 
                : 'Un nouveau ticket vous a été assigné',
            'link' => route('tickets.show', $this->ticket->id),
        ];
    }
}
