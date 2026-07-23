<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { computed } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { store, update } from '@/routes/tickets';
import type { Ticket } from '@/types/ticket';
import {
    TICKET_CATEGORIES,
    TICKET_PRIORITIES,
    TICKET_STATUSES,
} from '@/types/ticket';

const props = defineProps<{
    open: boolean;
    ticket: Ticket | null;
}>();

const emit = defineEmits<{
    'update:open': [value: boolean];
}>();

const isOpen = computed({
    get: () => props.open,
    set: (value: boolean) => emit('update:open', value),
});

const isEdit = computed(() => props.ticket !== null);

const formBindings = computed(() => {
    if (props.ticket) {
        return update.form(props.ticket.id);
    }

    return store.form();
});

function onSuccess(): void {
    isOpen.value = false;
}
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogContent
            class="max-h-[min(92vh,42rem)] gap-0 overflow-y-auto p-0 sm:max-w-lg"
            data-test="ticket-form-dialog"
            @open-auto-focus.prevent
        >
            <DialogHeader
                class="space-y-1 border-b border-border px-6 py-5 text-left"
            >
                <DialogTitle class="text-lg font-semibold tracking-tight">
                    {{ isEdit ? 'Edit ticket' : 'New ticket' }}
                </DialogTitle>
                <DialogDescription class="text-sm text-muted-foreground">
                    {{
                        isEdit
                            ? 'Update fields, priority, assignee, or status.'
                            : 'Log an internal IT support request.'
                    }}
                </DialogDescription>
            </DialogHeader>

            <Form
                :key="ticket?.id ?? 'create'"
                v-bind="formBindings"
                class="space-y-5 px-6 py-5"
                v-slot="{ errors, processing }"
                @success="onSuccess"
            >
                <div class="grid gap-2">
                    <Label for="modal-title">Title</Label>
                    <Input
                        id="modal-title"
                        name="title"
                        required
                        autofocus
                        maxlength="255"
                        :default-value="ticket?.title ?? ''"
                        placeholder="e.g. Laptop will not connect to VPN"
                        data-test="ticket-title"
                    />
                    <InputError :message="errors.title" />
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="grid gap-2">
                        <Label for="modal-category">Category</Label>
                        <select
                            id="modal-category"
                            name="category"
                            required
                            class="field-control"
                            data-test="ticket-category"
                        >
                            <option
                                v-for="category in TICKET_CATEGORIES"
                                :key="category"
                                :value="category"
                                :selected="
                                    category ===
                                    (ticket?.category ?? TICKET_CATEGORIES[0])
                                "
                            >
                                {{ category }}
                            </option>
                        </select>
                        <InputError :message="errors.category" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="modal-priority">Priority</Label>
                        <select
                            id="modal-priority"
                            name="priority"
                            required
                            class="field-control"
                            data-test="ticket-priority"
                        >
                            <option
                                v-for="priority in TICKET_PRIORITIES"
                                :key="priority"
                                :value="priority"
                                :selected="
                                    priority === (ticket?.priority ?? 'Medium')
                                "
                            >
                                {{ priority }}
                            </option>
                        </select>
                        <InputError :message="errors.priority" />
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="grid gap-2">
                        <Label for="modal-status">Status</Label>
                        <select
                            id="modal-status"
                            name="status"
                            required
                            class="field-control"
                            data-test="ticket-status"
                        >
                            <option
                                v-for="status in TICKET_STATUSES"
                                :key="status"
                                :value="status"
                                :selected="
                                    status === (ticket?.status ?? 'Open')
                                "
                            >
                                {{ status }}
                            </option>
                        </select>
                        <InputError :message="errors.status" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="modal-assignee">Assigned person</Label>
                        <Input
                            id="modal-assignee"
                            name="assigned_person"
                            required
                            maxlength="255"
                            :default-value="ticket?.assigned_person ?? ''"
                            placeholder="e.g. Ahmad IT"
                            data-test="ticket-assignee"
                        />
                        <InputError :message="errors.assigned_person" />
                    </div>
                </div>

                <div class="grid gap-2">
                    <Label for="modal-notes">Notes</Label>
                    <textarea
                        id="modal-notes"
                        name="notes"
                        rows="4"
                        class="field-control"
                        :value="ticket?.notes ?? ''"
                        placeholder="Optional context, steps tried, asset tag…"
                        data-test="ticket-notes"
                    />
                    <InputError :message="errors.notes" />
                </div>

                <DialogFooter
                    class="flex flex-col-reverse gap-2 border-t border-border/70 pt-4 sm:flex-row sm:justify-end"
                >
                    <Button
                        type="button"
                        variant="outline"
                        :disabled="processing"
                        @click="isOpen = false"
                    >
                        Cancel
                    </Button>
                    <Button
                        type="submit"
                        :disabled="processing"
                        data-test="save-ticket"
                    >
                        <Spinner v-if="processing" />
                        {{ isEdit ? 'Save changes' : 'Create ticket' }}
                    </Button>
                </DialogFooter>
            </Form>
        </DialogContent>
    </Dialog>
</template>
