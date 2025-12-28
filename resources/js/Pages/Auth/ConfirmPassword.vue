<script setup>
    import { ref } from 'vue';
    import { Head, useForm } from '@inertiajs/vue3';
    import AuthenticationCard from '@/Components/AuthenticationCard.vue';
    import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import TextInput from '@/Components/TextInput.vue';

    const form = useForm({
        password: '',
    });

    const passwordInput = ref(null);

    const submit = () => {
        form.post(route('password.confirm'), {
            onFinish: () => {
                form.reset();

                passwordInput.value.focus();
            },
        });
    };
</script>

<template>
    <Head title="Area segura" />

    <div class="page--login">
        <div class="left--login">
            <AuthenticationCard>
                <template #logo>
                    <AuthenticationCardLogo />
                </template>

                <div class="mensaje">
                    <h1>Bienvenido de nuevo</h1>
                    <p>Esta es un área segura de la aplicación. Por favor confirma tu contraseña para continuar.</p>
                </div>

                <form @submit.prevent="submit" class="contenedor--form">
                    <div class="contenedor--input">
                        <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                            type="password"
                            class="campo--input"
                            required
                            autocomplete="current-password"
                            autofocus
                            />
                            <InputLabel for="password" value="Contraseña" />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="flex justify-end mt-4">
                        <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">                    
                            <template #texto--boton>Confirmar</template>
                            <template #icono--boton>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
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
