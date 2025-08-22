<script setup lang="ts">
import type { NotificationData } from '@/types/generated';

const props = defineProps<{
  notification: NotificationData;
}>();
</script>

<template>
    <div
        class="flex flex-col md:flex-row justify-between gap-5 bg-[#0e1423] border-2 border-[#b3925c] h-auto w-11/12 md:w-full max-w-4xl p-4 md:pl-8 md:pr-15 m-auto mb-6 text-white rounded-lg">
        
        <div class="flex flex-col gap-2 w-full">
            <strong class="font-bold md:mb-2 text-[#b3925c]">Endereços:</strong>
            
            <ul class="flex flex-col gap-3 w-full">
                <li v-for="person in props.notification.notified_people" :key="person.id" class="bg-slate-800 rounded-lg hover:bg-[#3a4050] border border-gray-700 text-left text-sm text-white px-4 py-3">
                    {{ person.addresses[0]?.address ?? 'Endereço não cadastrado' }}
                </li>
            </ul>
        </div>
    </div>
</template>
