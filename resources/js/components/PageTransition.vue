<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { motion } from 'motion-v';
import { computed } from 'vue';

const page = usePage();
const pageKey = computed(
    () => `${page.component}:${page.url.split('?')[0] ?? page.url}`,
);
</script>

<template>
    <motion.div
        :key="pageKey"
        class="min-h-0 flex-1"
        :initial="{ opacity: 0, y: 8 }"
        :animate="{ opacity: 1, y: 0 }"
        :transition="{ duration: 0.22, ease: [0.22, 1, 0.36, 1] }"
    >
        <slot />
    </motion.div>
</template>
