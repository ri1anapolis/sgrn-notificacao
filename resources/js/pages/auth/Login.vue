<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowBigUp, LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const isCapsLockOn = ref(false);

const checkCapsLock = (event: KeyboardEvent) => {
    isCapsLockOn.value = event.getModifierState('CapsLock');
};

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthBase title="Notificação Certa - Sistema Extrajudicial">
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" novalidate class="mx-7 flex flex-col gap-6 rounded-lg border border-[#b3925c] bg-[#181410] p-5 pb-8">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email">Email</Label>
                    <Input id="email" type="email" required autofocus autocomplete="email" v-model="form.email" placeholder="seu@email.com" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password">Senha</Label>
                    </div>
                    <div class="relative">
                        <Input
                            id="password"
                            type="password"
                            required
                            autocomplete="current-password"
                            v-model="form.password"
                            placeholder="Digite sua senha..."
                            @keyup="checkCapsLock"
                            @keydown="checkCapsLock"
                        />
                        <div
                            v-if="isCapsLockOn"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-amber-500"
                            title="Caps Lock ativado"
                        >
                            <ArrowBigUp class="h-4 w-4 fill-amber-500" />
                        </div>
                    </div>
                    <InputError :message="form.errors.password" />
                </div>

                <div class="flex items-center justify-between">
                    <Label for="remember" class="flex items-center space-x-3">
                        <Checkbox id="remember" v-model="form.remember" />
                        <span>Lembrar</span>
                    </Label>
                </div>

                <Button type="submit" class="mt-4 w-full" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Entrar
                </Button>
            </div>
        </form>
    </AuthBase>
</template>
