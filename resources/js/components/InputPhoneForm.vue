<script setup lang="ts">
import { vMaska } from 'maska/vue';
import InputForm from './InputForm.vue';

defineProps<{
    modelValue: string;
    id: string;
    label: string;
    placeholder?: string;
    error?: string;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

const onlyNumbers = (val: string) => val.replace(/\D/g, '');

const phoneMaskOptions = {
    mask: ['(##) ####-####', '(##) #####-####'],

    preProcess: (val: string) => onlyNumbers(val).substring(0, 11),
};
</script>

<template>
    <InputForm
        :id="id"
        type="tel"
        :label="label"
        :placeholder="placeholder"
        :model-value="modelValue"
        @update:model-value="$emit('update:modelValue', $event)"
        v-maska="phoneMaskOptions"
        :error="error"
    />
</template>
