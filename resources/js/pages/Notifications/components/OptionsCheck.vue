<script setup lang="ts">
import RadioGroup from '../../../components/RadioGroup.vue';
import { ref } from 'vue';

const contactOptions = ref([
    { id: 'devedor_presente',  description: 'O Devedor estava presente e assinou a notificação.' },
    { id: 'procurador_presente', description: 'O Devedor Não Está no Local, Mas seu Procurador Assinou a Notificação. Procuração Com Poderes Formais.' },
    { id: 'compareceu_cartorio', description: 'Devedor Presente por Procurador: O Devedor Não Está no Local, Mas seu Procurador Assinou a Notificação. Procuração Com Poderes Formais.' },
    { id: 'contato_telefonico', description: 'Foi Feito Contato Telefônico com a Parte, que Marcou de ir ao Cartório Assinar a Notificação.' }
]);

const selectedContactType = ref('');


</script>

<template>
    <div>
        <RadioGroup
            title="Contato Direto e Resolutivo"
            :options="contactOptions"
            v-model="selectedContactType"
        />
    </div>

</template>