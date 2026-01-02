<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import { Link, router } from '@inertiajs/vue3'
    import { ref, watch, onMounted } from 'vue'
    import { useNotificationStore } from '@/stores/notificationStore'
    import DevelopmentCreateModal from '@/Components/Development/CreateModal.vue'
    import DevelopmentFileModal from '@/Components/Development/FileModal.vue'
    import DevelopmentImageModal from '@/Components/Development/ImageModal.vue'
    import HeaderBody from '@/Components/HeaderBody.vue';
    import Pagination from '@/Components/Pagination.vue'

    const notificationStore = useNotificationStore();

    const props = defineProps({
        developments: {
            type: Object,
            required: true
        },
        developers: {
            type: Array,
            required: true
        },
        countries: {
            type: Array,
            required: true
        },
        cities: {
            type: Array,
            required: true
        },
        approvalStatuses: {
            type: Array,
            required: true
        },
        businessStates: {
            type: Array,
            required: true
        },
        commercialStatuses: {
            type: Array,
            required: true
        },
        documentTypes: {
            type: Array,
            required: true
        }
    })

    // Estados de modales
    const showCreateModal = ref(false)
    const showFileModal = ref(false)
    const showImageModal = ref(false)
    const currentDevelopment = ref(null)

    // Estados para galería de imágenes
    const imageGalleryIndex = ref({}) // { devt_id: index }

    // Estado de búsqueda
    const searchQuery = ref('')
    let searchTimeout = null

    // Métodos para abrir/cerrar modales
    const openCreateModal = () => {
        showCreateModal.value = true
    }

    const closeCreateModal = () => {
        showCreateModal.value = false
        // No resetear currentDevelopment aquí porque lo necesitamos para FileModal
    }

    const closeFileModal = () => {
        showFileModal.value = false
    }

    const closeImageModal = () => {
        showImageModal.value = false
        currentDevelopment.value = null
    }

    // Flujo secuencial de modales
    const handleDevelopmentCreated = (development) => {
        currentDevelopment.value = development
        closeCreateModal()
        // Mostrar modal de archivos
        setTimeout(() => {
            showFileModal.value = true
        }, 300)
    }

    const handleFilesCompleted = () => {
        // No mostrar notificación aquí, solo avanzar
        closeFileModal()
        // Mostrar modal de imágenes
        setTimeout(() => {
            showImageModal.value = true
        }, 300)
    }

    const handleImagesCompleted = () => {
        closeImageModal()
        
        // Mostrar UNA SOLA notificación con el resumen completo
        notificationStore.success('Desarrollo creado exitosamente');
        
        // Esperar más tiempo antes de redirigir (dejar que desaparezca completamente)
        setTimeout(() => {
            window.location.href = route('development.index')
        }, 1000)
    }

    const deleteDevelopment = (id) => {
        if (confirm("¿Seguro que deseas eliminar este desarrollo?")) {
            router.delete(route("development.destroy", id));
        }
    };

    const getGalleryImages = (item) => {
        if (!item.images || item.images.length === 0) return [];
        // Primero la portada, luego las demás
        const coverImage = item.images.find(img => img.devImg_is_cover);
        const otherImages = item.images.filter(img => !img.devImg_is_cover);
        return coverImage ? [coverImage, ...otherImages] : item.images;
    };

    const getImageIndex = (devt_id) => {
        return imageGalleryIndex.value[devt_id] || 0;
    };

    const getCurrentImage = (item) => {
        const images = getGalleryImages(item);
        const index = getImageIndex(item.devt_id);
        return images[index];
    };

    const goToNextImage = (item, event) => {
        event.stopPropagation();
        const images = getGalleryImages(item);
        if (images.length > 0) {
            const currentIndex = imageGalleryIndex.value[item.devt_id] || 0;
            imageGalleryIndex.value[item.devt_id] = (currentIndex + 1) % images.length;
        }
    };

    const goToPrevImage = (item, event) => {
        event.stopPropagation();
        const images = getGalleryImages(item);
        if (images.length > 0) {
            const currentIndex = imageGalleryIndex.value[item.devt_id] || 0;
            imageGalleryIndex.value[item.devt_id] = (currentIndex - 1 + images.length) % images.length;
        }
    };

    const handleSearch = () => {
        router.get(route('development.index'), { search: searchQuery.value }, {
            replace: true,
            preserveScroll: true
        });
    };

    //RUTA EDITAR PROYECTO
    const goToEdit = (id) => {
        router.visit(route('development.edit', id))
    }

    //RUTA VISUALIZAR PROYECTO
    const goToShow = (id) => {
        router.visit(route('development.show', id))
    }

    onMounted(() => {
        searchQuery.value = route().params.search || '';
        
        // Watch para búsqueda en tiempo real con debounce
        watch(searchQuery, () => {
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }
            searchTimeout = setTimeout(() => {
                handleSearch();
            }, 500);
        });
    });

