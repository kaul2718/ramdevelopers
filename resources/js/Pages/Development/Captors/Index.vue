
<script setup>
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import axios from 'axios'
import { useNotificationStore } from '@/stores/notificationStore'

const notificationStore = useNotificationStore()

const props = defineProps({
    development: Object,
    captors: Array,
    availableUsers: Array,
})



const form = ref({
    user_id: '',
    devtUsr_is_main: false,
})

const isProcessing = ref(false)
const captorsList = ref(props.captors || [])
const availableList = ref(props.availableUsers || [])

// Verificar si ya hay un captador principal
const hasMainCaptor = () => {
    return props.captors && props.captors.some(c => c?.devtUsr_is_main === true)
}



const addCaptor = async () => {
    isProcessing.value = true
    
    try {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')

        const response = await axios.post(`/development/${props.development.devt_id}/captors`, {
            user_id: parseInt(form.value.user_id),
            devtUsr_is_main: form.value.devtUsr_is_main,
        }, {
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            }
        })
        
        if (response.status === 201) {
            notificationStore.success('Captador asignado correctamente')
            form.value.user_id = ''
            form.value.devtUsr_is_main = false
            // Recargar la página
            setTimeout(() => window.location.reload(), 1000)
        }
    } catch (error) {
        console.error('Error en addCaptor:', error)
        console.error('Response status:', error.response?.status)
        console.error('Response data:', error.response?.data)
        
        const message = error.response?.data?.message || 'Error al asignar el captador'
        notificationStore.error(message)
    } finally {
        isProcessing.value = false
    }
}

const removeCaptor = async (userId) => {
    if (!confirm('¿Estás seguro de que deseas remover este captador?')) return
    try {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')

        const response = await axios.delete(`/development/${props.development.devt_id}/captors/${userId}`, {
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            }
        })

        notificationStore.success('Captador removido correctamente')
        setTimeout(() => window.location.reload(), 1000)
    } catch (error) {
        console.error('Error en removeCaptor:', error)
        console.error('Response status:', error.response?.status)
        console.error('Response data:', error.response?.data)
        
        const message = error.response?.data?.message || 'Error al remover el captador'
        notificationStore.error(message)
    }
}

const setMainCaptor = async (userId) => {
    try {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')

        const response = await axios.patch(`/development/${props.development.devt_id}/captors/${userId}/set-main`, {}, {
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            }
        })

        // Actualizar estado local: desmarcar todos y marcar solo el actual como principal
        if (props.captors) {
            props.captors.forEach(captor => {
                captor.devtUsr_is_main = captor.user_id === userId;
            })
        }

        notificationStore.success('Captador establecido como principal')
        setTimeout(() => window.location.reload(), 1000)
    } catch (error) {
        console.error('Error en setMainCaptor:', error)
        console.error('Response status:', error.response?.status)
        console.error('Response data:', error.response?.data)
        
        const message = error.response?.data?.message || 'Error al establecer como principal'
        notificationStore.error(message)
    }
}
</script>

<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestionar Captadores - <span v-if="development">{{ development.devt_title }}</span>
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="!development" class="text-center py-12">
                    <p class="text-gray-500">Cargando desarrollo...</p>
                </div>
                
                <template v-else>
                    <!-- Tarjeta de Desarrollo -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                        <div class="px-4 py-5 sm:px-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Proyecto</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ development.devt_title }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Desarrollador</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ development.developer?.devr_commercial_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Ubicación</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ development.city?.city_name }}, {{ development.country?.ctry_name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Panel Principal: Lista de Captadores -->
                        <div class="lg:col-span-2">
                            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                                        Captadores Asignados ({{ (captors || []).length }})
                                    </h3>
                                </div>

                                <div v-if="!captors || captors.length === 0" class="px-4 py-5 sm:p-6">
                                    <p class="text-center text-gray-500">No hay captadores asignados a este desarrollo.</p>
                                </div>

                                <ul v-else class="divide-y divide-gray-200">
                                    <li v-for="(captor, idx) in captors" :key="idx" class="px-4 py-4 sm:px-6">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900">{{ captor?.user?.name }} {{ captor?.user?.lastname }}</p>
                                                <p class="text-sm text-gray-500">{{ captor?.user?.email }}</p>
                                                <span v-if="captor?.devtUsr_is_main" class="inline-block mt-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">Principal</span>
                                            </div>
                                            <div class="flex space-x-2">
                                                <button v-if="!captor?.devtUsr_is_main && !hasMainCaptor()" @click="setMainCaptor(captor?.user_id)" class="text-indigo-600 hover:text-indigo-900 text-sm">Marcar Principal</button>
                                                <button @click="removeCaptor(captor?.user_id)" class="text-red-600 hover:text-red-900 text-sm">Remover</button>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Panel Lateral: Agregar Captador -->
                        <div>
                            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900">Agregar Captador</h3>
                                </div>

                                <form @submit.prevent="addCaptor" class="px-4 py-5 sm:p-6">
                                    <div class="space-y-4">
                                        <div>
                                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Selecciona un usuario</label>
                                            <select id="user_id" v-model="form.user_id" class="w-full border border-gray-300 rounded-md py-2 px-3">
                                                <option value="">-- Selecciona un usuario --</option>
                                                <option v-for="user in (availableUsers || [])" :key="user?.id" :value="String(user?.id)">
                                                    {{ user?.name }} {{ user?.lastname }}
                                                </option>
                                            </select>
                                        </div>

                                        <div class="flex items-center">
                                            <input id="devtUsr_is_main" v-model="form.devtUsr_is_main" type="checkbox" class="h-4 w-4" :disabled="hasMainCaptor()" />
                                            <label for="devtUsr_is_main" class="ml-2 block text-sm text-gray-700" :class="{ 'text-gray-400': hasMainCaptor() }">
                                                Establecer como principal <span v-if="hasMainCaptor()" class="text-xs text-gray-500">(ya existe uno)</span>
                                            </label>
                                        </div>

                                        <button type="submit" :disabled="isProcessing || !form.user_id" class="w-full py-2 px-4 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50">
                                            {{ isProcessing ? 'Agregando...' : 'Agregar Captador' }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Botón de regreso -->
                    <div class="mt-6">
                        <Link :href="`/development/${development.devt_id}`" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">
                            ← Volver al Desarrollo
                        </Link>
                    </div>
                </template>
            </div>
        </div>
    </AppLayout>
</template>
