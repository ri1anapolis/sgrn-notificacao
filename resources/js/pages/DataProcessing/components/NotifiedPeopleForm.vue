<script setup lang="ts">
import InputForm from '@/components/InputForm.vue';
import InputPhoneForm from '@/components/InputPhoneForm.vue';
import { vMaska } from 'maska/vue';
import { computed } from 'vue';

const props = defineProps<{
    modelValue: App.Data.NotifiedPersonData;
    index: number;
    errors?: Record<string, string>;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: App.Data.NotifiedPersonData): void;
}>();

const person = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value),
});

const updateField = (field: keyof App.Data.NotifiedPersonData, value: string) => {
    person.value = { ...person.value, [field]: value };
};

const nameError = computed(() => props.errors?.[`notified_people.${props.index}.name`]);
const documentError = computed(() => props.errors?.[`notified_people.${props.index}.document`]);
const genderError = computed(() => props.errors?.[`notified_people.${props.index}.gender`]);
</script>

<template>
    <div class="grid grid-cols-1 gap-4 rounded-lg border border-zinc-400 p-6 md:grid-cols-2">
        <InputForm
            id="notificado"
            :label="`Notificado ${index + 1}`"
            placeholder="Nome do Notificado"
            required
            :model-value="person.name"
            @update:model-value="updateField('name', $event)"
            :error="nameError"
        />
        <InputForm
            id="cpf"
            type="text"
            :label="`CPF ${index + 1}`"
            placeholder="000.000.000-00"
            required
            :model-value="person.document"
            @update:model-value="updateField('document', $event)"
            v-maska
            data-maska="###.###.###-##"
            :error="documentError"
        />

        <InputPhoneForm
            id="telefone"
            label="Telefone"
            placeholder="(62) 00000-0000"
            :model-value="person.phone!"
            @update:model-value="updateField('phone', $event)"
        />

        <InputForm
            id="email"
            type="email"
            label="E-mail"
            placeholder="seu@email.com"
            :model-value="person.email!"
            @update:model-value="updateField('email', $event)"
        />

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Gênero *</label>
            <div class="mt-2 flex items-center gap-6">
                <div class="flex items-center">
                    <input
                        :id="`gender-masculine-${index}`"
                        :name="`gender-${index}`"
                        type="radio"
                        value="masculine"
                        :checked="person.gender === 'masculine'"
                        @change="updateField('gender', 'masculine')"
                        class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500"
                    />
                    <label :for="`gender-masculine-${index}`" class="ml-2 block text-sm text-gray-900">Masculino</label>
                </div>
                <div class="flex items-center">
                    <input
                        :id="`gender-feminine-${index}`"
                        :name="`gender-${index}`"
                        type="radio"
                        value="feminine"
                        :checked="person.gender === 'feminine'"
                        @change="updateField('gender', 'feminine')"
                        class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500"
                    />
                    <label :for="`gender-feminine-${index}`" class="ml-2 block text-sm text-gray-900">Feminino</label>
                </div>
            </div>
            <p v-if="genderError" class="mt-1 text-sm text-red-600">
                {{ genderError }}
            </p>
        </div>
    </div>
</template>
