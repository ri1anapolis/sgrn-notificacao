<script setup lang="ts">
import AppLayout from '@/layouts/app/AppHeaderLayout.vue';

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

    method: {
        type: String,
        required: false,
    }
})
</script>

<template>
    <AppLayout :text-button="props.textButton" :page-title="props.pageTitle" :link-button="props.linkButton" :method="props.method">
        <slot />
    </AppLayout>
</template>
