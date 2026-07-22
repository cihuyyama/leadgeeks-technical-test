<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { motion } from 'motion-v';
import { onMounted, onUnmounted } from 'vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { store } from '@/routes/login';

defineOptions({
    layout: {
        title: 'Log in to your account',
        description: 'Enter your email and password below to log in',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

function reloadIfRestoredFromBfcache(event: PageTransitionEvent): void {
    if (event.persisted) {
        window.location.reload();
    }
}

onMounted(() => {
    window.addEventListener('pageshow', reloadIfRestoredFromBfcache);
});

onUnmounted(() => {
    window.removeEventListener('pageshow', reloadIfRestoredFromBfcache);
});
</script>

<template>
    <Head title="Log in" />

    <div
        v-if="status"
        class="mb-4 text-center text-sm font-medium text-green-600"
    >
        {{ status }}
    </div>

    <div
        class="mx-auto grid w-full max-w-md gap-4 md:max-w-2xl md:grid-cols-[minmax(0,22rem)_minmax(0,15.5rem)] md:items-stretch md:justify-center md:gap-5"
    >
        <Form
            v-bind="store.form()"
            :reset-on-success="['password']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6 rounded-xl border border-border/80 bg-card p-5 shadow-none ring-1 ring-foreground/5"
        >
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email">Email address</Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        placeholder="demo@leadgeeks.test"
                        data-test="login-email"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <PasswordInput
                        id="password"
                        name="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        placeholder="Password"
                        data-test="login-password"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="flex items-center justify-between">
                    <Label for="remember" class="flex items-center space-x-3">
                        <Checkbox id="remember" name="remember" :tabindex="3" />
                        <span>Remember me</span>
                    </Label>
                </div>

                <Button
                    type="submit"
                    class="mt-4 w-full"
                    :tabindex="4"
                    :disabled="processing"
                    data-test="login-button"
                >
                    <Spinner v-if="processing" />
                    Log in
                </Button>
            </div>
        </Form>

        <motion.aside
            class="flex h-full flex-col justify-center rounded-xl border border-primary/20 bg-accent p-4 text-sm shadow-sm"
            data-test="demo-credentials"
            :initial="{ opacity: 0, y: 6 }"
            :animate="{ opacity: 1, y: 0 }"
            :transition="{ duration: 0.22, ease: [0.22, 1, 0.36, 1] }"
        >
            <div class="flex items-center gap-2">
                <span
                    class="inline-flex size-2 rounded-full bg-primary"
                    aria-hidden="true"
                />
                <p class="font-semibold text-accent-foreground">Demo access</p>
            </div>
            <p class="mt-1.5 text-muted-foreground">
                LeadGeeks Inc technical assessment — no registration required.
            </p>
            <dl class="mt-3 grid gap-1.5 font-mono text-xs sm:text-sm">
                <div class="flex flex-wrap gap-x-2">
                    <dt class="text-muted-foreground">Email:</dt>
                    <dd class="font-semibold text-foreground">
                        demo@leadgeeks.test
                    </dd>
                </div>
                <div class="flex flex-wrap gap-x-2">
                    <dt class="text-muted-foreground">Password:</dt>
                    <dd class="font-semibold text-foreground">password</dd>
                </div>
            </dl>
        </motion.aside>
    </div>
</template>
