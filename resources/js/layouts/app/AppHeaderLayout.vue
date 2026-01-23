<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import IconButton from '@/components/IconButton.vue';
import TitleHeader from '@/components/TitleHeader.vue';
import { usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import MobileMenu from './components/MobileMenu.vue';

defineProps<{
    pageTitle: string;
    textButton: string;
    linkButton: string;
    method?: 'get' | 'post';
    hasParameter?: boolean;
    isDashboard?: boolean;
}>();

const page = usePage();
const isAdmin = computed(() => page.props.auth.user?.role === 'admin');
</script>

<template>
    <div class="min-h-screen bg-gradient-to-b from-[#1c1710] to-black pb-24">
        <header class="grid grid-cols-10 items-center border-b border-zinc-700 px-4 py-4 md:px-8 md:py-6">
            <div class="col-span-3 flex items-center justify-start md:justify-center">
                <AppLogoIcon class-name="h-10 w-8 md:h-17 md:w-14" />
            </div>

            <div class="col-span-4 flex items-center justify-center">
                <TitleHeader class-name="font-semibold font-serif text-xs text-center md:text-2xl text-[#f2e8ad]" :page-title="pageTitle" />
            </div>

            <div class="col-span-3 flex items-center justify-end md:justify-center">
                <template v-if="isDashboard">
                    <div class="hidden lg:flex items-center gap-3">
                        <IconButton v-if="isAdmin" text="Documentos" link="templates.index"/>
                        <IconButton v-if="isAdmin" text="Diligências" link="diligence-results.index"/>
                        <IconButton v-if="isAdmin" text="Usuários" link="users.index" />
                        <IconButton :text="textButton" :link="linkButton" :method="method" :has-parameter="hasParameter" icon="logout" />
                    </div>

                    <MobileMenu
                        :is-admin="isAdmin"
                        :is-dashboard="isDashboard"
                        :text-button="textButton"
                        :link-button="linkButton"
                        :method="method"
                        :has-parameter="hasParameter"
                    />
                </template>

                <template v-else>
                    <IconButton :text="textButton" :link="linkButton" :method="method" :has-parameter="hasParameter" icon="logout" />
                </template>
            </div>
        </header>

        <main class="mt-12 md:mt-24">
            <slot />
        </main>
    </div>
</template>
