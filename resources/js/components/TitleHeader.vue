<script setup lang="ts">

const props = defineProps({
    pageTitle:{
        type: String,
        required: true,
    },

    className: {
        type: String,
        required: true,
    }
});

</script>

<template>
    <div>
        <h1 :class="className"> {{ props.pageTitle }}</h1>     
    </div>
</template>