</script>

<template>
    <AppLayout title="Proyectos">
        <HeaderBody titulo-body="Listado de proyectos" permisos="create development" nombre-btn="Crear nuevo" @create="showCreateModal = true"/>
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">Desarrollos</h1>
        </template>

        <div class="flex justify-between items-center pb-4">
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input 
                    v-model="searchQuery"
                    type="text"
                    placeholder="Buscar por título, pais, ciudad, desarrollador... "
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <div class="flex gap-2">
                <button 
                    v-if="searchQuery"
                    @click="searchQuery = ''"
                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 py-2 px-4 rounded transition-colors">
                    Limpiar
                </button>
            </div>
        </div>
        <div class="caja--tabla">
            <table>
                <thead>
                    <tr>
                        <th>Portada</th><th>Título</th><th>Desarrollador</th><th>País</th><th>Ciudad</th><th>Estado Comercial</th><th>Aprobación</th><th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in developments.data" :key="item.devt_id">
                        <td style="padding: 0;">
                            <div v-if="item.images && getGalleryImages(item).length > 0" class="relative w-28 h-28 overflow-hidden bg-gray-200 group transition-transform duration-300 cursor-pointer" style="border-radius: 1em;">
                                <!-- Imagen actual -->
                                <img :src="'/storage/' + getCurrentImage(item).devImg_url" 
                                        :alt="item.devt_title" 
                                        class="w-full h-full object-cover">
                                
                                <!-- Overlay con controles -->
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 flex items-center justify-between px-1">
                                    <!-- Botón anterior -->
                                    <button v-if="getGalleryImages(item).length > 1" 
                                        @click="goToPrevImage(item, $event)"
                                        class="text-white hover:bg-black hover:bg-opacity-60 rounded transition-all p-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                    <!-- Botón siguiente -->
                                    <button v-if="getGalleryImages(item).length > 1" 
                                        @click="goToNextImage(item, $event)"
                                        class="text-white hover:bg-black hover:bg-opacity-60 rounded transition-all p-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                                
                                <!-- Contador de imágenes -->
                                <div class="absolute bottom-1 right-1 bg-black bg-opacity-70 text-white text-xs rounded px-1.5 py-0.5 group-hover:bg-opacity-80 transition-all">
                                    {{ getImageIndex(item.devt_id) + 1 }}/{{ getGalleryImages(item).length }}
                                </div>
                            </div>
                            <div v-else class="w-28 h-28 rounded-lg overflow-hidden bg-gray-300 flex items-center justify-center transition-transform duration-300 cursor-pointer">
                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </td>
                        <td>{{ item.devt_title }}</td>
                        <td>
                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700">
                                {{ item.developer?.devr_commercial_name || 'Sin desarrollador' }}
                            </span>
                        </td>
                        <td>{{ item.country?.ctry_name || 'Sin país' }}</td>
                        <td>{{ item.city?.city_name || 'Sin ciudad' }}</td>
                        <td>
                            <span class="inline-flex items-center rounded-md bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700">
                                {{ item.business_status?.busiSta_name || 'Sin estado' }}
                            </span>
                        </td>
                        <td>
                            <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700">
                                {{ item.approval_status?.apvSta_name || 'Sin estado' }}
                            </span>
                        </td>
                        <td>
                            <div class="botonera--tabla">
                                <button @click="goToShow(item.devt_id)" class="btn--tipo1" title="Ver información">
                                    <svg class="size-5" viewBox="0 0 20 20" fill="CurrentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 3C5.5817 3 1.87987 5.95297 1.07386 10C1.87987 14.047 5.5817 17 10 17C14.4183 17 18.1201 14.047 18.9261 10C18.1201 5.95297 14.4183 3 10 3ZM10 15.5C6.86538 15.5 4.25623 13.5261 3.56877 10.9987C4.24924 8.4713 6.86538 6.5 10 6.5C13.1346 6.5 15.7508 8.4713 16.4312 10.9987C15.7438 13.5261 13.1346 15.5 10 15.5ZM10 7.75C8.48122 7.75 7.25 8.98122 7.25 10.5C7.25 12.0188 8.48122 13.25 10 13.25C11.5188 13.25 12.75 12.0188 12.75 10.5C12.75 8.98122 11.5188 7.75 10 7.75Z"></path>
                                    </svg>
                                </button>
                                <button @click="goToEdit(item.devt_id)" class="btn--tipo1" title="Editar campo" v-if="$page.props.user.permissions.includes('update development')">
                                    <svg class="size-5" viewBox="0 0 20 20" fill="CurrentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.53414 8.15675L8.96459 7.59496L8.96459 7.59496L9.53414 8.15675ZM13.8911 3.73968L13.3215 3.17789V3.17789L13.8911 3.73968ZM16.3154 3.75892L15.7367 4.31126L15.7367 4.31127L16.3154 3.75892ZM16.38 3.82658L16.9587 3.27423L16.9587 3.27423L16.38 3.82658ZM16.3401 6.13595L15.7803 5.56438L16.3401 6.13595ZM11.9186 10.4658L12.4784 11.0374L11.9186 10.4658ZM11.1223 10.9017L10.9404 10.1226V10.1226L11.1223 10.9017ZM9.07259 10.9951L8.52556 11.5788L8.52556 11.5788L9.07259 10.9951ZM9.09713 8.9664L9.87963 9.1328V9.1328L9.09713 8.9664ZM9.05721 10.9803L8.49542 11.5498H8.49542L9.05721 10.9803ZM17.1679 4.99458L16.368 4.98075V4.98075L17.1679 4.99458ZM15.1107 2.8693L15.1171 2.06932L15.1107 2.8693ZM9.22851 8.51246L8.52589 8.12992L8.52452 8.13247L9.22851 8.51246ZM9.22567 8.51772L8.52168 8.13773L8.5203 8.1403L9.22567 8.51772ZM11.5684 10.7654L11.9531 11.4668L11.9536 11.4666L11.5684 10.7654ZM11.5669 10.7662L11.9507 11.4681L11.9516 11.4676L11.5669 10.7662ZM11.3235 3.30005C11.7654 3.30005 12.1235 2.94188 12.1235 2.50005C12.1235 2.05822 11.7654 1.70005 11.3235 1.70005V3.30005ZM18.3 9.55887C18.3 9.11705 17.9418 8.75887 17.5 8.75887C17.0582 8.75887 16.7 9.11705 16.7 9.55887H18.3ZM3.47631 16.5237L4.042 15.9581H4.042L3.47631 16.5237ZM16.5237 16.5237L15.958 15.9581L15.958 15.9581L16.5237 16.5237ZM10.1037 8.71855L14.4606 4.30148L13.3215 3.17789L8.96459 7.59496L10.1037 8.71855ZM15.7367 4.31127L15.8013 4.37893L16.9587 3.27423L16.8941 3.20657L15.7367 4.31127ZM15.7803 5.56438L11.3589 9.89426L12.4784 11.0374L16.8998 6.70753L15.7803 5.56438ZM10.9404 10.1226C10.3417 10.2624 9.97854 10.3452 9.72166 10.3675C9.47476 10.3888 9.53559 10.3326 9.61962 10.4113L8.52556 11.5788C8.9387 11.966 9.45086 11.9969 9.85978 11.9615C10.2587 11.9269 10.7558 11.8088 11.3042 11.6807L10.9404 10.1226ZM8.31462 8.8C8.19986 9.33969 8.09269 9.83345 8.0681 10.2293C8.04264 10.6393 8.08994 11.1499 8.49542 11.5498L9.619 10.4107C9.70348 10.494 9.65043 10.5635 9.66503 10.3285C9.6805 10.0795 9.75378 9.72461 9.87963 9.1328L8.31462 8.8ZM9.61962 10.4113C9.61941 10.4111 9.6192 10.4109 9.619 10.4107L8.49542 11.5498C8.50534 11.5596 8.51539 11.5693 8.52556 11.5788L9.61962 10.4113ZM15.8013 4.37892C16.0813 4.67236 16.2351 4.83583 16.3279 4.96331C16.4073 5.07234 16.3667 5.05597 16.368 4.98075L17.9678 5.00841C17.9749 4.59682 17.805 4.27366 17.6213 4.02139C17.451 3.78756 17.2078 3.53522 16.9587 3.27423L15.8013 4.37892ZM16.8998 6.70753C17.1578 6.45486 17.4095 6.21077 17.5876 5.98281C17.7798 5.73698 17.9607 5.41987 17.9678 5.00841L16.368 4.98075C16.3693 4.90565 16.4103 4.8909 16.327 4.99749C16.2297 5.12196 16.0703 5.28038 15.7803 5.56438L16.8998 6.70753ZM14.4606 4.30148C14.7639 3.99402 14.9352 3.82285 15.0703 3.71873C15.1866 3.62905 15.1757 3.66984 15.1044 3.66927L15.1171 2.06932C14.6874 2.06591 14.3538 2.25081 14.0935 2.45151C13.8518 2.63775 13.5925 2.9032 13.3215 3.17789L14.4606 4.30148ZM16.8941 3.20657C16.6279 2.92765 16.373 2.65804 16.1345 2.46792C15.8774 2.26298 15.5468 2.07273 15.1171 2.06932L15.1044 3.66927C15.033 3.66871 15.0226 3.62768 15.1372 3.71904C15.2704 3.82522 15.4387 3.999 15.7367 4.31126L16.8941 3.20657ZM8.96459 7.59496C8.82923 7.73218 8.64795 7.90575 8.5259 8.12993L9.93113 8.895C9.92075 8.91406 9.91465 8.91711 9.93926 8.88927C9.97002 8.85445 10.0145 8.80893 10.1037 8.71854L8.96459 7.59496ZM9.87963 9.1328C9.9059 9.00925 9.91925 8.94785 9.93124 8.90366C9.94073 8.86868 9.94137 8.87585 9.93104 8.89515L8.5203 8.1403C8.39951 8.36605 8.35444 8.61274 8.31462 8.8L9.87963 9.1328ZM8.52452 8.13247L8.52168 8.13773L9.92967 8.89772L9.9325 8.89246L8.52452 8.13247ZM11.3589 9.89426C11.27 9.98132 11.2252 10.0248 11.1909 10.055C11.1635 10.0791 11.1658 10.0738 11.1832 10.0642L11.9536 11.4666C12.1727 11.3462 12.3427 11.1703 12.4784 11.0374L11.3589 9.89426ZM11.3042 11.6807C11.4912 11.6371 11.7319 11.5878 11.9507 11.4681L11.1831 10.0643C11.2007 10.0547 11.206 10.0557 11.1697 10.0663C11.1248 10.0793 11.0628 10.0941 10.9404 10.1226L11.3042 11.6807ZM11.1837 10.064L11.1822 10.0648L11.9516 11.4676L11.9531 11.4668L11.1837 10.064ZM16.399 6.10097L13.8984 3.60094L12.7672 4.73243L15.2677 7.23246L16.399 6.10097ZM10.8333 16.7001H9.16667V18.3001H10.8333V16.7001ZM3.3 10.8334V9.16672H1.7V10.8334H3.3ZM9.16667 3.30005H11.3235V1.70005H9.16667V3.30005ZM16.7 9.55887V10.8334H18.3V9.55887H16.7ZM9.16667 16.7001C7.5727 16.7001 6.45771 16.6984 5.61569 16.5851C4.79669 16.475 4.35674 16.2728 4.042 15.9581L2.91063 17.0894C3.5722 17.751 4.40607 18.0369 5.4025 18.1709C6.37591 18.3018 7.61793 18.3001 9.16667 18.3001V16.7001ZM1.7 10.8334C1.7 12.3821 1.6983 13.6241 1.82917 14.5976C1.96314 15.594 2.24905 16.4279 2.91063 17.0894L4.042 15.9581C3.72726 15.6433 3.52502 15.2034 3.41491 14.3844C3.3017 13.5423 3.3 12.4273 3.3 10.8334H1.7ZM10.8333 18.3001C12.3821 18.3001 13.6241 18.3018 14.5975 18.1709C15.5939 18.0369 16.4278 17.751 17.0894 17.0894L15.958 15.9581C15.6433 16.2728 15.2033 16.475 14.3843 16.5851C13.5423 16.6984 12.4273 16.7001 10.8333 16.7001V18.3001ZM16.7 10.8334C16.7 12.4274 16.6983 13.5423 16.5851 14.3844C16.475 15.2034 16.2727 15.6433 15.958 15.9581L17.0894 17.0894C17.7509 16.4279 18.0369 15.594 18.1708 14.5976C18.3017 13.6241 18.3 12.3821 18.3 10.8334H16.7ZM3.3 9.16672C3.3 7.57275 3.3017 6.45776 3.41491 5.61574C3.52502 4.79674 3.72726 4.35679 4.042 4.04205L2.91063 2.91068C2.24905 3.57225 1.96314 4.40612 1.82917 5.40255C1.6983 6.37596 1.7 7.61798 1.7 9.16672H3.3ZM9.16667 1.70005C7.61793 1.70005 6.37591 1.69835 5.4025 1.82922C4.40607 1.96319 3.5722 2.24911 2.91063 2.91068L4.042 4.04205C4.35674 3.72731 4.79669 3.52507 5.61569 3.41496C6.45771 3.30175 7.5727 3.30005 9.16667 3.30005V1.70005Z"></path>
                                    </svg>
                                </button>
                                <button @click="deleteDevelopment(item)"class="btn--tipo2" title="Eliminar campo" v-if="$page.props.user.permissions.includes('delete development')">
                                    <svg class="size-5" viewBox="0 0 20 20" fill="CurrentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.00031 5.49999V4.69999H3.20031V5.49999H4.00031ZM16.0003 5.49999H16.8003V4.69999H16.0003V5.49999ZM17.5003 5.49999L17.5003 6.29999C17.9421 6.29999 18.3003 5.94183 18.3003 5.5C18.3003 5.05817 17.9421 4.7 17.5003 4.69999L17.5003 5.49999ZM9.30029 9.24997C9.30029 8.80814 8.94212 8.44997 8.50029 8.44997C8.05847 8.44997 7.70029 8.80814 7.70029 9.24997H9.30029ZM7.70029 13.75C7.70029 14.1918 8.05847 14.55 8.50029 14.55C8.94212 14.55 9.30029 14.1918 9.30029 13.75H7.70029ZM12.3004 9.24997C12.3004 8.80814 11.9422 8.44997 11.5004 8.44997C11.0585 8.44997 10.7004 8.80814 10.7004 9.24997H12.3004ZM10.7004 13.75C10.7004 14.1918 11.0585 14.55 11.5004 14.55C11.9422 14.55 12.3004 14.1918 12.3004 13.75H10.7004ZM4.00031 6.29999H16.0003V4.69999H4.00031V6.29999ZM15.2003 5.49999V12.5H16.8003V5.49999H15.2003ZM11.0003 16.7H9.00031V18.3H11.0003V16.7ZM4.80031 12.5V5.49999H3.20031V12.5H4.80031ZM9.00031 16.7C7.79918 16.7 6.97882 16.6983 6.36373 16.6156C5.77165 16.536 5.49093 16.3948 5.29823 16.2021L4.16686 17.3334C4.70639 17.873 5.38104 18.0979 6.15053 18.2013C6.89702 18.3017 7.84442 18.3 9.00031 18.3V16.7ZM3.20031 12.5C3.20031 13.6559 3.19861 14.6033 3.29897 15.3498C3.40243 16.1193 3.62733 16.4279 4.16686 17.3334L5.29823 16.2021C5.10553 16.0094 4.96431 15.7286 4.88471 15.1366C4.80201 14.5215 4.80031 13.7011 4.80031 12.5H3.20031ZM15.2003 12.5C15.2003 13.7011 15.1986 14.5215 15.1159 15.1366C15.0363 15.7286 14.8951 16.0094 14.7024 16.2021L15.8338 17.3334C16.3733 16.7939 16.5982 16.1193 16.7016 15.3498C16.802 14.6033 16.8003 13.6559 16.8003 12.5H15.2003ZM11.0003 18.3C12.1562 18.3 13.1036 18.3017 13.8501 18.2013C14.6196 18.0979 15.2942 17.873 15.8338 17.3334L14.7024 16.2021C14.5097 16.3948 14.229 16.536 13.6369 16.6156C13.0218 16.6983 12.2014 16.7 11.0003 16.7V18.3ZM2.50031 4.69999C2.22572 4.7 2.04405 4.7 1.94475 4.7C1.89511 4.7 1.86604 4.7 1.85624 4.7C1.85471 4.7 1.85206 4.7 1.851 4.7C1.05253 5.50059 1.85233 6.3 1.85256 6.3C1.85273 6.3 1.85297 6.3 1.85327 6.3C1.85385 6.3 1.85472 6.3 1.85587 6.3C1.86047 6.3 1.86972 6.3 1.88345 6.3C1.99328 6.3 2.39045 6.3 2.9906 6.3C4.19091 6.3 6.2032 6.3 8.35279 6.3C10.5024 6.3 12.7893 6.3 14.5387 6.3C15.4135 6.3 16.1539 6.3 16.6756 6.3C16.9364 6.3 17.1426 6.29999 17.2836 6.29999C17.3541 6.29999 17.4083 6.29999 17.4448 6.29999C17.4631 6.29999 17.477 6.29999 17.4863 6.29999C17.4909 6.29999 17.4944 6.29999 17.4968 6.29999C17.498 6.29999 17.4988 6.29999 17.4994 6.29999C17.4997 6.29999 17.4999 6.29999 17.5001 6.29999C17.5002 6.29999 17.5003 6.29999 17.5003 5.49999C17.5003 4.69999 17.5002 4.69999 17.5001 4.69999C17.4999 4.69999 17.4997 4.69999 17.4994 4.69999C17.4988 4.69999 17.498 4.69999 17.4968 4.69999C17.4944 4.69999 17.4909 4.69999 17.4863 4.69999C17.477 4.69999 17.4631 4.69999 17.4448 4.69999C17.4083 4.69999 17.3541 4.69999 17.2836 4.69999C17.1426 4.7 16.9364 4.7 16.6756 4.7C16.1539 4.7 15.4135 4.7 14.5387 4.7C12.7893 4.7 10.5024 4.7 8.35279 4.7C6.2032 4.7 4.19091 4.7 2.9906 4.7C2.39044 4.7 1.99329 4.7 1.88347 4.7C1.86974 4.7 1.86051 4.7 1.85594 4.7C1.8548 4.7 1.85396 4.7 1.85342 4.7C1.85315 4.7 1.85298 4.7 1.85288 4.7C1.85284 4.7 2.65253 5.49941 1.85408 6.3C1.85314 6.3 1.85296 6.3 1.85632 6.3C1.86608 6.3 1.89511 6.3 1.94477 6.3C2.04406 6.3 2.22573 6.3 2.50031 6.29999L2.50031 4.69999ZM7.05028 5.49994V4.16661H5.45028V5.49994H7.05028ZM7.91695 3.29994H12.0836V1.69994H7.91695V3.29994ZM12.9503 4.16661V5.49994H14.5503V4.16661H12.9503ZM12.0836 3.29994C12.5623 3.29994 12.9503 3.68796 12.9503 4.16661H14.5503C14.5503 2.8043 13.4459 1.69994 12.0836 1.69994V3.29994ZM7.05028 4.16661C7.05028 3.68796 7.4383 3.29994 7.91695 3.29994V1.69994C6.55465 1.69994 5.45028 2.8043 5.45028 4.16661H7.05028ZM2.50031 6.29999C4.70481 6.29998 6.40335 6.29998 8.1253 6.29997C9.84725 6.29996 11.5458 6.29995 13.7503 6.29994L13.7503 4.69994C11.5458 4.69995 9.84724 4.69996 8.12529 4.69997C6.40335 4.69998 4.7048 4.699982.50031 4.69999L2.50031 6.29999ZM13.7503 6.29994L17.5003 6.29999L17.5003 4.69999L13.7503 4.69994L13.7503 6.29994ZM7.70029 9.24997V13.75H9.30029V9.24997H7.70029ZM10.7004 9.24997V13.75H12.3004V9.24997H10.7004Z"></path>
                                    </svg>
                                </button>
                                <!--<button
                                class="p-2 rounded-full bg-white group transition-all duration-500 hover:bg-black flex item-center">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path class="stroke-black group-hover:stroke-white"
                                        d="M10.0161 14.9897V15.0397M10.0161 9.97598V10.026M10.0161 4.96231V5.01231"
                                        stroke="black" stroke-width="2.5"
                                        stroke-linecap="round">
                                    </path>
                                </svg>
                            </button>-->
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <Pagination :objPage="developments" />

        <!-- Modales -->
        <DevelopmentCreateModal
            :show="showCreateModal"
            :developers="developers"
            :countries="countries"
            :cities="cities"
            :approval-statuses="approvalStatuses"
            :business-states="businessStates"
            :commercial-statuses="commercialStatuses"
            @close="closeCreateModal"
            @saved="handleDevelopmentCreated"
        />

        <DevelopmentFileModal
            :show="showFileModal"
            :development="currentDevelopment"
            :document-types="documentTypes"
            @close="closeFileModal"
            @saved="handleFilesCompleted"
        />

        <DevelopmentImageModal
            :show="showImageModal"
            :development="currentDevelopment"
            @close="closeImageModal"
            @saved="handleImagesCompleted"
        />
    </AppLayout>
</template>
