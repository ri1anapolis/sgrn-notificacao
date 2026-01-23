<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useToast } from 'vue-toastification';

const props = defineProps<{
    show: boolean;
    template: App.Data.DocumentTemplateData | null;
}>();

const emit = defineEmits(['close']);
const toast = useToast();

const selectedFile = ref<File | null>(null);
const isUploading = ref(false);

watch(
    () => props.show,
    (show) => {
        if (show) {
            selectedFile.value = null;
        }
    },
);

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        selectedFile.value = target.files[0];
    }
};

const submit = () => {
    if (!selectedFile.value || !props.template) return;

    isUploading.value = true;

    const formData = new FormData();
    formData.append('file', selectedFile.value);

    router.post(route('templates.update', props.template.id), formData, {
        forceFormData: true,
        onSuccess: () => {
            emit('close');
            isUploading.value = false;
        },
        onError: () => {
            toast.error('Erro ao enviar o arquivo. Verifique se é um .docx válido.');
            isUploading.value = false;
        },
    });
};
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm" @click="emit('close')" />

    <div v-if="show" class="fixed inset-0 z-50 m-auto h-fit max-h-[90vh] w-11/12 max-w-lg rounded-lg border-1 border-gray-700 bg-[#0e1423] shadow-xl">
        <div class="border-b border-yellow-800 p-6">
            <h3 class="text-xl font-semibold text-yellow-600">Enviar Novo Template</h3>
        </div>

        <div class="space-y-4 p-6 text-gray-300">
            <p>Selecione um novo arquivo para o template:</p>
            <p class="font-bold text-white">{{ template?.title }}</p>

            <div class="mt-4">
                <label class="mb-2 block font-bold text-gray-400">Arquivo (.docx)</label>
                <input
                    type="file"
                    accept=".docx"
                    @change="handleFileChange"
                    class="w-full rounded-lg border-2 border-[#3d4852] bg-[#1a1a1a] p-3 text-white file:mr-4 file:rounded file:border-0 file:bg-yellow-600 file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-yellow-700"
                />
                <p v-if="selectedFile" class="mt-2 text-sm text-green-400 underline decoration-green-900 underline-offset-4">
                    Selecionado: {{ selectedFile.name }}
                </p>
            </div>
        </div>

        <div class="flex justify-end gap-4 border-t border-gray-700 p-6">
            <button type="button" @click="emit('close')" class="px-4 py-2 text-gray-400 hover:text-white transition">Cancelar</button>

            <button
                type="button"
                :disabled="!selectedFile || isUploading"
                @click="submit"
                class="rounded-md bg-yellow-600 px-6 py-2 font-bold text-white transition hover:bg-yellow-700 disabled:cursor-not-allowed disabled:opacity-50"
            >
                {{ isUploading ? 'Enviando...' : 'Fazer Upload' }}
            </button>
        </div>
    </div>
</template>
