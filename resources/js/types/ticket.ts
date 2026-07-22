export type TicketStatus = 'Open' | 'In Progress' | 'Resolved' | 'Closed';
export type TicketPriority = 'Low' | 'Medium' | 'High';
export type TicketCategory =
    | 'Hardware'
    | 'Software'
    | 'Network'
    | 'Access'
    | 'Other';

export type Ticket = {
    id: number;
    title: string;
    category: TicketCategory | string;
    priority: TicketPriority | string;
    status: TicketStatus | string;
    assigned_person: string;
    notes: string | null;
    created_at: string;
    updated_at: string;
};

export type TicketStats = {
    total: number;
    open: number;
    in_progress: number;
    high_priority: number;
};

export const TICKET_CATEGORIES: TicketCategory[] = [
    'Hardware',
    'Software',
    'Network',
    'Access',
    'Other',
];

export const TICKET_PRIORITIES: TicketPriority[] = ['Low', 'Medium', 'High'];

export const TICKET_STATUSES: TicketStatus[] = [
    'Open',
    'In Progress',
    'Resolved',
    'Closed',
];
