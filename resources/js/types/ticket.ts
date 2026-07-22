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

export type TicketSortField =
    | 'created_at'
    | 'title'
    | 'status'
    | 'priority'
    | 'category'
    | 'assigned_person';

export type TicketFilters = {
    search: string;
    status: string;
    priority: string;
    category: string;
    sort: TicketSortField | string;
    direction: 'asc' | 'desc' | string;
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

export const TICKET_SORT_OPTIONS: {
    value: TicketSortField;
    label: string;
}[] = [
    { value: 'created_at', label: 'Created date' },
    { value: 'priority', label: 'Priority' },
    { value: 'status', label: 'Status' },
    { value: 'title', label: 'Title' },
    { value: 'category', label: 'Category' },
    { value: 'assigned_person', label: 'Assignee' },
];
