import { router } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';

const navigating = ref(false);
const visitLabel = ref('Loading…');
let startTimer: ReturnType<typeof setTimeout> | null = null;
let removeStart: (() => void) | null = null;
let removeFinish: (() => void) | null = null;
let removeError: (() => void) | null = null;
let listenersBound = false;

function clearStartTimer(): void {
    if (startTimer) {
        clearTimeout(startTimer);
        startTimer = null;
    }
}

function labelForVisit(event: { detail?: { visit?: { method?: string; url?: { pathname?: string } | string } } }): string {
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

        removeStart = router.on('start', (event) => {
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

        removeFinish = router.on('finish', end);
        removeError = router.on('error', end);
    });

    onUnmounted(() => {});

    return {
        navigating,
        visitLabel,
    };
}

export function bindPageLoadingListenersOnce(): void {
    if (listenersBound || typeof window === 'undefined') {
        return;
    }

    listenersBound = true;

    removeStart = router.on('start', (event) => {
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

    removeFinish = router.on('finish', end);
    removeError = router.on('error', end);
}

export { navigating, visitLabel };
