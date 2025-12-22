<script setup lang="ts">

import { useToast } from 'vue-toastification';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import DataProcessingHeader from './components/DataProcessingHeader.vue';
import NotificationForm from './components/NotificationForm.vue';

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

</script>

<template>

    <Head title="Tratamento de Dados" />
    <AppLayout link-button="dashboard" text-button="Voltar" page-title="Processamento de dados" method="get">
        <DataProcessingHeader :notification="notification" v-model:nature="selectedNature" class="md:-mt-14" />

        <div class="m-auto mt-5 h-auto w-11/12 md:w-4xl">
            <NotificationForm v-model:notified-people="form.notified_people" v-model:addresses="form.addresses"
                v-model:nature="selectedNature" v-model:notifiable="form.notifiable" :errors="form.errors" />
        </div>

        <div class="m-auto mt-8 flex w-11/12 justify-end md:w-4xl gap-6">
            <button @click.prevent="submit" :disabled="form.processing"
                class="rounded-md bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-400 hover:scale-105 duration-100 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer">
                {{ form.processing ? 'Salvando...' : 'Salvar Alterações' }}
            </button>

            <button @click="downloadDocument"
                class="rounded-md bg-amber-400 hover:bg-amber-600 px-6 py-2.5 text-sm font-semibold text-zinc-900 shadow-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer hover:scale-105 duration-100">
                Baixar Notificação
            </button>
        </div>
    </AppLayout>
</template>
