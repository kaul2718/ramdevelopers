<script setup>
import DialogModal from '@/Components/DialogModal.vue'

const props = defineProps({
    show: Boolean,
    city: Object,
    countries: {
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
        <template #content v-if="city">
            <div class="space-y-6">
                <div>
                    <p class="block text-sm font-medium text-gray-700 mb-2">Nombre</p>
                    <p class="text-gray-900 font-semibold">{{ city.city_name }}</p>
                </div>

                <div>
                    <p class="block text-sm font-medium text-gray-700 mb-2">Región</p>
                    <p class="text-gray-900">{{ city.city_region || 'N/A' }}</p>
                </div>

                <div>
                    <p class="block text-sm font-medium text-gray-700 mb-2">País</p>
                    <p class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-sm font-medium text-blue-700">
                        {{ countries.find(c => c.ctry_id == city.ctry_id)?.ctry_name || 'N/A' }}
                    </p>
                </div>

                <div>
                    <p class="block text-sm font-medium text-gray-700 mb-2">Estado</p>
                    <div :class="[
                        'inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-semibold',
                        city.city_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700'
                    ]">
                        <span :class="[
                            'w-2 h-2 rounded-full',
                            city.city_active ? 'bg-emerald-600' : 'bg-red-600'
                        ]"></span>
                        {{ city.city_active ? 'Activo' : 'Inactivo' }}
                    </div>
                </div>
            </div>
        </template>        
    </DialogModal>
</template>
