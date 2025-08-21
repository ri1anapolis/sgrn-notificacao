<script setup lang="ts">

const props = defineProps({
    imageUrl: {
        type: String,
        required: true,
    },
    
    altName: {
        type: String,
        required: true,
    },
})
</script>

<template>
    <div class=" w-full max-w-75 md:max-w-88 rounded-2xl bg-[#3f4555] pb-5 shadow-md shadow-[#ffffff1a] 
                 flex flex-col items-center gap-y-4 hover:scale-105 transition duration-350">
        <img :src="props.imageUrl" :alt="props.altName" class="object-cover rounded-t-2xl" />

        <slot />
    </div>
</template>
