<script setup lang="ts">
import { useProtocolFormatter } from '@/composables/useFormattedProtocol';
import { computed } from 'vue';
import { getNatureLabelFromType } from '@/utils/NotificationNatures';

const props = defineProps<{
    notification: App.Data.NotificationData;
    address?: App.Data.AddressData;
}>();

const formattedProtocol = computed(() => useProtocolFormatter(props.notification.protocol));
</script>

<template>
    <div
        class="mx-auto mb-6 flex h-auto w-11/12 flex-col justify-between gap-5 rounded-lg border-2 border-[#b3925c] bg-[#0e1423] p-4 px-8 text-lg text-white md:w-4xl md:flex-row">
        <div class="flex flex-row gap-2 md:flex-col md:gap-0">
            <strong class="mb-2 font-bold text-[#b3925c]">Protocolo: </strong>
            <p>{{ formattedProtocol }}</p>
        </div>
        <div class="flex flex-col gap-2 md:gap-0">
            <strong class="font-bold text-[#b3925c] md:mb-2">Natureza:</strong>
            <p class="text-[14px]">{{ getNatureLabelFromType(props.notification.notifiable_type) }}</p>
        </div>
        <div v-if="notification && notification.notified_people.length > 0" class="flex flex-col gap-2 md:gap-0">
            <strong class="mr-20 font-bold text-[#b3925c] md:mb-2">Notificados:</strong>
            <ul class="text-[14px] text-white md:flex-col md:gap-1">
                <li v-for="person in notification.notified_people" :key="person.id">
                    {{ person.name }} - {{ person.document }}
                </li>
            </ul>
        </div>
        <div v-else class="flex flex-col gap-2 md:gap-0">
            <strong class="mr-20 font-bold text-[#b3925c] md:mb-2">Notificados:</strong>
            <ul class="text-[14px] text-white md:flex-col md:gap-1">
                <li v-for="person in notification.notified_people" :key="person.id">
                    {{ person.name }} - {{ person.document }}
                </li>
            </ul>
        </div>
    </div>
</template>
