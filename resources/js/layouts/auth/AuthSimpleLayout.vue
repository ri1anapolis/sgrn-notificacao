<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex min-h-screen flex-col items-center justify-center bg-gradient-to-b from-[#1c1710] to-black">
        <div class="w-full md:w-3xl">
            <div class="text-center mb-6 md:mb-20">
                <h1 class="
                font-serif text-lg text-[#b3925c] md:text-3xl font-bold">{{ title }}</h1>
            </div>

            <div class="flex flex-col items-center mx-3 md:mx-0 gap-8 md:flex-row md:justify-center md:gap-34 md:items-start">
                <AppLogoIcon class-name="h-30 w-25 md:h-62 md:w-50 md:mt-5" />                            
                <div class="flex w-full md:max-w-lg flex-col">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>