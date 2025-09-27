<script setup lang="ts">
import { ref } from 'vue';

const emit = defineEmits<{
    (e: 'dateRegistered', value: string): void;
}>();

let dateComplete = ref<string | null>(null);
let hourComplete = ref<string | null>(null);

function registryDate() {
    const now = new Date();

    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');

    const hour = String(now.getHours()).padStart(2, '0');
    const minute = String(now.getMinutes()).padStart(2, '0');
    const second = String(now.getSeconds()).padStart(2, '0');

    dateComplete.value = `${day}/${month}/${year}`;
    hourComplete.value = `${hour}:${minute}:${second}`;

    const dateTimeForBackend = `${year}-${month}-${day} ${hour}:${minute}:${second}`;
    emit('dateRegistered', dateTimeForBackend);
}
</script>

<template>
    <div class="flex w-full flex-col md:w-80">
        <button
            type="button"
            class="bg-[#b3925c] mb-3 inline-flex items-center cursor-pointer justify-center gap-x-2 rounded-lg p-4 text-base font-medium text-black md:gap-x-3"
            @click="registryDate()"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="h-5 w-5 sm:h-6 sm:w-6"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Registrar Data e Hora
        </button>
        <div v-if="dateComplete !== null" class="w-full rounded-md border border-[#172030] bg-[#1720308e] p-3 text-center md:w-80">
            <p class="mb-1 text-gray-300">Registrado em:</p>
            <span class="text-lg font-semibold text-green-200"> {{ dateComplete }} às {{ hourComplete }}</span>
        </div>
    </div>
</template>
