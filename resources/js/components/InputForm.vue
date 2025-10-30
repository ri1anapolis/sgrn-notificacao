<script setup lang="ts">
import { computed } from 'vue';

defineOptions({
  inheritAttrs: false,
});

const props = defineProps<{
  id: string;
  label: string;
  modelValue: string | number | null;
  error?: string;
  required?: boolean;
}>();

const emit = defineEmits(['update:modelValue']);

const value = computed({
  get: () => (props.modelValue === null ? '' : props.modelValue),
  set: (val) => emit('update:modelValue', val),
});

const inputClasses = computed(() => [
  'mt-1 block w-full rounded-md border shadow-sm',
  'focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm p-2 px-3',
  props.error ? 'border-red-500' : 'border-gray-300',
]);
</script>

<template>
  <div>
    <label :for="id" class="ml-1 block text-sm font-medium text-gray-700">
      {{ label }} <span v-if="required" class="text-red-500">*</span>
    </label>
    <input :id="id" v-model="value" :class="inputClasses" v-bind="$attrs" />
    <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
  </div>
</template>
