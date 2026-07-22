<script setup lang="ts">
import { computed } from 'vue';
import { Badge } from '@/components/ui/badge';
import { cn } from '@/lib/utils';

const props = defineProps<{
    status: string;
}>();

const className = computed(() => {
    switch (props.status) {
        case 'Open':
            return 'border-amber-200 bg-amber-50 text-amber-800 dark:border-amber-900/50 dark:bg-amber-950/40 dark:text-amber-300';
        case 'In Progress':
            return 'border-[hsl(246_40%_80%)] bg-[hsl(246_45%_96%)] text-[hsl(246_65%_28%)] dark:border-[hsl(246_30%_35%)] dark:bg-[hsl(246_30%_18%)] dark:text-[hsl(246_50%_85%)]';
        case 'Resolved':
            return 'border-green-200 bg-green-50 text-green-800 dark:border-green-900/50 dark:bg-green-950/40 dark:text-green-300';
        case 'Closed':
            return 'border-slate-200 bg-slate-100 text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300';
        default:
            return '';
    }
});
</script>

<template>
    <Badge variant="outline" :class="cn('font-medium', className)">
        {{ status }}
    </Badge>
</template>
