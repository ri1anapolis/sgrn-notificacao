<script setup lang="ts">
defineProps<{
    processing: boolean;
    disabled: boolean;
    text: string;
    processingText?: string;
}>();

defineEmits(['click']);
</script>

<template>
    <button type="submit"
            :disabled="disabled || processing"
            @click="$emit('click')"
            class="inline-flex items-center justify-center w-full
                   px-8 py-3
                   text-white text-base font-semibold rounded-lg shadow-lg
                   transition-all duration-200 ease-in-out
                   focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2

                   disabled:cursor-not-allowed disabled:shadow-none

                   enabled:bg-sky-600 enabled:hover:bg-sky-700 enabled:active:scale-95 enabled:focus-visible:ring-sky-500
                   disabled:bg-gray-500 disabled:opacity-70">

        <svg v-if="processing" class="animate-spin -ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>

        <span>{{ processing ? (processingText || 'Salvando...') : text }}</span>
    </button>
</template>