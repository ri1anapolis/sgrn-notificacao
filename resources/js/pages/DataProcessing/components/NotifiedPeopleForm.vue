<script setup lang="ts">
import InputForm from './InputForm.vue'
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border border-zinc-400 rounded-lg p-6">
        <InputForm id="notificado" label="Notificado 1" placeholder="Nome do Notificado" required />
        <InputForm id="cpf" type="text" label="CPF 1" placeholder="000.000.000-00" required />
        <InputForm id="telefone" type="tel" label="Telefone" placeholder="(62) 99999-0000" />
        <InputForm id="email" type="email" label="E-mail" placeholder="seu@email.com" />

        <div>
            <label class="block text-sm font-medium text-gray-700">Gênero *</label>
            <div class="mt-2 flex items-center gap-6">
                <label class="inline-flex items-center">
                    <input type="radio" name="genero" value="masculino"
                        class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" />
                    <span class="ml-2 text-gray-700">Masculino</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="genero" value="feminino"
                        class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" />
                    <span class="ml-2 text-gray-700">Feminino</span>
                </label>
            </div>
        </div>
    </div>
</template>