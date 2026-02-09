<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { useToast } from 'vue-toastification';
import DeleteUserModal from './components/DeleteUserModal.vue';
import FirstAccessModal from './components/FirstAccessModal.vue';
import ResetPasswordModal from './components/ResetPasswordModal.vue';

const props = defineProps<{
    users: App.Data.UserData[];
    roles: string[];
}>();

const page = usePage();
const toast = useToast();

const showFirstAccessModal = ref(false);
const temporaryCode = ref<string | null>(null);
const createdUserEmail = ref<string>('');

const showResetPasswordModal = ref(false);
const showDeleteModal = ref(false);
const userToDelete = ref<App.Data.UserData | null>(null);

const isAdmin = computed(() => {
    return page.props.auth.user.role === 'admin' || page.props.auth.user.role === 'super-admin';
});

watch(
    () => page.props.flash?.temporary_code,
    (code) => {
        if (code) {
            temporaryCode.value = code as string;
            createdUserEmail.value = page.props.flash?.reset_email ?? '';

            showResetPasswordModal.value = false;
            showFirstAccessModal.value = false;

            if (page.props.flash?.reset_mode) {
                showResetPasswordModal.value = true;
            } else {
                showFirstAccessModal.value = true;
            }
        }
    },
    { immediate: true },
);

watch(
    () => page.props.flash?.success,
    (message) => {
        if (message) {
            toast.success(message as string, {
                timeout: 1000,
            });
        }
    },
    { immediate: true },
);

const isFormOpen = ref(false);
const resetMode = ref(false);
const editingUserId = ref<number | null>(null);

const form = useForm({
    name: '',
    email: '',
    role: 'employee',
});

const isEditing = computed(() => editingUserId.value !== null);

const roleOptions = computed(() => {
    const labels: Record<string, string> = {
        'super-admin': 'Super Administrador',
        admin: 'Administrador',
        employee: 'Funcionário / Colaborador',
    };

    if (!props.roles || props.roles.length === 0) return [];

    return props.roles.map((role) => ({
        id: role,
        description: labels[role] || role,
    }));
});

const openCreate = () => {
    editingUserId.value = null;
    form.reset();
    form.clearErrors();
    isFormOpen.value = true;
};

const openEdit = (user: App.Data.UserData) => {
    editingUserId.value = user.id;
    form.clearErrors();
    form.name = user.name;
    form.email = user.email;
    form.role = user.role;
    isFormOpen.value = true;
};

const openDelete = (user: App.Data.UserData) => {
    userToDelete.value = user;
    showDeleteModal.value = true;
};

const closeForm = () => {
    isFormOpen.value = false;
    editingUserId.value = null;
    form.reset();
};

const isCurrentUser = (userId: number) => {
    return page.props.auth.user.id === userId;
};

const submit = () => {
    if (isEditing.value) {
        form.put(route('users.update', editingUserId.value), {
            onSuccess: () => {
                closeForm();
                toast.success('Usuário atualizado com sucesso!', { timeout: 1000 });
            },
        });
    } else {
        createdUserEmail.value = form.email;
        form.post(route('users.store'), {
            onSuccess: () => closeForm(),
        });
    }
};
</script>

