<?php

namespace App\Http\Controllers;

use App\Models\TicketResponse;
use App\Http\Requests\StoreTicketResponseRequest;
use App\Http\Requests\UpdateTicketResponseRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Ticket;
use App\Events\ResponseAdded;

class TicketResponseController extends Controller
{
    use AuthorizesRequests;

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketResponseRequest $request, Ticket $ticket)
    {
        $this->authorize('respond', $ticket);

        $response = $ticket->responses()->create([
            'user_id' => auth()->user()->id,
            'content' => $request->content
        ]);

        event(new ResponseAdded($response));

        return back()->with('success', 'Réponse ajoutée avec succès');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketResponseRequest $request, TicketResponse $response)
    {
        $this->authorize('update', $response);

        $response->update($request->validated());

        return back()->with('success', 'Réponse mise à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketResponse $response)
    {
        $this->authorize('delete', $response);

        $response->delete();

        return back()->with('success', 'Réponse supprimée avec succès');
    }
}
