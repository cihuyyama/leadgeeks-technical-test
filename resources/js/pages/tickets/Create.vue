<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { dashboard } from '@/routes';
import { create, store } from '@/routes/tickets';
import {
    TICKET_CATEGORIES,
    TICKET_PRIORITIES,
    TICKET_STATUSES,
} from '@/types/ticket';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Tickets',
                href: dashboard(),
            },
            {
                title: 'New ticket',
                href: create(),
            },
        ],
    },
});
</script>

<template>
    <Head title="New ticket" />

    <div class="mx-auto flex w-full max-w-2xl flex-col gap-6 p-4 md:p-6">
        <Heading
            title="New ticket"
            description="Log an internal IT support request."
        />

        <Form
            v-bind="store.form()"
            class="space-y-6 rounded-xl border bg-card p-4 shadow-none md:p-6"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-2">
                <Label for="title">Title</Label>
                <Input
                    id="title"
                    name="title"
                    required
                    autofocus
                    maxlength="255"
                    placeholder="e.g. Laptop will not connect to VPN"
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
                        <option value="Medium" selected>Medium</option>
                        <option
                            v-for="priority in TICKET_PRIORITIES.filter(
                                (p) => p !== 'Medium',
                            )"
                            :key="priority"
                            :value="priority"
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
                        <option value="Open" selected>Open</option>
                        <option
                            v-for="status in TICKET_STATUSES.filter(
                                (s) => s !== 'Open',
                            )"
                            :key="status"
                            :value="status"
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
                        placeholder="e.g. Ahmad IT"
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
                    placeholder="Optional context, steps tried, asset tag…"
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
                    Create ticket
                </Button>
                <Button as-child variant="outline" type="button">
                    <Link :href="dashboard()">Cancel</Link>
                </Button>
            </div>
        </Form>
    </div>
</template>
