<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    imageUrl: {
        type: String,
        required: true,
    },

    pageUrl: {
        type: String,
        required: true
    }
})

const protocol = ref('');

const submit = () => {
    const formattedProtocol = protocol.value.replace(/\./g, '');
    router.get(route(props.pageUrl, { protocol: formattedProtocol }))
}
</script>

<template>
    <div class="
            w-full max-w-75 md:max-w-88 rounded-2xl bg-[#3f4555] pb-5 shadow-md shadow-[#ffffff1a]
            flex flex-col items-center gap-y-4 hover:scale-105 transition duration-350
        ">

        <img :src="imageUrl" alt="Ilustração de uma pessoa usando um notebook" class="object-cover rounded-t-2xl" />

        <form @submit.prevent="submit" class="flex w-11/12 items-center gap-x-2 rounded-2xl bg-[#1a1a1a] p-2">
            <input type="text" placeholder="Digite o protocolo..." class="
                    flex-grow bg-transparent text-white text-sm ml-5 placeholder-gray-400
                    border-0 focus:ring-0 focus:outline-none
                " v-model="protocol" />

            <button type="submit" class="
                        flex-shrink-0 rounded-md bg-[#c19a6b] p-2
                        hover:bg-[#b58f5e] transition-colors hover:cursor-pointer
                    ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="h-5 w-5 text-black">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </button>
        </form>
    </div>
</template>
