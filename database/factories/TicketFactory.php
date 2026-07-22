<?php

namespace Database\Factories;

use App\Enums\TicketCategory;
use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titles = [
            'Laptop will not boot after Windows update',
            'VPN disconnects every hour',
            'Unable to access shared drive Finance$',
            'Outlook not syncing calendar invites',
            'New hire needs AD account and MFA setup',
            'Printer on Floor 3 jamming constantly',
            'Zoom audio cuts out in conference room B',
            'Request admin rights for design software',
            'Phishing email reported by Sales',
            'Wi-Fi dead spots near warehouse dock',
            'Monitor flickering on docking station',
            'Password reset locked after failed attempts',
        ];

        $people = [
            'Alex Rivera',
            'Sam Chen',
            'Jordan Lee',
            'Taylor Brooks',
            'Morgan Diaz',
            'Casey Nguyen',
        ];

        return [
            'title' => fake()->randomElement($titles),
            'category' => fake()->randomElement(TicketCategory::values()),
            'priority' => fake()->randomElement(TicketPriority::values()),
            'status' => fake()->randomElement(TicketStatus::values()),
            'assigned_person' => fake()->randomElement($people),
            'notes' => fake()->optional(0.7)->sentence(12),
        ];
    }
}
