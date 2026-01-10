<script setup>
import { ref, watch, computed, onMounted } from 'vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { useNotificationStore } from '@/stores/notificationStore';

const page = usePage();
const props = defineProps({
    note: {
        type: Object,
        default: null
    },
    lead: {
        type: Object,
        default: null
    },
    updating: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['success', 'cancel']);

const notificationStore = useNotificationStore();

const isSubmitting = ref(false);
const errors = ref({});

const form = ref({
    leadNot_title: '',
    leadNot_description: '',
    leadNot_active: 1
});

const originalForm = ref({
    leadNot_title: '',
    leadNot_description: '',
    leadNot_active: 1
});

const hasChanges = computed(() => {
    return form.value.leadNot_title !== originalForm.value.leadNot_title ||
           form.value.leadNot_description !== originalForm.value.leadNot_description ||
           form.value.leadNot_active !== originalForm.value.leadNot_active;
});

const validateForm = () => {
    errors.value = {};
    
    if (!form.value.leadNot_title?.trim()) {
        errors.value.leadNot_title = 'El título es requerido';
    }
    if (!form.value.leadNot_description?.trim()) {
        errors.value.leadNot_description = 'La descripción es requerida';
    }
    
    return Object.keys(errors.value).length === 0;
};

const handleSubmit = async () => {
    if (!validateForm()) {
        return;
    }

    if (props.updating && !hasChanges.value) {
        notificationStore.error('No has realizado cambios');
        return;
    }
    
    if (!props.lead?.lead_id) {
        notificationStore.error('Error: Lead no identificado');
        return;
    }
    
    isSubmitting.value = true;
    const formData = new FormData();
    
    formData.append('leadNot_title', form.value.leadNot_title);
    formData.append('leadNot_description', form.value.leadNot_description);
    formData.append('leadNot_active', form.value.leadNot_active ? 1 : 0);
    formData.append('lead_id', props.lead.lead_id);
    formData.append('user_id', page.props.auth.user.id);
    
    if (props.updating) {
        formData.append('_method', 'PUT');
    }
    
    try {
        const route_name = props.updating ? 'leadnote.update' : 'leadnote.store';
        const route_param = props.updating ? [props.note.leadNot_id] : [];
        
        await axios.post(route(route_name, route_param), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        });
        
        emit('success');
    } catch (error) {
               
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
            const errorMessages = Object.values(errors.value).flat().join(', ');
            notificationStore.error(errorMessages);
        } else {
            notificationStore.error(
                props.updating 
                    ? 'Error al actualizar la nota' 
                    : 'Error al crear la nota'
            );
        }
    } finally {
        isSubmitting.value = false;
    }
};

const getError = (field) => {
    const error = errors.value[field];
    if (!error) return null;
    return Array.isArray(error) ? error[0] : error;
};

const initializeForm = () => {
    if (props.updating && props.note) {
        const formData = {
            leadNot_title: props.note.leadNot_title || '',
            leadNot_description: props.note.leadNot_description || '',
            leadNot_active: Number(props.note.leadNot_active ?? 1)
        };
        form.value = formData;
        originalForm.value = JSON.parse(JSON.stringify(formData));
    } else {
        form.value = {
            leadNot_title: '',
            leadNot_description: '',
            leadNot_active: 1
        };
        originalForm.value = {
            leadNot_title: '',
            leadNot_description: '',
            leadNot_active: 1
        };
    }
    errors.value = {};
};

watch(() => props.note, () => {
    initializeForm();
}, { deep: true });

watch(() => props.updating, () => {
    if (props.updating) {
        initializeForm();
    }
});

onMounted(() => {
    initializeForm();
});
</script>

<template>
    <FormSection @submitted="handleSubmit">
        <template #title>
            {{ updating ? 'Actualizar Nota' : 'Crear Nota' }}
        </template>

        <template #description>
            {{ updating ? 'Actualizar la información de la nota del lead' : 'Crear una nueva nota para el lead' }}
        </template>

        <template #form>
            <div class="contenedor--input">
                <TextInput
                    id="leadNot_title"
                    v-model="form.leadNot_title"
                    type="text"
                    autocomplete="off"
                    class="campo--input"
                    placeholder="Título de la nota">
                </TextInput>
                <InputLabel for="leadNot_title" value="Título"></InputLabel>
                <InputError :message="getError('leadNot_title')" class="mt-2" />
            </div>

            <div class="contenedor--input">
                <TextInput
                    id="leadNot_description"
                    v-model="form.leadNot_description"
                    rows="5"
                    autocomplete="off"
                    class="campo--input"
                    placeholder="Descripción de la nota"
                ></TextInput>
                <InputLabel for="leadNot_description" value="Descripción"></InputLabel>
                <InputError :message="getError('leadNot_description')" class="mt-2" />
            </div>

            <div v-if="updating" class="contenedor--input">
                <select id="leadNot_active" v-model.number="form.leadNot_active" class="campo--input">
                    <option :value="1">Activa</option>
                    <option :value="0">Inactiva</option>
                </select>
                <InputLabel for="leadNot_active" value="Estado"></InputLabel>
                <InputError :message="getError('leadNot_active')" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton :disabled="isSubmitting || (updating && !hasChanges)">
                <template #texto--boton>{{ isSubmitting ? 'Guardando...' : (updating ? 'Actualizar' : 'Crear') }}</template>
                <template #icono--boton>
                    <path v-if="!updating" stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    <path v-if="updating" stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </template>
            </PrimaryButton>
        </template>
    </FormSection>
</template>
