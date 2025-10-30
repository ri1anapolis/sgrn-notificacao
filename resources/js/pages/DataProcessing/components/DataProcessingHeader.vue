<script setup lang="ts">
import NatureSelect from './NatureSelect.vue';
import { useProtocolFormatter } from '@/composables/useFormattedProtocol';
import { computed } from 'vue';

type NatureModelValue = string | null;

const props = defineProps<{
    notification: App.Data.NotificationData;
    nature: NatureModelValue;
}>();

const emit = defineEmits<{
    (e: 'update:nature', value: NatureModelValue): void;
}>();

const formattedProtocol = computed(() => useProtocolFormatter(props.notification.protocol));
</script>

<template>
    <div
        class="flex flex-col md:flex-row bg-[#f2f3f4] border-2 border-[#b3925c] h-auto w-11/12 md:w-4xl md:p-2 md:pb-4 p-5 m-auto text-[#242424] rounded-lg gap-4 md:items-center md:justify-between md:px-12">

        <div class="text-xl flex md:mt-5 font-bold">
            <p class="text-lg pb-2 font-bold">Protocolo:</p>
            <p class="ml-2">{{ formattedProtocol }}</p>
        </div>

        <div class="flex gap-4 md:mt-3 items-center">
            <p class="text-lg font-bold">Natureza:</p>
            <NatureSelect class="-mt-2" :model-value="props.nature"
                @update:model-value="emit('update:nature', $event)" />
        </div>
    </div>
</template>
