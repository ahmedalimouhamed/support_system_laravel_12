<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;

class TicketStatusChangedNotification extends Notification
{
    use Queueable;

    public $ticket;
    public $previousStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket, $previousStatus)
    {
        $this->ticket = $ticket;
        $this->previousStatus = $previousStatus;
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
        Log::info("TicketStatusChangedNotification : toDatabase");
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_title' => $this->ticket->title,
            'previous_status' => $this->getStatusLabel($this->previousStatus),
            'new_status' => $this->getStatusLabel($this->ticket->status),
            'message' => "Le statut du ticket a changé de {$this->getStatusLabel($this->previousStatus)} à {$this->getStatusLabel($this->ticket->status)}",
            'link' => route('tickets.show', $this->ticket->id),
        ];
    }

    public function getStatusLabel($status)
    {
        $statusLabels = [
            'open' => 'Ouvert',
            'in_progress' => 'En cours',
            'resolved' => 'Résolu',
            'closed' => 'Fermé',
        ];
        return $statusLabels[$status] ?? $status;
    }
}
