<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    user: Object,
    countries: Array,
});

const form = useForm({
    _method: 'PUT',
    name: props.user.name,
    lastname: props.user.lastname || '',
    email: props.user.email,
    phone: props.user.phone || '',
    idiomas: props.user.idiomas || '',
    usr_id_ctry: props.user.usr_id_ctry || '',
    photo: null,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
    if (photoInput.value && photoInput.value.files[0]) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput(),
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (! photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>

<template>
    <FormSection @submitted="updateProfileInformation">
        <template #title>
            Información del perfil
        </template>

        <template #description>
            Actualiza la información de perfil y la dirección de correo electrónico de tu cuenta.
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div v-if="$page.props.jetstream.managesProfilePhotos" class="contenedor--input mb-6 pb-6 border-b">
                <!-- Profile Photo File Input -->
                <input
                    id="photo"
                    ref="photoInput"
                    type="file"
                    class="hidden"
                    @change="updatePhotoPreview"
                >

                <InputLabel for="photo" value="Foto de perfil" />

                <!-- Current Profile Photo -->
                <div v-show="! photoPreview" class="mt-2">
                    <img :src="user.profile_photo_url" :alt="user.name" class="rounded-full size-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div v-show="photoPreview" class="mt-2">
                    <span
                        class="block rounded-full size-20 bg-cover bg-no-repeat bg-center"
                        :style="'background-image: url(\'' + photoPreview + '\');'"
                    />
                </div>

                <div class="mt-2 space-x-2">
                    <SecondaryButton type="button" @click.prevent="selectNewPhoto">
                        Seleccionar una nueva foto
                    </SecondaryButton>

                    <SecondaryButton
                        v-if="user.profile_photo_path"
                        type="button"
                        @click.prevent="deletePhoto"
                    >
                        Eliminar foto
                    </SecondaryButton>
                </div>

                <InputError :message="form.errors.photo" class="mt-2" />
            </div>

            <!-- Name -->
            <div class="contenedor--input">
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autocomplete="name"
                />
                <InputLabel for="name" value="Nombre" />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <!-- Last Name -->
            <div class="contenedor--input">
                <TextInput
                    id="lastname"
                    v-model="form.lastname"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autocomplete="family-name"
                />
                <InputLabel for="lastname" value="Apellido" />
                <InputError :message="form.errors.lastname" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="contenedor--input">
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autocomplete="username"
                />
                <InputLabel for="email" value="Email" />
                <InputError :message="form.errors.email" class="mt-2" />

                <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
                    <p class="text-sm mt-2">
                        Tu dirección de correo electrónico no está verificada.

                        <Link
                            :href="route('verification.send')"
                            method="post"
                            as="button"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            @click.prevent="sendEmailVerification"
                        >
                            Haga clic aquí para volver a enviar el correo electrónico de verificación.
                        </Link>
                    </p>

                    <div v-show="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600">
                        Se ha enviado un nuevo enlace de verificación a su dirección de correo electrónico.
                    </div>
                </div>
            </div>

            <!-- Phone -->
            <div class="contenedor--input">
                <TextInput
                    id="phone"
                    v-model="form.phone"
                    type="tel"
                    class="mt-1 block w-full"
                    autocomplete="tel"
                />
                <InputLabel for="phone" value="Teléfono" />
                <InputError :message="form.errors.phone" class="mt-2" />
            </div>

            <!-- Languages -->
            <div class="contenedor--input">
                <TextInput
                    id="idiomas"
                    v-model="form.idiomas"
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Ej: Español, Inglés, Portugués"
                />
                <InputLabel for="idiomas" value="Idiomas" />
                <InputError :message="form.errors.idiomas" class="mt-2" />
            </div>

            <!-- Country -->
            <div class="contenedor--input">
                <select
                    id="usr_id_ctry"
                    v-model.number="form.usr_id_ctry"
                    class="campo--input"
                >
                    <option value="">Selecciona un país</option>
                    <option v-for="country in countries" :key="country.ctry_id" :value="country.ctry_id">
                        {{ country.ctry_name }}
                    </option>
                </select>
                <InputLabel for="usr_id_ctry" value="País" />
                <InputError :message="form.errors.usr_id_ctry" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="me-3">
                Guardado.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                <template #texto--boton>Guardar</template>
                <template #icono--boton>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </template>
            </PrimaryButton>
        </template>
    </FormSection>
</template>
