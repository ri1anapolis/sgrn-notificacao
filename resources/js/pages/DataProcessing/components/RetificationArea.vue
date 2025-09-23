<script setup lang="ts">
import ButtonSave from './ButtonSave.vue';
import InputForm from './InputForm.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    office: null,
    rectifyingPropertyRegistration: '',
    rectifyingPropertyIdentification: '',
    rectifyingPropertyRegistryOffice: '',
});
function submit() {
    return;
}

</script>

<template>
    <form @submit.prevent="submit" class="grid grid-cols-1 gap-5 p-6">
        <InputForm id="office" type="number" label="Nº de Ofício" placeholder="Digite o número do ofício" required />
        <InputForm id="rectifying-property-registration" type="text" label="Matrícula do Imóvel Retificando"
            placeholder="Digite a matrícula" />
        <InputForm id="rectifying-property-identification" type="text"
            label="Identificação do Imóvel que está sendo Retificado"
            placeholder="Digite o endereço completo do imóvel" />
        <InputForm id="rectifying-property-registry-office" type="text"
            label="Cartório onde o Imóvel Retificando está Localizado" placeholder="Digite o nome do cartório" />
        <ButtonSave />
    </form>
</template>