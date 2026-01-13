<script setup>
    import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
    import { computed } from 'vue';
    import AuthenticationCard from '@/Components/AuthenticationCard.vue';
    import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
    import Checkbox from '@/Components/Checkbox.vue';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import TextInput from '@/Components/TextInput.vue';
    import RightLogin from '@/Components/RightLogin.vue';
    import { router } from '@inertiajs/vue3';

    const page = usePage();

    defineProps({
        canResetPassword: Boolean,
        status: String,
        error: [String, Object],
    });

    const flashError = computed(() => page.props.flash?.error || null);

    const form = useForm({
        email: '',
        password: '',
        remember: false,
    });
    
    //RUTA REGISTRO
    const goToRegister = () => {
        router.visit('/register');
    };

    const submit = () => {
        form.transform(data => ({
            ...data,
            remember: form.remember ? 'on' : '',
        })).post(route('login'), {
            onFinish: () => form.reset('password'),
        });
    };
</script>

<template>
    <Head title="Log in" />

    <div class="page--login">
        <div class="left--login">
            <AuthenticationCard>
                <template #logo>
                    <AuthenticationCardLogo />
                </template>

                <div class="mensaje">
                    <h1>Bienvenido de nuevo</h1>
                    <p>Gestiona tus proyectos inmobiliarios con eficiencia.</p>
                </div>

                <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                    {{ status }}
                </div>

                <div v-if="flashError" class="mb-4 font-medium text-sm text-red-600 bg-red-50 p-3 rounded border border-red-200">
                    {{ flashError }}
                </div>

                <div v-if="error" class="mb-4 font-medium text-sm text-red-600 bg-red-50 p-3 rounded border border-red-200">
                    {{ error }}
                </div>

                <form @submit.prevent="submit" class="contenedor--form">
                    <div class="contenedor--input">
                        <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="campo--input"
                        required
                        autocomplete="username"
                        />
                        <InputLabel for="email" value="Email" />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="contenedor--input">
                        <TextInput
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="campo--input"
                        required
                        autocomplete="current-password"
                        />
                        <InputLabel for="password" value="Contraseña" />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="contenedor--switch">
                            <Checkbox v-model:checked="form.remember" name="remember" />
                            <span>Recordar</span>
                        </label>
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">                    
                            <template #texto--boton>Ingresar</template>
                            <template #icono--boton>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                            </template> 
                        </PrimaryButton>
                    </div>
                </form><br>

                <div class="contenedor--link">                    
                    <Link class="link" v-if="canResetPassword" :href="route('password.request')">
                        Olvidaste tu contraseña?
                    </Link>
                    <Link class="link" :href="route('register')">
                        Regístrate ahora, es gratis!
                    </Link>
                </div>
            </AuthenticationCard>
        </div>
        <RightLogin></RightLogin>
    </div>
</template>
