<script setup lang="ts">
import IconButton from '@/components/IconButton.vue';
import { ref } from 'vue';

defineProps<{
    isAdmin: boolean;
    isDashboard?: boolean;
    textButton: string;
    linkButton: string;
    method?: 'get' | 'post';
    hasParameter?: boolean;
}>();

const isOpen = ref(false);

const toggleMenu = () => {
    isOpen.value = !isOpen.value;
};

const closeMenu = () => {
    isOpen.value = false;
};
</script>

<template>
    <div class="lg:hidden">
        <button
            @click="toggleMenu"
            class="flex items-center justify-center p-2 rounded-lg bg-[#ffffff10] border border-zinc-700 text-[#f2e8ad] hover:cursor-pointer hover:bg-[#ffffff20] transition-all"
        >
            <svg v-if="!isOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 translate-y-4"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 translate-y-4"
        >
            <div
                v-if="isOpen"
                class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex flex-col items-center justify-center gap-8 p-10"
            >
                <button @click="closeMenu" class="hover:cursor-pointer absolute top-8 right-8 text-[#f2e8ad]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="flex flex-col items-center gap-6 w-full">
                    <IconButton
                        v-if="isDashboard && isAdmin"
                        text="Documentos"
                        link="templates.index"
                        icon="documents"
                        @click="closeMenu"
                    />
                    <IconButton
                        v-if="isDashboard && isAdmin"
                        text="Usuários"
                        link="users.index"
                        @click="closeMenu"
                    />
                    <IconButton
                        :text="textButton"
                        :link="linkButton"
                        :method="method"
                        :has-parameter="hasParameter"
                        icon="logout"
                        @click="closeMenu"
                    />
                </div>
            </div>
        </Transition>
    </div>
</template>