<template>
    <Head title="Gerenciamento de Usuários" />

    <AppLayout page-title="Configurações de Usuários" text-button="Voltar para Início" link-button="dashboard" method="get">
        <div class="mx-auto max-w-7xl px-4 py-10">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white">Usuários do Sistema</h2>
                    <p class="text-sm text-gray-400">Gerencie quem tem acesso à plataforma</p>
                </div>

                <button
                    v-if="!isFormOpen && isAdmin"
                    @click="openCreate"
                    class="rounded-lg bg-yellow-600 px-6 py-2.5 font-semibold text-white transition hover:bg-yellow-700"
                >
                    Novo Usuário
                </button>
            </div>

            <div v-if="isFormOpen" class="mb-10 flex justify-center">
                <form @submit.prevent="submit" class="w-full max-w-4xl rounded-lg border-2 border-gray-700 bg-[#0e1423] p-10 text-white shadow-xl">
                    <h2 class="mb-8 text-lg font-semibold text-yellow-600">
                        {{ isEditing ? 'Editar Usuário' : 'Cadastro de Novo Usuário' }}
                    </h2>

                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                        <div class="flex flex-col">
                            <label for="name" class="mb-3 font-bold text-gray-300">Nome Completo</label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="Digite o nome do usuário"
                                class="w-full rounded-lg border-2 border-[#3d4852] bg-[#1a1a1a] p-3 text-white placeholder-gray-500 transition focus:border-yellow-600 focus:ring-yellow-600"
                            />
                            <div v-if="form.errors.name" class="mt-1 text-xs text-red-500">{{ form.errors.name }}</div>
                        </div>

                        <div class="flex flex-col">
                            <label for="email" class="mb-3 font-bold text-gray-300">E-mail Corporativo</label>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                placeholder="usuario@empresa.com"
                                :readonly="isEditing"
                                :class="[
                                    'w-full rounded-lg border-2 p-3 text-white placeholder-gray-500 transition',
                                    isEditing
                                        ? 'cursor-not-allowed border-gray-700 bg-gray-800 text-gray-400'
                                        : 'border-[#3d4852] bg-[#1a1a1a] focus:border-yellow-600 focus:ring-yellow-600',
                                ]"
                            />
                            <div v-if="form.errors.email" class="mt-1 text-xs text-red-500">{{ form.errors.email }}</div>
                        </div>

                        <div class="flex flex-col md:col-span-2">
                            <label class="mb-3 font-bold text-gray-300">Nível de Acesso (Cargo)</label>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div
                                    v-for="role in roleOptions"
                                    :key="role.id"
                                    @click="form.role = role.id"
                                    :class="[
                                        'cursor-pointer rounded-lg border-2 p-4 text-center transition',
                                        form.role === role.id
                                            ? 'border-yellow-600 bg-yellow-600/10 text-white'
                                            : 'border-[#3d4852] bg-[#1a1a1a] text-gray-400 hover:border-gray-500',
                                    ]"
                                >
                                    <span class="font-bold">{{ role.description }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10">
                        <div class="flex w-full flex-col items-center gap-y-4">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="w-full max-w-xs rounded-lg bg-yellow-600 py-3 font-bold text-white transition hover:bg-yellow-700 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Salvando...' : isEditing ? 'Salvar Alterações' : 'Criar Usuário e Gerar Código' }}
                            </button>

                            <button type="button" @click="closeForm" class="font-medium text-gray-400 transition hover:text-white">Cancelar</button>
                        </div>
                    </div>
                </form>
            </div>

            <div v-if="!isFormOpen" class="overflow-hidden rounded-lg border-2 border-gray-700 bg-[#0e1423]">
                <table class="w-full text-left text-white">
                    <thead class="bg-[#1a1a1a] text-sm text-gray-400 uppercase">
                        <tr>
                            <th class="p-4">Nome</th>
                            <th class="p-4">E-mail</th>
                            <th class="p-4">Cargo</th>
                            <th class="p-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        <tr v-for="user in users" :key="user.id" class="transition hover:bg-white/5">
                            <td class="p-4 font-medium">{{ user.name }}</td>
                            <td class="p-4 text-gray-300">{{ user.email }}</td>
                            <td class="p-4">
                                <span
                                    class="rounded px-2 py-1 text-xs font-bold"
                                    :class="isAdmin ? 'bg-yellow-600/20 text-yellow-500' : 'bg-blue-600/20 text-blue-400'"
                                >
                                    {{ user.role.toUpperCase() }}
                                </span>
                            </td>
                            <td class="space-x-4 p-4 text-right">
                                <Link
                                    v-if="isCurrentUser(user.id)"
                                    :href="route('users.change-password', user.id)"
                                    class="font-semibold text-blue-400 hover:text-blue-300"
                                    title="Alterar Minha Senha"
                                >
                                    Alterar Senha
                                </Link>

                                <template v-if="isAdmin">
                                    <button @click="openEdit(user)" class="font-semibold text-yellow-600 hover:text-yellow-500">Editar</button>

                                    <button @click="openDelete(user)" class="font-semibold text-red-500 hover:text-red-400">Excluir</button>

                                    <Link
                                        v-if="page.props.auth.user.role === 'super-admin' && !isCurrentUser(user.id)"
                                        :href="route('users.reset-password', user.id)"
                                        method="post"
                                        as="button"
                                        class="font-semibold text-purple-400 hover:text-purple-300"
                                    >
                                        Resetar Senha
                                    </Link>
                                </template>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <FirstAccessModal
            :show="showFirstAccessModal"
            :temporary-code="temporaryCode!"
            :email="createdUserEmail"
            @close="showFirstAccessModal = false"
        />

        <ResetPasswordModal
            :show="showResetPasswordModal"
            :temporary-code="temporaryCode ?? ''"
            :email="createdUserEmail"
            @close="showResetPasswordModal = false"
        />

        <DeleteUserModal :show="showDeleteModal" :user="userToDelete" @close="showDeleteModal = false" />
    </AppLayout>
</template>
