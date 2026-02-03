<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useToast } from 'vue-toastification';
import RestoreTemplateModal from './components/RestoreTemplateModal.vue';
import UploadTemplateModal from './components/UploadTemplateModal.vue';

const props = defineProps<{
    templates: App.Data.DocumentTemplateData[];
}>();

const page = usePage();
const toast = useToast();

const showUploadModal = ref(false);
const showRestoreModal = ref(false);
const selectedTemplate = ref<App.Data.DocumentTemplateData | null>(null);

watch(
    () => page.props.flash?.success,
    (message) => {
        if (message) {
            toast.success(message as string, { timeout: 1000 });
        }
    },
    { immediate: true },
);

const openUploadModal = (template: App.Data.DocumentTemplateData) => {
    selectedTemplate.value = template;
    showUploadModal.value = true;
};

const openRestoreModal = (template: App.Data.DocumentTemplateData) => {
    selectedTemplate.value = template;
    showRestoreModal.value = true;
};

const downloadTemplate = (template: App.Data.DocumentTemplateData) => {
    window.location.href = route('templates.download', template.id);
};

const downloadOriginal = (template: App.Data.DocumentTemplateData) => {
    window.location.href = route('templates.download-original', template.id);
};
</script>

<template>
    <Head title="Gerenciamento de Templates" />

    <AppLayout page-title="Gerenciamento de Templates" text-button="Voltar para Início" link-button="dashboard" method="get">
        <div class="mx-auto max-w-7xl -mt-5 px-4 pb-10">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-white">Templates de Documentos</h2>
                <div class="mt-2 space-y-6">
                    <p class="text-lg text-gray-400">Gerencie os modelos de documentos utilizados pelo sistema.</p>
                    <p class="text-base text-gray-300 max-w-7xl">
                        <span class="text-yellow-500/80 font-semibold">Nota:</span> Esta funcionalidade deve ser usada apenas para ajustes de layout, espaçamentos ou correções ortográficas.
                        Não altere as variáveis (ex: <code class="text-gray-100 font-mono">${variavel}</code>), pois elas são processadas pelo sistema.
                        Para alterações que exijam novas variáveis, entre em contato com os desenvolvedores.
                    </p>
                </div>
            </div>

            <div class="overflow-hidden rounded-lg border-2 border-gray-700 bg-[#0e1423]">
                <table class="w-full text-left text-white">
                    <thead class="bg-[#1a1a1a] text-sm uppercase text-gray-400">
                        <tr>
                            <th class="p-4">Template</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Última Atualização</th>
                            <th class="p-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        <tr v-for="template in templates" :key="template.id" class="transition hover:bg-white/5">
                            <td class="p-4">
                                <div class="font-medium">{{ template.title }}</div>
                                <div v-if="template.description" class="mt-1 text-sm text-gray-500">{{ template.description }}</div>
                            </td>
                            <td class="p-4">
                                <span
                                    class="rounded px-2 py-1 text-xs font-bold"
                                    :class="template.is_customized ? 'bg-yellow-600/20 text-yellow-500' : 'bg-green-600/20 text-green-400'"
                                >
                                    {{ template.is_customized ? 'CUSTOMIZADO' : 'PADRÃO' }}
                                </span>
                            </td>
                            <td class="p-4 text-gray-300">
                                <template v-if="template.updated_at">
                                    {{ template.updated_at }}
                                    <span v-if="template.updated_by" class="block text-xs text-gray-500">por {{ template.updated_by }}</span>
                                </template>
                                <span v-else class="text-gray-500">-</span>
                            </td>
                            <td class="p-4">
                                <div class="flex flex-wrap items-center justify-end gap-4">
                                    <button @click="downloadTemplate(template)" class="font-semibold text-blue-400 hover:cursor-pointer hover:text-blue-300 transition">
                                        Baixar
                                    </button>
                                    <button
                                        v-if="template.is_customized"
                                        @click="downloadOriginal(template)"
                                        class="font-semibold text-gray-400 hover:cursor-pointer hover:text-gray-300 transition"
                                    >
                                        Baixar Original
                                    </button>
                                    <button @click="openUploadModal(template)" class="font-semibold text-yellow-600 hover:cursor-pointer hover:text-yellow-500 transition">
                                        Enviar Novo
                                    </button>
                                    <button
                                        v-if="template.is_customized"
                                        @click="openRestoreModal(template)"
                                        class="font-semibold text-red-500 hover:cursor-pointer hover:text-red-400 transition"
                                    >
                                        Restaurar Padrão
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <UploadTemplateModal :show="showUploadModal" :template="selectedTemplate" @close="showUploadModal = false" />

        <RestoreTemplateModal :show="showRestoreModal" :template="selectedTemplate" @close="showRestoreModal = false" />
    </AppLayout>
</template>
