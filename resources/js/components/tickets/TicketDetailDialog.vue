<script setup lang="ts">
import { computed } from 'vue';
import PriorityBadge from '@/components/tickets/PriorityBadge.vue';
import StatusBadge from '@/components/tickets/StatusBadge.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import type { Ticket } from '@/types/ticket';

const props = defineProps<{
    ticket: Ticket | null;
    open: boolean;
}>();

const emit = defineEmits<{
    'update:open': [value: boolean];
    edit: [ticket: Ticket];
    delete: [ticket: Ticket];
}>();

const isOpen = computed({
    get: () => props.open,
    set: (value: boolean) => emit('update:open', value),
});

function formatDateTime(value: string): string {
    try {
        return new Intl.DateTimeFormat(undefined, {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false,
        }).format(new Date(value));
    } catch {
        return value;
    }
}

function onEdit(): void {
    if (!props.ticket) {
        return;
    }

    emit('edit', props.ticket);
}

function onDelete(): void {
    if (!props.ticket) {
        return;
    }

    emit('delete', props.ticket);
}
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogContent
            class="max-h-[min(90vh,40rem)] gap-0 overflow-y-auto p-0 sm:max-w-lg"
            data-test="ticket-detail-dialog"
        >
            <template v-if="ticket">
                <DialogHeader
                    class="space-y-3 border-b border-border px-6 py-5 text-left"
                >
                    <div class="flex flex-wrap items-center gap-1.5 pr-6">
                        <StatusBadge :status="ticket.status" />
                        <PriorityBadge :priority="ticket.priority" />
                        <span
                            class="rounded-md border border-border bg-muted/50 px-2 py-0.5 text-[11px] font-medium text-muted-foreground"
                        >
                            {{ ticket.category }}
                        </span>
                    </div>
                    <DialogTitle
                        class="text-lg leading-snug font-semibold tracking-tight text-balance"
                    >
                        {{ ticket.title }}
                    </DialogTitle>
                    <DialogDescription class="text-xs tabular-nums">
                        Ticket #{{ ticket.id }}
                    </DialogDescription>
                </DialogHeader>

                <div class="space-y-5 px-6 py-5">
                    <dl class="grid gap-4 sm:grid-cols-2">
                        <div class="space-y-1">
                            <dt
                                class="text-xs font-medium tracking-wide text-muted-foreground uppercase"
                            >
                                Assigned
                            </dt>
                            <dd class="text-sm font-medium text-foreground">
                                {{ ticket.assigned_person }}
                            </dd>
                        </div>
                        <div class="space-y-1">
                            <dt
                                class="text-xs font-medium tracking-wide text-muted-foreground uppercase"
                            >
                                Category
                            </dt>
                            <dd class="text-sm text-foreground">
                                {{ ticket.category }}
                            </dd>
                        </div>
                        <div class="space-y-1">
                            <dt
                                class="text-xs font-medium tracking-wide text-muted-foreground uppercase"
                            >
                                Created
                            </dt>
                            <dd
                                class="text-sm text-foreground tabular-nums"
                                data-test="ticket-detail-created"
                            >
                                {{ formatDateTime(ticket.created_at) }}
                            </dd>
                        </div>
                        <div class="space-y-1">
                            <dt
                                class="text-xs font-medium tracking-wide text-muted-foreground uppercase"
                            >
                                Updated
                            </dt>
                            <dd
                                class="text-sm text-foreground tabular-nums"
                                data-test="ticket-detail-updated"
                            >
                                {{ formatDateTime(ticket.updated_at) }}
                            </dd>
                        </div>
                    </dl>

                    <div class="space-y-1.5">
                        <p
                            class="text-xs font-medium tracking-wide text-muted-foreground uppercase"
                        >
                            Notes
                        </p>
                        <div
                            class="rounded-lg border border-border bg-muted/30 px-3 py-2.5 text-sm leading-relaxed text-foreground"
                            data-test="ticket-detail-notes"
                        >
                            <p v-if="ticket.notes" class="whitespace-pre-wrap">
                                {{ ticket.notes }}
                            </p>
                            <p v-else class="text-muted-foreground italic">
                                No notes for this ticket.
                            </p>
                        </div>
                    </div>
                </div>

                <DialogFooter
                    class="flex-col gap-2 border-t border-border px-6 py-4 sm:flex-row sm:justify-end"
                >
                    <Button
                        type="button"
                        variant="outline"
                        class="h-11 w-full touch-manipulation sm:h-9 sm:w-auto"
                        @click="isOpen = false"
                    >
                        Close
                    </Button>
                    <Button
                        type="button"
                        variant="outline"
                        class="h-11 w-full touch-manipulation sm:h-9 sm:w-auto"
                        data-test="ticket-detail-edit"
                        @click="onEdit"
                    >
                        Edit
                    </Button>
                    <Button
                        type="button"
                        variant="destructive"
                        class="h-11 w-full touch-manipulation sm:h-9 sm:w-auto"
                        data-test="ticket-detail-delete"
                        @click="onDelete"
                    >
                        Delete
                    </Button>
                </DialogFooter>
            </template>
        </DialogContent>
    </Dialog>
</template>
