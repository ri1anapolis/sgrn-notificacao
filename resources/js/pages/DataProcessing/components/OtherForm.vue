<script setup lang="ts">
import InputDateForm from '@/components/InputDateForm.vue';
import { formatDateForInput } from '@/utils/formatters';
import { computed } from 'vue';
import InputForm from '../../../components/InputForm.vue';

type OtherModel = App.Data.OtherData | null;

const props = defineProps<{
    modelValue: OtherModel;
    errors: any;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: OtherModel): void;
}>();

const defaultData: App.Data.OtherData = {
    id: 0,
    creditor: '',
    office: null,
    guarantee_property_registration: null,
    guarantee_property_address: null,
    contract_registration_act: null,
    emoluments_intimation: null,
    contract_number: null,
    contract_date: null,
    total_amount_debt: null,
    debt_position_date: null,
    default_period: null,
    grace_period: false,
    contractual_clause: null,
    real_estate_registry_location: null,
};

const internalData = computed({
    get: () => {
        const data = props.modelValue ?? defaultData;
        return {
            ...data,
            contract_date: formatDateForInput(data.contract_date as string | null),
            debt_position_date: formatDateForInput(data.debt_position_date as string | null),
        };
    },
    set: (value) => emit('update:modelValue', value),
});

const updateField = (fieldName: keyof App.Data.OtherData, value: string | number | boolean | null) => {
    const currentData = internalData.value;
    internalData.value = {
        ...currentData,
        [fieldName]: value,
    } as App.Data.OtherData;
};

const handleInput = (event: Event, fieldName: keyof App.Data.OtherData, type: 'text' | 'number') => {
    const target = event.target as HTMLInputElement;
    let value: string | number | null = target.value;

    if (type === 'number') {
        value = value === '' ? null : Number(value);
    }

    updateField(fieldName, value);
};

const handleGracePeriod = (event: Event) => {
    const target = event.target as HTMLInputElement;
    updateField('grace_period', target.checked);
};
</script>

<template>
    <div class="grid grid-cols-1 gap-5 p-6">
        <InputForm
            id="creditor"
            type="text"
            label="Credor"
            placeholder="Digite o nome do credor"
            required
            :model-value="internalData.creditor"
            @update:model-value="updateField('creditor', $event)"
            :error="errors?.notifiable?.creditor"
        />
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
            id="guarantee-property-registration"
            type="text"
            label="Matrícula do Imóvel dado em Garantia"
            placeholder="Digite o número da matrícula"
            :model-value="internalData.guarantee_property_registration"
            @update:model-value="updateField('guarantee_property_registration', $event)"
            :error="errors?.notifiable?.guarantee_property_registration"
        />
        <InputForm
            id="guarantee-property-address"
            type="text"
            label="Identificação do Imóvel dado em GarantIA"
            placeholder="Digite o endereço completo do imóvel"
            :model-value="internalData.guarantee_property_address"
            @update:model-value="updateField('guarantee_property_address', $event)"
            :error="errors?.notifiable?.guarantee_property_address"
        />
        <InputForm
            id="contract-registration-act"
            type="text"
            label="Ato da matrícula com registro do contrato"
            placeholder="Ex: R-01"
            :model-value="internalData.contract_registration_act"
            @update:model-value="updateField('contract_registration_act', $event)"
            :error="errors?.notifiable?.contract_registration_act"
        />
        <InputForm
            id="emoluments-intimation"
            type="text"
            label="Emolumentos para intimação"
            placeholder="Ex: R$ 150,00"
            :model-value="internalData.emoluments_intimation"
            @update:model-value="updateField('emoluments_intimation', $event)"
            :error="errors?.notifiable?.emoluments_intimation"
        />
        <InputForm
            id="contract-number"
            type="text"
            label="Número do contrato"
            placeholder="Digite o número do contrato"
            :model-value="internalData.contract_number"
            @update:model-value="updateField('contract_number', $event)"
            :error="errors?.notifiable?.contract_number"
        />

        <InputDateForm
            id="contract-date"
            label="Data do contrato"
            :model-value="internalData.contract_date"
            @update:model-value="updateField('contract_date', $event)"
            :error="errors?.notifiable?.contract_date"
        />

        <InputForm
            id="total-amount-debt"
            type="number"
            label="Valor total da dívida"
            placeholder="Ex: R$ 10.000,00"
            :model-value="internalData.total_amount_debt"
            @input="(e: Event) => handleInput(e, 'total_amount_debt', 'number')"
            :error="errors?.notifiable?.total_amount_debt"
        />

        <InputDateForm
            id="debt-position-date"
            label="Data da posição da dívida"
            :model-value="internalData.debt_position_date"
            @update:model-value="updateField('debt_position_date', $event)"
            :error="errors?.notifiable?.debt_position_date"
        />

        <InputForm
            id="default-period"
            type="text"
            label="Período de inadimplemento"
            placeholder="Ex: 30 dias ou 01/01/2025 - 02/03/2025"
            :model-value="internalData.default_period"
            @update:model-value="updateField('default_period', $event)"
            :error="errors?.notifiable?.default_period"
        />

        <label class="inline-flex items-center">
            <input
                type="checkbox"
                name="grace-period"
                :checked="internalData.grace_period"
                @change="handleGracePeriod"
                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
            />
            <span class="ml-2 text-gray-700">Possui prazo de carência respeitado?</span>
        </label>
        <InputForm
            id="contractual-clause"
            type="text"
            label="Cláusula contratual de procuração mútua"
            placeholder="Digite a cláusula"
            :model-value="internalData.contractual_clause"
            @update:model-value="updateField('contractual_clause', $event)"
            :error="errors?.notifiable?.contractual_clause"
        />
        <InputForm
            id="real-estate-registry-location"
            type="text"
            label="Cartório onde o imóvel dado em garantia se encontra"
            placeholder="Digite o nome do cartório"
            :model-value="internalData.real_estate_registry_location"
            @update:model-value="updateField('real_estate_registry_location', $event)"
            :error="errors?.notifiable?.real_estate_registry_location"
        />
    </div>
</template>
