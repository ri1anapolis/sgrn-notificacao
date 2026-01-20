<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';

const props = defineProps<{
    user: App.Data.UserData;
}>();

const toast = useToast();

const form = useForm({
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.put(route('users.update-password', props.user.id), {
        onSuccess: () => {
            toast.success('Senha atualizada com sucesso!');
        },
    });
};
</script>

<template>
    <Head :title="'Alterar Senha - ' + user.name" />

    <AppLayout :page-title="'Alterar Senha: ' + user.name" text-button="Voltar para Usuários" link-button="users.index" method="get">
        <div class="mx-auto max-w-2xl px-4 py-10">
            <form @submit.prevent="submit" class="rounded-lg border-2 border-gray-700 bg-[#0e1423] p-10 text-white shadow-xl">
                <h2 class="mb-8 text-lg font-semibold text-yellow-600">Definir Nova Senha</h2>

                <div class="space-y-6">
                    <div class="flex flex-col">
                        <label class="mb-3 font-bold text-gray-300">Nova Senha</label>
                        <input
                            v-model="form.password"
                            type="password"
                            placeholder="Mínimo 8 caracteres"
                            class="w-full rounded-lg border-2 border-[#3d4852] bg-[#1a1a1a] p-3 text-white transition focus:border-yellow-600 focus:ring-yellow-600"
                        />
                        <div v-if="form.errors.password" class="mt-1 text-xs text-red-500">{{ form.errors.password }}</div>
                    </div>

                    <div class="flex flex-col">
                        <label class="mb-3 font-bold text-gray-300">Confirmar Nova Senha</label>
                        <input
                            v-model="form.password_confirmation"
                            type="password"
                            placeholder="Repita a nova senha"
                            class="w-full rounded-lg border-2 border-[#3d4852] bg-[#1a1a1a] p-3 text-white transition focus:border-yellow-600 focus:ring-yellow-600"
                        />
                    </div>

                    <div class="mt-10 flex flex-col items-center gap-y-4">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full max-w-xs rounded-lg bg-yellow-600 py-3 font-bold text-white transition hover:bg-yellow-700 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Salvando...' : 'Atualizar Minha Senha' }}
                        </button>

                        <Link :href="route('users.index')" class="font-medium text-gray-400 transition hover:text-white"> Cancelar </Link>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
