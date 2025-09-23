<script setup lang="ts">
import NotifiedPeopleForm from './NotifiedPeopleForm.vue';
import ButtonAdd from './ButtonAdd.vue';
import AddresForm from './AddressForm.vue';
import AlienationRealEstate from './AlienationRealEstate.vue';
import AlienationMovableProperty from './AlienationMovableProperty.vue';
import PurchaseAndSaleSubdivision from './PurchaseAndSaleSubdivision.vue';
import { natures } from '@/constants/natures';
import { computed } from 'vue';
import PurchaseAndSaleIncorporation from './PurchaseAndSaleIncorporation.vue';
import RetificationArea from './RetificationArea.vue';
import AdjudicationForm from './AdjudicationForm.vue';
import AdversePossessionForm from './AdversePossessionForm.vue';
import Others from './Others.vue';

const props = defineProps({
    nature: {
        type: String,
        required: true,
    }
})

const selectedNatureText = computed(() =>
    natures.find(n => n.value === props.nature)?.text ?? ''
)

</script>

<template>
    <div class="bg-ice-snow border-bege-claro border-2 p-5 rounded-lg">
        <h2 class="text-xl font-bold text-[#242424] text-center mb-5">Dados da Notificação</h2>
        <p class="font-semibold text-xl text-[#242424] mb-2">Notificados: </p>
        <NotifiedPeopleForm class="mb-5 mx-5" />
        <ButtonAdd text-button="Adicionar outro notificado +" class="mb-5 mt-10" />

        <p class="font-semibold text-xl text-[#242424] mb-2">Endereços: </p>
        <AddresForm class="mb-5 mx-5" />
        <ButtonAdd text-button="Adicionar outro endereço +" class="mb-5 mt-10" />

        <div class="border-b border-[#4c484868] mt-10"></div>
        <div v-if="selectedNatureText">
            <h2 class="text-xl font-medium text-center text-[#242424] mt-5 mb-5">Dados Específicos da Notificação: {{
                selectedNatureText }}</h2>
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
