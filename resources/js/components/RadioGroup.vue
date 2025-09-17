<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    options: {
        type: Array,
        required: true,
    },
    modelValue: {
        type: [String, Number],
        default: '',
    }
});

defineEmits(['update:modelValue']);
</script>

<template>
    <div class="w-auto max-w-4xl mx-auto">
        <h3 class="flex items-center text-bege-claro font-semibold">
            <span class="flex-grow h-px bg-bege-claro/50"></span>
            <span class="mx-4">{{ title }}</span>
            <span class="flex-grow h-px bg-bege-claro/50"></span>
        </h3>

        <div class="mt-4 flex flex-col gap-y-4 rounded-lg border-2 border-[#b3925c21] bg-[#0e1423] p-6 text-white">

            <label v-for="option in options" :key="option.id" class="flex items-center gap-x-4 p-3 rounded-md hover:bg-white/10 cursor-pointer">

                <input
                    :value="option.id"
                    @change="$emit('update:modelValue', $event.target.value)"
                    type="radio"
                    name="radio-group"
                    class="hidden peer"
                >

                <div class="
                    flex-shrink-0 w-5 h-5 rounded-full border-2 border-gray-500
                    flex items-center justify-center
                    peer-checked:border-sky-400 peer-checked:bg-sky-950
                ">
                    <div class="w-2 h-2 rounded-full bg-white hidden peer-checked:block"></div>
                </div>

                <div class="flex flex-col">
                    <span class="text-sm md:text-base">{{ option.description }}</span>
                </div>
            </label>
        </div>
    </div>
</template>