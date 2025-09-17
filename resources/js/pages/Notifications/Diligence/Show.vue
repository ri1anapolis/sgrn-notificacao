<script setup lang="ts">
import NotificationStageHeader from '@/components/NotificationStageHeader.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { NotificationData } from '@/types/generated';
import { Head } from '@inertiajs/vue3';
import TabNavigation from '@/pages/Notifications/components//TabNavigation.vue';
import AddressDiligence from '@/pages/Notifications/components//AddressDiligence.vue';
import OptionsCheck from '@/pages/Notifications/components//OptionsCheck.vue';
import ButtonDate from '@/pages/Notifications/components//ButtonDate.vue';
import ButtonFinalDiligence from '@/pages/Notifications/components//ButtonFinalDiligence.vue';
import { ref } from 'vue';

const props = defineProps<{
    notification: NotificationData;
}>();

const tabs = ref(['Visita 01', 'Visita 02', 'Visita 03']);
const activeTab = ref(tabs.value[0])
const updateTab = (tab: string) => {
    activeTab.value = tab
};

</script>

<template>
    <Head title="Fase Notificação - Diligência" />
    <AppLayout link-button="dashboard" text-button="Voltar" page-title="Fase de Notificação" method="get">

        <NotificationStageHeader :notification="props.notification" class="md:-mt-14" />

        <AddressDiligence completeAddress="endereço completo" />
        <div class="mt-9 mb-3">
            <TabNavigation :tabs="tabs" @tab-change="updateTab"/>
        </div>
        <div class="bg-[#0e1423] border-2 border-gray-700 h-auto w-auto max-w-4xl p-10 m-auto mb-10 text-white rounded-lg">
            <h2 class="mb-5">Notificação - {{ activeTab }}</h2>
            <div class="flex flex-col md:flex-row mb-10 md:gap-x-8 md:items-start">
                <ButtonDate />

                <div class="flex flex-col mt-4 md:flex-1 md:mt-0">
                    <label for="observation" class="font-bold text-gray-300 mb-3">Campo de Observação</label>
                    <textarea
                        id="observation"
                        placeholder="Digite aqui os detalhes da sua diligência"
                        rows="4"
                        class="
                            w-full bg-[#1a1a1a] text-white placeholder-gray-500
                            rounded-lg border-2 border-[#3d4852]
                            focus:ring-bege-claro focus:border-bege-claro
                            transition resize-none overflow-hidden"
                    ></textarea>
                </div>
            </div>
            <div class="flex justify-between">
                <OptionsCheck/>
            </div>
            <div class="mt-4">
                <ButtonFinalDiligence :protocolo="props.notification"/>
            </div>
        </div>

    </AppLayout>
</template>