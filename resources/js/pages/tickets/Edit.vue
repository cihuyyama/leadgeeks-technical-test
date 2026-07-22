<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { motion } from 'motion-v';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { dashboard } from '@/routes';
import { update } from '@/routes/tickets';
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
        <motion.div
            :initial="{ opacity: 0, y: 8 }"
            :animate="{ opacity: 1, y: 0 }"
            :transition="{ duration: 0.22, ease: [0.22, 1, 0.36, 1] }"
        >
            <p
                class="mb-1 text-xs font-semibold tracking-wide text-primary uppercase"
            >
                LeadGeeks Inc
            </p>
            <Heading
                title="Edit ticket"
                description="Update fields, priority, assignee, or status."
            />
        </motion.div>

        <motion.div
            :initial="{ opacity: 0, y: 10 }"
            :animate="{ opacity: 1, y: 0 }"
            :transition="{
                duration: 0.24,
                delay: 0.05,
                ease: [0.22, 1, 0.36, 1],
            }"
        >
            <Form
                v-bind="update.form(ticket.id)"
                class="space-y-6 rounded-xl border border-border/80 bg-card p-4 shadow-none ring-1 ring-foreground/5 md:p-6"
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
                            class="field-control"
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
                            class="field-control"
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
                            class="field-control"
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
                        class="field-control"
                        :value="ticket.notes ?? ''"
                        data-test="ticket-notes"
                    />
                    <InputError :message="errors.notes" />
                </div>

                <div class="flex flex-wrap items-center gap-3 border-t border-border/70 pt-4">
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
        </motion.div>
    </div>
</template>
