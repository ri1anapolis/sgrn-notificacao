<script setup lang="ts">
const props = defineProps<{
    show: boolean;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'select', variant: 'public_entity' | 'private'): void;
}>();

const selectVariant = (variant: 'public_entity' | 'private') => {
    emit('select', variant);
};
</script>

<template>
    <div v-if="show" @click="emit('close')" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm transition-opacity">
    </div>

    <div v-if="show"
        class="fixed inset-0 z-50 m-auto h-fit max-h-[80vh] w-11/12 max-w-lg overflow-y-auto rounded-lg border-2 border-gray-700 bg-[#0e1423] shadow-lg">
        <div class="sticky top-0 flex items-center justify-between border-b border-gray-700 bg-[#0e1423] p-8">
            <h3 class="text-xl font-semibold text-[#d4af37]">
                Tipo de Notificação - Usucapião
            </h3>
            <button @click="emit('close')" class="text-gray-400 transition hover:text-white hover:cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-8">
            <p class="text-center text-gray-300 mb-6">Selecione o tipo de notificação para download:</p>

            <div class="space-y-8">
                <button
                    @click="selectVariant('private')"
                    class="w-full flex items-center justify-between px-6 py-4 rounded-lg border border-gray-700 bg-[#1a1f2e] cursor-pointer hover:border-amber-400 hover:bg-[#1f2638] transition group">
                    <div class="text-left">
                        <p class="text-white font-medium text-lg">Particulares</p>
                    </div>
                    <div class="text-amber-400 group-hover:translate-x-1 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </div>
                </button>

                <button
                    @click="selectVariant('public_entity')"
                    class="w-full flex items-center justify-between px-6 py-4 rounded-lg border border-gray-700 bg-[#1a1f2e] cursor-pointer hover:border-blue-400 hover:bg-[#1f2638] transition group">
                    <div class="text-left">
                        <p class="text-white font-medium text-lg">Entes Públicos</p>
                    </div>
                    <div class="text-blue-400 group-hover:translate-x-1 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </div>
                </button>
            </div>
        </div>
    </div>
</template>
