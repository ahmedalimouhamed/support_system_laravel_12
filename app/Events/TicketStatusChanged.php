<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Ticket;

class TicketStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticket;
    public $previousStatus;

    /**
     * Create a new event instance.
     */
    public function __construct(Ticket $ticket, $previousStatus=null)
    {
        $this->ticket = $ticket;
        $this->previousStatus = $previousStatus;
    }
}
