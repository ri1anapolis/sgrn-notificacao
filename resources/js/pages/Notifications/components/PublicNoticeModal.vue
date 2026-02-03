<script setup lang="ts">
import InputForm from '@/components/InputForm.vue';
import InputDateForm from '@/components/InputDateForm.vue';
import { useForm } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import { useToast } from 'vue-toastification';
import { formatDateForInput } from '@/utils/formatters';

interface Publication {
    publication_order: number;
    edition: string | null;
    notice_number: string | null;
    publication_date: string | null;
}

const props = defineProps<{
    show: boolean;
    notificationProtocol: string;
    publicNotice?: App.Data.PublicNoticeData | null;
}>();

const emit = defineEmits(['close']);
const toast = useToast();

const DEFAULT_ORGAN = 'ONR - Operador Nacional do Sistema de Registro Eletrônico de Imóveis (www.registrodeimoveis.org.br)';

const organOption = ref<'onr' | 'outro'>('onr');
const customOrgan = ref('');

const createEmptyPublication = (order: number): Publication => ({
    publication_order: order,
    edition: null,
    notice_number: null,
    publication_date: null,
});

const form = useForm({
    publication_organ: DEFAULT_ORGAN,
    days_between_email_and_notice: null as number | null,
    publications: [
        createEmptyPublication(1),
    ] as Publication[],
});

watch(() => props.show, (isShow) => {
    if (isShow && props.publicNotice) {
        if (props.publicNotice.publication_organ === DEFAULT_ORGAN) {
            organOption.value = 'onr';
            customOrgan.value = '';
        } else {
            organOption.value = 'outro';
            customOrgan.value = props.publicNotice.publication_organ;
        }
        form.days_between_email_and_notice = props.publicNotice.days_between_email_and_notice;
        
        const existingPubs = props.publicNotice.publications || [];
        if (existingPubs.length > 0) {
            form.publications = existingPubs.map((pub: App.Data.PublicNoticePublicationData) => ({
                publication_order: pub.publication_order,
                edition: pub.edition,
                notice_number: pub.notice_number,
                publication_date: formatDateForInput(pub.publication_date),
            }));
        } else {
            form.publications = [createEmptyPublication(1)];
        }
    } else if (isShow && !props.publicNotice) {
        organOption.value = 'onr';
        customOrgan.value = '';
        form.days_between_email_and_notice = null;
        form.publications = [createEmptyPublication(1)];
    }
});

const computedOrgan = computed(() => {
    return organOption.value === 'onr' ? DEFAULT_ORGAN : customOrgan.value;
});

const isFormValid = computed(() => {
    if (organOption.value === 'outro' && !customOrgan.value.trim()) {
        return false;
    }
    return true;
});

const addPublication = () => {
    const nextOrder = form.publications.length + 1;
    form.publications.push(createEmptyPublication(nextOrder));
};

const removePublication = (index: number) => {
    if (form.publications.length > 1) {
        form.publications.splice(index, 1);
        form.publications.forEach((pub, i) => {
            pub.publication_order = i + 1;
        });
    }
};

const submit = () => {
    if (!isFormValid.value) {
        toast.error('Preencha todos os campos obrigatórios.', { timeout: 3000 });
        return;
    }

    form.publication_organ = computedOrgan.value;
    
    form.post(route('notifications.public-notice.store', props.notificationProtocol), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Dados do edital salvos com sucesso!');
            emit('close');
        },
        onError: () => {
            toast.error('Erro ao salvar os dados. Verifique os campos.', { timeout: 3000 });
        },
    });
};
</script>


