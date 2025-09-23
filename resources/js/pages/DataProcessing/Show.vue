<script setup lang="ts">
import DataProcessingHeader from '@/pages/DataProcessing/components/DataProcessingHeader.vue';
import NotificationForm from '@/pages/DataProcessing/components/NotificationForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps<{
    notification: App.Data.NotificationData;
}>();

const selectedNature = ref('');

</script>

<template>

    <Head title="Tratamento de Dados" />
    <AppLayout link-button="dashboard" text-button="Voltar" page-title="Fase de Notificação" method="get">

        <DataProcessingHeader :notification="notification" class="md:-mt-14" v-model="selectedNature" />

        <div class="h-auto w-full max-w-4xl mt-5 m-auto">
            <NotificationForm :nature="selectedNature" />
        </div>
    </AppLayout>
</template>
