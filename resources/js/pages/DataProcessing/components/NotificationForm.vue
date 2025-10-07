<script setup lang="ts">
import { natures } from '@/constants/natures';
import { computed } from 'vue';
import AddresForm from './AddressForm.vue';
import AdjudicationForm from './AdjudicationForm.vue';
import AdversePossessionForm from './AdversePossessionForm.vue';
import AlienationMovableProperty from './AlienationMovableProperty.vue';
import AlienationRealEstate from './AlienationRealEstate.vue';
import ButtonAdd from './ButtonAdd.vue';
import NotifiedPeopleForm from './NotifiedPeopleForm.vue';
import Others from './Others.vue';
import PurchaseAndSaleIncorporation from './PurchaseAndSaleIncorporation.vue';
import PurchaseAndSaleSubdivision from './PurchaseAndSaleSubdivision.vue';
import RetificationArea from './RetificationArea.vue';

const props = defineProps({
    nature: {
        type: String,
        required: true,
    },
});

const selectedNatureText = computed(() => natures.find((n) => n.value === props.nature)?.text ?? '');
</script>

<template>
    <div class="bg-ice-snow border-bege-claro rounded-lg border-2 p-5">
        <h2 class="mb-5 text-center text-xl font-bold text-[#242424]">Dados da Notificação</h2>
        <p class="mb-2 text-xl font-semibold text-[#242424]">Notificados:</p>
        <NotifiedPeopleForm class="mx-5 mb-5" />
        <ButtonAdd text-button="Adicionar outro notificado +" class="mt-10 mb-5" />

        <p class="mb-2 text-xl font-semibold text-[#242424]">Endereços:</p>
        <AddresForm class="mx-5 mb-5" />
        <ButtonAdd text-button="Adicionar outro endereço +" class="mt-10 mb-5" />

        <div class="mt-10 border-b border-[#4c484868]"></div>
        <div v-if="selectedNatureText">
            <h2 class="mt-5 mb-5 text-center text-xl font-medium text-[#242424]">Dados Específicos da Notificação: {{ selectedNatureText }}</h2>
            <AlienationRealEstate v-if="props.nature === 'alienacao_fiduciaria_imovel'" />
            <AlienationMovableProperty v-if="props.nature === 'alienacao_fiduciaria_movel'" />
            <PurchaseAndSaleSubdivision v-if="props.nature === 'compromisso_loteamento'" />
            <PurchaseAndSaleIncorporation v-if="props.nature === 'compromisso_incorporacao'" />
            <RetificationArea v-if="props.nature === 'retificacao_area'" />
            <AdjudicationForm v-if="props.nature === 'adjudicacao'" />
            <AdversePossessionForm v-if="props.nature === 'usucapiao'" />
            <Others v-if="props.nature === 'diversos'" />
        </div>
    </div>
</template>
