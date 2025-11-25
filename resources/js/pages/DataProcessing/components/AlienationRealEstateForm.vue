<script setup lang="ts">
import { computed } from 'vue';
import InputForm from '../../../components/InputForm.vue';
import { vMaska } from "maska/vue";
import { registrationMask, ordinalMask, actMask, currencyMask } from '@/utils/masks';
import InputDateForm from '@/components/InputDateForm.vue';
import { formatDateForInput } from '@/utils/formatters';
import { registryOptions } from '@/constants/natures';

type AlienationRealEstateModel = App.Data.AlienationRealEstateData | null;

const props = defineProps<{
    modelValue: AlienationRealEstateModel;
    errors: any;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: AlienationRealEstateModel): void;
}>();

const selectedRegistry = computed({
    get: () => {
        const currentVal = internalData.value.real_estate_registry_location;

        if (currentVal === null) return '';
        if (registryOptions.includes(currentVal)) {
            return currentVal;
        }

        return 'Outro';
    },
    set: (value) => {
        if (value === 'Outro') {
            if (registryOptions.includes(internalData.value.real_estate_registry_location || '')) {
                updateField('real_estate_registry_location', '');
            }
        } else {
            updateField('real_estate_registry_location', value);
        }
    }
});

const defaultData: App.Data.AlienationRealEstateData = {
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
            default_period: data.default_period,
        };
    },
    set: (value) => emit('update:modelValue', value),
});

const updateField = (fieldName: keyof App.Data.AlienationRealEstateData, value: string | number | boolean | null) => {
    const currentData = internalData.value;
    internalData.value = {
        ...currentData,
        [fieldName]: value,
    } as App.Data.AlienationRealEstateData;
};

const handleInput = (event: Event, fieldName: keyof App.Data.AlienationRealEstateData, type: 'text' | 'number') => {
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
        <InputForm id="creditor" type="text" label="Credor" placeholder="Digite o nome do credor" required
            :model-value="internalData.creditor" @update:model-value="updateField('creditor', $event)"
            :error="errors?.notifiable?.creditor" />

        <InputForm id="office" type="number" label="Nº de Ofício" placeholder="Digite o número do ofício"
            v-maska="registrationMask" required :model-value="internalData.office"
            @input="(e: Event) => handleInput(e, 'office', 'number')" :error="errors?.notifiable?.office" />

        <InputForm id="guarantee-property-registration" type="text" label="Matrícula do Imóvel dado em Garantia"
            placeholder="Digite o número da matrícula" v-maska="registrationMask"
            :model-value="internalData.guarantee_property_registration"
            @update:model-value="updateField('guarantee_property_registration', $event)"
            :error="errors?.notifiable?.guarantee_property_registration" />

        <InputForm id="guarantee-property-address" type="text" label="Identificação do Imóvel dado em Garantia"
            placeholder="Digite o endereço completo do imóvel" :model-value="internalData.guarantee_property_address"
            @update:model-value="updateField('guarantee_property_address', $event)"
            :error="errors?.notifiable?.guarantee_property_address" />

        <InputForm id="contract-registration-act" type="text" label="Ato da matrícula com registro do contrato"
            placeholder="R-01" v-maska="actMask" :model-value="internalData.contract_registration_act"
            @update:model-value="updateField('contract_registration_act', $event)"
            :error="errors?.notifiable?.contract_registration_act" />

        <InputForm id="emoluments-intimation" type="text" label="Emolumentos para intimação" placeholder="R$ 0,00"
            v-maska="currencyMask" :model-value="internalData.emoluments_intimation"
            @update:model-value="updateField('emoluments_intimation', $event)"
            :error="errors?.notifiable?.emoluments_intimation" />

        <InputForm id="contract-number" type="text" label="Número do contrato" placeholder="Digite o número do contrato"
            :model-value="internalData.contract_number" @update:model-value="updateField('contract_number', $event)"
            :error="errors?.notifiable?.contract_number" />

        <InputDateForm id="contract-date" label="Data do contrato" :model-value="internalData.contract_date"
            @update:model-value="updateField('contract_date', $event)" :error="errors?.notifiable?.contract_date" />

        <InputForm id="total-amount-debt" type="text" label="Valor total da dívida" placeholder="R$ 0,00"
            v-maska="currencyMask" :model-value="internalData.total_amount_debt"
            @update:model-value="updateField('total_amount_debt', $event)"
            :error="errors?.notifiable?.total_amount_debt" />

        <InputDateForm id="debt-position-date" label="Data da posição da dívida"
            :model-value="internalData.debt_position_date"
            @update:model-value="updateField('debt_position_date', $event)"
            :error="errors?.notifiable?.debt_position_date" />

        <InputForm id="default-period" type="text" label="Período de inadimplemento"
            placeholder="01/01/2025 à 02/03/2025" :model-value="internalData.default_period"
            @update:model-value="updateField('default_period', $event)" :error="errors?.notifiable?.default_period" />

        <label class="inline-flex items-center cursor-pointer md:w-5/12">
            <input type="checkbox" name="grace-period" :checked="internalData.grace_period" @change="handleGracePeriod"
                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
            <span class="ml-2 text-gray-700">Possui prazo de carência respeitado?</span>
        </label>

        <InputForm id="contractual-clause" type="text" label="Cláusula contratual de procuração mútua"
            placeholder="Digite a cláusula" v-maska="ordinalMask" :model-value="internalData.contractual_clause"
            @update:model-value="updateField('contractual_clause', $event)"
            :error="errors?.notifiable?.contractual_clause" />

        <label for="registry-select" class="block text-sm font-medium text-gray-700">
            Cartório onde o imóvel dado em garantia se encontra
        </label>

        <div class="relative">
            <select id="registry-select" v-model="selectedRegistry"
                class="block w-full p-2 rounded-md border-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                <option value="" disabled>Selecione uma opção</option>
                <option v-for="option in registryOptions" :key="option" :value="option">
                    {{ option }}
                </option>
                <option value="Outro">Outro (Digitar manualmente)</option>
            </select>

            <p v-if="selectedRegistry !== 'Outro' && errors?.notifiable?.real_estate_registry_location"
                class="mt-1 text-sm text-red-600">
                {{ errors.notifiable.real_estate_registry_location }}
            </p>
        </div>

        <div v-if="selectedRegistry === 'Outro'" class="animate-fade-in-down">
            <InputForm id="real-estate-registry-location" type="text" label="Digite o nome do cartório"
                placeholder="Digite o nome do cartório..." :model-value="internalData.real_estate_registry_location"
                @update:model-value="updateField('real_estate_registry_location', $event)"
                :error="errors?.notifiable?.real_estate_registry_location" />
        </div>
    </div>
</template>
