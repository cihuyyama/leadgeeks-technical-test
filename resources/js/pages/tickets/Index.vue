<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight, Plus, Search, X } from '@lucide/vue';
import { motion } from 'motion-v';
import { computed, ref, watch } from 'vue';
import Heading from '@/components/Heading.vue';
import PriorityBadge from '@/components/tickets/PriorityBadge.vue';
import StatusBadge from '@/components/tickets/StatusBadge.vue';
import TicketDetailDialog from '@/components/tickets/TicketDetailDialog.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { dashboard } from '@/routes';
import { create, destroy, edit } from '@/routes/tickets';
import type {
    PaginatedTickets,
    Ticket,
    TicketFilters,
    TicketStats,
} from '@/types/ticket';
import {
    TICKET_CATEGORIES,
    TICKET_PRIORITIES,
    TICKET_SORT_OPTIONS,
    TICKET_STATUSES,
} from '@/types/ticket';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Tickets',
                href: dashboard(),
            },
        ],
    },
});

const props = defineProps<{
    tickets: PaginatedTickets;
    stats: TicketStats;
    filters: TicketFilters;
    resultCount?: number;
}>();

const search = ref(props.filters.search ?? '');
const status = ref(props.filters.status ?? 'all');
const priority = ref(props.filters.priority ?? 'all');
const category = ref(props.filters.category ?? 'all');
const sort = ref(props.filters.sort ?? 'created_at');
const direction = ref(props.filters.direction ?? 'desc');

const selectedTicket = ref<Ticket | null>(null);
const detailOpen = ref(false);

watch(
    () => props.filters,
    (next) => {
        search.value = next.search ?? '';
        status.value = next.status ?? 'all';
        priority.value = next.priority ?? 'all';
        category.value = next.category ?? 'all';
        sort.value = next.sort ?? 'created_at';
        direction.value = next.direction ?? 'desc';
    },
    { deep: true },
);

const rows = computed(() => props.tickets.data ?? []);

const hasActiveFilters = computed(() => {
    return (
        (search.value ?? '').trim() !== '' ||
        status.value !== 'all' ||
        priority.value !== 'all' ||
        category.value !== 'all' ||
        sort.value !== 'created_at' ||
        direction.value !== 'desc'
    );
});

const pageLinks = computed(() =>
    (props.tickets.links ?? []).filter(
        (link) =>
            !link.label.includes('Previous') && !link.label.includes('Next'),
    ),
);

const rangeLabel = computed(() => {
    if (props.tickets.total === 0 || props.tickets.from === null) {
        return 'No matching tickets.';
    }

    return `Showing ${props.tickets.from}–${props.tickets.to} of ${props.tickets.total} tickets.`;
});

const selectClass = 'field-control';

const summaryCards = [
    {
        key: 'total',
        label: 'Total Tickets',
        value: () => props.stats.total,
    },
    {
        key: 'open',
        label: 'Open Tickets',
        value: () => props.stats.open,
    },
    {
        key: 'in_progress',
        label: 'In Progress',
        value: () => props.stats.in_progress,
    },
    {
        key: 'high_priority',
        label: 'High Priority',
        value: () => props.stats.high_priority,
    },
] as const;

let searchTimer: ReturnType<typeof setTimeout> | null = null;

function buildQuery(
    overrides: Partial<TicketFilters> & { page?: number } = {},
) {
    const next = {
        search: search.value,
        status: status.value,
        priority: priority.value,
        category: category.value,
        sort: sort.value,
        direction: direction.value,
        ...overrides,
    };

    const query: Record<string, string> = {};

    if (next.search.trim() !== '') {
        query.search = next.search.trim();
    }

    if (next.status !== 'all') {
        query.status = next.status;
    }

    if (next.priority !== 'all') {
        query.priority = next.priority;
    }

    if (next.category !== 'all') {
        query.category = next.category;
    }

    if (next.sort !== 'created_at') {
        query.sort = next.sort;
    }

    if (next.direction !== 'desc') {
        query.direction = next.direction;
    }

    if (typeof next.page === 'number' && next.page > 1) {
        query.page = String(next.page);
    }

    return query;
}