<template>
    <div v-if="show" @click="emit('close')" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm transition-opacity">
    </div>

    <div v-if="show"
        class="fixed inset-0 z-50 m-auto h-fit max-h-[90vh] w-11/12 max-w-3xl overflow-y-auto rounded-lg border-2 border-gray-700 bg-[#0e1423] shadow-lg">
        <div class="sticky top-0 flex items-center justify-between border-b border-gray-700 bg-[#0e1423] p-6">
            <h3 class="text-xl font-semibold text-[#d4af37]">
                Dados de Editais
            </h3>
            <button @click="emit('close')" class="text-gray-400 transition hover:text-white cursor-pointer hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form @submit.prevent="submit" class="p-10 space-y-6 text-sm">
            <div>
                <label class="block mb-2 font-bold text-amber-200 ">Órgão em que Foi Publicado</label>
                <select v-model="organOption"
                    class="w-full rounded-md border border-gray-600 bg-[#1a1f2e] p-3 text-white focus:border-[#d4af37] focus:ring focus:ring-[#d4af37]/20">
                    <option value="onr">ONR - Operador Nacional do Sistema de Registro Eletrônico de Imóveis (www.registrodeimoveis.org.br)</option>
                    <option value="outro">Outro</option>
                </select>
                <input v-if="organOption === 'outro'" v-model="customOrgan" type="text" placeholder="Digite o órgão de publicação"
                    class="mt-2 w-full rounded-md border border-gray-600 bg-[#1a1f2e] p-3 text-white focus:border-[#d4af37] focus:ring focus:ring-[#d4af37]/20" />
            </div>

            <div>
                <label class="block text-sm font-bold text-amber-200 mb-2">Quantidade de Dias Entre Envio de E-mail e Edital</label>
                <input v-model.number="form.days_between_email_and_notice" type="number" min="0" placeholder="Ex: 15"
                    class="w-full rounded-md border border-gray-600 bg-[#1a1f2e] p-3 text-white focus:border-[#d4af37] focus:ring focus:ring-[#d4af37]/20" />
            </div>

            <div>
                <div class="flex items-center justify-between mb-4">
                    <label class="block font-bold text-amber-200">Registro das Publicações</label>
                    <button type="button" @click="addPublication"
                        class="text-sm text-[#d4af37] hover:text-[#f0c850] flex items-center gap-1 hover:scale-102 transition cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Adicionar Publicação
                    </button>
                </div>

                <div class="space-y-3">
                    <div v-for="(pub, index) in form.publications" :key="index"
                        class="grid grid-cols-12 gap-3 items-center p-3 px-5 rounded-lg bg-[#1a1f2e] border border-gray-700">
                        <div class="col-span-3">
                            <span class="block text-sm text-gray-200">{{ pub.publication_order }}ª Publicação</span>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-200 mb-2">Edição</label>
                            <input v-model="pub.edition" type="text" placeholder="Ex: 001"
                                class="w-full rounded-md border border-gray-600 bg-[#0e1423] p-2 text-sm text-white focus:border-[#d4af37]" />
                        </div>
                        <div class="col-span-3">
                            <label class="block text-xs text-gray-200 mb-2">Número do Edital</label>
                            <input v-model="pub.notice_number" type="text" placeholder="Ex: 123"
                                class="w-full rounded-md border border-gray-600 bg-[#0e1423] p-2 text-sm text-white focus:border-[#d4af37]" />
                        </div>
                        <div class="col-span-3">
                            <label class="block text-xs text-gray-200 mb-2">Data da Publicação</label>
                            <InputDateForm
                                :id="`pub-date-${index}`"
                                label=""
                                class="text-white border-gray-600 bg-[#0e1423]"
                                :model-value="pub.publication_date"
                                @update:model-value="(val) => form.publications[index].publication_date = val"
                            />
                        </div>
                        <div class="col-span-1 flex justify-center">
                            <button v-if="form.publications.length > 1" type="button" @click="removePublication(index)"
                                class="text-red-400 hover:text-red-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t border-gray-700">
                <button type="button" @click="emit('close')"
                    class="px-6 py-2 rounded-md border border-gray-600 text-gray-300 hover:bg-gray-700 transition cursor-pointer hover:scale-105">
                    Cancelar
                </button>
                <button type="submit" :disabled="form.processing || !isFormValid"
                    class="px-6 py-2 rounded-md bg-[#d4af37] text-black font-medium hover:bg-[#f0c850] transition disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer hover:scale-105">
                    Salvar Dados do Edital
                </button>
            </div>
        </form>
    </div>
</template>
