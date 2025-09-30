<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import ButtonOut from '@/components/ButtonOut.vue';
import TitleHeader from '@/components/TitleHeader.vue';

const props = defineProps({
    pageTitle: {
        type: String,
        required: true,
    },
    textButton: {
        type: String,
        required: true,
    },

    linkButton: {
        type: String,
        required: true,
    },
    method: {
        type: String,
        required: false,
    },
    hasParameter: { type: Boolean, required: false, default: false },
});
</script>

<template>
    <div class="min-h-screen bg-gradient-to-b from-[#1c1710] to-black pb-24">
        <header class="flex items-center justify-center gap-6 border-b border-zinc-700 px-8 py-6 md:gap-24">
            <AppLogoIcon class-name="h-12 w-10 md:h-17 md:w-14 md:-mt-2" />
            <TitleHeader class-name="font-semibold font-serif text-sm text-center md:text-2xl text-[#f2e8ad]"
                :page-title="props.pageTitle" />
            <ButtonOut :link-button="props.linkButton" :text-button="props.textButton" :method="props.method"
                :has-parameter="props.hasParameter" />
        </header>
        <main class="mt-12 md:mt-24">
            <slot />
        </main>
    </div>
</template>
