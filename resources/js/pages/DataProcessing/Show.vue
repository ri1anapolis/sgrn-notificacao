<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import DataProcessingHeader from '@/pages/DataProcessing/components/DataProcessingHeader.vue';
import NotificationForm from '@/pages/DataProcessing/components/NotificationForm.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps<{
    notification: App.Data.NotificationData;
    protocol: string;
}>();

const selectedNature = ref('');
</script>

<template>
    <Head title="Tratamento de Dados" />
    <AppLayout link-button="dashboard" text-button="Voltar" page-title="Fase de Notificação" method="get">
        <DataProcessingHeader :notification="notification" class="md:-mt-14" v-model="selectedNature" />

        <div class="m-auto mt-5 h-auto w-full max-w-4xl">
            <NotificationForm :nature="selectedNature" />
        </div>
    </AppLayout>
</template>
