<script setup lang="ts">
import { computed } from 'vue';
import { Badge } from '@/components/ui/badge';
import { cn } from '@/lib/utils';

const props = defineProps<{
    priority: string;
}>();

const className = computed(() => {
    switch (props.priority) {
        case 'High':
            return 'border-red-200 bg-red-50 text-red-800 dark:border-red-900/50 dark:bg-red-950/40 dark:text-red-300';
        case 'Medium':
            return 'border-amber-200 bg-amber-50 text-amber-800 dark:border-amber-900/50 dark:bg-amber-950/40 dark:text-amber-300';
        case 'Low':
            return 'border-slate-200 bg-slate-100 text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300';
        default:
            return '';
    }
});
</script>

<template>
    <Badge variant="outline" :class="cn('font-medium', className)">
        {{ priority }}
    </Badge>
</template>
