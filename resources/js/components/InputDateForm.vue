<script setup lang="ts">
import { vMaska } from 'maska/vue';
import { computed } from 'vue';
import InputForm from './InputForm.vue';

const props = defineProps<{
    modelValue: string | null;
    id: string;
    label: string;
    placeholder?: string;
    error?: string;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: string | null): void;
}>();

const onlyNumbers = (val: string) => val.replace(/\D/g, '');

const dateMaskOptions = {
    mask: '##/##/####',
    preProcess: (val: string) => onlyNumbers(val).substring(0, 8),
};

const ymdToDmy = (date: string | null): string => {
    if (!date || !date.match(/^\d{4}-\d{2}-\d{2}$/)) return '';
    const [y, m, d] = date.split('-');
    return `${d}/${m}/${y}`;
};

const dmyToYmd = (date: string | null): string | null => {
    if (!date || !date.match(/^\d{2}\/\d{2}\/\d{4}$/)) return null;
    const [d, m, y] = date.split('/');

    if (y.includes('_') || y.length < 4) return null;
    return `${y}-${m}-${d}`;
};

const maskedValue = computed({
    get: () => {
        return ymdToDmy(props.modelValue);
    },
    set: (newValue) => {
        emit('update:modelValue', dmyToYmd(newValue));
    },
});
</script>

<template>
    <InputForm
        :id="id"
        type="tel"
        :label="label"
        :placeholder="placeholder ?? 'DD/MM/AAAA'"
        v-model="maskedValue"
        v-maska="dateMaskOptions"
        :error="error"
    />
</template>
