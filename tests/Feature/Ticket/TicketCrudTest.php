<?php

namespace Tests\Feature\Ticket;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Laptop will not boot',
            'category' => 'Hardware',
            'priority' => 'High',
            'status' => 'Open',
            'assigned_person' => 'Alex IT',
            'notes' => 'User reports black screen after power-on.',
        ], $overrides);
    }

    public function test_guests_cannot_access_ticket_create_page(): void
    {
        $this->get(route('tickets.create'))
            ->assertRedirect(route('login'));
    }

    public function test_guests_cannot_store_tickets(): void
    {
        $this->post(route('tickets.store'), $this->validPayload())
            ->assertRedirect(route('login'));

        $this->assertDatabaseCount('tickets', 0);
    }

    public function test_authenticated_users_can_view_create_form(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('tickets.create'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('tickets/Create'));
    }

    public function test_authenticated_users_can_store_a_ticket(): void
    {
        $user = User::factory()->create();
        $payload = $this->validPayload();

        $response = $this->actingAs($user)
            ->post(route('tickets.store'), $payload);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('tickets', [
            'title' => $payload['title'],
            'category' => $payload['category'],
            'priority' => $payload['priority'],
            'status' => $payload['status'],
            'assigned_person' => $payload['assigned_person'],
            'notes' => $payload['notes'],
        ]);
    }

    public function test_store_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('tickets.create'))
            ->post(route('tickets.store'), []);

        $response
            ->assertSessionHasErrors([
                'title',
                'category',
                'priority',
                'status',
                'assigned_person',
            ])
            ->assertRedirect(route('tickets.create'));

        $this->assertDatabaseCount('tickets', 0);
    }

    public function test_store_validates_enum_values(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('tickets.create'))
            ->post(route('tickets.store'), $this->validPayload([
                'category' => 'Invalid',
                'priority' => 'Critical',
                'status' => 'Pending',
            ]));

        $response
            ->assertSessionHasErrors(['category', 'priority', 'status'])
            ->assertRedirect(route('tickets.create'));
    }

    public function test_notes_can_be_null_when_storing(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('tickets.store'), $this->validPayload([
                'notes' => null,
            ]))
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('tickets', [
            'title' => 'Laptop will not boot',
            'notes' => null,
        ]);
    }

    public function test_authenticated_users_can_view_edit_form(): void
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create();

        $this->actingAs($user)
            ->get(route('tickets.edit', $ticket))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('tickets/Edit')
                ->has('ticket')
                ->where('ticket.id', $ticket->id)
                ->where('ticket.title', $ticket->title));
    }

    public function test_authenticated_users_can_update_a_ticket(): void
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create([
            'title' => 'Old title',
            'status' => 'Open',
            'priority' => 'Low',
        ]);

        $payload = $this->validPayload([
            'title' => 'VPN drops every hour',
            'category' => 'Network',
            'priority' => 'Medium',
            'status' => 'In Progress',
            'assigned_person' => 'Sam Network',
            'notes' => 'Investigating gateway logs.',
        ]);

        $response = $this->actingAs($user)
            ->put(route('tickets.update', $ticket), $payload);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'title' => 'VPN drops every hour',
            'category' => 'Network',
            'priority' => 'Medium',
            'status' => 'In Progress',
            'assigned_person' => 'Sam Network',
            'notes' => 'Investigating gateway logs.',
        ]);
    }

    public function test_update_validates_required_fields(): void
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('tickets.edit', $ticket))
            ->put(route('tickets.update', $ticket), [
                'title' => '',
                'category' => '',
                'priority' => '',
                'status' => '',
                'assigned_person' => '',
            ]);

        $response
            ->assertSessionHasErrors([
                'title',
                'category',
                'priority',
                'status',
                'assigned_person',
            ])
            ->assertRedirect(route('tickets.edit', $ticket));
    }

    public function test_authenticated_users_can_delete_a_ticket(): void
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('tickets.destroy', $ticket));

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseMissing('tickets', ['id' => $ticket->id]);
    }

    public function test_guests_cannot_delete_tickets(): void
    {
        $ticket = Ticket::factory()->create();

        $this->delete(route('tickets.destroy', $ticket))
            ->assertRedirect(route('login'));

        $this->assertDatabaseHas('tickets', ['id' => $ticket->id]);
    }
}
