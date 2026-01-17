<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store.first'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Primeiro Acesso - Definir Senha" />

    <div class="flex min-h-screen items-center justify-center bg-[#0e1423] px-4">
        <form @submit.prevent="submit" class="w-full max-w-lg rounded-lg border-2 border-gray-700 bg-[#0e1423] p-10 text-white shadow-2xl">
            <div class="mb-8 text-center">
                <h2 class="text-2xl font-bold text-yellow-600">Primeiro Acesso</h2>
                <p class="mt-2 text-sm text-gray-400">Por segurança, você precisa definir uma nova senha antes de continuar.</p>
            </div>

            <div class="space-y-6">
                <div class="flex flex-col">
                    <label for="password" class="mb-3 font-bold text-gray-300">Nova Senha</label>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        placeholder="••••••••"
                        class="w-full rounded-lg border-2 border-[#3d4852] bg-[#1a1a1a] p-3 text-white placeholder-gray-500 transition focus:border-yellow-600 focus:ring-yellow-600"
                        required
                    />
                    <div v-if="form.errors.password" class="mt-1 text-xs text-red-500">{{ form.errors.password }}</div>
                </div>

                <div class="flex flex-col">
                    <label for="password_confirmation" class="mb-3 font-bold text-gray-300">Confirmar Nova Senha</label>
                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        placeholder="••••••••"
                        class="w-full rounded-lg border-2 border-[#3d4852] bg-[#1a1a1a] p-3 text-white placeholder-gray-500 transition focus:border-yellow-600 focus:ring-yellow-600"
                        required
                    />
                </div>
            </div>

            <div class="mt-10 flex flex-col items-center">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full rounded-lg bg-yellow-600 py-3 font-bold text-white transition hover:bg-yellow-700 disabled:opacity-50"
                >
                    {{ form.processing ? 'Salvando...' : 'Atualizar Senha e Entrar' }}
                </button>
            </div>
        </form>
    </div>
</template>
