<script>
    export default {
        name: 'PermissionIndex'
    }
</script>

<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import { Link, router } from '@inertiajs/vue3'

    defineProps({
        permissions: {
            type: Object,
            required: true
        }
    })

    const deletePermission = (id) => {
        if (confirm("¿Seguro que deseas eliminar este permiso?")) {
            router.delete(route("permissions.destroy", id));
        }
    };
</script>

<template>
    <AppLayout>
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">Permisos</h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-border-gray-200">
                    <div class="flex justify-end pr-2" v-if="$page.props.user.permissions.includes('create permissions')">
                        <Link 
                            :href="route('permissions.create')"
                            class="text-white bg-indigo-500 hover:bg-indigo-700 py-2 px-4 rounded">
                            Crear Permiso
                        </Link>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="flex flex-col">
                        <div class="overflow-x-auto pb-4">
                            <div class="block">
                                <div class="overflow-x-auto w-full border rounded-lg border-gray-300">
                                    <table class="w-full rounded-xl">
                                        <thead>
                                            <tr class="bg-gray-50">
                                                <th scope="col" class="p-5 text-left whitespace-nowrap text-sm leading-6 font-semibold text-gray-900 capitalize">
                                                    Id
                                                </th>
                                                <th scope="col" class="p-5 text-left whitespace-nowrap text-sm leading-6 font-semibold text-gray-900 capitalize min-w-[200px]">
                                                    Nombre
                                                </th>
                                                <th scope="col" class="p-5 text-left whitespace-nowrap text-sm leading-6 font-semibold text-gray-900 capitalize">
                                                    Guard
                                                </th>
                                                <th scope="col" class="p-5 text-left whitespace-nowrap text-sm leading-6 font-semibold text-gray-900 capitalize">
                                                    Acciones
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-300">
                                            <tr 
                                                v-for="item in permissions.data" 
                                                :key="item.id"
                                                class="bg-white transition-all duration-500 hover:bg-gray-50">
                                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">
                                                    {{ item.id }}
                                                </td>
                                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">
                                                    {{ item.name }}
                                                </td>
                                                <td class="p-5 whitespace-nowrap text-sm leading-6 font-medium text-gray-900">
                                                    <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600">{{ item.guard_name }}</span>
                                                </td>
                                                <td class="flex p-5 items-center gap-0.5">
                                                    <Link :href="route('permissions.edit', item.id)"
                                                        v-if="$page.props.user.permissions.includes('update permissions')"
                                                        class="p-2  rounded-full bg-white group transition-all duration-500 hover:bg-indigo-600 flex item-center">
                                                        <svg class="cursor-pointer" width="20" height="20"
                                                            viewBox="0 0 20 20" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path class="fill-indigo-500 group-hover:fill-white"
                                                                d="M9.53414 8.15675L8.96459 7.59496L8.96459 7.59496L9.53414 8.15675ZM13.8911 3.73968L13.3215 3.17789V3.17789L13.8911 3.73968ZM16.3154 3.75892L15.7367 4.31126L15.7367 4.31127L16.3154 3.75892ZM16.38 3.82658L16.9587 3.27423L16.9587 3.27423L16.38 3.82658ZM16.3401 6.13595L15.7803 5.56438L16.3401 6.13595ZM11.9186 10.4658L12.4784 11.0374L11.9186 10.4658ZM11.1223 10.9017L10.9404 10.1226V10.1226L11.1223 10.9017ZM9.07259 10.9951L8.52556 11.5788L8.52556 11.5788L9.07259 10.9951ZM9.09713 8.9664L9.87963 9.1328V9.1328L9.09713 8.9664ZM9.05721 10.9803L8.49542 11.5498H8.49542L9.05721 10.9803ZM17.1679 4.99458L16.368 4.98075V4.98075L17.1679 4.99458ZM15.1107 2.8693L15.1171 2.06932L15.1107 2.8693ZM9.22851 8.51246L8.52589 8.12992L8.52452 8.13247L9.22851 8.51246ZM9.22567 8.51772L8.52168 8.13773L8.5203 8.1403L9.22567 8.51772ZM11.5684 10.7654L11.9531 11.4668L11.9536 11.4666L11.5684 10.7654ZM11.5669 10.7662L11.9507 11.4681L11.9516 11.4676L11.5669 10.7662ZM11.3235 3.30005C11.7654 3.30005 12.1235 2.94188 12.1235 2.50005C12.1235 2.05822 11.7654 1.70005 11.3235 1.70005V3.30005ZM18.3 9.55887C18.3 9.11705 17.9418 8.75887 17.5 8.75887C17.0582 8.75887 16.7 9.11705 16.7 9.55887H18.3ZM3.47631 16.5237L4.042 15.9581H4.042L3.47631 16.5237ZM16.5237 16.5237L15.958 15.9581L15.958 15.9581L16.5237 16.5237ZM10.1037 8.71855L14.4606 4.30148L13.3215 3.17789L8.96459 7.59496L10.1037 8.71855ZM15.7367 4.31127L15.8013 4.37893L16.9587 3.27423L16.8941 3.20657L15.7367 4.31127ZM15.7803 5.56438L11.3589 9.89426L12.4784 11.0374L16.8998 6.70753L15.7803 5.56438ZM10.9404 10.1226C10.3417 10.2624 9.97854 10.3452 9.72166 10.3675C9.47476 10.3888 9.53559 10.3326 9.61962 10.4113L8.52556 11.5788C8.9387 11.966 9.45086 11.9969 9.85978 11.9615C10.2587 11.9269 10.7558 11.8088 11.3042 11.6807L10.9404 10.1226Z"
                                                                fill="#818CF8"></path>
                                                        </svg>
                                                    </Link>
                                                    <button @click="deletePermission(item.id)"
                                                        v-if="$page.props.user.permissions.includes('delete permissions')"
                                                        class="p-2 rounded-full bg-white group transition-all duration-500 hover:bg-red-600 flex item-center">
                                                        <svg class="" width="20" height="20" viewBox="0 0 20 20"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path class="fill-red-600 group-hover:fill-white"
                                                                d="M4.00031 5.49999V4.69999H3.20031V5.49999H4.00031ZM16.0003 5.49999H16.8003V4.69999H16.0003V5.49999ZM17.5003 5.49999L17.5003 6.29999C17.9421 6.29999 18.3003 5.94183 18.3003 5.5C18.3003 5.05817 17.9421 4.7 17.5003 4.69999L17.5003 5.49999ZM9.30029 9.24997C9.30029 8.80814 8.94212 8.44997 8.50029 8.44997C8.05847 8.44997 7.70029 8.80814 7.70029 9.24997H9.30029ZM7.70029 13.75C7.70029 14.1918 8.05847 14.55 8.50029 14.55C8.94212 14.55 9.30029 14.1918 9.30029 13.75H7.70029ZM12.3004 9.24997C12.3004 8.80814 11.9422 8.44997 11.5004 8.44997C11.0585 8.44997 10.7004 8.80814 10.7004 9.24997H12.3004ZM10.7004 13.75C10.7004 14.1918 11.0585 14.55 11.5004 14.55C11.9422 14.55 12.3004 14.1918 12.3004 13.75H10.7004ZM4.00031 6.29999H16.0003V4.69999H4.00031V6.29999ZM15.2003 5.49999V12.5H16.8003V5.49999H15.2003ZM11.0003 16.7H9.00031V18.3H11.0003V16.7ZM4.80031 12.5V5.49999H3.20031V12.5H4.80031ZM9.00031 16.7C7.79918 16.7 6.97882 16.6983 6.36373 16.5851C5.77165 16.536 5.49093 16.3948 5.29823 16.2021L4.16686 17.3334C4.70639 17.873 5.38104 18.0979 6.15053 18.2013C6.89702 18.3017 7.84442 18.3 9.00031 18.3V16.7ZM3.20031 12.5C3.20031 13.6559 3.19861 14.6033 3.29897 15.3498C3.40243 16.1193 3.62733 16.4279 4.16686 17.3334L5.29823 16.2021C5.10553 16.0094 4.96431 15.7286 4.88471 15.1366C4.80201 14.5215 4.80031 13.7011 4.80031 12.5H3.20031ZM15.2003 12.5C15.2003 13.7011 15.1986 14.5215 15.1159 15.1366C15.0363 15.7286 14.8951 16.0094 14.7024 16.2021L15.8338 17.3334C16.3733 16.7939 16.5982 16.1193 16.7016 15.3498C16.802 14.6033 16.8003 13.6559 16.8003 12.5H15.2003ZM11.0003 18.3C12.1562 18.3 13.1036 18.3017 13.8501 18.2013C14.6196 18.0979 15.2942 17.873 15.8338 17.3334L14.7024 16.2021C14.5097 16.3948 14.229 16.536 13.6369 16.6156C13.0218 16.6983 12.2014 16.7 11.0003 16.7V18.3Z"
                                                                fill="#F87171"></path>
                                                        </svg>
                                                    </button>
                                                    <button
                                                        class="p-2 rounded-full bg-white group transition-all duration-500 hover:bg-black flex item-center">
                                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path class="stroke-black group-hover:stroke-white"
                                                                d="M10.0161 14.9897V15.0397M10.0161 9.97598V10.026M10.0161 4.96231V5.01231"
                                                                stroke="black" stroke-width="2.5"
                                                                stroke-linecap="round">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paginación -->
                <div class="flex items-center justify-between border-t border-gray-200 px-4 py-3 sm:px-6 mt-4">
                    <!-- Mobile Pagination -->
                    <div class="flex flex-1 justify-between sm:hidden">
                        <Link v-if="permissions.prev_page_url" :href="permissions.prev_page_url" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Anterior</Link>
                        <Link v-if="permissions.next_page_url" :href="permissions.next_page_url" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Siguiente</Link>
                    </div>

                    <!-- Desktop Pagination -->
                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">Mostrando <span class="font-medium">{{ permissions.from }}</span> a <span class="font-medium">{{ permissions.to }}</span> de <span class="font-medium">{{ permissions.total }}</span> resultados</p>
                        </div>

                        <div>
                            <nav aria-label="Pagination" class="isolate inline-flex -space-x-px rounded-md shadow-sm">
                                <Link v-if="permissions.prev_page_url" :href="permissions.prev_page_url" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"><span class="sr-only">Anterior</span><svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="w-5 h-5"><path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" /></svg></Link>

                                <Link v-for="page in permissions.last_page" :key="page" :href="`?page=${page}`" :class="[
                                    'relative inline-flex items-center px-4 py-2 text-sm font-semibold focus:z-20 focus:outline-offset-0',
                                    page === permissions.current_page ? 'z-10 text-white bg-indigo-600 focus-visible:outline-2 focus-visible:outline-indigo-600' : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50'
                                ]">{{ page }}</Link>

                                <Link v-if="permissions.next_page_url" :href="permissions.next_page_url" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"><span class="sr-only">Siguiente</span><svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="w-5 h-5"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" /></svg></Link>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
