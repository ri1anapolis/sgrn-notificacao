<script setup lang="ts">
interface Props {
    id: string
    type?: string
    label: string
    placeholder?: string
    required?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    type: "text",
    placeholder: "",
    required: false
})
</script>

<template>
    <div>
        <label :for="id" class="block text-sm font-medium text-gray-700 ml-1">
            {{ label }} <span v-if="required">*</span>
        </label>
        <input :id="id" :type="type" :placeholder="placeholder" :required="required" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm
             focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm p-2 px-3" />
    </div>
</template>
