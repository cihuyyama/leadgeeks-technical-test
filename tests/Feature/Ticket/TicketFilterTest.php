<?php

namespace Tests\Feature\Ticket;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_filters_tickets_by_title_and_assignee(): void
    {
        $user = User::factory()->create();

        Ticket::factory()->create([
            'title' => 'VPN cannot connect',
            'assigned_person' => 'Ayu',
            'status' => 'Open',
            'priority' => 'High',
        ]);
        Ticket::factory()->create([
            'title' => 'Printer jam',
            'assigned_person' => 'Budi',
            'status' => 'Open',
            'priority' => 'Low',
        ]);

        $this->actingAs($user)
            ->get(route('dashboard', ['search' => 'VPN']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('tickets/Index')
                ->has('tickets', 1)
                ->where('tickets.0.title', 'VPN cannot connect')
                ->where('filters.search', 'VPN')
                ->where('resultCount', 1)
                ->where('stats.total', 2));
    }

    public function test_status_filter_limits_list_but_stats_stay_global(): void
    {
        $user = User::factory()->create();

        Ticket::factory()->create(['status' => 'Open', 'priority' => 'Low', 'title' => 'Open A']);
        Ticket::factory()->create(['status' => 'In Progress', 'priority' => 'High', 'title' => 'Progress B']);
        Ticket::factory()->create(['status' => 'Closed', 'priority' => 'Medium', 'title' => 'Closed C']);

        $this->actingAs($user)
            ->get(route('dashboard', ['status' => 'Open']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('tickets', 1)
                ->where('tickets.0.status', 'Open')
                ->where('filters.status', 'Open')
                ->where('stats.total', 3)
                ->where('stats.open', 1)
                ->where('stats.in_progress', 1)
                ->where('stats.high_priority', 1));
    }

    public function test_priority_and_category_filters_combine(): void
    {
        $user = User::factory()->create();

        Ticket::factory()->create([
            'title' => 'Match',
            'priority' => 'High',
            'category' => 'Network',
            'status' => 'Open',
        ]);
        Ticket::factory()->create([
            'title' => 'Wrong priority',
            'priority' => 'Low',
            'category' => 'Network',
            'status' => 'Open',
        ]);
        Ticket::factory()->create([
            'title' => 'Wrong category',
            'priority' => 'High',
            'category' => 'Hardware',
            'status' => 'Open',
        ]);

        $this->actingAs($user)
            ->get(route('dashboard', [
                'priority' => 'High',
                'category' => 'Network',
            ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('tickets', 1)
                ->where('tickets.0.title', 'Match')
                ->where('filters.priority', 'High')
                ->where('filters.category', 'Network'));
    }

    public function test_sort_by_priority_puts_high_first_when_ascending_severity_order(): void
    {
        $user = User::factory()->create();

        Ticket::factory()->create(['title' => 'Low ticket', 'priority' => 'Low', 'status' => 'Open']);
        Ticket::factory()->create(['title' => 'High ticket', 'priority' => 'High', 'status' => 'Open']);
        Ticket::factory()->create(['title' => 'Medium ticket', 'priority' => 'Medium', 'status' => 'Open']);

        $this->actingAs($user)
            ->get(route('dashboard', [
                'sort' => 'priority',
                'direction' => 'asc',
            ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('tickets', 3)
                ->where('tickets.0.priority', 'High')
                ->where('tickets.1.priority', 'Medium')
                ->where('tickets.2.priority', 'Low')
                ->where('filters.sort', 'priority')
                ->where('filters.direction', 'asc'));
    }

    public function test_sort_by_created_at_desc_is_default(): void
    {
        $user = User::factory()->create();

        $older = Ticket::factory()->create([
            'title' => 'Older',
            'created_at' => now()->subDays(2),
        ]);
        $newer = Ticket::factory()->create([
            'title' => 'Newer',
            'created_at' => now()->subHour(),
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('tickets.0.id', $newer->id)
                ->where('tickets.1.id', $older->id)
                ->where('filters.sort', 'created_at')
                ->where('filters.direction', 'desc'));
    }

    public function test_search_matches_notes(): void
    {
        $user = User::factory()->create();

        Ticket::factory()->create([
            'title' => 'Access request',
            'notes' => 'Need shared drive for Finance Q3',
            'status' => 'Open',
        ]);
        Ticket::factory()->create([
            'title' => 'Other',
            'notes' => null,
            'status' => 'Open',
        ]);

        $this->actingAs($user)
            ->get(route('dashboard', ['search' => 'Finance Q3']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('tickets', 1)
                ->where('tickets.0.title', 'Access request'));
    }
}
