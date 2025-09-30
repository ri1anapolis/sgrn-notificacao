<script setup lang="ts">
import type { PropType } from 'vue';

interface RadioOption {
    id: number | string;
    description: string;
}

defineProps({
    title: {
        type: String,
        required: true,
    },
    options: {
        type: Array as PropType<RadioOption[]>,
        required: true,
    },
    modelValue: {
        type: [String, Number, null],
        default: null,
    }
});

const emit = defineEmits(['update:modelValue']);

const handleChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    emit('update:modelValue', Number(target.value));
}
</script>

<template>
    <div class="m-auto mx-5 w-full max-w-4xl">
        <h3 class="flex items-center font-semibold text-bege-claro -mb-7">
            <span class="h-px flex-grow bg-[#b3925c]"></span>
            <span class="mx-4 md:text-lg text-[#b3925c]">{{ title }}</span>
            <span class="h-px flex-grow bg-[#b3925c]"></span>
        </h3>

        <div class="w-full mt-4 flex flex-col gap-y-5 rounded-lg rounded-t-none border border-t-0 border-[#b3925c] bg-[#0e1423] p-6 text-white">

            <label v-for="option in options" :key="option.id"
                class="flex cursor-pointer items-center gap-x-4 rounded-md p-3 hover:bg-white/10">

                <input :value="option.id" :checked="modelValue === option.id" @change="handleChange" type="radio"
                    :name="'radio-group-' + title" class="peer hidden">

                <div
                    class="flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full border-2 border-gray-500 peer-checked:border-sky-500 peer-checked:bg-sky-950">
                    <div class="hidden h-2 w-2 rounded-full bg-white peer-checked:block"></div>
                </div>

                <div class="flex flex-col">
                    <span class="text-sm md:text-base text-gray-200">{{ option.description }}</span>
                </div>
            </label>
        </div>
    </div>
</template>
