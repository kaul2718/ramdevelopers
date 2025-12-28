<script setup>
    import { computed } from 'vue';
    import { Head, Link, useForm } from '@inertiajs/vue3';
    import AuthenticationCard from '@/Components/AuthenticationCard.vue';
    import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';

    const props = defineProps({
        status: String,
    });

    const form = useForm({});

    const submit = () => {
        form.post(route('verification.send'));
    };

    const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <Head title="Verificación de email" />

    <div class="page--login">
        <div class="left--login">
            <AuthenticationCard>
                <template #logo>
                    <AuthenticationCardLogo />
                </template>

                <div class="mensaje">
                    <h1>Antes de continuar</h1>
                    <p>¿Podría verificar su dirección de correo electrónico haciendo clic en el enlace que le acabamos de enviar por correo electrónico? Si no ha recibido el correo electrónico, estaremos encantados de enviarle otro.</p>
                </div>

                <div v-if="verificationLinkSent" class="mb-4 font-medium text-sm text-green-600">
                    Se ha enviado un nuevo enlace de verificación a la dirección de correo electrónico que proporcionaste en la configuración de tu perfil.
                </div>

                <form @submit.prevent="submit" class="contenedor--form">
                    <div class="contenedor--link">                        
                        <Link class="link" :href="route('profile.show')">
                            Editar Perfil
                        </Link>
                        <Link class="link" :href="route('logout')">
                            Cerrar sesión
                        </Link>
                    </div><br>

                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">                  
                            <template #texto--boton>Reenviar correo electrónico de verificación</template>
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
