<script setup lang="ts">
import type { NotificationData } from '@/types/generated';
import { useProtocolFormatter } from '@/composables/useFormattedProtocol';
import { computed } from 'vue';

const props = defineProps<{
  notification: NotificationData;
}>();

const formattedProtocol = computed(() => useProtocolFormatter(props.notification.protocol));
</script>

<template>
    <div
        class="flex flex-col md:flex-row justify-between gap-5 bg-[#0e1423] border-2 border-[#b3925c] h-auto w-11/12 md:w-full max-w-4xl p-4 md:pl-8 md:pr-15 m-auto mb-6 text-white rounded-lg">
        <div class="flex flex-row md:flex-col gap-2 md:gap-0">
            <strong class="font-bold mb-2 text-[#b3925c]">Protocolo: </strong>
            <p>{{ formattedProtocol }}</p>
        </div>
        <div class="flex flex-col gap-2 md:gap-0">
            <strong class="font-bold md:mb-2 text-[#b3925c]">Natureza:</strong>
            <p class="text-[14px]">{{ props.notification.nature }}</p>
        </div>
        <div class="flex flex-col gap-2 md:gap-0">
            <strong class="font-bold md:mb-2 text-[#b3925c]">Notificados:</strong>
            <ul class="text-[14px] md:flex-col md:gap-1 text-white">
                <li v-for="person in props.notification.notified_people" :key="person.id">
                    {{ person.name }}
                </li>
            </ul>
        </div>
    </div>
</template>
