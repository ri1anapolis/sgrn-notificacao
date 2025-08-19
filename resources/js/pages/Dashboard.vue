<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import CardSection from '@/components/CardSection.vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const userRole = computed(() => page.props.auth.user?.role);

</script>

<template>
    <Head title="Dashboard" />


    <AppLayout link-button="logout" text-button="Sair" page-title="Notificação Certa - Sistema Extrajudicial">
        <div class="max-w-7xl m-auto flex flex-wrap justify-center gap-12 mb-5">
            <CardSection v-if="userRole == 'admin'" image-url="/images/Card-Tratamento-de-Dados.jpeg" page-url="tratamento-dados.index" />
            <CardSection image-url="/images/Card-Fase-de-Notificação.png" page-url="fase-notificacao.index" />
            <CardSection v-if="userRole == 'admin'" image-url="/images/Card-Cartorio-Consulta.png" page-url="consulta-notificacao.index" />            
        </div>
    </AppLayout>
</template>
