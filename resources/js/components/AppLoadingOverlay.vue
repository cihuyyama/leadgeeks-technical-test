<script setup lang="ts">
import { AnimatePresence, motion } from 'motion-v';
import { onMounted } from 'vue';
import {
    bindPageLoadingListenersOnce,
    navigating,
    visitLabel,
} from '@/composables/usePageLoading';

onMounted(() => {
    bindPageLoadingListenersOnce();
});
</script>

<template>
    <AnimatePresence>
        <motion.div
            v-if="navigating"
            key="page-loading"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-foreground/40 backdrop-blur-[2px]"
            role="status"
            aria-live="polite"
            aria-busy="true"
            :initial="{ opacity: 0 }"
            :animate="{ opacity: 1 }"
            :exit="{ opacity: 0 }"
            :transition="{ duration: 0.18, ease: [0.22, 1, 0.36, 1] }"
            data-test="page-loading-overlay"
        >
            <motion.div
                class="flex min-w-[12rem] flex-col items-center gap-4 rounded-2xl border border-border bg-card px-8 py-6 shadow-xl ring-1 ring-foreground/5"
                :initial="{ opacity: 0, y: 10, scale: 0.96 }"
                :animate="{ opacity: 1, y: 0, scale: 1 }"
                :exit="{ opacity: 0, y: 6, scale: 0.98 }"
                :transition="{ duration: 0.22, ease: [0.22, 1, 0.36, 1] }"
            >
                <div class="relative size-11">
                    <span
                        class="absolute inset-0 rounded-full border-2 border-primary/20"
                    />
                    <span
                        class="absolute inset-0 animate-spin rounded-full border-2 border-transparent border-t-primary border-r-primary/40"
                    />
                    <span class="absolute inset-2 rounded-full bg-primary/10" />
                </div>
                <div class="text-center">
                    <p
                        class="text-sm font-semibold tracking-tight text-foreground"
                    >
                        {{ visitLabel }}
                    </p>
                    <p class="mt-0.5 text-xs text-muted-foreground">
                        LeadGeeks IT
                    </p>
                </div>
            </motion.div>
        </motion.div>
    </AnimatePresence>
</template>
