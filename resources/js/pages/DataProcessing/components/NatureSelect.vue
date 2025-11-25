<script setup lang="ts">
import { natures } from '@/constants/natures';

type ModelValue = string | null;

interface Props {
    modelValue?: ModelValue;
}

const props = defineProps<Props>();
const emit = defineEmits<{
    (e: 'update:modelValue', value: ModelValue): void
}>();

</script>

<template>
    <label for="natureza-select">
        <select name="menu" id="menu" :value="props.modelValue"
            @change="$emit('update:modelValue', ($event.target as HTMLSelectElement).value || null)"
            class="w-72 rounded-md border p-2 border-[#ab9260] focus:ring-bege-claro focus:border-bege-claro bg-[#d7c59b]">
            <option class="font-light" value="" disabled>Selecione uma natureza...</option>
            <option v-for="nature in natures" :key="nature.value" :value="nature.value">
                {{ nature.text }}
            </option>
        </select>
    </label>
</template>
