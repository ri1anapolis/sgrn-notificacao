<script setup lang="ts">
import CardSection from '@/components/CardSection.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    imageUrl: {
        type: String,
        required: true,
    },

    altName: {
        type: String,
        required: true,
    },

    routeName: {
        type: String,
        required: false,
    },

    paramName: {
        type: String,
        required: false,
        default: 'notification',
    },
});

const form = useForm({
    protocol: '',
});

const submit = () => {
    if (!form.protocol) return;

    const formattedProtocol = form.protocol.replace(/\D/g, '').slice(0, 6);

    form.get(route(props.routeName, { [props.paramName]: formattedProtocol }), {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <CardSection :image-url="props.imageUrl" :altName="props.altName">
        <form
            @submit.prevent="submit"
            class="flex w-11/12 items-center gap-x-2 rounded-2xl bg-[#1a1a1a] p-2"
        >
            <input
                type="text"
                placeholder="Digite o protocolo..."
                maxlength="6"
                class="ml-5 flex-grow border-0 bg-transparent text-sm text-white placeholder-gray-400 focus:ring-0 focus:outline-none"
                v-model="form.protocol"
                @input="form.protocol = form.protocol.replace(/\D/g, '').slice(0, 6)"
            />

            <button
                type="submit"
                class="flex-shrink-0 rounded-md bg-[#c19a6b] p-2 transition-colors hover:cursor-pointer hover:bg-[#b58f5e]"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="h-5 w-5 text-black"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"
                    />
                </svg>
            </button>
        </form>
    </CardSection>
</template>
