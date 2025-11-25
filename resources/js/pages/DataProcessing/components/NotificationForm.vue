<script setup lang="ts">
import { natures } from '@/constants/natures';
import { computed, onMounted } from 'vue';
import AddressForm from './AddressForm.vue';
import AdjudicationForm from './AdjudicationForm.vue';
import AdversePossessionForm from './AdversePossessionForm.vue';
import AlienationMovablePropertyForm from './AlienationMovablePropertyForm.vue';
import AlienationRealEstateForm from './AlienationRealEstateForm.vue';
import ButtonAdd from './ButtonAdd.vue';
import NotifiedPeopleForm from './NotifiedPeopleForm.vue';
import OtherForm from './OtherForm.vue';
import PurchaseAndSaleIncorporationForm from './PurchaseAndSaleIncorporationForm.vue';
import PurchaseAndSaleSubdivisionForm from './PurchaseAndSaleSubdivisionForm.vue';
import RetificationAreaForm from './RetificationAreaForm.vue';

type NatureModelValue = string | null;

const props = defineProps<{
    notifiedPeople: App.Data.NotifiedPersonData[];
    addresses: App.Data.AddressData[];
    nature: NatureModelValue;
    notifiable: any;
    errors: any;
}>();

const emit = defineEmits<{
    (e: 'update:notifiedPeople', value: App.Data.NotifiedPersonData[]): void;
    (e: 'update:addresses', value: App.Data.AddressData[]): void;
    (e: 'update:nature', value: NatureModelValue): void;
    (e: 'update:notifiable', value: any): void;
}>();

const people = computed({
    get: () => props.notifiedPeople,
    set: (value) => emit('update:notifiedPeople', value),
});

const addrs = computed({
    get: () => props.addresses,
    set: (value) => emit('update:addresses', value),
});

const notifiableData = computed({
    get: () => props.notifiable,
    set: (value) => emit('update:notifiable', value),
});

const selectedNatureText = computed(() => {
    const nature = natures.find((n) => n.value === props.nature);
    return nature ? nature.text : null;
});

const addNotifiedPerson = () => {
    people.value = [
        ...people.value,
        {
            id: 0,
            name: '',
            document: '',
            phone: '',
            email: '',
            gender: 'masculine',
        } as App.Data.NotifiedPersonData,
    ];
};

const removeNotifiedPerson = (index: number) => {
    const newList = [...people.value];
    newList.splice(index, 1);
    people.value = newList;
};

const addAddress = () => {
    addrs.value = [
        ...addrs.value,
        {
            id: 0,
            address: '',
            diligences: null,
            notifiedPeople: null,
        } as App.Data.AddressData,
    ];
};

const removeAddress = (index: number) => {
    const newList = [...addrs.value];
    newList.splice(index, 1);
    addrs.value = newList;
};

onMounted(() => {
    if (people.value.length === 0) {
        addNotifiedPerson();
    }
    if (addrs.value.length === 0) {
        addAddress();
    }
});
</script>

<template>
    <div class="border-bege-claro rounded-lg border-2 bg-[#f2f3f4] p-5">
        <h2 class="mb-5 text-center text-xl font-bold text-[#242424]">Dados da Notificação</h2>

        <p class="mb-2 text-xl font-semibold text-[#242424]">Notificados:</p>
        <div v-for="(person, index) in people" :key="`person-${index}`" class="relative mx-5 mb-5">
            <NotifiedPeopleForm v-model="people[index]" :index="index" />
            <button v-if="(people.length > 1)" @click.prevent="removeNotifiedPerson(index)"
                class="absolute -top-2 -right-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 font-bold text-white transition-all duration-300 hover:scale-120 hover:bg-red-600 cursor-pointer">
                X
            </button>
        </div>
        <ButtonAdd text-button="Adicionar outro notificado +" @click="addNotifiedPerson" class="mt-10 mb-5" />

        <p class="mb-2 text-xl font-semibold text-[#242424]">Endereços:</p>
        <div v-for="(address, index) in addrs" :key="`address-${index}`" class="relative mx-5 mb-5">
            <AddressForm v-model="addrs[index]" :index="index" />
            <button v-if="(addrs.length > 1)" @click.prevent="removeAddress(index)"
                class="absolute -top-2 -right-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 font-bold text-white transition-all duration-300 hover:scale-120 hover:bg-red-600 cursor-pointer">
                X
            </button>
        </div>
        <ButtonAdd text-button="Adicionar outro endereço +" @click="addAddress" class="mt-10 mb-5" />

        <div class="mt-10 border-b border-[#4c484868]"></div>

        <div v-if="selectedNatureText">
            <h2 class="mt-5 mb-5 text-center text-xl font-medium text-[#242424]">Dados Específicos da Notificação: {{
                selectedNatureText }}</h2>
            <AlienationRealEstateForm v-if="nature === 'alienacao_fiduciaria_imovel'" v-model="notifiableData"
                :errors="errors" />
            <AlienationMovablePropertyForm v-if="nature === 'alienacao_fiduciaria_movel'" v-model="notifiableData"
                :errors="errors" />
            <PurchaseAndSaleSubdivisionForm v-if="nature === 'compromisso_loteamento'" v-model="notifiableData"
                :errors="errors" />
            <PurchaseAndSaleIncorporationForm v-if="nature === 'compromisso_incorporacao'" v-model="notifiableData"
                :errors="errors" />
            <RetificationAreaForm v-if="nature === 'retificacao_area'" v-model="notifiableData" :errors="errors" />
            <AdjudicationForm v-if="nature === 'adjudicacao'" v-model="notifiableData" :errors="errors" />
            <AdversePossessionForm v-if="nature === 'usucapiao'" v-model="notifiableData" :errors="errors" />
            <OtherForm v-if="nature === 'diversos'" v-model="notifiableData" :errors="errors" />
        </div>
    </div>
</template>
