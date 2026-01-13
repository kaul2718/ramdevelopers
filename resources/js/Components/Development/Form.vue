<script>
    export default {
        name: 'DevelopmentForm'
    }
</script>

<script setup>
    import FormSection from '@/Components/FormSection.vue'
    import InputError from '@/Components/InputError.vue'
    import InputLabel from '@/Components/InputLabel.vue'
    import PrimaryButton from '@/Components/PrimaryButton.vue'
    import TextInput from '@/Components/TextInput.vue'
    import { onMounted, ref, watch, computed } from 'vue'
    import axios from 'axios'
    import { useNotificationStore } from '@/stores/notificationStore'

    const notificationStore = useNotificationStore()

    const props = defineProps({
        updating: {
            type: Boolean,
            default: false
        },
        form: {
            type: Object,
            default: null
        },
        development: {
            type: Object,
            default: null
        },
        developers: {
            type: Array,
            default: () => []
        },
        countries: {
            type: Array,
            default: () => []
        },
        cities: {
            type: Array,
            default: () => []
        },
        approvalStatuses: {
            type: Array,
            default: () => []
        },
        businessStates: {
            type: Array,
            default: () => []
        },
        commercialStatuses: {
            type: Array,
            default: () => []
        },
        housingTypes: {
            type: Array,
            default: () => []
        },
        currencies: {
            type: Array,
            default: () => []
        },
        showSubmitButton: {
            type: Boolean,
            default: true
        }
    })

    const emit = defineEmits(['submit', 'saved', 'cancel'])

    // Usar form prop de Inertia o crear uno local para ediciones sin Inertia
    const form = props.form ? computed(() => props.form) : ref({
        devr_id: '',
        ctry_id: '',
        city_id: '',
        apvSta_id: '',
        busiSta_id: '',
        commSta_id: '',
        houTyp_id: '',
        curr_id: '',
        devt_title: '',
        devt_slug: '',
        devt_address: '',
        devt_short_description: '',
        devt_long_description: '',
        devt_price_from: '',
        devt_price_to: '',
        devt_delivery_year: '',
        devt_estimated_profit: '',
        devt_estimated_profit_is_percentage: false,
        devt_is_featured: false,
        devt_storage_rooms: '0',
        devt_parking_spaces: '0',
        devt_terraces: '0',
        devt_swimming_pools: '0',
        devt_children_areas: '0',
        devt_green_zones: '0',
        devt_elevators: '0',
        devt_golf_courses: '0',
        devt_bedrooms: '0'
    })

    const errors = ref({})
    const isLoading = ref(false)
    let isInitialLoad = true


    // Filtrar ciudades según el país seleccionado
    const filteredCities = computed(() => {
        if (!form.value.ctry_id) {
            return []
        }
        return props.cities.filter(city => String(city.ctry_id) === String(form.value.ctry_id))
    })

    onMounted(() => {
        // Solo cargar datos si no tenemos form de Inertia y sí tenemos development prop
        if (!props.form && props.updating && props.development) {
            form.value = {
                devr_id: String(props.development.devr_id || ''),
                ctry_id: String(props.development.ctry_id || ''),
                city_id: String(props.development.city_id || ''),
                apvSta_id: String(props.development.apvSta_id || ''),
                busiSta_id: String(props.development.busiSta_id || ''),
                commSta_id: String(props.development.commSta_id || ''),
                houTyp_id: String(props.development.houTyp_id || ''),
                devt_title: props.development.devt_title || '',
                devt_slug: props.development.devt_slug || '',
                devt_address: props.development.devt_address || '',
                devt_short_description: props.development.devt_short_description || '',
                devt_long_description: props.development.devt_long_description || '',
                devt_price_from: String(props.development.devt_price_from || ''),
                devt_price_to: String(props.development.devt_price_to || ''),
                devt_delivery_year: String(props.development.devt_delivery_year || ''),
                devt_estimated_profit: String(props.development.devt_estimated_profit || ''),
                devt_estimated_profit_is_percentage: Boolean(props.development.devt_estimated_profit_is_percentage),
                devt_is_featured: Boolean(props.development.devt_is_featured),
                devt_storage_rooms: String(props.development.devt_storage_rooms || '0'),
                devt_parking_spaces: String(props.development.devt_parking_spaces || '0'),
                devt_terraces: String(props.development.devt_terraces || '0'),
                devt_swimming_pools: String(props.development.devt_swimming_pools || '0'),
                devt_children_areas: String(props.development.devt_children_areas || '0'),
                devt_green_zones: String(props.development.devt_green_zones || '0'),
                devt_elevators: String(props.development.devt_elevators || '0'),
                devt_golf_courses: String(props.development.devt_golf_courses || '0'),
                devt_bedrooms: String(props.development.devt_bedrooms || '0')
            }
        }
        isInitialLoad = false
    })

    // Resetear city_id cuando cambie el país (solo después de la carga inicial)
    watch(() => form.value.ctry_id, () => {
        if (!isInitialLoad) {
            form.value.city_id = ''
        }
    })

    // Validar honorarios en tiempo real cuando cambia el tipo o el valor
    watch(() => [form.value.devt_estimated_profit_is_percentage, form.value.devt_estimated_profit], () => {
        if (form.value.devt_estimated_profit_is_percentage) {
            if (form.value.devt_estimated_profit !== '') {
                const profit = parseFloat(form.value.devt_estimated_profit)
                if (isNaN(profit) || profit < 1 || profit > 100) {
                    errors.value.devt_estimated_profit = 'Si es porcentaje, el valor debe estar entre 1 y 100'
                } else {
                    delete errors.value.devt_estimated_profit
                }
            } else {
                delete errors.value.devt_estimated_profit
            }
        } else {
            // Para monto fijo
            if (form.value.devt_estimated_profit !== '' && parseFloat(form.value.devt_estimated_profit) < 0) {
                errors.value.devt_estimated_profit = 'Los honorarios no pueden ser negativos'
            } else {
                delete errors.value.devt_estimated_profit
            }
        }
    })

    const validateForm = () => {
        errors.value = {}

        const requiredFields = {
            devr_id: 'Desarrollador',
            ctry_id: 'País',
            city_id: 'Ciudad',
            apvSta_id: 'Estado de Aprobación',
            busiSta_id: 'Estado del Proyecto',
            commSta_id: 'Tipo de Proyecto',
            houTyp_id: 'Tipo de Vivienda',
            devt_title: 'Título del Desarrollo',
            devt_slug: 'Slug',
            devt_address: 'Dirección',
            devt_short_description: 'Resumen',
            devt_long_description: 'Descripción'
        }

        for (const [field, label] of Object.entries(requiredFields)) {
            if (!form.value[field] || (typeof form.value[field] === 'string' && form.value[field].trim() === '')) {
                errors.value[field] = `${label} es requerido`
            }
        }

        // Validaciones numéricas
        if (form.value.devt_delivery_year && parseInt(form.value.devt_delivery_year) < 2020) {
            errors.value.devt_delivery_year = 'El año de entrega no puede ser menor a 2020'
        }

        if (form.value.devt_price_from !== '' && parseFloat(form.value.devt_price_from) < 0) {
            errors.value.devt_price_from = 'El precio inicial no puede ser negativo'
        }

        if (form.value.devt_price_to !== '' && parseFloat(form.value.devt_price_to) < 0) {
            errors.value.devt_price_to = 'El precio final no puede ser negativo'
        }

        if (form.value.devt_estimated_profit !== '' && parseFloat(form.value.devt_estimated_profit) < 0) {
            errors.value.devt_estimated_profit = 'Los honorarios no pueden ser negativos'
        }

        if (form.value.devt_estimated_profit_is_percentage) {
            if (form.value.devt_estimated_profit === '') {
                // Si es porcentaje pero está vacío, no requiere validación
            } else {
                const profit = parseFloat(form.value.devt_estimated_profit)
                if (isNaN(profit) || profit < 1 || profit > 100) {
                    errors.value.devt_estimated_profit = 'Si es porcentaje, el valor debe estar entre 1 y 100'
                }
            }
        } else {
            // Para monto fijo, permitir cualquier valor >= 0 (ya validado arriba)
        }

        return Object.keys(errors.value).length === 0
    }

    const handleSubmit = async () => {
        if (!validateForm()) {
            return
        }

        isLoading.value = true

        try {
            const endpoint = props.updating
                ? route('development.update', props.development.devt_id)
                : route('development.store')

            const method = props.updating ? 'put' : 'post'

            const response = await axios[method](endpoint, form.value)

            notificationStore.success(
                props.updating
                    ? 'Desarrollo actualizado correctamente'
                    : 'Desarrollo creado correctamente'
            )

            // Emitir solo el objeto development, no la respuesta completa
            const development = response.data.development || response.data

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
            
            emit('saved', development)
        } catch (error) {
            const errorMessage = typeof error.response?.data?.message === 'string'
                ? error.response.data.message
                : Array.isArray(error.response?.data?.errors)
                    ? error.response.data.errors.join(', ')
                    : 'Error al guardar el desarrollo'

            notificationStore.error(errorMessage)
        } finally {
            isLoading.value = false
        }
    }
