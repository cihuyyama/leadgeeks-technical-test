<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Http\Requests\Ticket\UpdateTicketRequest;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TicketController extends Controller
{
    /**
     * Display the ticket dashboard with list, filters, and summary stats.
     *
     * Stats always reflect the full dataset (not the current filters).
     * The list is filtered/sorted via query string.
     */
    public function index(Request $request): Response
    {
        $filters = [
            'search' => $request->string('search')->trim()->toString(),
            'status' => $request->string('status')->toString() ?: 'all',
            'priority' => $request->string('priority')->toString() ?: 'all',
            'category' => $request->string('category')->toString() ?: 'all',
            'sort' => $request->string('sort')->toString() ?: 'created_at',
            'direction' => strtolower($request->string('direction')->toString() ?: 'desc') === 'asc'
                ? 'asc'
                : 'desc',
        ];

        $allowedSorts = ['created_at', 'title', 'status', 'priority', 'category', 'assigned_person'];
        if (! in_array($filters['sort'], $allowedSorts, true)) {
            $filters['sort'] = 'created_at';
        }

        $tickets = Ticket::query()
            ->search($filters['search'])
            ->status($filters['status'])
            ->priority($filters['priority'])
            ->category($filters['category'])
            ->sortedBy($filters['sort'], $filters['direction'])
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Ticket::query()->count(),
            'open' => Ticket::query()->where('status', 'Open')->count(),
            'in_progress' => Ticket::query()->where('status', 'In Progress')->count(),
            'high_priority' => Ticket::query()->where('priority', 'High')->count(),
        ];

        return Inertia::render('tickets/Index', [
            'tickets' => $tickets,
            'stats' => $stats,
            'filters' => $filters,
            'resultCount' => $tickets->total(),
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
