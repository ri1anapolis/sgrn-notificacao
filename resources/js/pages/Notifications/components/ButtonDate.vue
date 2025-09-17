<script setup lang="ts">
import { ref } from 'vue';
let dateComplete = ref<string | null>(null);
let hourComplete = ref<string | null>(null);

function registryDate(){
    const now = new Date();

    const day = String(now.getDate());
    const month = String(now.getMonth() + 1);
    const year = String(now.getFullYear());
    const dayFormatted = day.padStart(2, '0');
    const monthFormatted = month.padStart(2, '0');

    const finalDate = `${dayFormatted}/${monthFormatted}/${year}`

    const hour = String(now.getHours());
    const minute = String(now.getMinutes());
    const second = String(now.getSeconds());
    const hourFormatted = hour.padStart(2, '0');
    const minuteFormatted = minute.padStart(2, '0');
    const secondFormatted = second.padStart(2, '0');

    const finalHour = `${hourFormatted}:${minuteFormatted}:${secondFormatted}`

    dateComplete.value = finalDate
    hourComplete.value = finalHour;
}
</script>

<template>
    <div class="flex flex-col w-full md:w-80">
        <button class="
            inline-flex items-center mb-3 gap-x-2 md:gap-x-3
            bg-bege-claro text-black font-medium justify-center
            p-4 rounded-lg text-base
        " @click="registryDate()">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"

                class="w-5 h-5 sm:w-6 sm:h-6"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                />
            </svg>
            Registrar Data e Hora
        </button>
        <div v-if="dateComplete !== null" class="bg-[#1720308e] p-3 border border-[#172030] rounded-md w-full md:w-80 text-center">
            <p class="text-gray-300 mb-1">Registrado em:</p>
            <span class="text-bege-claro text-lg font-semibold"> {{ dateComplete }} às {{ hourComplete }}</span>
        </div>
    </div>
</template>