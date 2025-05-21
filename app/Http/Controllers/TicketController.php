<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Events\TicketCreated;
use App\Events\TicketStatusChanged;
use App\Events\TicketAssigned;
use App\Models\User;

class TicketController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Ticket::class);

        $tickets = Ticket::with(['user', 'assignedTo', 'category'])
            ->when(auth()->user()->hasRole('user'), function($query){
                return $query->where('user_id', auth()->id());
            })
            ->when(auth()->user()->hasRole('agent'), function($query){
                return $query->where('assigned_to', auth()->id());
            })
            ->latest()
            ->get()
            ->addResponseCount();

        return view('tickets.index', [
            'tickets' => $tickets->groupByStatus()
        ]);
    }

    public function create()
    {
        $this->authorize('create', Ticket::class);

        return view('tickets.create', [
            'categories' => Category::all(),
            'priorities' => ['low', 'medium', 'high', 'critical']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        $ticket = $request->user()->tickets()->create($request->validated());

        event(new TicketCreated($ticket));

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket créé avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        return view('tickets.show', [
            'ticket' => $ticket->load(['responses.user', 'user', 'assignedTo', 'category']),
            'canRespond' => auth()->user()->can('respond', $ticket)
        ]);
    }

    public function edit(Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        return view('tickets.edit', [
            'ticket' => $ticket,
            'categories' => Category::all(),
            'priorities' => ['low', 'medium', 'high', 'critical'],
            'statuses' => ['open', 'in_progress', 'resolved', 'closed'],
            'agents' => User::role('agent')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $previousStatus = $ticket->status;
        $previousAgentId = $ticket->assigned_to;

        $ticket->update($request->validated());

        if($ticket->wasChanged('status')){
            event(new TicketStatusChanged($ticket, $previousStatus));
        }

        if($ticket->wasChanged('assigned_to')){
            event(new TicketAssigned($ticket, $previousAgentId));
        }

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $this->authorize('delete', $ticket);

        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket supprimé avec succès');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $previousAgentId = $ticket->assigned_to;
        $ticket->update([
            'assigned_to' => $request->agent_id
        ]);

        event(new TicketAssigned($ticket, $previousAgentId));

        return back()->with('success', 'Ticket assigné avec succès');
    }

    public function restore(Ticket $ticket)
    {
        $this->authorize('restore', $ticket);

        $ticket->restore();

        return redirect()->route('tickets.index')->with('success', 'Ticket restauré avec succès');
    }

    public function forceDelete(Ticket $ticket)
    {
        $this->authorize('forceDelete', $ticket);

        $ticket->forceDelete();

        return redirect()->route('tickets.index')->with('success', 'Ticket supprimé définitivement avec succès');
    }
}
