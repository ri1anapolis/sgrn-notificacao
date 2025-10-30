<script setup lang="ts">
import { computed } from 'vue';
import InputForm from '../../../components/InputForm.vue';

type AdversePossessionModel = App.Data.AdversePossessionData | null;

const props = defineProps<{
    modelValue: AdversePossessionModel;
    errors: any;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: AdversePossessionModel): void;
}>();

const defaultData: App.Data.AdversePossessionData = {
    id: 0,
    office: null,
    adverse_possession_property_registration: '',
    adverse_possession_property_identification: '',
    adverse_possession_property_registry_office: '',
};

const internalData = computed({
    get: () => props.modelValue ?? defaultData,
    set: (value) => emit('update:modelValue', value),
});

const updateField = (fieldName: keyof App.Data.AdversePossessionData, value: string | number | boolean | null) => {
    const currentData = internalData.value;
    internalData.value = {
        ...currentData,
        [fieldName]: value,
    } as App.Data.AdversePossessionData;
};

const handleInput = (event: Event, fieldName: keyof App.Data.AdversePossessionData, type: 'text' | 'number') => {
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
            id="adverse-possession-property-registration"
            type="text"
            label="Matrícula do Imóvel Usucapido"
            placeholder="Digite a matrícula"
            required
            :model-value="internalData.adverse_possession_property_registration"
            @update:model-value="updateField('adverse_possession_property_registration', $event)"
            :error="errors?.notifiable?.adverse_possession_property_registration"
        />

        <InputForm
            id="adverse-possession-property-identification"
            type="text"
            label="Identificação do Imóvel que está sendo Usucapido"
            placeholder="Digite o endereço completo do imóvel"
            required
            :model-value="internalData.adverse_possession_property_identification"
            @update:model-value="updateField('adverse_possession_property_identification', $event)"
            :error="errors?.notifiable?.adverse_possession_property_identification"
        />

        <InputForm
            id="adverse-possession-property-registry-office"
            type="text"
            label="Cartório onde o Imóvel Usucapido se Encontra"
            placeholder="Digite o nome do cartório"
            required
            :model-value="internalData.adverse_possession_property_registry_office"
            @update:model-value="updateField('adverse_possession_property_registry_office', $event)"
            :error="errors?.notifiable?.adverse_possession_property_registry_office"
        />
    </div>
</template>
