<script setup lang="ts">
import { computed } from 'vue';
import InputForm from '../../../components/InputForm.vue';

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

const handleInput = (event: Event, fieldName: keyof App.Data.RetificationAreaData, type: 'text' | 'number' | 'date') => {
    const target = event.target as HTMLInputElement;
    let value: string | number | null = target.value;

    if (type === 'number') {
        value = value === '' ? null : Number(value);
    } else if (type === 'date' && value === '') {
        value = null;
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
            id="rectifying-property-registration"
            type="text"
            label="Matrícula do Imóvel Retificando"
            placeholder="Digite a matrícula"
            :model-value="internalData.rectifying_property_registration"
            @update:model-value="updateField('rectifying_property_registration', $event)"
            :error="errors?.notifiable?.rectifying_property_registration"
        />
        <InputForm
            id="rectifying-property-identification"
            type="text"
            label="Identificação do Imóvel que está sendo Retificado"
            placeholder="Digite o endereço completo do imóvel"
            :model-value="internalData.rectifying_property_identification"
            @update:model-value="updateField('rectifying_property_identification', $event)"
            :error="errors?.notifiable?.rectifying_property_identification"
        />
        <InputForm
            id="rectifying-property-registry-office"
            type="text"
            label="Cartório onde o Imóvel Retificando está Localizado"
            placeholder="Digite o nome do cartório"
            :model-value="internalData.rectifying_property_registry_office"
            @update:model-value="updateField('rectifying_property_registry_office', $event)"
            :error="errors?.notifiable?.rectifying_property_registry_office"
        />
    </div>
</template>
