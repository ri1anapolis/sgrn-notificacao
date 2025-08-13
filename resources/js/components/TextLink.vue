<script setup lang="ts">
import { Method } from '@inertiajs/core';
import { Link } from '@inertiajs/vue3';

interface Props {
    href: string;
    method?: Method;
    as?: string;
}

defineProps<Props>();
</script>

<template>
    <Link
        :href="href"
        :method="method"
        :as="as"
        class="text-zinc-400 underline hover:text-slate-300"
    >
        <slot />
    </Link>
</template>
