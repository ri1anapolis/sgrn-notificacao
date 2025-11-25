<script setup lang="ts">
const props = defineProps<{
    modelValue: App.Data.AddressData;
    index: number;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: App.Data.AddressData): void;
}>();

const updateAddress = (event: Event) => {
    const target = event.target as HTMLInputElement;
    emit('update:modelValue', { ...props.modelValue, address: target.value });
};
</script>

<template>
    <div class="grid grid-cols-1 gap-4 rounded-lg border border-zinc-400 p-6">
        <label :for="`address-${index}`" class="block text-sm font-medium text-gray-700">
            Endereço {{ index + 1 }} <span class="text-red-500">*</span>
        </label>
        <input type="text" :id="`address-${index}`" placeholder="Digite o endereço completo" :value="modelValue.address"
            @input="updateAddress"
            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" />
    </div>
</template>
