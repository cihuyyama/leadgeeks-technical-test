import { router } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const navigating = ref(false);
const visitLabel = ref('Loading…');
let startTimer: ReturnType<typeof setTimeout> | null = null;
let listenersBound = false;

function clearStartTimer(): void {
    if (startTimer) {
        clearTimeout(startTimer);
        startTimer = null;
    }
}

function labelForVisit(event: {
    detail?: {
        visit?: { method?: string; url?: { pathname?: string } | string };
    };
}): string {
    const visit = event.detail?.visit;
    const method = (visit?.method ?? 'get').toLowerCase();

    if (method === 'delete') {
        return 'Deleting…';
    }

    if (method === 'post') {
        return 'Saving…';
    }

    if (method === 'put' || method === 'patch') {
        return 'Updating…';
    }

    return 'Loading…';
}

export function usePageLoading() {
    onMounted(() => {
        if (listenersBound || typeof window === 'undefined') {
            return;
        }

        listenersBound = true;
        bindRouterListeners();
    });

    return {
        navigating,
        visitLabel,
    };
}

function bindRouterListeners(): void {
    router.on('start', (event) => {
        visitLabel.value = labelForVisit(event as never);
        clearStartTimer();
        startTimer = setTimeout(() => {
            navigating.value = true;
        }, 120);
    });

    const end = () => {
        clearStartTimer();
        navigating.value = false;
    };

    router.on('finish', end);
    router.on('error', end);
}

export function bindPageLoadingListenersOnce(): void {
    if (listenersBound || typeof window === 'undefined') {
        return;
    }

    listenersBound = true;
    bindRouterListeners();
}

export { navigating, visitLabel };
