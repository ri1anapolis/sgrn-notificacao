<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import SearchCard from '@/components/SearchCard.vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const userRole = computed(() => page.props.auth.user?.role);

</script>

<template>
    <Head title="Dashboard" />


    <AppLayout link-button="logout" text-button="Sair" page-title="Notificação Certa - Sistema Extrajudicial" method="post">
        <div class="max-w-7xl m-auto flex flex-wrap justify-center gap-12 mb-5">
            <SearchCard v-if="userRole == 'admin'" 
            image-url="/images/Card-Tratamento-de-Dados.jpeg" 
            alt-name="Ilustração de um funcionário em uma mesa, digitando em um notebook para o tratamento de dados." 
            route-name="notification.stage.index" />

            <SearchCard 
            image-url="/images/Card-Fase-de-Notificação.png" 
            alt-name="Ilustração de um notificador ao lado de sua motocicleta, segurando uma prancheta para a fase de notificação." 
            route-name="notifications.index" />

            <!--<SearchCard v-if="userRole == 'admin'"
            image-url="/images/Card-Cartorio-Consulta.png" 
            alt-name="Ilustração de uma pessoa em um computador, consultando uma lista de perfis de notificação." 
            route-name="notification.stage.index" />-->

        </div>
    </AppLayout>
</template>
