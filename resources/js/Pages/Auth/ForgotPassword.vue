<script setup>
    import { Head, Link, useForm } from '@inertiajs/vue3';
    import AuthenticationCard from '@/Components/AuthenticationCard.vue';
    import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import TextInput from '@/Components/TextInput.vue';

    defineProps({
        status: String,
    });

    const form = useForm({
        email: '',
    });

    const submit = () => {
        form.post(route('password.email'));
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
                    <h1>Olvidaste tu contraseña?</h1>
                    <p>Solo tienes que facilitarnos tu dirección de correo electrónico y te enviaremos un enlace que te permitirá elegir una nueva.</p>
                </div>

                <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                    {{ status }}
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
                    </div>

                    <div class="contenedor--link">
                        <div></div>
                        <Link class="link" :href="route('login')">
                            Volver al Log-in
                        </Link>
                    </div><br>

                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">                    
                            <template #texto--boton>Enviar email</template>
                            <template #icono--boton>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
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
