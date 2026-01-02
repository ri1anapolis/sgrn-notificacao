<script setup lang="ts">
interface NotifiedPersonData {
    id: number;
    name: string;
    document: string;
    phone: string | null;
    email: string | null;
}

const props = defineProps<{
    show: boolean;
    notifiedPeople: NotifiedPersonData[];
}>();

const emit = defineEmits(['close', 'select']);

const selectPerson = (person: NotifiedPersonData) => {
    emit('select', person);
};
</script>

<template>
    <div v-if="show" @click="emit('close')" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm transition-opacity">
    </div>

    <div v-if="show"
        class="fixed inset-0 z-50 m-auto h-fit max-h-[80vh] w-11/12 max-w-2xl overflow-y-auto rounded-lg border-2 border-gray-700 bg-[#0e1423] shadow-lg">
        <div class="sticky top-0 flex items-center justify-between border-b border-gray-700 bg-[#0e1423] p-10">
            <h3 class="text-xl font-semibold text-[#d4af37]">
                Comunicações Digitais: Selecione o Contato
            </h3>
            <button @click="emit('close')" class="text-gray-400 transition hover:text-white hover:cursor-pointer ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-8">
            <p class="text-center text-gray-300 mb-6">Selecione o Notificado para registrar o contato digital:</p>

            <div class="space-y-4">
                <div v-for="person in notifiedPeople" :key="person.id"
                    @click="selectPerson(person)"
                    class="flex items-center justify-between px-4 py-3 rounded-lg border border-gray-700 bg-[#1a1f2e] cursor-pointer hover:border-[#d4af37] transition group">
                    <div class="space-y-1">
                        <p class="text-white font-medium">{{ person.name }}</p>
                        <p class="text-sm text-gray-400">CPF: {{ person.document }}</p>
                        <div v-if="person.phone" class="flex items-center gap-2 text-sm">
                            <span class="w-2 h-2 rounded-full bg-gray-500"></span>
                            <span class="text-gray-300">{{ person.phone }}</span>
                        </div>
                        <div v-if="person.email" class="flex items-center gap-2 text-sm">
                            <span class="text-[#d4af37]">✉</span>
                            <span class="text-[#d4af37]">{{ person.email }}</span>
                        </div>
                    </div>
                    <div class="text-[#d4af37] group-hover:translate-x-1 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </div>
                </div>

                <div v-if="notifiedPeople.length === 0" class="text-center py-8 text-gray-500">
                    Nenhum notificado encontrado nesta notificação.
                </div>
            </div>
        </div>
    </div>
</template>
