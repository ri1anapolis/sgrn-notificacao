<script setup lang="ts">
defineProps<{
    address: App.Data.AddressData;
}>();
</script>

<template>
    <div class="-mt-3">
        <div
            class="flex bg-[#0e1423] border-2 border-[#b3925c21] h-auto mx-auto w-11/12 md:w-4xl pt-5 p-5  text-white rounded-lg">
            <div class="flex flex-col w-full">
                <strong class="text-bege-claro  text-lg mb-5">Endereço da Notificação:</strong>
                <div class="w-full group flex items-center">
                    <span class="text-lg">
                        {{ address.address }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>
