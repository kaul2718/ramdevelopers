<script setup>
    import { Head, Link, useForm } from '@inertiajs/vue3';
    import AuthenticationCard from '@/Components/AuthenticationCard.vue';
    import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
    import Checkbox from '@/Components/Checkbox.vue';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import TextInput from '@/Components/TextInput.vue';
    import RightLogin from '@/Components/RightLogin.vue';

    const form = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        terms: false,
    });

    const submit = () => {
        form.post(route('register'), {
            onFinish: () => form.reset('password', 'password_confirmation'),
        });
    };
</script>

<template>
    <Head title="Registro" />

    <div class="page--login">
        <div class="left--login">
            <AuthenticationCard>
                <template #logo>
                    <AuthenticationCardLogo />
                </template>

                <div class="mensaje">
                    <h1>Únete a nuestra app</h1>
                    <p>Accede a nuestro catálogo completo de proyectos, e interactúa con nuestros agentes.</p>
                </div>

                <form @submit.prevent="submit" class="contenedor--form">
                    <div class="contenedor--input">
                        <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="campo--input"
                        required
                            autofocus
                            autocomplete="name"
                            />
                        <InputLabel for="name" value="Nombre" />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

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
                        autocomplete="new-password"
                        />
                        <InputLabel for="password" value="Contraseña" />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

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


                    <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="flex items-center justify-between">
                        <label class="contenedor--switch">
                            <Checkbox id="terms" v-model:checked="form.terms" name="terms" required/>
                            <span>
                                Acepto los 
                                <a target="_blank" :href="route('terms.show')" >Términos</a>
                                y
                                <a target="_blank" :href="route('policy.show')">Políticas</a>
                            </span>
                            <InputError class="mt-2" :message="form.errors.terms" />
                        </label>
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">                    
                            <template #texto--boton>Registrar</template>
                            <template #icono--boton>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                            </template>
                        </PrimaryButton>
                    </div>
                </form><br>

                <div class="contenedor--link" style="font-size: 1em;">   
                    <Link class="link" :href="route('login')">
                        Ya tienes cuenta?
                    </Link>
                </div>
            </AuthenticationCard>
        </div>
        <RightLogin></RightLogin>
    </div>
</template>
