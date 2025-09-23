<script setup lang="ts">
import NatureSelect from './NatureSelect.vue';
import { useProtocolFormatter } from '@/composables/useFormattedProtocol';
import { computed } from 'vue';

type ModelValue = string;

const props = defineProps<{
    modelValue?: ModelValue;
    notification: App.Data.NotificationData;

}>();

const formattedProtocol = computed(() => useProtocolFormatter(props.notification.protocol));

const emit = defineEmits<{
    (e: 'update:modelValue', value: ModelValue): void
}>();

</script>

<template>
    <div
        class="flex items-center bg-ice-snow border-2 border-bege-claro h-auto w-full max-w-4xl md:p-2 md:pb-4 p-1 pb-4 m-auto text-[#242424] rounded-lg justify-between gap-16">
        <div class="text-xl flex flex-col md:flex-row ml-10 md:mt-5 font-bold">
            <p class="pb-2">Protocolo: </p>
            <p class="ml-2">{{ formattedProtocol }}</p>
        </div>

        <div class='flex flex-col mr-5 mt-3 md:flex-row'>
            <p class=" text-lg pb-2 md:p-1 md:mt-1 md:mr-4 font-bold">Natureza: </p>
            <NatureSelect :model-value="props.modelValue" @update:model-value="emit('update:modelValue', $event)" />
        </div>
    </div>
</template>