function applyFilters(
    overrides: Partial<TicketFilters> & { page?: number } = {},
) {
    router.get(dashboard.url(), buildQuery({ page: 1, ...overrides }), {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

function goToPage(url: string | null): void {
    if (!url) {
        return;
    }

    router.get(
        url,
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
}

function onSearchInput(): void {
    if (searchTimer) {
        clearTimeout(searchTimer);
    }

    searchTimer = setTimeout(() => {
        applyFilters({ search: search.value });
    }, 300);
}

function onFilterChange(): void {
    applyFilters();
}

function clearFilters(): void {
    search.value = '';
    status.value = 'all';
    priority.value = 'all';
    category.value = 'all';
    sort.value = 'created_at';
    direction.value = 'desc';
    router.get(
        dashboard.url(),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
}

function formatDateTime(value: string): string {
    try {
        return new Intl.DateTimeFormat(undefined, {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false,
        }).format(new Date(value));
    } catch {
        return value;
    }
}

function pageLabel(label: string): string {
    return label
        .replaceAll('&laquo;', '«')
        .replaceAll('&raquo;', '»')
        .replaceAll('&amp;', '&')
        .replaceAll(/<[^>]*>/g, '')
        .trim();
}

function openTicketDetail(ticket: Ticket): void {
    selectedTicket.value = ticket;
    detailOpen.value = true;
}

function onDetailOpenChange(open: boolean): void {
    detailOpen.value = open;

    if (!open) {
        selectedTicket.value = null;
    }
}

function confirmDelete(ticket: Ticket): void {
    if (
        !window.confirm(
            `Delete ticket "${ticket.title}"? This cannot be undone.`,
        )
    ) {
        return;
    }

    detailOpen.value = false;
    selectedTicket.value = null;

    router.delete(destroy.url(ticket.id), {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="IT Ticket Dashboard" />

    <div class="flex flex-col gap-6 p-4 md:p-6">
        <motion.div
            class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between"
            :initial="{ opacity: 0, y: 6 }"
            :animate="{ opacity: 1, y: 0 }"
            :transition="{ duration: 0.22, ease: [0.22, 1, 0.36, 1] }"
        >
            <div class="space-y-1">
                <p
                    class="text-xs font-semibold tracking-wide text-primary uppercase"
                >
                    LeadGeeks Inc
                </p>
                <Heading
                    title="IT Ticket Dashboard"
                    description="Track, update, and close internal IT support tickets."
                />
            </div>
            <Button
                as-child
                class="h-11 w-full touch-manipulation sm:h-9 sm:w-auto sm:self-start"
                data-test="new-ticket"
            >
                <Link :href="create()">
                    <Plus class="size-4" />
                    New ticket
                </Link>
            </Button>
        </motion.div>

        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <motion.div
                v-for="(card, index) in summaryCards"
                :key="card.key"
                :initial="{ opacity: 0, y: 10 }"
                :animate="{ opacity: 1, y: 0 }"
                :transition="{
                    duration: 0.22,
                    delay: index * 0.04,
                    ease: [0.22, 1, 0.36, 1],
                }"
            >
                <Card
                    class="h-full gap-2 border-border/80 bg-card py-4 shadow-none ring-1 ring-foreground/5"
                    :class="{
                        'ring-primary/15': card.key === 'high_priority',
                    }"
                >
                    <CardHeader class="px-4 pb-0">
                        <CardDescription
                            class="text-xs font-medium tracking-wide text-muted-foreground"
                        >
                            {{ card.label }}
                        </CardDescription>
                        <CardTitle
                            class="text-3xl font-semibold tracking-tight tabular-nums"
                            :class="
                                card.key === 'high_priority'
                                    ? 'text-primary'
                                    : 'text-foreground'
                            "
                        >
                            {{ card.value() }}
                        </CardTitle>
                    </CardHeader>
                </Card>
            </motion.div>
        </div>

        <motion.div
            :initial="{ opacity: 0, y: 10 }"
            :animate="{ opacity: 1, y: 0 }"
            :transition="{
                duration: 0.24,
                delay: 0.1,
                ease: [0.22, 1, 0.36, 1],
            }"
        >
            <Card
                class="gap-0 overflow-hidden py-0 shadow-none ring-1 ring-foreground/5"
            >
                <CardHeader class="space-y-4 border-b bg-card px-4 py-4">
                    <div
                        class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between"
                    >
                        <div>
                            <CardTitle class="text-base tracking-tight"
                                >All tickets</CardTitle
                            >
                            <CardDescription>
                                Search, filter, and sort.
                                <span class="font-medium text-foreground">{{
                                    rangeLabel
                                }}</span>
                            </CardDescription>
                        </div>
                        <Button
                            v-if="hasActiveFilters"
                            type="button"
                            variant="ghost"
                            size="sm"
                            class="h-11 touch-manipulation self-start sm:h-9"
                            data-test="clear-filters"
                            @click="clearFilters"
                        >
                            <X class="size-4" />
                            Clear filters
                        </Button>
                    </div>

                    <div
                        class="grid gap-3 md:grid-cols-2 xl:grid-cols-6"
                        data-test="ticket-filters"
                    >
                        <div class="grid gap-1.5 xl:col-span-2">
                            <Label for="filter-search" class="text-xs"
                                >Search</Label
                            >
                            <div class="relative">
                                <Search
                                    class="pointer-events-none absolute top-1/2 left-2.5 size-4 -translate-y-1/2 text-muted-foreground"
                                />
                                <Input
                                    id="filter-search"
                                    v-model="search"
                                    type="search"
                                    placeholder="Title, assignee, notes..."
                                    class="pl-8"
                                    data-test="filter-search"
                                    @input="onSearchInput"
                                />
                            </div>
                        </div>

                        <div class="grid gap-1.5">
                            <Label for="filter-status" class="text-xs"
                                >Status</Label
                            >
                            <select
                                id="filter-status"
                                v-model="status"
                                :class="selectClass"
                                data-test="filter-status"
                                @change="onFilterChange"
                            >
                                <option value="all">All statuses</option>
                                <option
                                    v-for="option in TICKET_STATUSES"
                                    :key="option"
                                    :value="option"
                                >
                                    {{ option }}
                                </option>
                            </select>
                        </div>

                        <div class="grid gap-1.5">
                            <Label for="filter-priority" class="text-xs"
                                >Priority</Label
                            >
                            <select
                                id="filter-priority"
                                v-model="priority"
                                :class="selectClass"
                                data-test="filter-priority"
                                @change="onFilterChange"
                            >
                                <option value="all">All priorities</option>
                                <option
                                    v-for="option in TICKET_PRIORITIES"
                                    :key="option"
                                    :value="option"
                                >
                                    {{ option }}
                                </option>
                            </select>
                        </div>

                        <div class="grid gap-1.5">
                            <Label for="filter-category" class="text-xs"
                                >Category</Label
                            >
                            <select
                                id="filter-category"
                                v-model="category"
                                :class="selectClass"
                                data-test="filter-category"
                                @change="onFilterChange"
                            >
                                <option value="all">All categories</option>
                                <option
                                    v-for="option in TICKET_CATEGORIES"
                                    :key="option"
                                    :value="option"
                                >
                                    {{ option }}
                                </option>
                            </select>
                        </div>

                        <div class="grid gap-1.5">
                            <Label for="filter-sort" class="text-xs"
                                >Sort by</Label
                            >
                            <div class="flex gap-2">
                                <select
                                    id="filter-sort"
                                    v-model="sort"
                                    :class="selectClass"
                                    data-test="filter-sort"
                                    @change="onFilterChange"
                                >
                                    <option
                                        v-for="option in TICKET_SORT_OPTIONS"
                                        :key="option.value"
                                        :value="option.value"
                                    >
                                        {{ option.label }}
                                    </option>
                                </select>
                                <select
                                    id="filter-direction"
                                    v-model="direction"
                                    :class="selectClass"
                                    class="max-w-[5.5rem]"
                                    data-test="filter-direction"
                                    @change="onFilterChange"
                                    aria-label="Sort direction"
                                >
                                    <option value="desc">Desc</option>
                                    <option value="asc">Asc</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </CardHeader>
                <CardContent class="p-0">
                    <div
                        v-if="rows.length === 0 && stats.total === 0"
                        class="flex flex-col items-center justify-center gap-3 px-4 py-16 text-center"
                        data-test="tickets-empty"
                    >
                        <p class="text-sm font-medium">No tickets yet</p>
                        <p class="max-w-sm text-sm text-muted-foreground">
                            Create the first ticket to populate the dashboard
                            cards and list.
                        </p>
                        <Button as-child size="sm">
                            <Link :href="create()">New ticket</Link>
                        </Button>
                    </div>

                    <div
                        v-else-if="rows.length === 0"
                        class="flex flex-col items-center justify-center gap-3 px-4 py-16 text-center"
                        data-test="tickets-no-results"
                    >
                        <p class="text-sm font-medium">No tickets match</p>
                        <p class="max-w-sm text-sm text-muted-foreground">
                            Try a different search term or clear filters to see
                            all {{ stats.total }} tickets.
                        </p>
                        <Button
                            type="button"
                            size="sm"
                            variant="outline"
                            @click="clearFilters"
                        >
                            Clear filters
                        </Button>
                    </div>

                    <template v-else>
                        <!-- Phone: stacked ticket list (touch-first). md+: dense table. -->
                        <TooltipProvider :delay-duration="250">
                            <ul
                                class="divide-y divide-border md:hidden"
                                data-test="ticket-card-list"
                            >
                                <li
                                    v-for="ticket in rows"
                                    :key="`card-${ticket.id}`"
                                    class="ticket-mobile-card cursor-pointer px-4 py-4"
                                    data-test="ticket-row"
                                    role="button"
                                    tabindex="0"
                                    @click="openTicketDetail(ticket)"
                                    @keydown.enter.prevent="
                                        openTicketDetail(ticket)
                                    "
                                >
                                    <div class="space-y-3">
                                        <div class="space-y-1.5">
                                            <div
                                                class="flex flex-wrap items-center gap-1.5"
                                            >
                                                <StatusBadge
                                                    :status="ticket.status"
                                                />
                                                <PriorityBadge
                                                    :priority="ticket.priority"
                                                />
                                                <span
                                                    class="rounded-md border border-border bg-muted/50 px-2 py-0.5 text-[11px] font-medium text-muted-foreground"
                                                >
                                                    {{ ticket.category }}
                                                </span>
                                            </div>
                                            <Tooltip v-if="ticket.notes">
                                                <TooltipTrigger as-child>
                                                    <h3
                                                        class="text-base leading-snug font-semibold tracking-tight text-balance text-foreground"
                                                    >
                                                        {{ ticket.title }}
                                                    </h3>
                                                </TooltipTrigger>
                                                <TooltipContent
                                                    side="top"
                                                    class="max-w-xs text-left text-xs leading-relaxed"
                                                >
                                                    <p class="font-semibold">
                                                        Notes
                                                    </p>
                                                    <p
                                                        class="mt-1 whitespace-pre-wrap"
                                                    >
                                                        {{ ticket.notes }}
                                                    </p>
                                                </TooltipContent>
                                            </Tooltip>
                                            <h3
                                                v-else
                                                class="text-base leading-snug font-semibold tracking-tight text-balance text-foreground"
                                            >
                                                {{ ticket.title }}
                                            </h3>
                                        </div>

                                        <dl
                                            class="grid grid-cols-2 gap-x-3 gap-y-2 text-xs"
                                        >
                                            <div class="min-w-0">
                                                <dt
                                                    class="font-medium text-muted-foreground"
                                                >
                                                    Assigned
                                                </dt>
                                                <dd
                                                    class="truncate font-medium text-foreground"
                                                >
                                                    {{ ticket.assigned_person }}
                                                </dd>
                                            </div>
                                            <div class="min-w-0">
                                                <dt
                                                    class="font-medium text-muted-foreground"
                                                >
                                                    Created
                                                </dt>
                                                <dd
                                                    class="text-foreground tabular-nums"
                                                >
                                                    {{
                                                        formatDateTime(
                                                            ticket.created_at,
                                                        )
                                                    }}
                                                </dd>
                                            </div>
                                        </dl>

                                        <div
                                            class="grid grid-cols-2 gap-2 pt-0.5"
                                            @click.stop
                                        >
                                            <Button
                                                as-child
                                                variant="outline"
                                                class="h-11 touch-manipulation"
                                            >
                                                <Link
                                                    :href="edit(ticket.id)"
                                                    data-test="edit-ticket"
                                                >
                                                    Edit
                                                </Link>
                                            </Button>
                                            <Button
                                                variant="destructive"
                                                type="button"
                                                class="h-11 touch-manipulation"
                                                data-test="delete-ticket"
                                                @click="confirmDelete(ticket)"
                                            >
                                                Delete
                                            </Button>
                                        </div>
                                    </div>
                                </li>
                            </ul>

                            <div class="hidden overflow-x-auto md:block">
                                <table
                                    class="ticket-table min-w-[760px] text-left text-sm"
                                    data-test="ticket-table"
                                >
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3">Title</th>
                                            <th class="px-4 py-3">Category</th>
                                            <th class="px-4 py-3">Priority</th>
                                            <th class="px-4 py-3">Status</th>
                                            <th class="px-4 py-3">Assigned</th>
                                            <th class="px-4 py-3">Created</th>
                                            <th class="px-4 py-3 text-right">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="ticket in rows"
                                            :key="ticket.id"
                                            class="cursor-pointer"
                                            data-test="ticket-row"
                                            tabindex="0"
                                            @click="openTicketDetail(ticket)"
                                            @keydown.enter.prevent="
                                                openTicketDetail(ticket)
                                            "
                                        >
                                            <td
                                                class="max-w-[240px] px-4 py-3.5"
                                            >
                                                <Tooltip v-if="ticket.notes">
                                                    <TooltipTrigger as-child>
                                                        <div
                                                            class="font-medium tracking-tight text-foreground"
                                                        >
                                                            {{ ticket.title }}
                                                        </div>
                                                    </TooltipTrigger>
                                                    <TooltipContent
                                                        side="top"
                                                        class="max-w-xs text-left text-xs leading-relaxed"
                                                    >
                                                        <p
                                                            class="font-semibold"
                                                        >
                                                            Notes
                                                        </p>
                                                        <p
                                                            class="mt-1 whitespace-pre-wrap"
                                                        >
                                                            {{ ticket.notes }}
                                                        </p>
                                                    </TooltipContent>
                                                </Tooltip>
                                                <div
                                                    v-else
                                                    class="font-medium tracking-tight text-foreground"
                                                >
                                                    {{ ticket.title }}
                                                </div>
                                            </td>
                                            <td
                                                class="px-4 py-3.5 text-muted-foreground"
                                            >
                                                {{ ticket.category }}
                                            </td>
                                            <td class="px-4 py-3.5">
                                                <PriorityBadge
                                                    :priority="ticket.priority"
                                                />
                                            </td>
                                            <td class="px-4 py-3.5">
                                                <StatusBadge
                                                    :status="ticket.status"
                                                />
                                            </td>
                                            <td
                                                class="px-4 py-3.5 text-foreground"
                                            >
                                                {{ ticket.assigned_person }}
                                            </td>
                                            <td
                                                class="px-4 py-3.5 whitespace-nowrap text-muted-foreground tabular-nums"
                                            >
                                                {{
                                                    formatDateTime(
                                                        ticket.created_at,
                                                    )
                                                }}
                                            </td>
                                            <td
                                                class="px-4 py-3.5"
                                                @click.stop
                                                @keydown.stop
                                            >
                                                <div
                                                    class="flex items-center justify-end gap-2"
                                                >
                                                    <Button
                                                        as-child
                                                        variant="outline"
                                                        size="sm"
                                                        class="h-9 min-w-16"
                                                    >
                                                        <Link
                                                            :href="
                                                                edit(ticket.id)
                                                            "
                                                            data-test="edit-ticket"
                                                        >
                                                            Edit
                                                        </Link>
                                                    </Button>
                                                    <Button
                                                        variant="destructive"
                                                        size="sm"
                                                        type="button"
                                                        class="h-9 min-w-16"
                                                        data-test="delete-ticket"
                                                        @click="
                                                            confirmDelete(
                                                                ticket,
                                                            )
                                                        "
                                                    >
                                                        Delete
                                                    </Button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </TooltipProvider>

                        <div
                            class="flex flex-col gap-3 border-t bg-card/60 px-4 py-3 sm:flex-row sm:items-center sm:justify-between"
                            data-test="ticket-pagination"
                        >
                            <p
                                class="text-sm text-muted-foreground tabular-nums sm:text-xs"
                            >
                                {{ rangeLabel }}
                                <span v-if="tickets.last_page > 1">
                                    · Page {{ tickets.current_page }} of
                                    {{ tickets.last_page }}
                                </span>
                            </p>

                            <div
                                v-if="tickets.last_page > 1"
                                class="flex flex-wrap items-center gap-1.5"
                            >
                                <Button
                                    type="button"
                                    variant="outline"
                                    size="sm"
                                    class="h-11 touch-manipulation gap-1 px-3 sm:h-9 sm:px-2.5"
                                    :disabled="!tickets.prev_page_url"
                                    data-test="pagination-prev"
                                    @click="goToPage(tickets.prev_page_url)"
                                >
                                    <ChevronLeft class="size-4" />
                                    Prev
                                </Button>

                                <Button
                                    v-for="(link, index) in pageLinks"
                                    :key="`${link.label}-${index}`"
                                    type="button"
                                    size="sm"
                                    class="h-11 min-w-11 touch-manipulation px-2.5 tabular-nums sm:h-9 sm:min-w-9"
                                    :variant="
                                        link.active ? 'default' : 'outline'
                                    "
                                    :disabled="!link.url"
                                    :data-test="
                                        link.active
                                            ? 'pagination-page-active'
                                            : 'pagination-page'
                                    "
                                    @click="goToPage(link.url)"
                                >
                                    {{ pageLabel(link.label) }}
                                </Button>

                                <Button
                                    type="button"
                                    variant="outline"
                                    size="sm"
                                    class="h-11 touch-manipulation gap-1 px-3 sm:h-9 sm:px-2.5"
                                    :disabled="!tickets.next_page_url"
                                    data-test="pagination-next"
                                    @click="goToPage(tickets.next_page_url)"
                                >
                                    Next
                                    <ChevronRight class="size-4" />
                                </Button>
                            </div>
                        </div>
                    </template>
                </CardContent>
            </Card>
        </motion.div>

        <TicketDetailDialog
            :ticket="selectedTicket"
            :open="detailOpen"
            @update:open="onDetailOpenChange"
            @delete="confirmDelete"
        />
    </div>
</template>
