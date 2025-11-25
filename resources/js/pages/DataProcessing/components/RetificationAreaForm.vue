<script setup lang="ts">
import { computed } from 'vue';
import InputForm from '../../../components/InputForm.vue';
import { vMaska } from "maska/vue";
import { registrationMask } from '@/utils/masks';
import { registryOptions } from '@/constants/natures';

type RetificationAreaModel = App.Data.RetificationAreaData | null;

const props = defineProps<{
    modelValue: RetificationAreaModel;
    errors: any;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: RetificationAreaModel): void;
}>();

const defaultData: App.Data.RetificationAreaData = {
    id: 0,
    office: null,
    rectifying_property_registration: null,
    rectifying_property_identification: null,
    rectifying_property_registry_office: null,
};

const selectedRegistry = computed({
    get: () => {
        const currentVal = internalData.value.rectifying_property_registry_office;
        if (currentVal === null) return '';
        if (registryOptions.includes(currentVal)) {
            return currentVal;
        }
        return 'Outro';
    },
    set: (value) => {
        if (value === 'Outro') {
            const current = internalData.value.rectifying_property_registry_office;
            if (current === null || registryOptions.includes(current)) {
                updateField('rectifying_property_registry_office', '');
            }
        } else {
            updateField('rectifying_property_registry_office', value);
        }
    }
});

const internalData = computed({
    get: () => {
        return props.modelValue ?? defaultData;
    },
    set: (value) => emit('update:modelValue', value),
});

const updateField = (fieldName: keyof App.Data.RetificationAreaData, value: string | number | boolean | null) => {
    const currentData = internalData.value;
    internalData.value = {
        ...currentData,
        [fieldName]: value,
    } as App.Data.RetificationAreaData;
};

</script>

<template>
    <div class="grid grid-cols-1 gap-5 p-6">
        <InputForm id="office" type="text" label="Nº de Ofício" placeholder="Digite o número do ofício" required
            v-maska="registrationMask" :model-value="internalData.office"
            @update:model-value="updateField('office', $event)" :error="errors?.notifiable?.office" />

        <InputForm id="rectifying-property-registration" type="text" label="Matrícula do Imóvel Retificando"
            placeholder="Digite a matrícula" v-maska="registrationMask"
            :model-value="internalData.rectifying_property_registration"
            @update:model-value="updateField('rectifying_property_registration', $event)"
            :error="errors?.notifiable?.rectifying_property_registration" />

        <InputForm id="rectifying-property-identification" type="text"
            label="Identificação do Imóvel que está sendo Retificado" placeholder="Digite o endereço completo do imóvel"
            :model-value="internalData.rectifying_property_identification"
            @update:model-value="updateField('rectifying_property_identification', $event)"
            :error="errors?.notifiable?.rectifying_property_identification" />

        <div class="flex flex-col gap-2">
            <label for="registry-select" class="block text-sm font-medium text-gray-700">
                Cartório onde o Imóvel Retificando está Localizado
            </label>
            <div class="relative">
                <select id="registry-select" v-model="selectedRegistry"
                    class="block w-full p-2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <option value="" disabled>Selecione uma opção</option>
                    <option v-for="option in registryOptions" :key="option" :value="option">
                        {{ option }}
                    </option>
                    <option value="Outro">Outro (Digitar manualmente)</option>
                </select>
                <p v-if="selectedRegistry !== 'Outro' && errors?.notifiable?.rectifying_property_registry_office"
                    class="mt-1 text-sm text-red-600">
                    {{ errors.notifiable.rectifying_property_registry_office }}
                </p>
            </div>

            <div v-if="selectedRegistry === 'Outro'" class="animate-fade-in-down">
                <InputForm id="rectifying-property-registry-office" type="text"
                    label="Digite o nome do cartório (Manual)" placeholder="Digite o nome do cartório..."
                    :model-value="internalData.rectifying_property_registry_office"
                    @update:model-value="updateField('rectifying_property_registry_office', $event)"
                    :error="errors?.notifiable?.rectifying_property_registry_office" />
            </div>
        </div>
    </div>
</template>