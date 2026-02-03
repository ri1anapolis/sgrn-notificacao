<script setup lang="ts">
import InputDateForm from '@/components/InputDateForm.vue';
import { useForm } from '@inertiajs/vue3';
import { vMaska } from 'maska/vue';
import { computed, watch } from 'vue';
import { useToast } from 'vue-toastification';
import { formatDateForInput } from '@/utils/formatters';

const props = defineProps<{
    show: boolean;
    notificationProtocol: string;
    person: App.Data.NotifiedPersonData | null;
}>();

const emit = defineEmits(['close', 'back']);
const toast = useToast();

const form = useForm({
    contact_date: null as string | null,
    contact_time: '',
    whatsapp_result: '',
    email_result: '',
    custom_result: '',
});

const timeMaskOptions = {
    mask: '##:##',
};

watch([() => props.show, () => props.person], ([isShow, person]) => {
    if (isShow && person) {
        const contact = person.digital_contact;
        if (contact) {
            form.contact_date = formatDateForInput(contact.contact_date);
            form.contact_time = contact.contact_time || '';
            form.whatsapp_result = contact.whatsapp_result || '';
            form.email_result = contact.email_result || '';
            form.custom_result = contact.custom_result || '';
        } else {
            form.reset();
        }
    }
}, { immediate: true });

const isFormValid = computed(() => {
    const hasDate = !!form.contact_date;
    const hasTime = form.contact_time.length === 5;
    const hasWhatsapp = !!form.whatsapp_result?.trim();
    const hasEmail = !!form.email_result?.trim();
    const hasCustom = !!form.custom_result?.trim();
    
    return hasDate && hasTime && (hasWhatsapp || hasEmail || hasCustom);
});

const submit = () => {
    if (!props.person) return;

    form.post(route('notifications.digital-contacts.store', {
        notification: props.notificationProtocol,
        notifiedPerson: props.person.id,
    }), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Contato registrado com sucesso!');
            emit('close');
        },
        onError: () => {
            toast.error('Erro ao registrar contato. Verifique os campos.', { timeout: 3000 });
        },
    });
};
</script>


<template>
    <div v-if="show" @click="emit('close')" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm transition-opacity">
    </div>

    <div v-if="show && person"
        class="fixed inset-0 z-50 m-auto h-fit max-h-[90vh] w-11/12 max-w-2xl overflow-y-auto rounded-lg border-2 border-gray-700 bg-[#0e1423] shadow-lg">
        <div class="sticky top-0 flex items-center justify-between border-b border-gray-700 bg-[#0e1423] p-8">
            <div class="flex items-center gap-4">
                <button @click="emit('back')" class="text-gray-400 transition hover:text-white cursor-pointer hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>
                <h3 class="text-xl font-semibold text-[#d4af37]">
                    Registrar Contato para {{ person.name }}
                </h3>
            </div>
            <button @click="emit('close')" class="text-gray-400 transition hover:text-white cursor-pointer hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form @submit.prevent="submit" class="p-8 space-y-6 text-sm">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Data do Contato *</label>
                    <InputDateForm
                        id="contact-date"
                        label=""
                        class="text-white border-gray-600 bg-[#1a1f2e] p-3"
                        v-model="form.contact_date"
                    />
                    <p v-if="form.errors.contact_date" class="mt-1 text-sm text-red-500">{{ form.errors.contact_date }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Horário de Contato *</label>
                    <input v-model="form.contact_time" v-maska="timeMaskOptions" type="tel" placeholder="--:--"
                        class="w-full rounded-md border border-gray-600 bg-[#1a1f2e] p-3 text-white focus:border-[#d4af37] focus:ring focus:ring-[#d4af37]/20" />
                    <p v-if="form.errors.contact_time" class="mt-1 text-sm text-red-500">{{ form.errors.contact_time }}</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Resultado do Contato por WhatsApp *</label>
                <textarea v-model="form.whatsapp_result" rows="3" placeholder="Digite o resultado do WhatsApp"
                    class="w-full rounded-md border border-gray-600 bg-[#1a1f2e] p-3 text-white focus:border-[#d4af37] focus:ring focus:ring-[#d4af37]/20"></textarea>
                <p v-if="form.errors.whatsapp_result" class="mt-1 text-sm text-red-500">{{ form.errors.whatsapp_result }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Resultado do Contato por E-mail *</label>
                <textarea v-model="form.email_result" rows="3" placeholder="Digite o resultado do e-mail"
                    class="w-full rounded-md border border-gray-600 bg-[#1a1f2e] p-3 text-white focus:border-[#d4af37] focus:ring focus:ring-[#d4af37]/20"></textarea>
                <p v-if="form.errors.email_result" class="mt-1 text-sm text-red-500">{{ form.errors.email_result }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Resultado do Contato (fora das opções padrão)</label>
                <textarea v-model="form.custom_result" rows="3" placeholder="Digite aqui o resultado"
                    class="w-full rounded-md border border-gray-600 bg-[#1a1f2e] p-3 text-white focus:border-[#d4af37] focus:ring focus:ring-[#d4af37]/20"></textarea>
                <p v-if="form.errors.custom_result" class="mt-1 text-sm text-red-500">{{ form.errors.custom_result }}</p>
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t border-gray-700 ">
                <button type="button" @click="emit('close')"
                    class="cursor-pointer px-6 py-2 rounded-md border border-gray-600 text-gray-300 hover:bg-gray-700 transition hover:scale-105">
                    Cancelar
                </button>
                <button type="submit" :disabled="form.processing || !isFormValid"
                    class="cursor-pointer px-6 py-2 rounded-md bg-[#d4af37] text-black font-medium hover:bg-[#f0c850] transition hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                    Salvar Contato
                </button>
            </div>
        </form>
    </div>
</template>
