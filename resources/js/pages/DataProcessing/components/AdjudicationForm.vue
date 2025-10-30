<script setup lang="ts">
import { computed } from 'vue';
import InputForm from '../../../components/InputForm.vue';

type AdjudicationModel = App.Data.AdjudicationData | null;

const props = defineProps<{
    modelValue: AdjudicationModel;
    errors: any;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: AdjudicationModel): void;
}>();

const defaultData: App.Data.AdjudicationData = {
    id: 0,
    office: null,
    adjudicated_property_registration: null,
    adjudicated_property_identification: null,
    adjudicated_property_registry_office: null,
};

const internalData = computed({
    get: () => props.modelValue ?? defaultData,
    set: (value) => emit('update:modelValue', value),
});

const updateField = (fieldName: keyof App.Data.AdjudicationData, value: string | number | boolean | null) => {
    const currentData = internalData.value;
    internalData.value = {
        ...currentData,
        [fieldName]: value,
    } as App.Data.AdjudicationData;
};

const handleInput = (event: Event, fieldName: keyof App.Data.AdjudicationData, type: 'text' | 'number') => {
    const target = event.target as HTMLInputElement;
    let value: string | number | null = target.value;

    if (type === 'number') {
        value = value === '' ? null : Number(value);
    }

    updateField(fieldName, value);
};
</script>

<template>
    <div class="grid grid-cols-1 gap-5 p-6">
        <InputForm
            id="office"
            type="number"
            label="Nº de Ofício"
            placeholder="Digite o número do ofício"
            required
            :model-value="internalData.office"
            @input="(e: Event) => handleInput(e, 'office', 'number')"
            :error="errors?.notifiable?.office"
        />

        <InputForm
            id="adjudicated-property-registration"
            type="text"
            label="Matrícula do Imóvel Adjudicado"
            placeholder="Digite a matrícula"
            :model-value="internalData.adjudicated_property_registration"
            @update:model-value="updateField('adjudicated_property_registration', $event)"
            :error="errors?.notifiable?.adjudicated_property_registration"
        />

        <InputForm
            id="adjudicated-property-identification"
            type="text"
            label="Identificação do Imóvel que está sendo Adjudicado"
            placeholder="Digite o endereço completo do imóvel"
            :model-value="internalData.adjudicated_property_identification"
            @update:model-value="updateField('adjudicated_property_identification', $event)"
            :error="errors?.notifiable?.adjudicated_property_identification"
        />

        <InputForm
            id="adjudicated-property-registry-office"
            type="text"
            label="Cartório onde o Imóvel Adjudicado está Localizado"
            placeholder="Digite o nome do cartório"
            :model-value="internalData.adjudicated_property_registry_office"
            @update:model-value="updateField('adjudicated_property_registry_office', $event)"
            :error="errors?.notifiable?.adjudicated_property_registry_office"
        />
    </div>
</template>
