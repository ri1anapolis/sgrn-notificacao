<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
    show: boolean;
    template: App.Data.DocumentTemplateData | null;
}>();

const emit = defineEmits(['close']);

const confirmationText = ref('');
const form = useForm({});

watch(
    () => props.show,
    (show) => {
        if (show) {
            confirmationText.value = '';
        }
    },
);

const isConfirmed = computed(() => {
    confirmationText.value = confirmationText.value.toUpperCase();
    return confirmationText.value === 'RESTAURAR';
});

const submit = () => {
    if (!props.template) return;

    form.delete(route('templates.restore', props.template.id), {
        onSuccess: () => emit('close'),
    });
};
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm" @click="emit('close')" />

    <div v-if="show" class="fixed inset-0 z-50 m-auto h-fit max-h-[90vh] w-11/12 max-w-lg rounded-lg border-1 border-gray-700 bg-[#0e1423] shadow-xl">
        <div class="border-b border-red-700 p-6">
            <h3 class="text-xl font-semibold text-red-500">Restaurar Template Padrão</h3>
        </div>

        <div class="space-y-5 p-6 text-sm text-gray-300">
            <p>Esta ação irá <span class="font-semibold text-red-500">excluir permanentemente</span> a customização do template:</p>

            <p class="font-bold text-white">{{ template?.title }}</p>

            <p>Para confirmar a restauração ao padrão original, digite <span class="font-bold text-yellow-500">RESTAURAR</span> abaixo:</p>

            <input
                v-model="confirmationText"
                type="text"
                placeholder="Digite RESTAURAR"
                class="w-full rounded-md border border-gray-600 bg-[#1a1a1a] p-3 text-white focus:border-red-500 uppercase"
            />

            <p class="text-xs text-gray-400 my-3">Após restaurar, terá somente o template padrão, sem customizações.</p>
        </div>

        <div class="flex justify-end gap-4 border-t border-gray-700 p-6">
            <button type="button" @click="emit('close')" class="px-4 py-2 text-gray-400 hover:text-white transition">Cancelar</button>

            <button
                type="button"
                :disabled="!isConfirmed || form.processing"
                @click="submit"
                class="rounded-md bg-red-600 px-6 py-2 font-bold text-white transition hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-50"
            >
                Restaurar Padrão
            </button>
        </div>
    </div>
</template>
