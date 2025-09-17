<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

</script>

<template>
    <div class="flex flex-col w-full md:w-80 md:justify-self-center">
        <Link
            :href="route('dashboard')"
            as="button"
            class="mb-3 bg-bege-claro text-zinc-950 font-medium justify-center p-3 rounded-lg text-xl transition-all hover:brightness-150 active:scale-95">
            Salvar dados da visita
        </Link>
    </div>
</template>