<script setup lang="ts">

import { useToast } from 'vue-toastification';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import DataProcessingHeader from './components/DataProcessingHeader.vue';
import NotificationForm from './components/NotificationForm.vue';
import PublicNoticeModal from '@/pages/Notifications/components/PublicNoticeModal.vue';
import DigitalContactSelectModal from '@/pages/Notifications/components/DigitalContactSelectModal.vue';
import DigitalContactFormModal from '@/pages/Notifications/components/DigitalContactFormModal.vue';

const props = defineProps<{
    notification: App.Data.NotificationData;
}>();

import {
    NATURE_TO_TYPE_MAP,
    TYPE_TO_NATURE_MAP
} from '@/utils/NotificationNatures';

const getNatureFromType = (type: string | null | undefined): string | null => {
    if (!type) return null;
    return TYPE_TO_NATURE_MAP[type] ?? null;
};

const parseNumber = (value: unknown) => {
    if (!value) return null;
    return Number(String(value).replace(/\D/g, ''));
};

const parseCurrency = (value: unknown) => {
    if (!value) return null;
    if (typeof value === 'number') return value;
    const clean = String(value).replace(/[^\d,]/g, '').replace(',', '.');
    return Number(clean);
};

const parseDate = (value: unknown) => {
    if (!value || typeof value !== 'string') return null;
    if (value.includes('-')) return value;

    const parts = value.split('/');
    if (parts.length !== 3) return null;

    return `${parts[2]}-${parts[1]}-${parts[0]}`;
};

const selectedNature = ref<string | null>(getNatureFromType(props.notification.notifiable_type));

const form = useForm({
    notified_people: [...(props.notification.notified_people || [])],
    addresses: [...(props.notification.addresses || [])],
    notifiable: props.notification.notifiable ? { ...props.notification.notifiable } : null,
});

watch(selectedNature, (newNature, oldNature) => {
    if (newNature !== oldNature && oldNature !== undefined) {
        form.notifiable = null;
    }
});

const toast = useToast();

const showPublicNoticeModal = ref(false);
const showDigitalContactSelectModal = ref(false);
const showDigitalContactFormModal = ref(false);
const selectedPerson = ref<App.Data.NotifiedPersonData | null>(null);

const openDigitalContactFlow = () => {
    showDigitalContactSelectModal.value = true;
};

const onPersonSelected = (person: App.Data.NotifiedPersonData) => {
    selectedPerson.value = person;
    showDigitalContactSelectModal.value = false;
    showDigitalContactFormModal.value = true;
};

const onBackToPersonSelect = () => {
    showDigitalContactFormModal.value = false;
    showDigitalContactSelectModal.value = true;
};

const closeAllModals = () => {
    showPublicNoticeModal.value = false;
    showDigitalContactSelectModal.value = false;
    showDigitalContactFormModal.value = false;
    selectedPerson.value = null;
};

const submit = () => {
    const notifiableType = selectedNature.value ? NATURE_TO_TYPE_MAP[selectedNature.value] : null;

    let notifiablePayload = form.notifiable;

    if (selectedNature.value) {
        if (!notifiablePayload) {
            notifiablePayload = {};
        }

        notifiablePayload = {
            ...notifiablePayload,
            notifiable_type: notifiableType,
            office: parseNumber(notifiablePayload.office),
            emoluments_intimation: String(parseCurrency(notifiablePayload.emoluments_intimation)),
            total_amount_debt: String(parseCurrency(notifiablePayload.total_amount_debt)),
            contract_date: parseDate(notifiablePayload.contract_date),
            debt_position_date: parseDate(notifiablePayload.debt_position_date),
        };
    } else {
        notifiablePayload = null;
    }

    const addressesPayload = form.addresses.map((address) => ({
        id: address.id > 0 ? address.id : null,
        full_address: address.address,
    }));

    const finalPayload = {
        ...form.data(),
        notifiable: notifiablePayload,
        notified_people: form.notified_people.map((notifiedPerson) => ({
            id: notifiedPerson.id > 0 ? notifiedPerson.id : null,
            name: notifiedPerson.name,
            document: notifiedPerson.document,
            email: notifiedPerson.email,
            phone: notifiedPerson.phone,
            gender: notifiedPerson.gender,
        })),
        addresses: addressesPayload,
    };

    form.transform(() => finalPayload).put(route('data-processing.update', props.notification.protocol), {
        preserveScroll: true,

        onSuccess: () => {
            toast.success("Alterações Salvas Com Sucesso!");
        },

        onError: (errors) => {
            console.log("ERROS DE VALIDAÇÃO:", errors);
            let specificErrorShown = false;

            const hasAddressError = Object.keys(errors).some(key => key.startsWith('addresses'));
            if (hasAddressError) {
                toast.error("Erro ao salvar. O campo de endereço não foi preenchido.");
                specificErrorShown = true;
            }

            const hasPeopleError = Object.keys(errors).some(key => key.startsWith('notified_people'));
            if (hasPeopleError) {
                toast.error("Erro ao salvar. O campo do Notificado não foi preenchido.");
                specificErrorShown = true;
            }

            if (!specificErrorShown) {
                toast.error("Erro ao salvar. Verifique os campos.");
            }
        }
    });
};

