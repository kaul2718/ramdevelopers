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
    import { watch } from 'vue';

    defineProps({
        countries: {
            type: Array,
            default: () => []
        }
    });

    const form = useForm({
        name: '',
        lastname: '',
        email: '',
        phone: '',
        usr_id_ctry: '',
        password: '',
        password_confirmation: '',
        terms: false,
    });

    // Watcher para convertir email a minúsculas
    watch(() => form.email, (newEmail) => {
        if (newEmail) {
            form.email = newEmail.toLowerCase();
        }
    });

    const submit = () => {
        // Convertir email a minúsculas antes de enviar
        form.email = form.email.toLowerCase();
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
                        id="lastname"
                        v-model="form.lastname"
                        type="text"
                        class="campo--input"
                        required
                        autocomplete="family-name"
                        />
                        <InputLabel for="lastname" value="Apellido" />
                        <InputError class="mt-2" :message="form.errors.lastname" />
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
                        id="phone"
                        v-model="form.phone"
                        type="text"
                        class="campo--input"
                        autocomplete="tel"
                        />
                        <InputLabel for="phone" value="Teléfono" />
                        <InputError class="mt-2" :message="form.errors.phone" />
                    </div>

                    <div class="contenedor--input">
                        <select
                        id="usr_id_ctry"
                        v-model="form.usr_id_ctry"
                        class="campo--input"
                        >
                            <option value="">Seleccionar país...</option>
                            <option v-for="country in countries" :key="country.ctry_id" :value="country.ctry_id">
                                {{ country.ctry_name }}
                            </option>
                        </select>
                        <InputLabel for="usr_id_ctry" value="País" />
                        <InputError class="mt-2" :message="form.errors.usr_id_ctry" />
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
