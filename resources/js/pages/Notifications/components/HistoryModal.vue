<script setup lang="ts">
import { computed } from 'vue';

interface DiligenceData {
    id: number;
    visit_number_label: string;
    history: HistoryEntry[];
}

interface HistoryEntry {
    id: number;
    created_at: string;
    user: { name: string } | null;
    old_diligence_result_id: number | null;
    new_diligence_result_id: number | null;
    oldResult: { description: string } | null;
    newResult: { description: string } | null;
    old_observations: string | null;
    new_observations: string | null;
}

const props = defineProps<{
    show: boolean;
    diligence: DiligenceData | null;
}>();

const emit = defineEmits(['close']);

const sortedHistory = computed(() => {
    if (!props.diligence?.history) {
        return [];
    }

    return [...props.diligence.history].sort((a, b) =>
        new Date(b.created_at).getTime() - new Date(a.created_at).getTime()
    );
});
</script>

<template>
    <div v-if="show" @click="emit('close')" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm transition-opacity">
    </div>

    <div v-if="show && diligence"
        class="fixed inset-0 z-50 m-auto h-fit max-h-[80vh] w-11/12 max-w-2xl overflow-y-auto rounded-lg border-2 border-gray-700 bg-[#0e1423] shadow-lg">
        <div class="sticky top-0 flex items-center justify-between border-b border-gray-700 bg-[#0e1423] p-6">
            <h3 class="text-xl font-semibold text-white">
                Histórico de Edições: {{ diligence.visit_number_label }}
            </h3>
            <button @click="emit('close')" class="text-gray-400 transition hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-6">
            <ul class="space-y-4">
                <li v-for="entry in sortedHistory" :key="entry.id"
                    class="rounded-lg border border-gray-700 bg-gray-900/50 p-4">
                    <div class="mb-3 flex flex-col items-start justify-between gap-2 sm:flex-row sm:items-center">
                        <span class="font-medium text-sky-300">
                            Editado por: {{ entry.user?.name || 'Usuário desconhecido' }}
                        </span>
                        <span class="text-xs text-gray-400 sm:text-sm">
                            Editado no dia: {{ new Date(entry.created_at).toLocaleString('pt-BR') }}
                        </span>
                    </div>

                    <div class="space-y-3">
                        <div v-if="entry.new_diligence_result_id !== entry.old_diligence_result_id">
                            <span class="text-xs font-semibold uppercase text-gray-500">Resultado</span>
                            <span class="mt-1 block text-white"> Alterado de:
                                <strong class="rounded bg-red-900/50 px-1 py-0.5 font-medium text-red-300">{{
                                    entry.oldResult?.description || 'N/A' }}</strong>
                            </span>
                            <span class="mt-1 block text-white"> para:
                                <strong class="rounded bg-green-900/50 px-1 py-0.5 font-medium text-green-300">{{
                                    entry.newResult?.description || 'N/A' }}</strong>
                            </span>
                        </div>

                        <div v-if="entry.new_observations !== entry.old_observations">
                            <span class="text-xs font-semibold uppercase text-gray-500">Observações</span>
                            <div class="mt-1 flex flex-col gap-1">
                                <span class="rounded bg-red-900/50 p-2 text-sm text-red-300">
                                    <strong class="mr-1">DE:</strong>
                                    <em class="opacity-90">{{ entry.old_observations || '(vazio)' }}</em>
                                </span>
                                <span class="rounded bg-green-900/50 p-2 text-sm text-green-300">
                                    <strong class="mr-1">PARA:</strong>
                                    <em class="blockopacity-90">{{ entry.new_observations || '(vazio)' }}</em>
                                </span>
                            </div>
                        </div>

                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