const downloadDocument = () => {
    if (form.isDirty) {
        alert('Salve as alterações antes de gerar o documento!');
        return;
    }

    const url = route('data-processing.notification.download', props.notification.protocol);
    window.open(url, '_self');
};

const downloadEnvelope = () => {
    if (form.isDirty) {
        alert('Salve as alterações antes de gerar o documento!');
        return;
    }

    const url = route('data-processing.envelope.download', props.notification.protocol);
    window.open(url, '_self');
};

const downloadCertificate = () => {
    if (form.isDirty) {
        alert('Salve as alterações antes de gerar o documento!');
        return;
    }

    if (!props.notification.can_download_certificate) {
        toast.info("A certidão só pode ser emitida se houver uma notificação de sucesso ou se todos os endereços tiverem 3 visitas realizadas.");
        return;
    }

    const url = route('data-processing.certificate.download', props.notification.protocol);
    window.open(url, '_self');
};

</script>

<template>

    <Head title="Tratamento de Dados" />
    <AppLayout link-button="dashboard" text-button="Voltar" page-title="Processamento de dados" method="get">
        <DataProcessingHeader :notification="notification" v-model:nature="selectedNature" class="md:-mt-14" />

        <div class="m-auto mt-5 h-auto w-11/12 md:w-4xl">
            <NotificationForm v-model:notified-people="form.notified_people" v-model:addresses="form.addresses"
                v-model:nature="selectedNature" v-model:notifiable="form.notifiable" :errors="form.errors" />
        </div>

        <div class="m-auto mt-8 flex w-11/12 justify-end md:w-4xl gap-6 flex-wrap">
            <button @click.prevent="submit" :disabled="form.processing"
                class="rounded-md bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-400 hover:scale-105 duration-100 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer">
                {{ form.processing ? 'Salvando...' : 'Salvar Alterações' }}
            </button>

            <button @click="downloadDocument"
                class="rounded-md bg-amber-400 hover:bg-amber-600 px-6 py-2.5 text-sm font-semibold text-zinc-900 shadow-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer hover:scale-105 duration-100">
                Baixar Notificação
            </button>

            <button @click="downloadEnvelope"
                class="rounded-md bg-stone-500 hover:bg-stone-700 px-6 py-2.5 text-sm font-semibold text-white shadow-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer hover:scale-105 duration-100">
                Baixar Envelope
            </button>

            <button @click="downloadCertificate"
                :class="[
                    'rounded-md px-6 py-2.5 text-sm font-semibold shadow-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 cursor-pointer hover:scale-105 duration-100',
                    notification.can_download_certificate ? 'bg-emerald-600 hover:bg-emerald-800 text-white' : 'bg-zinc-400 text-zinc-100'
                ]">
                Baixar Certidão
            </button>

            <button @click="showPublicNoticeModal = true"
                class="rounded-md bg-[#d4af37] hover:bg-[#f0c850] px-6 py-2.5 text-sm font-semibold text-black shadow-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#d4af37] cursor-pointer hover:scale-105 duration-100">
                Dados de Edital
            </button>

            <button @click="openDigitalContactFlow"
                class="rounded-md bg-green-600 hover:bg-green-700 px-6 py-2.5 text-sm font-semibold text-white shadow-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 cursor-pointer hover:scale-105 duration-100">
                WhatsApp/Email
            </button>
        </div>

        <PublicNoticeModal
            :show="showPublicNoticeModal"
            :notification-protocol="notification.protocol"
            :public-notice="notification.public_notice"
            @close="showPublicNoticeModal = false"
        />

        <DigitalContactSelectModal
            :show="showDigitalContactSelectModal"
            :notified-people="form.notified_people"
            @close="showDigitalContactSelectModal = false"
            @select="onPersonSelected"
        />

        <DigitalContactFormModal
            :show="showDigitalContactFormModal"
            :notification-protocol="notification.protocol"
            :person="selectedPerson"
            @close="closeAllModals"
            @back="onBackToPersonSelect"
        />
    </AppLayout>
</template>

