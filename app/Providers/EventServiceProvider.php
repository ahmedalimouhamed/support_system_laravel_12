<?php

namespace App\Providers;

use App\Events\ResponseAdded;
use App\Events\TicketAssigned;
use App\Events\TicketCreated;
use App\Events\TicketRestored;
use App\Events\TicketStatusChanged;
use Illuminate\Support\ServiceProvider;
use App\Listeners\SendTicketCreatedNotification;
use App\Listeners\SendTicketAssignedNotification;
use App\Listeners\SendResponseAddedNotification;
use App\Listeners\SendTicketStatusChangedNotification;
use App\Listeners\SendTicketRestoredNotification;
use App\Listeners\UpdateTicketStatusOnResponse;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        TicketCreated::class => [
            SendTicketCreatedNotification::class,
        ],
        TicketAssigned::class => [
            SendTicketAssignedNotification::class,
        ],
        ResponseAdded::class => [
            SendResponseAddedNotification::class,
            UpdateTicketStatusOnResponse::class,
        ],
        TicketStatusChanged::class => [
            SendTicketStatusChangedNotification::class,
        ],
        TicketRestored::class => [
            SendTicketRestoredNotification::class,
        ],
    ];
    
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
