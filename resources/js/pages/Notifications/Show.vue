<script setup lang="ts">
import NotificationStageHeader from '@/components/NotificationStageHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import AddressesCard from '@/pages/Notifications/components/AddressesCard.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{
    notification: App.Data.NotificationData;
}>();
</script>

<template>
    <Head title="Fase Notificação" />
    <AppLayout link-button="dashboard" text-button="Voltar" page-title="Fase de Notificação" method="get">
        <NotificationStageHeader :notification="notification" class="md:-mt-14" />

        <AddressesCard :notification="notification" />
    </AppLayout>
</template>
