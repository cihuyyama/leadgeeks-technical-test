<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';

const name = usePage().props.name;
</script>

<template>
    <div
        class="flex aspect-square size-8 shrink-0 items-center justify-center overflow-hidden rounded-md bg-white shadow-sm ring-1 ring-white/20"
    >
        <img
            src="/images/leadgeeks-mark.png"
            alt="LeadGeeks"
            class="size-7 object-contain"
            width="28"
            height="28"
        />
    </div>
    <div class="ml-2 grid min-w-0 flex-1 text-left text-sm">
        <span class="truncate leading-tight font-semibold tracking-tight text-sidebar-foreground">
            {{ name }}
        </span>
        <span class="truncate text-[11px] leading-tight text-sidebar-foreground/65">
            IT Tickets
        </span>
    </div>
</template>
