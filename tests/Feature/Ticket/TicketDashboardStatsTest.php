<?php

namespace Tests\Feature\Ticket;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketDashboardStatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_dashboard(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_home_redirects_guests_to_login(): void
    {
        $this->get(route('home'))
            ->assertRedirect(route('login'));
    }

    public function test_home_redirects_authenticated_users_to_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('home'))
            ->assertRedirect(route('dashboard'));
    }

    public function test_dashboard_lists_tickets_and_stats(): void
    {
        $user = User::factory()->create();

        Ticket::factory()->create([
            'title' => 'Open high ticket',
            'status' => 'Open',
            'priority' => 'High',
        ]);
        Ticket::factory()->create([
            'title' => 'In progress medium',
            'status' => 'In Progress',
            'priority' => 'Medium',
        ]);
        Ticket::factory()->create([
            'title' => 'Resolved high',
            'status' => 'Resolved',
            'priority' => 'High',
        ]);
        Ticket::factory()->create([
            'title' => 'Closed low',
            'status' => 'Closed',
            'priority' => 'Low',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('tickets/Index')
                ->has('tickets', 4)
                ->has('stats')
                ->where('stats.total', 4)
                ->where('stats.open', 1)
                ->where('stats.in_progress', 1)
                ->where('stats.high_priority', 2));
    }

    public function test_dashboard_stats_are_zero_when_no_tickets(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('tickets/Index')
                ->has('tickets', 0)
                ->where('stats.total', 0)
                ->where('stats.open', 0)
                ->where('stats.in_progress', 0)
                ->where('stats.high_priority', 0));
    }

    public function test_high_priority_counts_all_statuses(): void
    {
        $user = User::factory()->create();

        Ticket::factory()->create(['status' => 'Open', 'priority' => 'High']);
        Ticket::factory()->create(['status' => 'In Progress', 'priority' => 'High']);
        Ticket::factory()->create(['status' => 'Resolved', 'priority' => 'High']);
        Ticket::factory()->create(['status' => 'Closed', 'priority' => 'High']);
        Ticket::factory()->create(['status' => 'Open', 'priority' => 'Low']);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('stats.total', 5)
                ->where('stats.open', 2)
                ->where('stats.in_progress', 1)
                ->where('stats.high_priority', 4));
    }
}
