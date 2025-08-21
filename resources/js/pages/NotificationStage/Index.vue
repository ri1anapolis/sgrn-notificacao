<script setup lang="ts">
import NotificationStageHeader from '@/components/NotificationStageHeader.vue';
import AddressesCard from '@/components/AddressesCard.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import type { NotificationData } from '@/types/generated.ts';

const props = defineProps<{
  notification: NotificationData;
}>();

</script>

<template>
    <Head title="Fase Notificação" />
    <AppLayout link-button="dashboard" text-button="Voltar" page-title="Fase de Notificação" method="get">
        <NotificationStageHeader 
        :notification="props.notification"
        class="md:-mt-14"/>

        <AddressesCard :notification="props.notification"/>
    </AppLayout>

</template> 