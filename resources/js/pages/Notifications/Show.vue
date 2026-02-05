<script setup lang="ts">
import NotificationStageHeader from '@/components/NotificationStageHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import AddressesCard from '@/pages/Notifications/components/AddressesCard.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useToast } from 'vue-toastification';

const props = defineProps<{
    notification: App.Data.NotificationData;
}>();

const processing = ref(false);
const toast = useToast();

const closeNotification = () => {
    processing.value = true;

    router.post(
        route('notifications.close',props.notification.protocol),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Notificação encerrada com sucesso!');
                processing.value = false;
            },
            onError: () => {
                toast.error('Erro ao encerrar a notificação.', { timeout: 3000 });
                processing.value = false;
            },
        }
    );
};

const reopenNotification = () => {
    processing.value = true;

    router.post(
        route('notifications.reopen', props.notification.protocol),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Encerramento desfeito com sucesso!');
                processing.value = false;
            },
            onError: () => {
                toast.error('Erro ao desfazer encerramento.', { timeout: 3000 });
                processing.value = false;
            },
        }
    );
};
</script>

<template>
    <Head title="Fase Notificação" />
    <AppLayout link-button="dashboard" text-button="Voltar" page-title="Fase de Notificação" method="get">
        <NotificationStageHeader :notification="notification" class="md:-mt-14" />

        <AddressesCard :notification="notification" />

        <div
            v-if="notification.has_success_diligence && !notification.is_closed"
            class="mx-auto mb-6 w-11/12 max-w-4xl text-center"
        >
            <button
                @click="closeNotification"
                :disabled="processing"
                class="cursor-pointer rounded-lg border-2 border-yellow-600 bg-yellow-600 px-6 py-3 font-semibold text-white transition hover:bg-yellow-700 disabled:opacity-50"
            >
                {{ processing ? 'Encerrando...' : 'Encerrar Notificação' }}
            </button>
            <p class="mt-2 text-sm text-gray-400">
                A certidão mostrará apenas a última visita bem-sucedida
            </p>
        </div>

        <div
            v-if="notification.is_closed"
            class="mx-auto mb-6 w-11/12 max-w-4xl rounded-lg border-2 border-green-500 bg-green-500/10 p-4 text-center"
        >
            <p class="font-semibold text-green-400">
                ✓ Notificação Encerrada
            </p>
            <p class="mt-1 text-sm text-gray-300">
                A certidão contém apenas a última visita bem-sucedida
            </p>
            <button
                @click="reopenNotification"
                :disabled="processing"
                class="cursor-pointer mt-4 rounded-lg border-2 border-gray-400 bg-transparent px-4 py-2 text-sm font-medium text-gray-300 transition hover:border-gray-300 hover:bg-gray-700 disabled:opacity-50"
            >
                {{ processing ? 'Desfazendo...' : 'Desfazer Encerramento' }}
            </button>
        </div>
    </AppLayout>
</template>
