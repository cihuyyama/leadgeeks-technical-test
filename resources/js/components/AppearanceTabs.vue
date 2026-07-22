<script setup lang="ts">
import { Sun } from '@lucide/vue';
import { onMounted } from 'vue';
import { useAppearance } from '@/composables/useAppearance';

const { updateAppearance } = useAppearance();

onMounted(() => {
    updateAppearance('light');
});
</script>

<template>
    <div class="space-y-3">
        <div
            class="inline-flex items-center gap-2 rounded-lg border border-border bg-card px-3.5 py-2 text-sm text-foreground shadow-xs"
        >
            <Sun class="size-4 text-primary" />
            <span class="font-medium">Light</span>
            <span class="text-muted-foreground">· always on for this demo</span>
        </div>
        <p class="text-sm text-muted-foreground">
            Dark and system themes are disabled so the dashboard stays
            consistent for reviewers.
        </p>
    </div>
</template>
