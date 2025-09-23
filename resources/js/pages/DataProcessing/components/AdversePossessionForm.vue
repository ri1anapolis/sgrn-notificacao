<script setup lang="ts">
import ButtonSave from './ButtonSave.vue';
import InputForm from './InputForm.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    office: null,
    adversePossessionPropertyRegistration: '',
    adversePossessionPropertyIdentification: '',
    adversePossessionPropertyRegistryOffice: '',
});

function submit() {
    return;
}
</script>

<template>
    <form @submit.prevent="submit" class="grid grid-cols-1 gap-5 p-6">
        <InputForm id="office" type="number" label="Nº de Ofício" placeholder="Digite o número do ofício" required />

        <InputForm id="adverse-possession-property-registration" type="text" label="Matrícula do Imóvel Usucapido"
            placeholder="Digite a matrícula" required />

        <InputForm id="adverse-possession-property-identification" type="text"
            label="Identificação do Imóvel que está sendo Usucapido" placeholder="Digite o endereço completo do imóvel"
            required />

        <InputForm id="adverse-possession-property-registry-office" type="text"
            label="Cartório onde o Imóvel Usucapido se Encontra" placeholder="Digite o nome do cartório" required />

        <ButtonSave />
    </form>
</template>
