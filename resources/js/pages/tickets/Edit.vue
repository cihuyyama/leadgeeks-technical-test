<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { dashboard } from '@/routes';
import { edit, update } from '@/routes/tickets';
import type { Ticket } from '@/types/ticket';
import {
    TICKET_CATEGORIES,
    TICKET_PRIORITIES,
    TICKET_STATUSES,
} from '@/types/ticket';

defineProps<{
    ticket: Ticket;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Tickets',
                href: dashboard(),
            },
            {
                title: 'Edit ticket',
                href: '/tickets',
            },
        ],
    },
});

</script>

<template>
    <Head :title="`Edit: ${ticket.title}`" />

    <div class="mx-auto flex w-full max-w-2xl flex-col gap-6 p-4 md:p-6">
        <Heading
            title="Edit ticket"
            description="Update fields, priority, assignee, or status."
        />

        <Form
            v-bind="update.form(ticket.id)"
            class="space-y-6 rounded-xl border bg-card p-4 shadow-none md:p-6"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-2">
                <Label for="title">Title</Label>
                <Input
                    id="title"
                    name="title"
                    required
                    maxlength="255"
                    :default-value="ticket.title"
                    data-test="ticket-title"
                />
                <InputError :message="errors.title" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="category">Category</Label>
                    <select
                        id="category"
                        name="category"
                        required
                        class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-xs outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                        data-test="ticket-category"
                    >
                        <option
                            v-for="category in TICKET_CATEGORIES"
                            :key="category"
                            :value="category"
                            :selected="category === ticket.category"
                        >
                            {{ category }}
                        </option>
                    </select>
                    <InputError :message="errors.category" />
                </div>

                <div class="grid gap-2">
                    <Label for="priority">Priority</Label>
                    <select
                        id="priority"
                        name="priority"
                        required
                        class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-xs outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                        data-test="ticket-priority"
                    >
                        <option
                            v-for="priority in TICKET_PRIORITIES"
                            :key="priority"
                            :value="priority"
                            :selected="priority === ticket.priority"
                        >
                            {{ priority }}
                        </option>
                    </select>
                    <InputError :message="errors.priority" />
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="status">Status</Label>
                    <select
                        id="status"
                        name="status"
                        required
                        class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-xs outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                        data-test="ticket-status"
                    >
                        <option
                            v-for="status in TICKET_STATUSES"
                            :key="status"
                            :value="status"
                            :selected="status === ticket.status"
                        >
                            {{ status }}
                        </option>
                    </select>
                    <InputError :message="errors.status" />
                </div>

                <div class="grid gap-2">
                    <Label for="assigned_person">Assigned person</Label>
                    <Input
                        id="assigned_person"
                        name="assigned_person"
                        required
                        maxlength="255"
                        :default-value="ticket.assigned_person"
                        data-test="ticket-assignee"
                    />
                    <InputError :message="errors.assigned_person" />
                </div>
            </div>

            <div class="grid gap-2">
                <Label for="notes">Notes</Label>
                <textarea
                    id="notes"
                    name="notes"
                    rows="4"
                    class="flex min-h-24 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                    :value="ticket.notes ?? ''"
                    data-test="ticket-notes"
                />
                <InputError :message="errors.notes" />
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <Button
                    type="submit"
                    :disabled="processing"
                    data-test="save-ticket"
                >
                    <Spinner v-if="processing" />
                    Save changes
                </Button>
                <Button as-child variant="outline" type="button">
                    <Link :href="dashboard()">Cancel</Link>
                </Button>
            </div>
        </Form>
    </div>
</template>
