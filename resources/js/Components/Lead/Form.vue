<script setup>
    import FormSection from '@/Components/FormSection.vue'
    import InputError from '@/Components/InputError.vue'
    import InputLabel from '@/Components/InputLabel.vue'
    import PrimaryButton from '@/Components/PrimaryButton.vue'
    import TextInput from '@/Components/TextInput.vue'

    const props = defineProps({
        form: {
            type: Object,
            required: true
        },
        developments: Array,
        countries: Array,
        sources: Array,
        statuses: Array,
        users: Array,
        updating: {
            type: Boolean,
            required: false,
            default: false
        }
    })

    defineEmits(['submit'])
</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{ updating ? 'Actualizar Lead' : 'Crear Nuevo Lead' }}
        </template>
        <template #description>
            {{ updating ? 'Actualizar la información del lead seleccionado' : 'Crear un nuevo lead en el sistema' }}
        </template>

        <template #form>
            <!-- Nombre del Cliente -->
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="lead_client_name" value="Nombre del Cliente *"></InputLabel>
                <TextInput 
                    id="lead_client_name" 
                    v-model="form.lead_client_name" 
                    type="text" 
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="$page.props.errors.lead_client_name" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="lead_client_email" value="Email"></InputLabel>
                <TextInput 
                    id="lead_client_email" 
                    v-model="form.lead_client_email" 
                    type="email" 
                    class="mt-1 block w-full"
                />
                <InputError :message="$page.props.errors.lead_client_email" class="mt-2" />
            </div>

            <!-- Teléfono -->
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="lead_client_phone" value="Teléfono"></InputLabel>
                <TextInput 
                    id="lead_client_phone" 
                    v-model="form.lead_client_phone" 
                    type="tel" 
                    class="mt-1 block w-full"
                />
                <InputError :message="$page.props.errors.lead_client_phone" class="mt-2" />
            </div>

            <!-- Idioma -->
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="lead_language" value="Idiomas (Opcional)"></InputLabel>
                <select id="lead_language" v-model="form.lead_language"
                    class="mt-1 block w-full border-gray-300 rounded-md">
                    <option value="">Selecciona un idioma</option>
                    <option value="Español">Español</option>
                    <option value="Inglés">Inglés</option>
                    <option value="Francés">Francés</option>
                </select>
                <InputError :message="$page.props.errors.lead_language" class="mt-2" />
            </div>

            <!-- País -->
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="ctry_id" value="País"></InputLabel>
                <select
                    id="ctry_id"
                    v-model.number="form.ctry_id"
                    class="mt-1 block w-full border-gray-300 rounded-md"
                >
                    <option value="">-- Seleccionar --</option>
                    <option v-for="country in countries" :key="country.ctry_id" :value="country.ctry_id">
                        {{ country.ctry_name }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.ctry_id" class="mt-2" />
            </div>

            <!-- Desarrollo -->
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="devt_id" value="Desarrollo"></InputLabel>
                <select
                    id="devt_id"
                    v-model.number="form.devt_id"
                    class="mt-1 block w-full border-gray-300 rounded-md"
                >
                    <option value="">-- Seleccionar --</option>
                    <option v-for="dev in developments" :key="dev.devt_id" :value="dev.devt_id">
                        {{ dev.devt_title }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.devt_id" class="mt-2" />
            </div>

            <!-- Fuente del Lead -->
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="leadSou_id" value="Fuente del Lead"></InputLabel>
                <select
                    id="leadSou_id"
                    v-model.number="form.leadSou_id"
                    class="mt-1 block w-full border-gray-300 rounded-md"
                >
                    <option value="">-- Seleccionar --</option>
                    <option v-for="source in sources" :key="source.leadSou_id" :value="source.leadSou_id">
                        {{ source.leadSou_name }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.leadSou_id" class="mt-2" />
            </div>

            <!-- Estado del Lead -->
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="leadSta_id" value="Estado del Lead"></InputLabel>
                <select
                    id="leadSta_id"
                    v-model.number="form.leadSta_id"
                    class="mt-1 block w-full border-gray-300 rounded-md"
                >
                    <option value="">-- Seleccionar --</option>
                    <option v-for="status in statuses" :key="status.leadSta_id" :value="status.leadSta_id">
                        {{ status.leadSta_name }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.leadSta_id" class="mt-2" />
            </div>

            <!-- Usuario Responsable -->
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="user_id" value="Usuario Responsable"></InputLabel>
                <select
                    id="user_id"
                    v-model.number="form.user_id"
                    class="mt-1 block w-full border-gray-300 rounded-md"
                >
                    <option value="">-- Seleccionar --</option>
                    <option v-for="user in users" :key="user.id" :value="user.id">
                        {{ user.name }} 
                    </option>
                </select>
                <InputError :message="$page.props.errors.user_id" class="mt-2" />
            </div>

            <!-- Mensaje -->
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="lead_message" value="Mensaje"></InputLabel>
                <textarea
                    id="lead_message"
                    v-model="form.lead_message"
                    rows="5"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                ></textarea>
                <InputError :message="$page.props.errors.lead_message" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                {{ updating ? 'Actualizar' : 'Crear' }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
