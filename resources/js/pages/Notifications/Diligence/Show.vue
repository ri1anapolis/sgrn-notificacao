<script setup lang="ts">
import NotificationStageHeader from '@/components/NotificationStageHeader.vue';
import RadioGroup from '@/components/RadioGroup.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import AddressDiligence from '@/pages/Notifications/components//AddressDiligence.vue';
import ButtonDate from '@/pages/Notifications/components//ButtonDate.vue';
import TabNavigation from '@/pages/Notifications/components//TabNavigation.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { useToast } from 'vue-toastification';
import ButtonSaveDiligence from '../components/ButtonSaveDiligence.vue';

const props = defineProps<{
    notification: App.Data.NotificationData;
    address: App.Data.AddressData & {
        diligences: App.Data.DiligenceData[];
    };
    diligenceResults: App.Data.DiligenceResultData[];
}>();

const tabs = ref(['Visita 01', 'Visita 02', 'Visita 03']);
const diligenceCount = computed(() => props.address.diligences.length);
const initialTabIndex = Math.min(diligenceCount.value, tabs.value.length - 1);
const activeTab = ref(tabs.value[initialTabIndex]);

const form = useForm({
    visit_number: 1,
    diligence_result_id: null as number | null,
    observations: '',
    date: null as string | null,
});

const isFormComplete = computed<boolean>(() => {
    return form.diligence_result_id !== null && form.date !== null;
});

const groupedDiligenceResults = computed(() => {
    return props.diligenceResults.reduce(
        (acc, result) => {
            if (!acc[result.group]) {
                acc[result.group] = [];
            }
            acc[result.group].push(result);
            return acc;
        },
        {} as Record<string, App.Data.DiligenceResultData[]>,
    );
});

const diligenceForActiveTab = computed(() => {
    const visitNumber = parseInt(activeTab.value.replace('Visita ', ''), 10);

    return props.address.diligences.find((d: App.Data.DiligenceData) => d.visit_number === visitNumber);
});

const isReadOnly = computed(() => !!diligenceForActiveTab.value);

const selectedResultDescription = computed(() => {
    if (!diligenceForActiveTab.value || !diligenceForActiveTab.value.diligence_result) {
        return 'N/A';
    }
    return diligenceForActiveTab.value.diligence_result.description;
});

const updateTab = (tab: string) => {
    activeTab.value = tab;
};

watch(
    activeTab,
    (newTab) => {
        form.visit_number = parseInt(newTab.replace('Visita ', ''), 10);
    },
    { immediate: true },
);

const handleDateRegistration = (dateTime: string) => {
    form.date = dateTime;
};
const toast = useToast();
const saveDiligence = () => {
    if (isReadOnly.value) return;
    form.post(
        route('notifications.diligence.store', {
            notification: props.notification.protocol,
            address: props.address.id,
        }),
        {
            preserveScroll: true,
            onSuccess: () => {
                form.reset('diligence_result_id', 'observations', 'date');
                toast.success('Notificação registrada com sucesso!');
                setTimeout(() => {
                    router.visit(route('dashboard'));
                }, 1500);
            },
        },
    );
};
</script>

<template>
    <Head title="Fase Notificação - Diligência" />
    <AppLayout
        :link-button="route('notifications.show', notification.protocol)"
        :has-parameter="true"
        text-button="Voltar"
        page-title="Fase de Notificação"
        method="get"
    >
        <NotificationStageHeader :notification="notification" :address="address" class="md:-mt-14" />

        <div class="mb-6">
            <AddressDiligence :address="address" />
        </div>

        <div class="mt-9 mb-3">
            <TabNavigation :tabs="tabs" :diligences-count="diligenceCount" :active-tab="activeTab" @tab-change="updateTab" />
        </div>

        <form
            @submit.prevent="saveDiligence"
            class="mx-auto mb-10 h-auto w-11/12 rounded-lg border-2 border-gray-700 bg-[#0e1423] p-10 text-white md:w-4xl"
        >
            <h2 class="mb-8 text-lg">Notificação - {{ activeTab }}</h2>

            <div v-if="isReadOnly && diligenceForActiveTab" class="mb-10 flex flex-col gap-6">
                <div>
                    <p class="mb-1 text-gray-300">Registrado em:</p>
                    <span class="text-bege-claro text-lg font-semibold">
                        {{ new Date(diligenceForActiveTab.date).toLocaleString('pt-BR') }}
                    </span>
                </div>

                <div>
                    <label class="font-bold text-gray-300">Campo de Observação:</label>
                    <p class="mt-2 w-full resize-none overflow-hidden rounded-lg border-2 border-[#3d4852] bg-[#1a1a1a] p-3 text-white">
                        {{ diligenceForActiveTab.observations || 'Nenhuma observação.' }}
                    </p>
                </div>

                <div>
                    <p class="mb-1 text-gray-300">Feito por:</p>
                    <span class="text-bege-claro text-lg font-semibold">
                        {{ diligenceForActiveTab.user?.name || 'Usuário não identificado' }}
                    </span>
                </div>
            </div>
            <div v-else class="mb-10 flex flex-col md:flex-row md:items-start md:gap-x-8">
                <ButtonDate @date-registered="handleDateRegistration" />
                <div class="mt-4 flex flex-col md:mt-0 md:flex-1">
                    <label for="observation" class="mb-3 font-bold text-gray-300">Campo de Observação</label>
                    <textarea
                        id="observation"
                        v-model="form.observations"
                        placeholder="Digite aqui os detalhes da sua diligência"
                        rows="4"
                        class="focus:ring-bege-claro focus:border-bege-claro w-full resize-none overflow-hidden rounded-lg border-2 border-[#3d4852] bg-[#1a1a1a] p-3 text-white placeholder-gray-500 transition"
                    >
                    </textarea>
                </div>
            </div>

            <div v-if="isReadOnly" class="flex flex-col gap-y-4">
                <h3 class="text-bege-claro flex items-center font-semibold">
                    <span class="bg-bege-claro/50 h-px flex-grow"></span>
                    <span class="mx-4 text-lg text-yellow-600">Resultado da Diligência</span>
                    <span class="bg-bege-claro/50 h-px flex-grow"></span>
                </h3>
                <p class="rounded-lg border-2 border-[#b3925c21] bg-[#0e1423] p-6 text-center text-white">
                    {{ selectedResultDescription }}
                </p>
            </div>

            <div v-else class="flex flex-col items-center gap-y-8">
                <RadioGroup
                    v-for="(results, group) in groupedDiligenceResults"
                    :key="group"
                    :title="group"
                    :options="results"
                    v-model="form.diligence_result_id"
                    name="diligence_result_id"
                />
            </div>

            <div v-if="!isReadOnly" class="mt-8">
                <div class="flex w-full flex-col items-center">
                    <ButtonSaveDiligence
                        text="Salvar Dados da Visita"
                        processing-text="Salvando..."
                        :processing="form.processing"
                        :disabled="!isFormComplete || form.processing"
                    />
                </div>
            </div>
        </form>
    </AppLayout>
</template>
