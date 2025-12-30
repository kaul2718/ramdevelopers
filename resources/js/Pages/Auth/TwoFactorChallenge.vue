<script setup>
    import { nextTick, ref } from 'vue';
    import { Head, useForm } from '@inertiajs/vue3';
    import AuthenticationCard from '@/Components/AuthenticationCard.vue';
    import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import TextInput from '@/Components/TextInput.vue';
    import RightLogin from '@/Components/RightLogin.vue';

    const recovery = ref(false);

    const form = useForm({
        code: '',
        recovery_code: '',
    });

    const recoveryCodeInput = ref(null);
    const codeInput = ref(null);

    const toggleRecovery = async () => {
        recovery.value ^= true;

        await nextTick();

        if (recovery.value) {
            recoveryCodeInput.value.focus();
            form.code = '';
        } else {
            codeInput.value.focus();
            form.recovery_code = '';
        }
    };

    const submit = () => {
        form.post(route('two-factor.login'));
    };
</script>

<template>
    <Head title="Two-factor Confirmation" />

    <div class="page--login">
        <div class="left--login">
            <AuthenticationCard>
                <template #logo>
                    <AuthenticationCardLogo />
                </template>

                <div class="mensaje">
                    <h1>Atención!</h1>
                    <p v-if="! recovery">Confirma el acceso a tu cuenta introduciendo el código de autenticación proporcionado por tu aplicación de preferencia.</p>
                    <p v-else>Confirma el acceso a tu cuenta introduciendo uno de tus códigos de recuperación de emergencia.</p>
                </div>

                <form @submit.prevent="submit" class="contenedor--form">
                    <div v-if="! recovery" class="contenedor--input">
                        <TextInput
                        id="code"
                        ref="codeInput"
                        v-model="form.code"
                        type="text"
                        inputmode="numeric"
                        class="campo--input"
                        autofocus
                        autocomplete="one-time-code"
                        />
                        <InputLabel for="code" value="Código" />
                        <InputError class="mt-2" :message="form.errors.code" />
                    </div>

                    <div v-else class="contenedor--input">
                        <TextInput
                        id="recovery_code"
                        ref="recoveryCodeInput"
                        v-model="form.recovery_code"
                        type="text"
                        class="campo--input"
                        autocomplete="one-time-code"
                        />
                        <InputLabel for="recovery_code" value="Código de recuperación" />
                        <InputError class="mt-2" :message="form.errors.recovery_code" />
                    </div>

                    <div class="contenedor--link">
                        <div></div>
                        <button type="button" class="link" @click.prevent="toggleRecovery">
                            <template v-if="! recovery">
                                Usar un código de verificación
                            </template>
                            
                            <template v-else>
                                Usar un código de autenticación
                            </template>
                        </button>
                    </div>
                    
                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">                                                
                            <template #texto--boton>Ingresar</template>
                            <template #icono--boton>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                            </template> 
                        </PrimaryButton>
                    </div>
                </form>
            </AuthenticationCard>
        </div>
        <RightLogin></RightLogin>
    </div>
</template>
