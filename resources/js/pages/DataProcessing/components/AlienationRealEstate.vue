<script setup lang="ts">
import ButtonSave from './ButtonSave.vue';
import InputForm from './InputForm.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    creditor: '',
    office: null,
    guaranteePropertyRegistration: null,
    guaranteePropertyAddress: '',
    contractRegistrationAct: '',
    emolumentsIntimation: '',
    contractNumber: null,
    contractDate: '',
    totalAmountDebt: null,
    debtPositionDate: '',
    defaultPeriod: '',
    gracePeriod: false,
    contractualClause: '',
    realEstateRegistryLocation: '',
});

function submit() {
    return;
}

</script>

<template>
    <form @submit.prevent="submit" class="grid grid-cols-1 gap-5 p-6">
        <InputForm id="creditor" type="text" label="Credor" placeholder="Digite o nome do credor" required />
        <InputForm id="office" type="number" label="Nº de Ofício" placeholder="Digite o número do ofício" required />
        <InputForm id="guarantee-property-registration" type="number" label="Matrícula do Imóvel dado em Garantia"
            placeholder="Digite o número da matrícula" />
        <InputForm id="guarantee-property-address" type="text" label="Identificação do Imóvel dado em Garantia"
            placeholder="Digite o endereço completo do imóvel" />
        <InputForm id="contract-registration-act" type="number" label="Ato da matrícula com registro do contrato"
            placeholder="Ex: R-01" />
        <InputForm id="emoluments-intimation" type="text" label="Emolumentos para intimação"
            placeholder="Ex: R$ 150,00" />
        <InputForm id="contract-number" type="number" label="Número do contrato"
            placeholder="Digite o número do contrato" />
        <InputForm id="contract-date" type="date" label="Data do contrato" placeholder="01/01/2025" />
        <InputForm id="total-amount-debt" type="number" label="Valor total da dívida" placeholder="Ex: R$ 10.000,00" />
        <InputForm id="debt-position-date" type="date" label="Data do posição da dívida" placeholder="01/01/2025" />
        <InputForm id="default-period" type="date" label="Período de inadimplemento" placeholder="01/01/2025" />

        <label class="inline-flex items-center">
            <input type="radio" name="grace-period" value="grace-period"
                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" />
            <span class="ml-2 text-gray-700">Possui prazo de carência respeitado?</span>
        </label>

        <InputForm id="contractual-clause" type="text" label="Cláusula contratual de procuração mútua"
            placeholder="Digite a cláusula" />
        <InputForm id="real-estate-registry-contract" type="text" label="Cartório onde o imóvel dado em garantia"
            placeholder="Digite o nome do cartório" />
        <InputForm id="real-estate-registry-location" type="text"
            label="Cartório onde o imóvel dado em garantia se encontra" placeholder="Digite o nome do cartório" />
        <ButtonSave />
    </form>
</template>
