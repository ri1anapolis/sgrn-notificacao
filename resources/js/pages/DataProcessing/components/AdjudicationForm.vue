<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import ButtonSave from './ButtonSave.vue';
import InputForm from './InputForm.vue';

const form = useForm({
    office: null,
    adjudicatedPropertyRegistration: '',
    adjudicatedPropertyIdentification: '',
    adjudicatedPropertyRegistryOffice: '',
});
function submit() {
    return;
}
</script>

<template>
    <form @submit.prevent="submit" class="grid grid-cols-1 gap-5 p-6">
        <InputForm id="office" type="number" label="Nº de Ofício" placeholder="Digite o número do ofício" required />

        <InputForm id="adjudicated-property-registration" type="text" label="Matrícula do Imóvel Adjudicado" placeholder="Digite a matrícula" />

        <InputForm
            id="adjudicated-property-identification"
            type="text"
            label="Identificação do Imóvel que está sendo Adjudicado"
            placeholder="Digite o endereço completo do imóvel"
        />

        <InputForm
            id="adjudicated-property-registry-office"
            type="text"
            label="Cartório onde o Imóvel Adjudicado está Localizado"
            placeholder="Digite o nome do cartório"
        />

        <ButtonSave />
    </form>
</template>
