<script setup lang="ts">
import { router } from '@inertiajs/vue3';

const props = defineProps({
    text: {
        type: String,
        required: true,
    },

    link: {
        type: String,
        required: true,
    },

    method: {
        type: String as () => 'get' | 'post',
        default: 'get',
    },

    hasParameter: {
        type: Boolean,
        default: false,
    },

    icon: {
        type: String,
        required:false,
    },
});

function handleClick() {
    const url = props.hasParameter ? props.link : route(props.link);

    if (props.method === 'post') {
        router.post(url);
    } else {
        router.get(url);
    }
}
</script>

<template>
    <div class="flex justify-center">
        <button class="btn-shine flex items-center gap-x-1 rounded-xl border border-sky-500 bg-[#ffffff15] p-2 text-sky-500 text-sm" @click="handleClick">
            <span>{{ props.text }}</span>

            <slot name="icon">
                <svg
                    v-if="props.icon === 'arrow'"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="h-5 w-5"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15M12 18.75V5.25" />
                </svg>

                <svg
                    v-else-if="props.icon === 'logout'"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="h-5 w-5"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6
               A2.25 2.25 0 0 0 5.25 5.25v13.5
               A2.25 2.25 0 0 0 7.5 21h6
               A2.25 2.25 0 0 0 15.75 18.75V15
               m-3-3 3 3m0 0-3 3m3-3H21"
                    />
                </svg>
            </slot>
        </button>
    </div>
</template>

<style scoped>
.btn-shine {
    position: relative;
    overflow: hidden;
}

.btn-shine::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: linear-gradient(110deg, transparent 20%, rgba(255, 255, 255, 0.4) 50%, transparent 80%);
    transform: translateX(-100%);
    transition: transform 500ms ease-in-out;
}

.btn-shine:hover::before {
    transform: translateX(100%);
}

.btn-shine:hover {
    cursor: pointer;
}
</style>
