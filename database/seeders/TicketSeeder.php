<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Seed realistic IT support tickets.
     */
    public function run(): void
    {
        $tickets = [
            [
                'title' => 'Laptop will not boot after Windows update',
                'category' => 'Hardware',
                'priority' => 'High',
                'status' => 'Open',
                'assigned_person' => 'Alex Rivera',
                'notes' => 'User stuck on black screen after overnight patch. On-site needed.',
            ],
            [
                'title' => 'VPN disconnects every hour for remote staff',
                'category' => 'Network',
                'priority' => 'High',
                'status' => 'In Progress',
                'assigned_person' => 'Sam Chen',
                'notes' => 'Gateway logs show idle timeout mismatch. Testing new profile.',
            ],
            [
                'title' => 'Unable to access shared drive Finance$',
                'category' => 'Access',
                'priority' => 'Medium',
                'status' => 'Open',
                'assigned_person' => 'Jordan Lee',
                'notes' => 'Permissions group missing after org restructure.',
            ],
            [
                'title' => 'Outlook not syncing calendar invites',
                'category' => 'Software',
                'priority' => 'Medium',
                'status' => 'In Progress',
                'assigned_person' => 'Taylor Brooks',
                'notes' => 'Cached credentials cleared; waiting on Microsoft 365 health.',
            ],
            [
                'title' => 'New hire needs AD account and MFA setup',
                'category' => 'Access',
                'priority' => 'High',
                'status' => 'Open',
                'assigned_person' => 'Morgan Diaz',
                'notes' => 'Start date Monday. Laptop imaged and ready for handoff.',
            ],
            [
                'title' => 'Printer on Floor 3 jamming constantly',
                'category' => 'Hardware',
                'priority' => 'Low',
                'status' => 'Resolved',
                'assigned_person' => 'Casey Nguyen',
                'notes' => 'Replaced pickup rollers; test pages clean.',
            ],
            [
                'title' => 'Zoom audio cuts out in conference room B',
                'category' => 'Hardware',
                'priority' => 'Medium',
                'status' => 'Closed',
                'assigned_person' => 'Alex Rivera',
                'notes' => 'Faulty USB audio interface replaced. Room retested OK.',
            ],
            [
                'title' => 'Request admin rights for Adobe Creative Cloud',
                'category' => 'Software',
                'priority' => 'Low',
                'status' => 'Open',
                'assigned_person' => 'Jordan Lee',
                'notes' => 'Manager approved for Marketing designer role.',
            ],
            [
                'title' => 'Phishing email reported by Sales team',
                'category' => 'Other',
                'priority' => 'High',
                'status' => 'In Progress',
                'assigned_person' => 'Sam Chen',
                'notes' => 'Message quarantined. Checking if any links were clicked.',
            ],
            [
                'title' => 'Wi-Fi dead spots near warehouse loading dock',
                'category' => 'Network',
                'priority' => 'Medium',
                'status' => 'Open',
                'assigned_person' => 'Taylor Brooks',
                'notes' => 'Site survey scheduled; temporary AP ordered.',
            ],
        ];

        foreach ($tickets as $ticket) {
            Ticket::query()->create($ticket);
        }
    }
}
