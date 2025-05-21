<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;

class TicketAssignedNotification extends Notification
{
    use Queueable;

    public $ticket;
    public $isNewAssignment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket, bool $isNewAssignment = true)
    {
        $this->ticket = $ticket;
        $this->isNewAssignment = $isNewAssignment;
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
        Log::info("TicketAssignedNotification : toDatabase");
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_title' => $this->ticket->title,
            'message' => $this->isNewAssignment 
                ? 'Vous avez été assigné à un nouveau ticket' 
                : 'Vous avez été désassigné d\'un ticket',
            'link' => route('tickets.show', $this->ticket->id),
        ];
    }
}
