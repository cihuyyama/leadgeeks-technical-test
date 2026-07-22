<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus } from '@lucide/vue';
import Heading from '@/components/Heading.vue';
import PriorityBadge from '@/components/tickets/PriorityBadge.vue';
import StatusBadge from '@/components/tickets/StatusBadge.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { dashboard } from '@/routes';
import { create, destroy, edit } from '@/routes/tickets';
import type { Ticket, TicketStats } from '@/types/ticket';

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
    tickets: Ticket[];
    stats: TicketStats;
}>();

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

function formatDate(value: string): string {
    try {
        return new Intl.DateTimeFormat(undefined, {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        }).format(new Date(value));
    } catch {
        return value;
    }
}

function confirmDelete(ticket: Ticket): void {
    if (
        !window.confirm(
            `Delete ticket “${ticket.title}”? This cannot be undone.`,
        )
    ) {
        return;
    }

    router.delete(destroy.url(ticket.id), {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="IT Ticket Dashboard" />

    <div class="flex flex-col gap-6 p-4 md:p-6">
        <div
            class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between"
        >
            <Heading
                title="IT Ticket Dashboard"
                description="Track, update, and close internal IT support tickets."
            />
            <Button as-child class="shrink-0 self-start" data-test="new-ticket">
                <Link :href="create()">
                    <Plus class="size-4" />
                    New ticket
                </Link>
            </Button>
        </div>

        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <Card
                v-for="card in summaryCards"
                :key="card.key"
                class="gap-2 py-4 shadow-none"
            >
                <CardHeader class="px-4 pb-0">
                    <CardDescription class="text-xs font-medium tracking-wide">
                        {{ card.label }}
                    </CardDescription>
                    <CardTitle class="text-3xl font-semibold tabular-nums">
                        {{ card.value() }}
                    </CardTitle>
                </CardHeader>
            </Card>
        </div>

        <Card class="gap-0 py-0 shadow-none">
            <CardHeader class="border-b px-4 py-4">
                <CardTitle class="text-base">All tickets</CardTitle>
                <CardDescription>
                    Title, category, priority, status, assignee, and created
                    date.
                </CardDescription>
            </CardHeader>
            <CardContent class="p-0">
                <div
                    v-if="tickets.length === 0"
                    class="flex flex-col items-center justify-center gap-3 px-4 py-16 text-center"
                    data-test="tickets-empty"
                >
                    <p class="text-sm font-medium">No tickets yet</p>
                    <p class="max-w-sm text-sm text-muted-foreground">
                        Create the first ticket to populate the dashboard cards
                        and list.
                    </p>
                    <Button as-child size="sm">
                        <Link :href="create()">New ticket</Link>
                    </Button>
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="w-full min-w-[720px] text-left text-sm">
                        <thead class="border-b bg-muted/40 text-muted-foreground">
                            <tr>
                                <th class="px-4 py-3 font-medium">Title</th>
                                <th class="px-4 py-3 font-medium">Category</th>
                                <th class="px-4 py-3 font-medium">Priority</th>
                                <th class="px-4 py-3 font-medium">Status</th>
                                <th class="px-4 py-3 font-medium">Assigned</th>
                                <th class="px-4 py-3 font-medium">Created</th>
                                <th class="px-4 py-3 font-medium text-right">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="ticket in tickets"
                                :key="ticket.id"
                                class="border-b last:border-0 hover:bg-muted/30"
                                data-test="ticket-row"
                            >
                                <td class="max-w-[220px] px-4 py-3">
                                    <div class="font-medium">
                                        {{ ticket.title }}
                                    </div>
                                    <p
                                        v-if="ticket.notes"
                                        class="mt-0.5 line-clamp-1 text-xs text-muted-foreground"
                                    >
                                        {{ ticket.notes }}
                                    </p>
                                </td>
                                <td class="px-4 py-3 text-muted-foreground">
                                    {{ ticket.category }}
                                </td>
                                <td class="px-4 py-3">
                                    <PriorityBadge
                                        :priority="ticket.priority"
                                    />
                                </td>
                                <td class="px-4 py-3">
                                    <StatusBadge :status="ticket.status" />
                                </td>
                                <td class="px-4 py-3">
                                    {{ ticket.assigned_person }}
                                </td>
                                <td
                                    class="px-4 py-3 whitespace-nowrap text-muted-foreground"
                                >
                                    {{ formatDate(ticket.created_at) }}
                                </td>
                                <td class="px-4 py-3">
                                    <div
                                        class="flex items-center justify-end gap-2"
                                    >
                                        <Button
                                            as-child
                                            variant="outline"
                                            size="sm"
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
                                            size="sm"
                                            type="button"
                                            data-test="delete-ticket"
                                            @click="confirmDelete(ticket)"
                                        >
                                            Delete
                                        </Button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
