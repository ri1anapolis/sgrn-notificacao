<script setup lang=ts>

const props = defineProps({
    textButton: {
        type: String,
        required: true,
    }
})

</script>

<template>
    <div
        class="px-6 py-3 text-zinc-800 bg-[#d9c084] rounded-3xl font-semibold w-fit m-auto border border-bege-claro transition duration-300 hover:scale-105 text-sm">
        <button>
            {{ props.textButton }}
        </button>
    </div>
</template>
