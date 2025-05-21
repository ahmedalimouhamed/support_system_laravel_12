<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class TicketCollection extends Collection
{
    public function groupByStatus()
    {
        return $this->groupBy(function ($ticket) {
            return $ticket->status;
        });
    }

    public function countByPriority()
    {
        return $this->countBy(function ($ticket) {
            return $ticket->priority;
        });
    }

    public function addResponseCount()
    {
        return $this->each(function ($ticket) {
            $ticket->response_count = $ticket->responses->count();
        });
    }


}
