<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { motion } from 'motion-v';
import AppLoadingOverlay from '@/components/AppLoadingOverlay.vue';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="auth-brand-shell relative flex min-h-svh flex-col items-center justify-center gap-6 overflow-hidden bg-background p-6 md:p-10"
    >
        <motion.div
            class="relative w-full max-w-md md:max-w-5xl"
            :initial="{ opacity: 0, y: 12 }"
            :animate="{ opacity: 1, y: 0 }"
            :transition="{ duration: 0.28, ease: [0.22, 1, 0.36, 1] }"
        >
            <div class="flex flex-col gap-8">
                <div class="mx-auto flex w-full max-w-sm flex-col items-center gap-4">
                    <Link
                        :href="home()"
                        class="flex flex-col items-center gap-3 font-medium"
                    >
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-xl bg-white shadow-sm ring-1 ring-border"
                        >
                            <img
                                src="/images/leadgeeks-mark.png"
                                alt="LeadGeeks Inc"
                                class="size-10 object-contain"
                                width="40"
                                height="40"
                            />
                        </div>
                        <div class="text-center">
                            <p
                                class="text-sm font-semibold tracking-tight text-foreground"
                            >
                                LeadGeeks Inc
                            </p>
                            <p class="text-xs text-muted-foreground">
                                Internal IT Tickets
                            </p>
                        </div>
                        <span class="sr-only">{{ title }}</span>
                    </Link>
                    <div class="space-y-2 text-center">
                        <h1
                            class="text-xl font-semibold tracking-tight text-foreground"
                        >
                            {{ title }}
                        </h1>
                        <p class="text-center text-sm text-muted-foreground">
                            {{ description }}
                        </p>
                    </div>
                </div>
                <slot />
            </div>
        </motion.div>
        <AppLoadingOverlay />
    </div>
</template>
