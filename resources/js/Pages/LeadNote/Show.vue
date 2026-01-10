<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { formatDate } from '@/Helpers/dateHelper'
import CreateModal from '@/Components/LeadNote/CreateModal.vue'
import EditModal from '@/Components/LeadNote/EditModal.vue'
import ConfirmModal from '@/Components/ConfirmModal.vue'
import { useNotificationStore } from '@/stores/notificationStore'

const props = defineProps({
    leadNote: Object,
})

const notificationStore = useNotificationStore()

const showCreateModal = ref(false)
const showEditModal = ref(false)
const showConfirmModal = ref(false)

const openCreateModal = () => {
    showCreateModal.value = true
}

const openEditModal = () => {
    showEditModal.value = true
}

const closeCreateModal = () => {
    showCreateModal.value = false
}

const closeEditModal = () => {
    showEditModal.value = false
}

const openDeleteModal = () => {
    showConfirmModal.value = true
}

const closeDeleteModal = () => {
    showConfirmModal.value = false
}

const confirmDelete = () => {
    router.delete(route('leadnote.destroy', props.leadNote.leadNot_id), {
        onSuccess: () => {
            closeDeleteModal()
            notificationStore.success('Nota eliminada exitosamente')
            
            // Remover la notificación después de 3 segundos
            setTimeout(() => {
                const notyfToasts = document.querySelectorAll('.notyf__toast')
                notyfToasts.forEach(toast => {
                    toast.style.opacity = '0'
                    toast.style.transition = 'opacity 0.3s ease-out'
                    setTimeout(() => {
                        toast.remove()
                    }, 300)
                })
            }, 3000)
        }
    })
}

const handleSaved = () => {
    window.location.href = route('leadnote.show', props.leadNote.lead_id)
}
</script>

<template>
    <AppLayout title="Detalle de Nota de Lead">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nota de Lead #{{ leadNote.leadNot_id }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header Actions -->
                <div class="mb-6 flex gap-3">
                    <Link :href="route('leadnote.index')" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                        Volver
                    </Link>
                    <button 
                        @click="openCreateModal"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">
                        Nueva Nota
                    </button>
                    <button 
                        @click="openEditModal"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                        Editar
                    </button>
                    <button 
                        @click="openDeleteModal"
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">
                        Eliminar
                    </button>
                </div>

                <!-- Note Details -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="grid grid-cols-6 gap-6">
                            <!-- Título -->
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Título</label>
                                <p class="mt-2 text-sm text-gray-900">{{ leadNote.leadNot_title || '-' }}</p>
                            </div>

                            <!-- Estado -->
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Estado</label>
                                <span v-if="leadNote.leadNot_active" class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    Activo
                                </span>
                                <span v-else class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    Inactivo
                                </span>
                            </div>

                            <!-- Lead -->
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Lead</label>
                                <Link :href="route('lead.show', leadNote.lead?.lead_id)" class="mt-2 text-sm text-blue-600 hover:text-blue-900">
                                    {{ leadNote.lead?.lead_client_name }}
                                </Link>
                            </div>

                            <!-- Usuario -->
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Usuario</label>
                                <p class="mt-2 text-sm text-gray-900">{{ leadNote.user?.name }} {{ leadNote.user?.lastname }}</p>
                            </div>

                            <!-- Creado -->
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Creado</label>
                                <p class="mt-2 text-sm text-gray-900">{{ formatDate(leadNote.created_at) }}</p>
                            </div>

                            <!-- Descripción -->
                            <div class="col-span-6">
                                <label class="block text-sm font-medium text-gray-700">Descripción</label>
                                <p class="mt-2 text-sm text-gray-900 whitespace-pre-wrap">{{ leadNote.leadNot_description || '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modales -->
        <CreateModal
            :show="showCreateModal"
            :lead="{ lead_id: leadNote.lead_id }"
            @close="closeCreateModal"
            @saved="handleSaved"
        />

        <EditModal
            :show="showEditModal"
            :note="leadNote"
            :lead="{ lead_id: leadNote.lead_id }"
            @close="closeEditModal"
            @saved="handleSaved"
        />

        <ConfirmModal
            :show="showConfirmModal"
            title="Eliminar Nota"
            message="¿Está seguro de que desea eliminar esta nota? Esta acción no se puede deshacer."
            confirm-text="Eliminar"
            cancel-text="Cancelar"
            :isDangerous="true"
            @confirm="confirmDelete"
            @close="closeDeleteModal"
        />
    </AppLayout>
</template>
