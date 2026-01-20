<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
    show: boolean;
    user: App.Data.UserData | null;
}>();

const emit = defineEmits(['close']);

const confirmationEmail = ref('');

const form = useForm({});

watch(
    () => props.show,
    (show) => {
        if (show) {
            confirmationEmail.value = '';
        }
    },
);

const isConfirmed = computed(() => {
    if (!props.user) return false;
    return confirmationEmail.value === props.user.email;
});

const submit = () => {
    if (!props.user) return;

    form.delete(route('users.destroy', props.user.id), {
        onSuccess: () => emit('close'),
    });
};
</script>

<template>
    <!-- Overlay -->
    <div v-if="show" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm" @click="emit('close')" />

    <!-- Modal -->
    <div v-if="show" class="fixed inset-0 z-50 m-auto h-fit max-h-[90vh] w-11/12 max-w-lg rounded-lg border-2 border-red-700 bg-[#0e1423] shadow-xl">
        <div class="border-b border-red-700 p-6">
            <h3 class="text-xl font-semibold text-red-400">Excluir Usuário</h3>
        </div>

        <div class="space-y-4 p-6 text-sm text-gray-300">
            <p>Esta ação é <span class="font-semibold text-red-400">irreversível</span>.</p>

            <p>Para confirmar, digite exatamente o e-mail do usuário:</p>

            <div class="rounded-md bg-[#1a1a1a] p-3 font-mono text-yellow-400">
                {{ user?.email }}
            </div>

            <input
                v-model="confirmationEmail"
                type="email"
                placeholder="Digite o e-mail do usuário"
                class="w-full rounded-md border border-gray-600 bg-[#1a1a1a] p-3 text-white focus:border-red-500"
            />
        </div>

        <div class="flex justify-end gap-4 border-t border-gray-700 p-6">
            <button type="button" @click="emit('close')" class="px-4 py-2 text-gray-400 hover:text-white">Cancelar</button>

            <button
                type="button"
                :disabled="!isConfirmed || form.processing"
                @click="submit"
                class="rounded-md bg-red-600 px-6 py-2 font-bold text-white transition hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-50"
            >
                Excluir Usuário
            </button>
        </div>
    </div>
</template>
