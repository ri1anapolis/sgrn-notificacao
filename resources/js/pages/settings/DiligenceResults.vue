<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useToast } from 'vue-toastification';

type GroupedResults = {
    title: string;
    results: App.Data.DiligenceResultData[];
}[];

const props = defineProps<{
    groups: GroupedResults;
}>();

const page = usePage();
const toast = useToast();

const editingResultId = ref<number | null>(null);
const editForm = useForm({ description: '' });

const addForm = useForm({
    group: '',
    code: '',
    description: '',
});

const showAddModal = ref(false);
const expandedGroups = ref<Set<string>>(new Set(props.groups.map(g => g.title)));

watch(
    () => page.props.flash?.success,
    (message) => {
        if (message) {
            toast.success(message as string, { timeout: 1000 });
        }
    },
    { immediate: true },
);

const toggleGroup = (title: string) => {
    if (expandedGroups.value.has(title)) {
        expandedGroups.value.delete(title);
    } else {
        expandedGroups.value.add(title);
    }
};

const startEditing = (result: App.Data.DiligenceResultData) => {
    editingResultId.value = result.id;
    editForm.description = result.description;
};

const cancelEditing = () => {
    editingResultId.value = null;
    editForm.reset();
};

const saveEdit = (result: App.Data.DiligenceResultData) => {
    editForm.patch(route('diligence-results.update', result.id), {
        preserveScroll: true,
        onSuccess: () => {
            editingResultId.value = null;
            editForm.reset();
        },
    });
};

const toggleActive = (result: App.Data.DiligenceResultData) => {
    useForm({}).patch(route('diligence-results.toggle', result.id), {
        preserveScroll: true,
    });
};

const openAddModal = (groupTitle: string) => {
    addForm.group = groupTitle;
    addForm.code = '';
    addForm.description = '';
    showAddModal.value = true;
};

const submitAdd = () => {
    addForm.post(route('diligence-results.store'), {
        preserveScroll: true,
        onSuccess: () => {
            showAddModal.value = false;
            addForm.reset();
        },
    });
};

