<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Seed realistic IT support tickets (20 rows → 2 pages at 10/page).
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
            [
                'title' => 'Monitor flickering on docking station',
                'category' => 'Hardware',
                'priority' => 'Medium',
                'status' => 'Open',
                'assigned_person' => 'Casey Nguyen',
                'notes' => 'Happens only when laptop is docked; cable and dock firmware check next.',
            ],
            [
                'title' => 'Password reset locked after failed attempts',
                'category' => 'Access',
                'priority' => 'High',
                'status' => 'Resolved',
                'assigned_person' => 'Morgan Diaz',
                'notes' => 'Unlocked AD account and walked user through MFA re-enrollment.',
            ],
            [
                'title' => 'Slack desktop app crashes on launch',
                'category' => 'Software',
                'priority' => 'Low',
                'status' => 'In Progress',
                'assigned_person' => 'Taylor Brooks',
                'notes' => 'Clearing app cache and reinstalling latest build.',
            ],
            [
                'title' => 'Office 365 license expired for contractor',
                'category' => 'Access',
                'priority' => 'Medium',
                'status' => 'Open',
                'assigned_person' => 'Jordan Lee',
                'notes' => 'Need temporary E3 seat until contract end date confirmed.',
            ],
            [
                'title' => 'Ethernet port dead on reception desk PC',
                'category' => 'Hardware',
                'priority' => 'Medium',
                'status' => 'Open',
                'assigned_person' => 'Alex Rivera',
                'notes' => 'USB-C NIC as interim; motherboard NIC likely failed.',
            ],
            [
                'title' => 'Company website SSL certificate expiring soon',
                'category' => 'Network',
                'priority' => 'High',
                'status' => 'In Progress',
                'assigned_person' => 'Sam Chen',
                'notes' => 'Renewal ticket with registrar; cutover planned this weekend.',
            ],
            [
                'title' => 'Request dual monitors for finance analyst',
                'category' => 'Hardware',
                'priority' => 'Low',
                'status' => 'Closed',
                'assigned_person' => 'Casey Nguyen',
                'notes' => 'Two 27" displays installed and calibrated.',
            ],
            [
                'title' => 'GitLab CI runners offline after power outage',
                'category' => 'Software',
                'priority' => 'High',
                'status' => 'Resolved',
                'assigned_person' => 'Sam Chen',
                'notes' => 'Brought runners back online; pipeline queue cleared.',
            ],
            [
                'title' => 'Guest Wi-Fi captive portal not loading',
                'category' => 'Network',
                'priority' => 'Low',
                'status' => 'Open',
                'assigned_person' => 'Taylor Brooks',
                'notes' => 'DNS redirect intermittent; checking controller config.',
            ],
            [
                'title' => 'Backup job failed for file server overnight',
                'category' => 'Other',
                'priority' => 'High',
                'status' => 'In Progress',
                'assigned_person' => 'Morgan Diaz',
                'notes' => 'Storage target full; expanding volume and re-running job.',
            ],
        ];

        foreach ($tickets as $ticket) {
            Ticket::query()->create($ticket);
        }
    }
}
