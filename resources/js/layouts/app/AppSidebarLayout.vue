<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppLoadingOverlay from '@/components/AppLoadingOverlay.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import PageTransition from '@/components/PageTransition.vue';
import { Toaster } from '@/components/ui/sonner';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar />
        <AppContent variant="sidebar" class="overflow-x-hidden">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <PageTransition>
                <slot />
            </PageTransition>
        </AppContent>
        <AppLoadingOverlay />
        <Toaster />
    </AppShell>
</template>
