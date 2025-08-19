<script setup lang="ts">
import TitleHeader from '@/components/TitleHeader.vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import ButtonOut from '@/components/ButtonOut.vue';

const props = defineProps({
    pageTitle: {
        type: String,
        required: true,
    },
    textButton:{
        type: String,
        required: true,
    },

    linkButton:{
        type: String,
        required: true,
    },
})

</script>

<template>
    <div class="min-h-screen bg-gradient-to-b from-[#1c1710] to-black pb-24">
        <header class="flex items-center justify-center gap-6 md:gap-24 border-b border-zinc-700 py-6 px-8">
            <AppLogoIcon class-name="h-12 w-10 md:h-17 md:w-14 md:-mt-2"/>
            <TitleHeader class-name="font-semibold font-serif text-sm text-center md:text-2xl text-[#f2e8ad]" :page-title="props.pageTitle"/>
            <ButtonOut :link-button="props.linkButton" :text-button="props.textButton"/>
        </header>
        <main class="mt-12 md:mt-24">
            <slot />
        </main>
    </div>
</template>
 