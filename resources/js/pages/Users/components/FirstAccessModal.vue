<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    show: boolean;
    email: string;
    temporaryCode: string;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
}>();

const maskedEmail = computed(() => {
    const [name, domain] = props.email.split('@');
    if (!domain) return props.email;
    return `${name.slice(0, 2)}***@${domain}`;
});

const copyCode = async () => {
    await navigator.clipboard.writeText(props.temporaryCode);
};
</script>

<template>
    <!-- Overlay -->
    <div v-if="show" @click="emit('close')" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm transition-opacity" />

    <!-- Modal -->
    <div v-if="show" class="fixed inset-0 z-50 m-auto h-fit w-11/12 max-w-md rounded-lg border-2 border-gray-700 bg-[#0e1423] shadow-lg">
        <div class="flex items-center justify-between border-b border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-yellow-500">Primeiro Acesso</h3>
            <button @click="emit('close')" class="text-gray-400 transition hover:scale-105 hover:text-white">✕</button>
        </div>

        <div class="space-y-4 p-6 text-sm text-gray-300">
            <p>
                O usuário <strong class="text-white">{{ maskedEmail }}</strong> foi criado com sucesso.
            </p>

            <div class="rounded-lg border border-yellow-600/40 bg-yellow-600/10 p-4">
                <p class="mb-2 text-xs text-yellow-400 uppercase">Código de primeiro acesso</p>

                <div class="flex items-center justify-between gap-3">
                    <code class="text-lg font-bold text-yellow-300">
                        {{ temporaryCode }}
                    </code>

                    <button
                        @click="copyCode"
                        class="rounded-md bg-yellow-600 px-3 py-1.5 text-xs font-bold text-black transition hover:bg-yellow-500"
                    >
                        Copiar
                    </button>
                </div>
            </div>

            <p class="text-xs text-gray-400">
                ⚠️ Este código é exibido apenas uma vez. Oriente o usuário a alterá-lo no primeiro login. Caso você perca esse código, o ideal é você
                excluir o usuário e criar um novamente para gerar um novo código.
            </p>
        </div>

        <div class="flex justify-end border-t border-gray-700 p-4">
            <button @click="emit('close')" class="rounded-md px-4 py-2 text-sm text-gray-300 transition hover:text-white">Entendi</button>
        </div>
    </div>
</template>
