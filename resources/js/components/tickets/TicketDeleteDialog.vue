<script setup lang="ts">
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Spinner } from '@/components/ui/spinner';
import type { Ticket } from '@/types/ticket';

const props = defineProps<{
    ticket: Ticket | null;
    open: boolean;
    processing?: boolean;
}>();

const emit = defineEmits<{
    'update:open': [value: boolean];
    confirm: [ticket: Ticket];
}>();

const isOpen = computed({
    get: () => props.open,
    set: (value: boolean) => emit('update:open', value),
});

function onConfirm(): void {
    if (!props.ticket || props.processing) {
        return;
    }

    emit('confirm', props.ticket);
}
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogContent
            class="gap-0 p-0 sm:max-w-md"
            data-test="ticket-delete-dialog"
        >
            <template v-if="ticket">
                <DialogHeader
                    class="space-y-2 border-b border-border px-6 py-5 text-left"
                >
                    <DialogTitle class="text-lg font-semibold tracking-tight">
                        Delete ticket?
                    </DialogTitle>
                    <DialogDescription class="text-sm text-muted-foreground">
                        This permanently removes
                        <span class="font-medium text-foreground">
                            “{{ ticket.title }}”
                        </span>
                        from the dashboard. This cannot be undone.
                    </DialogDescription>
                </DialogHeader>

                <div class="space-y-3 px-6 py-4">
                    <dl
                        class="rounded-lg border border-border bg-muted/30 px-3 py-2.5 text-sm"
                    >
                        <div class="flex items-center justify-between gap-3">
                            <dt class="text-muted-foreground">Status</dt>
                            <dd class="font-medium text-foreground">
                                {{ ticket.status }}
                            </dd>
                        </div>
                        <div
                            class="mt-1.5 flex items-center justify-between gap-3"
                        >
                            <dt class="text-muted-foreground">Assignee</dt>
                            <dd class="font-medium text-foreground">
                                {{ ticket.assigned_person }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <DialogFooter
                    class="flex-col gap-2 border-t border-border px-6 py-4 sm:flex-row sm:justify-end"
                >
                    <Button
                        type="button"
                        variant="outline"
                        class="h-11 w-full touch-manipulation sm:h-9 sm:w-auto"
                        :disabled="processing"
                        data-test="ticket-delete-cancel"
                        @click="isOpen = false"
                    >
                        Cancel
                    </Button>
                    <Button
                        type="button"
                        variant="destructive"
                        class="h-11 w-full touch-manipulation sm:h-9 sm:w-auto"
                        :disabled="processing"
                        data-test="ticket-delete-confirm"
                        @click="onConfirm"
                    >
                        <Spinner v-if="processing" />
                        Delete ticket
                    </Button>
                </DialogFooter>
            </template>
        </DialogContent>
    </Dialog>
</template>
