<script setup>
import DialogModal from '@/Components/DialogModal.vue'

const props = defineProps({
    show: Boolean,
    user: Object,
    countries: {
        type: Array,
        default: () => []
    },
    roles: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits(['close'])

const closeModal = () => {
    emit('close')
}
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #content v-if="user">
            <div class="space-y-6">
                <!-- Foto y Nombre -->
                <div class="flex items-center gap-4 pb-4 border-b">
                    <img v-if="user.profile_photo_path" :src="'/storage/' + user.profile_photo_path" :alt="user.name"
                        class="w-16 h-16 rounded-full object-cover border-2 border-indigo-500">
                    <div v-else
                        class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center text-2xl font-semibold text-gray-700 border-2 border-indigo-500">
                        {{ user.name.charAt(0) }}
                    </div>
                    <div>
                        <h3 class="modal-title">{{ user.name }} {{ user.lastname }}</h3>
                        <p class="modal-subtitle">{{ user.email }}</p>

                    </div>

                </div>

                <!-- Información Personal -->
                <div>
                    <h4 class="modal-section-title">Información Personal</h4>
                    <div class="modal-grid">
                        <div>
                            <p class="modal-value">Nombre</p>
                            <p class="modal-label">{{ user.name }}</p>
                        </div>
                        <div>
                            <p class="modal-value">Apellido</p>
                            <p class="modal-label">{{ user.lastname }}</p>
                        </div>
                        <div>
                            <p class="modal-value">Correo</p>
                            <p class="modal-label">{{ user.email }}</p>
                        </div>
                        <div>
                            <p class="modal-value">Teléfono</p>
                            <p class="modal-label">{{ user.phone || 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="modal-value">Idioma</p>
                            <p class="modal-label">{{ user.idiomas || 'No especificado' }}</p>
                        </div>
                        <div v-if="user.country">
                            <p class="modal-value">País</p>
                            <p
                                class="modal-label inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-sm font-medium text-blue-700">
                                {{ user.country.ctry_name }}
                            </p>
                        </div>
                        <div class="mt-2">
                            <p class="modal-value">Estado</p>

                            <p v-if="user.usr_active"
                                class="modal-label inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-sm font-medium text-green-700">
                                ✓ Activo
                            </p>

                            <p v-else
                                class="modal-label inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-sm font-medium text-gray-700">
                                ✗ Inactivo
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Roles -->
                <div v-if="user.roles && user.roles.length > 0">
                    <h4 class="modal-section-title">Rol Asignado</h4>
                    <div class="flex flex-wrap gap-2">
                        <span v-for="role in user.roles" :key="role.id"
                            class="inline-flex items-center rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-800">
                            {{ role.name }}
                        </span>
                    </div>
                </div>
            </div>
        </template>
    </DialogModal>
</template>