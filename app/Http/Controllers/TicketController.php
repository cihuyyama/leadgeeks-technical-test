<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Http\Requests\Ticket\UpdateTicketRequest;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TicketController extends Controller
{
    /**
     * Display the ticket dashboard with list and summary stats.
     */
    public function index(): Response
    {
        $tickets = Ticket::query()
            ->latest()
            ->get();

        $stats = [
            'total' => $tickets->count(),
            'open' => $tickets->where('status', 'Open')->count(),
            'in_progress' => $tickets->where('status', 'In Progress')->count(),
            'high_priority' => $tickets->where('priority', 'High')->count(),
        ];

        return Inertia::render('tickets/Index', [
            'tickets' => $tickets,
            'stats' => $stats,
        ]);
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create(): Response
    {
        return Inertia::render('tickets/Create');
    }

    /**
     * Store a newly created ticket.
     */
    public function store(StoreTicketRequest $request): RedirectResponse
    {
        Ticket::query()->create($request->validated());

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Ticket created.',
        ]);

        return to_route('dashboard');
    }

    /**
     * Show the form for editing the specified ticket.
     */
    public function edit(Ticket $ticket): Response
    {
        return Inertia::render('tickets/Edit', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * Update the specified ticket.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        $ticket->update($request->validated());

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Ticket updated.',
        ]);

        return to_route('dashboard');
    }

    /**
     * Remove the specified ticket.
     */
    public function destroy(Ticket $ticket): RedirectResponse
    {
        $ticket->delete();

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Ticket deleted.',
        ]);

        return to_route('dashboard');
    }
}
