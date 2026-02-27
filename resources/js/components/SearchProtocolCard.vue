<script setup lang="ts">
import CardSection from '@/components/CardSection.vue';
import { router, useForm } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import axios from 'axios';
import { ref } from 'vue';

const props = defineProps({
    imageUrl: {
        type: String,
        required: true,
    },

    altName: {
        type: String,
        required: true,
    },

    routeName: {
        type: String,
        required: false,
    },

    paramName: {
        type: String,
        required: false,
        default: 'notification',
    },
    canCreate: {
        type: Boolean,
        default: false,
    }
});

const form = useForm({
    protocol: '',
});

const toast = useToast();

const showConfirmModal = ref(false);
const isChecking = ref(false);
const protocolToCreate = ref('');


const submit = async () => {
    if (!form.protocol) return;

    const formattedProtocol = form.protocol.replace(/[^a-zA-Z0-9]/g, '').slice(0, 7).toUpperCase();

    isChecking.value = true;

    try {
        const response = await axios.get(route('data-processing.check', formattedProtocol));

        if (response.data.exists) {
            form.get(route(props.routeName, { [props.paramName]: formattedProtocol }), {
                preserveState: true,
                preserveScroll: true,

                onError: (errors) => {
                    window.scrollTo({ top: 0, behavior: 'smooth' });

                    if (errors.geral) {
                        toast.error(errors.geral, { timeout: 3000 });
                    } else {
                        toast.error('Ocorreu um erro inesperado..', { timeout: 3000 });
                    }
                },
            });
        } else {
            if (props.canCreate) {
                protocolToCreate.value = formattedProtocol;
                showConfirmModal.value = true;
            } else {
                toast.error(`O protocolo ${formattedProtocol} não foi encontrado.`, { timeout: 3000 });
            }
        }
    } catch (error) {
        toast.error('Erro ao verificar a existência do protocolo.', { timeout: 3000 });
    } finally {
        isChecking.value = false;
    }
};


const createProtocol = () => {
    router.post(route('data-processing.store'), {
        protocol: protocolToCreate.value
    }, {
        onSuccess: () => {
            showConfirmModal.value = false;
            toast.success('Protocolo criado com sucesso!');
        },
        onError: () => {
            toast.error('Não foi possível criar o protocolo.', { timeout: 3000 });
        }
    });
};
</script>

<template>
    <CardSection :image-url="props.imageUrl" :altName="props.altName">
        <form @submit.prevent="submit" class="flex w-11/12 items-center gap-x-2 rounded-2xl bg-[#1a1a1a] p-2">
            <input type="text" placeholder="Digite o protocolo..." maxlength="7"
                class="ml-5 flex-grow border-0 bg-transparent text-sm text-white placeholder-gray-400 focus:ring-0 focus:outline-none"
                v-model="form.protocol" @input="form.protocol = form.protocol.replace(/[^a-zA-Z0-9]/g, '').slice(0, 7).toUpperCase()"
                :disabled="isChecking" />

            <button type="submit" :disabled="isChecking"
                class="flex-shrink-0 rounded-md bg-[#c19a6b] p-2 transition-colors hover:cursor-pointer hover:bg-[#b58f5e] disabled:opacity-50">
                <svg v-if="isChecking" class="h-5 w-5 animate-spin text-black" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="h-5 w-5 text-black">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </button>
        </form>

        <Teleport to="body">
            <div v-if="showConfirmModal"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm">
                <div class="w-11/12 max-w-md rounded-lg border border-gray-700 bg-[#0e1423] p-6 shadow-xl">
                    <h3 class="text-lg font-bold text-white mb-2">Protocolo não encontrado</h3>
                    <p class="text-gray-300 mb-6">
                        O protocolo <strong class="text-[#c19a6b]">{{ protocolToCreate }}</strong> não existe no
                        sistema.
                        <br>Deseja criar um novo registro para ele?
                    </p>
                    <div class="flex justify-end gap-3">
                        <button @click="showConfirmModal = false"
                            class="rounded-md border border-gray-600 px-4 py-2 text-sm text-gray-300 hover:bg-gray-800 transition cursor-pointer">
                            Cancelar
                        </button>
                        <button @click="createProtocol"
                            class="rounded-md bg-[#c19a6b] px-4 py-2 text-sm font-semibold text-black hover:bg-[#b58f5e] transition cursor-pointer">
                            Sim, Criar Novo
                        </button>
                    </div>
                </div>
            </div>
        </Teleport>
    </CardSection>
</template>
