<script setup lang="ts">
const props = defineProps<{
    show: boolean;
    email: string;
    temporaryCode: string;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
}>();

const copyCode = async () => {
    await navigator.clipboard.writeText(props.temporaryCode);
};
</script>

<template>
    <div v-if="show" @click="emit('close')" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm transition-opacity" />

    <div v-if="show" class="fixed inset-0 z-50 m-auto h-fit w-11/12 max-w-md rounded-lg border-2 border-purple-700 bg-[#0e1423] shadow-lg">
        <div class="flex items-center justify-between border-b border-purple-700 p-6">
            <h3 class="text-lg font-semibold text-purple-400">Senha Resetada</h3>
            <button @click="emit('close')" class="text-gray-400 transition hover:scale-105 hover:text-white">✕</button>
        </div>

        <div class="space-y-4 p-6 text-sm text-gray-300">
            <p>
                A senha do usuário <strong class="text-white">{{ email }}</strong> foi resetada com sucesso.
            </p>

            <div class="rounded-lg border border-purple-600/40 bg-purple-600/10 p-4">
                <p class="mb-2 text-xs text-purple-400 uppercase">Novo código temporário</p>

                <div class="flex items-center justify-between gap-3">
                    <code class="text-lg font-bold text-purple-300">
                        {{ temporaryCode }}
                    </code>

                    <button
                        @click="copyCode"
                        class="rounded-md bg-purple-600 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-purple-500"
                    >
                        Copiar
                    </button>
                </div>
            </div>

            <p class="text-xs text-gray-400 italic">
                Informe este código ao colaborador. Ele será solicitado a criar uma nova senha pessoal no próximo login.
            </p>
        </div>

        <div class="flex justify-end border-t border-gray-700 p-4">
            <button @click="emit('close')" class="rounded-md px-4 py-2 text-sm text-gray-300 transition hover:text-white">Fechar</button>
        </div>
    </div>
</template>
