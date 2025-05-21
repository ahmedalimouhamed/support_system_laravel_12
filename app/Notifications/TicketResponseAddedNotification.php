<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\TicketResponse;
use Illuminate\Support\Facades\Log;

class TicketResponseAddedNotification extends Notification
{
    use Queueable;

    public $response;

    /**
     * Create a new notification instance.
     */
    public function __construct(TicketResponse $response)
    {
        $this->response = $response;
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
        Log::info("TicketResponseAddedNotification : toDatabase");
        return [
            'ticket_id' => $this->response->ticket_id,
            'ticket_title' => $this->response->ticket->title,
            'response_id' => $this->response->id,
            'message' => 'Nouvelle réponse de ' . $this->response->user->name,
            'link' => route('tickets.show', $this->response->ticket_id),
        ];
    }
}
