<script setup lang="ts">
const props = defineProps({
    completeAddress: {
        type: String,
        required: true,
    }
})

</script>

<template>
    <div class="-mt-3">
        <div class="flex bg-[#0e1423] border-2 border-[#b3925c21] h-auto w-auto max-w-4xl pt-5 p-5 m-auto text-white rounded-lg">
            <div class="flex flex-col w-full">
                <strong class="text-bege-claro font- text-xl mb-5">Endereço da Notificação:</strong>
                <div class="w-full group flex items-center">
                    <span class="text-lg">
                        {{ props.completeAddress }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>