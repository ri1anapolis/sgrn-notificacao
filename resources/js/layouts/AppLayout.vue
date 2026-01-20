<script setup lang="ts">
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';

defineProps<{
    pageTitle: string;
    textButton: string;
    linkButton: string;
    method?: 'get' | 'post';
    hasParameter?: boolean;
    isDashboard?: boolean;
}>();
</script>

<template>
    <AppHeaderLayout
        :page-title="pageTitle"
        :text-button="textButton"
        :link-button="linkButton"
        :method="method"
        :has-parameter="hasParameter"
        :is-dashboard="isDashboard"
    >
        <slot />
    </AppHeaderLayout>
</template>
