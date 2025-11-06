<script setup lang="ts">
import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue';

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <AuthLayout :title="props.title">
        <slot />
    </AuthLayout>
</template>