const restoreOriginal = (result: App.Data.DiligenceResultData) => {
    useForm({}).patch(route('diligence-results.restore', result.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Resultados de Diligência" />

    <AppLayout page-title="Resultados de Diligência" text-button="Voltar para Início" link-button="dashboard" method="get">
        <div class="mx-auto max-w-7xl -mt-5 px-4 pb-10">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-white">Opções de Diligência</h2>
                <div class="mt-2 space-y-2">
                    <p class="text-lg text-gray-400">Gerencie as opções de resultado de diligência utilizadas no sistema.</p>
                    <p class="text-base text-gray-300">
                        <span class="text-yellow-500/80 font-semibold">Nota:</span>
                        Opções desativadas não aparecerão para novos registros, mas continuarão visíveis em diligências já realizadas.
                    </p>
                </div>
            </div>

            <div class="space-y-4">
                <div v-for="group in groups" :key="group.title" class="rounded-lg border-2 border-gray-700 bg-[#0e1423] overflow-hidden">
                    <button
                        @click="toggleGroup(group.title)"
                        class="w-full flex items-center justify-between p-4 bg-[#1a1a1a] hover:bg-[#222] transition"
                    >
                        <div class="flex items-center gap-3">
                            <svg
                                class="w-5 h-5 text-gray-400 transition-transform"
                                :class="{ 'rotate-90': expandedGroups.has(group.title) }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                            <span class="text-lg font-semibold text-white">{{ group.title }}</span>
                            <span class="text-sm text-gray-500">({{ group.results.length }} opções)</span>
                        </div>
                        <button
                            @click.stop="openAddModal(group.title)"
                            class="px-3 py-1 text-sm font-medium text-green-400 hover:text-green-300 hover:bg-green-900/20 rounded transition"
                        >
                            + Adicionar
                        </button>
                    </button>

                    <div v-show="expandedGroups.has(group.title)" class="overflow-x-auto">
                        <table class="w-full text-left text-white">
                            <thead class="text-sm uppercase text-gray-400 border-b border-gray-700">
                                <tr>
                                    <th class="p-4 w-32">Código</th>
                                    <th class="p-4">Descrição</th>
                                    <th class="p-4 w-32 text-center">Status</th>
                                    <th class="p-4 w-48 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                <tr v-for="result in group.results" :key="result.id" class="transition hover:bg-white/5">
                                    <td class="p-4">
                                        <code class="text-xs bg-gray-800 px-2 py-1 rounded">{{ result.code }}</code>
                                    </td>
                                    <td class="p-4">
                                        <template v-if="editingResultId === result.id">
                                            <textarea
                                                v-model="editForm.description"
                                                rows="2"
                                                class="w-full bg-[#1a1a1a] border border-gray-600 rounded px-3 py-2 text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                                            />
                                        </template>
                                        <template v-else>
                                            <span class="text-sm" :class="{ 'text-gray-500 line-through': !result.active }">
                                                {{ result.description }}
                                            </span>
                                            <span v-if="result.is_custom" class="ml-2 px-2 py-0.5 text-xs font-bold bg-blue-600/20 text-blue-400 rounded">
                                                CUSTOMIZADO
                                            </span>
                                        </template>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span
                                            class="px-2 py-1 text-xs font-bold rounded"
                                            :class="result.active ? 'bg-green-600/20 text-green-400' : 'bg-red-600/20 text-red-400'"
                                        >
                                            {{ result.active ? 'ATIVO' : 'INATIVO' }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex flex-wrap items-center justify-end gap-3">
                                            <template v-if="editingResultId === result.id">
                                                <button
                                                    @click="saveEdit(result)"
                                                    :disabled="editForm.processing"
                                                    class="font-semibold text-green-400 hover:text-green-300 transition"
                                                >
                                                    Salvar
                                                </button>
                                                <button
                                                    @click="cancelEditing"
                                                    class="font-semibold text-gray-400 hover:text-gray-300 transition"
                                                >
                                                    Cancelar
                                                </button>
                                            </template>
                                            <template v-else>
                                                <button
                                                    @click="startEditing(result)"
                                                    class="font-semibold text-yellow-500 hover:text-yellow-400 transition"
                                                >
                                                    Editar
                                                </button>
                                                <button
                                                    v-if="result.is_modified"
                                                    @click="restoreOriginal(result)"
                                                    class="font-semibold text-blue-400 hover:text-blue-300 transition"
                                                >
                                                    Restaurar
                                                </button>
                                                <button
                                                    @click="toggleActive(result)"
                                                    :class="result.active ? 'text-red-400 hover:text-red-300' : 'text-green-400 hover:text-green-300'"
                                                    class="font-semibold transition"
                                                >
                                                    {{ result.active ? 'Desativar' : 'Ativar' }}
                                                </button>
                                            </template>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <Teleport to="body">
            <div v-if="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70">
                <div class="bg-[#0e1423] border-2 border-gray-700 rounded-lg p-6 w-full max-w-lg mx-4">
                    <h3 class="text-xl font-bold text-white mb-4">Adicionar Nova Opção</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Grupo</label>
                            <input
                                v-model="addForm.group"
                                type="text"
                                readonly
                                class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 text-gray-400"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Código (identificador único)</label>
                            <input
                                v-model="addForm.code"
                                type="text"
                                placeholder="ex: minha_nova_opcao"
                                class="w-full bg-[#1a1a1a] border border-gray-600 rounded px-3 py-2 text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                            />
                            <p v-if="addForm.errors.code" class="mt-1 text-sm text-red-400">{{ addForm.errors.code }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Descrição</label>
                            <textarea
                                v-model="addForm.description"
                                rows="3"
                                placeholder="Descreva a opção de diligência..."
                                class="w-full bg-[#1a1a1a] border border-gray-600 rounded px-3 py-2 text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                            />
                            <p v-if="addForm.errors.description" class="mt-1 text-sm text-red-400">{{ addForm.errors.description }}</p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button
                            @click="showAddModal = false"
                            class="px-4 py-2 text-gray-400 hover:text-white transition"
                        >
                            Cancelar
                        </button>
                        <button
                            @click="submitAdd"
                            :disabled="addForm.processing"
                            class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded transition disabled:opacity-50"
                        >
                            {{ addForm.processing ? 'Salvando...' : 'Adicionar' }}
                        </button>
                    </div>
                </div>
            </div>
        </Teleport>
    </AppLayout>
</template>