</script>

<template>
    <FormSection @submitted="handleSubmit">
        <template #title>
            {{ updating ? 'Actualizar Desarrollo' : 'Crear Desarrollo' }}
        </template>

        <template #description>
            {{ updating ? 'Actualizar la información del desarrollo seleccionado' : 'Crear un nuevo desarrollo' }}
        </template>

        <template #form>
            <details class="acordeon--seccion" open>
                <summary>
                    <h3>Información general</h3>
                </summary>
                <div class="contenido--seccion">
                    <div class="caja--doble">
                        <!-- Desarrollador -->
                        <div class="contenedor--input">
                            <select id="devr_id" v-model="form.devr_id" class="campo--input">
                                <option value="">Selecciona un desarrollador</option>
                                <option v-for="developer in developers" :key="developer.devr_id" :value="String(developer.devr_id)">
                                    {{ developer.devr_commercial_name }}
                                </option>
                            </select>
                            <InputLabel for="devr_id" value="Desarrollador" />
                            <InputError :message="errors.devr_id" class="mt-2" />
                        </div>

                        <!-- Estado de Aprobación -->
                        <div class="contenedor--input">
                            <select id="apvSta_id" v-model="form.apvSta_id" class="campo--input">
                                <option value="">Selecciona un estado</option>
                                <option v-for="status in approvalStatuses" :key="status.apvSta_id"
                                    :value="String(status.apvSta_id)">
                                    {{ status.apvSta_name }}
                                </option>
                            </select>
                            <InputLabel for="apvSta_id" value="Estado de Aprobación" />
                            <InputError :message="errors.apvSta_id" class="mt-2" />
                        </div>

                        <!-- Estado del Proyecto -->
                        <div class="contenedor--input">
                            <select id="busiSta_id" v-model="form.busiSta_id" class="campo--input">
                                <option value="">Selecciona un estado</option>
                                <option v-for="state in businessStates" :key="state.busiSta_id" :value="String(state.busiSta_id)">
                                    {{ state.busiSta_name }}
                                </option>
                            </select>
                            <InputLabel for="busiSta_id" value="Estado del Proyecto" />
                            <InputError :message="errors.busiSta_id" class="mt-2" />
                        </div>

                        <!-- Tipo de Proyecto -->
                        <div class="contenedor--input">
                            <select id="commSta_id" v-model="form.commSta_id" class="campo--input">
                                <option value="">Selecciona un tipo</option>
                                <option v-for="status in commercialStatuses" :key="status.commSta_id"
                                    :value="String(status.commSta_id)">
                                    {{ status.commSta_name }}
                                </option>
                            </select>
                            <InputLabel for="commSta_id" value="Tipo de Proyecto" />
                            <InputError :message="errors.commSta_id" class="mt-2" />
                        </div>

                        <div class="caja--doble">
                            <!-- Año de Entrega -->
                            <div class="contenedor--input">
                                <TextInput id="devt_delivery_year" v-model="form.devt_delivery_year" type="number" min="2020"
                                    class="campo--input" placeholder="Ej: 2025" />
                                <InputLabel for="devt_delivery_year" value="Año de Entrega" />
                                <InputError :message="errors.devt_delivery_year" class="mt-2" />
                            </div>

                            <!-- Destacado -->
                            <div class="contenedor--input">
                                <select id="devt_is_featured" v-model.number="form.devt_is_featured" class="campo--input">
                                    <option :value="false">No</option>
                                    <option :value="true">Sí</option>
                                </select>
                                <InputLabel for="devt_is_featured" value="Destacado" />
                                <InputError :message="errors.devt_is_featured" class="mt-2" />
                            </div>
                        </div>

                        <!-- Tipo de Vivienda -->
                        <div class="contenedor--input">
                            <select id="houTyp_id" v-model="form.houTyp_id" class="campo--input">
                                <option value="">Selecciona un tipo de vivienda</option>
                                <option v-for="type in housingTypes" :key="type.houTyp_id" :value="String(type.houTyp_id)">
                                    {{ type.houTyp_name }}
                                </option>
                            </select>
                            <InputLabel for="houTyp_id" value="Tipo de Vivienda" />
                            <InputError :message="errors.houTyp_id" class="mt-2" />
                        </div>

                        <!-- Título -->
                        <div class="contenedor--input">
                            <TextInput id="devt_title" v-model="form.devt_title" type="text" class="campo--input"
                                placeholder="Ej: Proyecto Residencial Downtown" />
                            <InputLabel for="devt_title" value="Título del Desarrollo" />
                            <InputError :message="errors.devt_title" class="mt-2" />
                        </div>

                        <!-- Slug -->
                        <div class="contenedor--input">
                            <TextInput id="devt_slug" v-model="form.devt_slug" type="text" class="campo--input"
                                placeholder="Ej: proyecto-residencial-downtown" />
                            <InputLabel for="devt_slug" value="Slug" />
                            <InputError :message="errors.devt_slug" class="mt-2" />
                        </div>

                    </div>

                    <!-- Resumen Corto -->
                    <div class="contenedor--input">
                        <TextInput id="devt_short_description" v-model="form.devt_short_description" class="campo--input"
                            placeholder="Resumen breve del proyecto" rows="3" />
                        <InputLabel for="devt_short_description" value="Resumen" />
                        <InputError :message="errors.devt_short_description" class="mt-2" />
                    </div>

                    <!-- Descripción Larga -->
                    <div class="contenedor--input">
                        <TextInput id="devt_long_description" v-model="form.devt_long_description" class="campo--input"
                            placeholder="Descripción detallada del proyecto" rows="4" />
                        <InputLabel for="devt_long_description" value="Descripción" />
                        <InputError :message="errors.devt_long_description" class="mt-2" />
                    </div>
                </div>
            </details>

            <details class="acordeon--seccion">
                <summary>
                    <h3>Locación</h3>
                </summary>
                <div class="contenido--seccion">
                    <div class="caja--doble">
                        <!-- País -->
                        <div class="contenedor--input">
                            <select id="ctry_id" v-model="form.ctry_id" class="campo--input">
                                <option value="">Selecciona un país</option>
                                <option v-for="country in countries" :key="country.ctry_id" :value="String(country.ctry_id)">
                                    {{ country.ctry_name }}
                                </option>
                            </select>
                            <InputLabel for="ctry_id" value="País" />
                            <InputError :message="errors.ctry_id" class="mt-2" />
                        </div>

                        <!-- Ciudad -->
                        <div class="contenedor--input">
                            <select id="city_id" v-model="form.city_id" :disabled="!form.ctry_id" class="campo--input">
                                <option value="">{{ form.ctry_id ? 'Selecciona una ciudad' : 'Primero selecciona un país' }}
                                </option>
                                <option v-for="city in filteredCities" :key="city.city_id" :value="String(city.city_id)">
                                    {{ city.city_name }}
                                </option>
                            </select>
                            <InputLabel for="city_id" value="Ciudad" />
                            <InputError :message="errors.city_id" class="mt-2" />
                        </div>

                        <!-- Dirección -->
                        <div class="contenedor--input">
                            <TextInput id="devt_address" v-model="form.devt_address" type="text" class="campo--input"
                                placeholder="Ej: Calle Principal 123, Zona 10" />
                            <InputLabel for="devt_address" value="Dirección" />
                            <InputError :message="errors.devt_address" class="mt-2" />
                        </div>

                    </div>
                </div>
            </details>

            <details class="acordeon--seccion">
                <summary>
                    <h3>Información económica</h3>
                </summary>
                <div class="contenido--seccion">
                    <div class="caja--doble">
                        <!-- Moneda -->
                        <div class="contenedor--input">
                            <select id="curr_id" v-model="form.curr_id" class="campo--input">
                                <option value="">Selecciona una moneda</option>
                                <option v-for="currency in currencies" :key="currency.curr_id" :value="String(currency.curr_id)">
                                    {{ currency.curr_symbol }} {{ currency.curr_name }}
                                </option>
                            </select>
                            <InputLabel for="curr_id" value="Moneda" />
                            <InputError :message="errors.curr_id" class="mt-2" />
                        </div>

                        <div class="caja--doble">
                            <!-- Precio Hasta -->
                            <div class="contenedor--input">
                                <TextInput id="devt_price_to" v-model="form.devt_price_to" type="number" min="0" step="0.01"
                                    class="campo--input" placeholder="Ej: 150000" />
                                <InputLabel for="devt_price_to" value="Precio Hasta" />
                                <InputError :message="errors.devt_price_to" class="mt-2" />
                            </div>

                            <!-- Precio Desde -->
                            <div class="contenedor--input">
                                <TextInput id="devt_price_from" v-model="form.devt_price_from" type="number" min="0" step="0.01"
                                    class="campo--input" placeholder="Ej: 50000" />
                                <InputLabel for="devt_price_from" value="Precio Desde" />
                                <InputError :message="errors.devt_price_from" class="mt-2" />
                            </div>
                        </div>
                        
                        <!-- Tipo de Honorarios -->
                        <div class="contenedor--input">
                            <select id="devt_estimated_profit_is_percentage" v-model.boolean="form.devt_estimated_profit_is_percentage" class="campo--input">
                                <option :value="false">Monto Fijo</option>
                                <option :value="true">Porcentaje</option>
                            </select>
                            <InputLabel for="devt_estimated_profit_is_percentage" value="Tipo de Honorarios" />
                            <InputError :message="errors.devt_estimated_profit_is_percentage" class="mt-2" />
                        </div>
                        
                        <!-- Honorarios Estimados -->
                        <div class="contenedor--input">
                            <TextInput 
                                id="devt_estimated_profit" 
                                v-model="form.devt_estimated_profit" 
                                type="number" 
                                :min="form.devt_estimated_profit_is_percentage ? 1 : 0"
                                :max="form.devt_estimated_profit_is_percentage ? 100 : undefined"
                                step="0.01" 
                                class="campo--input" 
                                :placeholder="form.devt_estimated_profit_is_percentage ? 'Ej: 25 (1-100)' : 'Ej: 20000'" 
                            />
                            <InputLabel for="devt_estimated_profit" :value="form.devt_estimated_profit_is_percentage ? 'Honorarios (%)' : 'Honorarios ($)'" />
                            <InputError :message="errors.devt_estimated_profit" class="mt-2" />
                        </div>
                    </div>
                </div>
            </details>

            <details class="acordeon--seccion">
                <summary>
                    <h3>Amenidades</h3>
                </summary>
                <div class="contenido--seccion">
                    <div class="caja--doble">
                        <div class="caja--doble">
                            <div class="contenedor--input">
                                <TextInput id="devt_bedrooms" v-model="form.devt_bedrooms" type="number" min="0" class="campo--input"
                                    placeholder="Ej: 3" />
                                <InputLabel for="devt_bedrooms" value="Número de Dormitorios" />
                                <InputError :message="errors.devt_bedrooms" class="mt-2" />
                            </div>

                            <div class="contenedor--input">
                                <TextInput id="devt_storage_rooms" v-model="form.devt_storage_rooms" type="number" min="0"
                                    class="campo--input" placeholder="Ej: 2" />
                                <InputLabel for="devt_storage_rooms" value="Trasteros" />
                                <InputError :message="errors.devt_storage_rooms" class="mt-2" />
                            </div>
                        </div>

                        <div class="caja--doble">
                            <div class="contenedor--input">
                                <TextInput id="devt_parking_spaces" v-model="form.devt_parking_spaces" type="number" min="0"
                                    class="campo--input" placeholder="Ej: 2" />
                                <InputLabel for="devt_parking_spaces" value="Parking" />
                                <InputError :message="errors.devt_parking_spaces" class="mt-2" />
                            </div>

                            <div class="contenedor--input">
                                <TextInput id="devt_terraces" v-model="form.devt_terraces" type="number" min="0" class="campo--input"
                                    placeholder="Ej: 1" />
                                <InputLabel for="devt_terraces" value="Terraza" />
                                <InputError :message="errors.devt_terraces" class="mt-2" />
                            </div>
                        </div>

                        <div class="caja--doble">
                            <div class="contenedor--input">
                                <TextInput id="devt_swimming_pools" v-model="form.devt_swimming_pools" type="number" min="0"
                                    class="campo--input" placeholder="Ej: 1" />
                                <InputLabel for="devt_swimming_pools" value="Piscina" />
                                <InputError :message="errors.devt_swimming_pools" class="mt-2" />
                            </div>

                            <div class="contenedor--input">
                                <TextInput id="devt_children_areas" v-model="form.devt_children_areas" type="number" min="0"
                                    class="campo--input" placeholder="Ej: 1" />
                                <InputLabel for="devt_children_areas" value="Área Infantil" />
                                <InputError :message="errors.devt_children_areas" class="mt-2" />
                            </div>
                        </div>    

                        <div class="caja--doble">
                            <div class="contenedor--input">
                                <TextInput id="devt_green_zones" v-model="form.devt_green_zones" type="number" min="0"
                                    class="campo--input" placeholder="Ej: 2" />
                                <InputLabel for="devt_green_zones" value="Zonas Verdes" />
                                <InputError :message="errors.devt_green_zones" class="mt-2" />
                            </div>

                            <div class="contenedor--input">
                                <TextInput id="devt_elevators" v-model="form.devt_elevators" type="number" min="0" class="campo--input"
                                    placeholder="Ej: 2" />
                                <InputLabel for="devt_elevators" value="Ascensor" />
                                <InputError :message="errors.devt_elevators" class="mt-2" />
                            </div>
                        </div>

                        <div class="caja--doble">
                            <div class="contenedor--input">
                                <TextInput id="devt_golf_courses" v-model="form.devt_golf_courses" type="number" min="0"
                                    class="campo--input" placeholder="Ej: 1" />
                                <InputLabel for="devt_golf_courses" value="Golf" />
                                <InputError :message="errors.devt_golf_courses" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>
            </details>
        </template>

        <template #actions>
            <PrimaryButton v-if="showSubmitButton" :disabled="isLoading">
                <template #texto--boton>{{ isLoading ? 'Guardando...' : (updating ? 'Actualizar' : 'Crear')
                    }}</template>
                <template #icono--boton>
                    <path v-if="!updating" stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    <path v-if="updating" stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </template>
            </PrimaryButton>
        </template>
    </FormSection>
</template>
