<script setup>
    import CreateModal from '@/Components/LeadNote/CreateModal.vue';
    import EditModal from '@/Components/LeadNote/EditModal.vue';
    import ConfirmModal from '@/Components/ConfirmModal.vue';
    import { router } from '@inertiajs/vue3';
    import { ref, onMounted, onUnmounted } from 'vue';
    import { useNotificationStore } from '@/stores/notificationStore';
    import axios from 'axios';

    const props = defineProps({
        show: {
            type: Boolean,
            required: true
        },
        lead: {
            type: Object,
            default: null
        }
    });

    const emit = defineEmits(['close', 'saved', 'updated']);

    const notificationStore = useNotificationStore();

    const showNoteCreateModal = ref(false);
    const showNoteEditModal = ref(false);
    const showNoteDeleteModal = ref(false);
    const selectedNote = ref(null);
    const noteToDelete = ref(null);

    const closeModal = () => {
        emit('close');
    };

    const handleKeyDown = (event) => {
        if (event.key === 'Escape' && props.show) {
            closeModal();
        }
    };

    onMounted(() => {
        document.addEventListener('keydown', handleKeyDown);
    });

    onUnmounted(() => {
        document.removeEventListener('keydown', handleKeyDown);
    });

    const openCreateNoteModal = () => {
        selectedNote.value = null;
        showNoteCreateModal.value = true;
    };

    const closeCreateNoteModal = () => {
        showNoteCreateModal.value = false;
        selectedNote.value = null;
    };

    const openEditNoteModal = (note) => {
        selectedNote.value = note;
        showNoteEditModal.value = true;
    };

    const closeEditNoteModal = () => {
        showNoteEditModal.value = false;
        selectedNote.value = null;
    };

    const openDeleteNoteModal = (note) => {
        noteToDelete.value = note;
        showNoteDeleteModal.value = true;
    };

    const closeNoteDeleteModal = () => {
        showNoteDeleteModal.value = false;
        noteToDelete.value = null;
    };

    const confirmDeleteNote = async () => {
        if (noteToDelete.value && props.lead) {
            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const noteId = noteToDelete.value.leadNot_id;
                
                await axios.delete(`/leadnote/${noteId}`, {
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                });

                closeNoteDeleteModal();
                notificationStore.success('Nota eliminada exitosamente');

                // Recargar las notas del lead con actualización automática en el modal
                router.get(route('lead.index'), {}, {
                    preserveState: true,
                    onSuccess: (page) => {
                        // Actualizar el lead seleccionado con las notas actualizadas
                        const updatedLead = page.props.leads.data.find(l => l.lead_id === props.lead.lead_id);
                        if (updatedLead) {
                            emit('updated', updatedLead);
                        }
                    }
                });

                setTimeout(() => {
                    const notyfToasts = document.querySelectorAll('.notyf__toast');
                    notyfToasts.forEach(toast => {
                        toast.style.opacity = '0';
                        toast.style.transition = 'opacity 0.3s ease-out';
                        setTimeout(() => {
                            toast.remove();
                        }, 300);
                    });
                }, 3000);
            } catch (error) {
                notificationStore.error('Error al eliminar la nota');
            }
        }
    };

    const handleNoteSaved = (mode) => {
        if (mode === 'create') {
            closeCreateNoteModal();
        } else {
            closeEditNoteModal();
        }
        notificationStore.success(mode === 'create' ? 'Nota creada exitosamente' : 'Nota actualizada exitosamente');

        // Recargar las notas del lead
        if (props.lead?.lead_id) {
            router.get(route('lead.index'), {}, {
                preserveState: true,
                onSuccess: (page) => {
                    // Actualizar el lead seleccionado con las nuevas notas
                    const updatedLead = page.props.leads.data.find(l => l.lead_id === props.lead.lead_id);
                    if (updatedLead) {
                        // Emitir event para actualizar el lead en el padre
                        emit('updated', updatedLead);
                    }
                }
            });
        }

        setTimeout(() => {
            const notyfToasts = document.querySelectorAll('.notyf__toast');
            notyfToasts.forEach(toast => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.3s ease-out';
                setTimeout(() => {
                    toast.remove();
                }, 300);
            });
        }, 3000);
    };

    const formatDate = (date) => {
        if (!date) return '';
        return new Date(date).toLocaleDateString('es-ES', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
    };
</script>

<template>
    <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-96 flex flex-col">
            <!-- Header del Modal -->
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    Notas de {{ lead?.lead_client_name }}
                </h3>
                <button @click="closeModal" class="text-gray-400 hover:text-gray-600 transition">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>

            <!-- Acciones del Modal -->
            <div class="flex gap-2 p-6 border-b border-gray-200">
                <button @click="openCreateNoteModal"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                    Agregar Nota
                </button>
            </div>

            <!-- Contenido del Modal -->
            <div class="overflow-y-auto flex-1 p-6">
                <div v-if="lead?.notes && lead.notes.length > 0" class="space-y-4">
                    <div v-for="note in [...lead.notes].reverse()" :key="note.leadNot_id"
                        class="p-4 border border-gray-200 rounded-lg hover:border-purple-300 transition">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">{{ note.leadNot_title }}</h4>
                            </div>
                            <div class="flex gap-2 ml-4">
                                <button @click="openEditNoteModal(note)"
                                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-xs font-medium">
                                    Editar
                                </button>
                                <button @click="openDeleteNoteModal(note)"
                                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition text-xs font-medium">
                                    Eliminar
                                </button>
                            </div>
                            <span v-if="note.leadNot_active"
                                class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ml-2">
                                Activa
                            </span>
                            <span v-else
                                class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-700 ml-2">
                                Inactiva
                            </span>
                        </div>
                        <p class="text-gray-600 text-sm mb-2">{{ note.leadNot_description }}</p>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>{{ note.user?.name }} {{ note.user?.lastname }}</span>
                            <span>{{ formatDate(note.created_at) }}</span>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-8">
                    <p class="text-gray-500">No hay notas asociadas a este lead</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modales internos -->
    <CreateModal :show="showNoteCreateModal" :lead="lead"
        @close="closeCreateNoteModal" @saved="handleNoteSaved" />

    <EditModal :show="showNoteEditModal" :note="selectedNote" :lead="lead"
        @close="closeEditNoteModal" @saved="handleNoteSaved" />

    <ConfirmModal :show="showNoteDeleteModal" title="Eliminar Nota"
        message="¿Está seguro de que desea eliminar esta nota? Esta acción no se puede deshacer."
        confirm-text="Eliminar" cancel-text="Cancelar" :isDangerous="true" @confirm="confirmDeleteNote"
        @close="closeNoteDeleteModal" />
</template>
