<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Recuperar contraseña" />

    <div class="page--login">
        <div class="left--login">
            <AuthenticationCard>
                <template #logo>
                    <AuthenticationCardLogo />
                </template>

                <div class="mensaje">
                    <h1>Renovar contraseña</h1>
                    <p>Crea una nueva calve de acceso para que puedas recuperar tu cuenta.</p>
                </div>

                <form @submit.prevent="submit" class="contenedor--form">
                    <div class="contenedor--input">
                        <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="campo--input"
                        required
                        autofocus
                        autocomplete="username"
                        />
                        <InputLabel for="email" value="Email" />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div><br>

                    <div class="contenedor--input">
                        <TextInput
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="campo--input"
                        required
                        autocomplete="new-password"
                        />
                        <InputLabel for="password" value="Contraseña" />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div><br>

                    <div class="contenedor--input">
                        <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="campo--input"
                        required
                        autocomplete="new-password"
                        />
                        <InputLabel for="password_confirmation" value="Confirmar contraseña" />
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">                   
                            <template #texto--boton>Guardar contraseña</template>
                            <template #icono--boton>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </template> 
                        </PrimaryButton>
                    </div>
                </form>
            </AuthenticationCard>
        </div>
        <div class="right--login">
            <div class="contenedor--texto">
                <h2>Construyendo el futuro, <span>proyecto a</span> proyecto.</h2>
                <p>Accede a las herramientas más avanzadas de gestión inmobiliaria y visualización de catálogos en tiempo real.</p>
                <div class="rejilla--inferior">
                    <div>
                        <h4>25+</h4>
                        <span>Proyectos Activos</span>
                    </div>
                    <div>
                        <h4>100%</h4>
                        <span>Gestión Digital</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
