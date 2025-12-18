<script setup>
    import { ref } from 'vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import ApplicationMark from '@/Components/ApplicationMark.vue';
    import Banner from '@/Components/Banner.vue';
    import Dropdown from '@/Components/Dropdown.vue';
    import DropdownLink from '@/Components/DropdownLink.vue';
    import NavLink from '@/Components/NavLink.vue';
    import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

    defineProps({
        title: String,
    });

    const showingNavigationDropdown = ref(false);

    const switchToTeam = (team) => {
        router.put(route('current-team.update'), {
            team_id: team.id,
        }, {
            preserveState: false,
        });
    };
    
    //RUTA DASHBOARD
    const goToDashboard = () => {
        router.visit('dashboard');
    };
    
    //RUTA DEVELOPERS
    const goToDevelopers = () => {
        router.visit('developers');
    };
    
    //RUTA DEVELOPMENTS
    const goToDevelopments = () => {
        router.visit('development');
    };
    
    //RUTA LEADS
    const goToLeads = () => {
        router.visit('lead');
    };
    
    //RUTA ADMIN
    const goToAdmin = () => {
        router.visit('admin');
    };
    
    //RUTA PROFILE
    const goToProfile = () => {
        router.visit('/user/profile');
    };
    
    //RUTA LOGOUT
    const logout = () => {
        router.post(route('logout'));
    };

</script>

<template>
    <div>
        <Head :title="title" />
        
        <Banner />
        
        <div class="h-screen flex bg-gray-100">
            <aside class="sidebar sidebar--default">
                <div class="sidebar--container">
                    <div class="sidebar--logo">
                        <a href="javascript:;">
                            <img src="https://pagedone.io/asset/uploads/1701235273.png" alt="Pagedone logo image">
                        </a>
                        <a href="javascript:;" class="w-6 h-6 relative">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <g id="Menu">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M12 17.25h8.25" />
                                </g>
                            </svg>
                        </a>
                    </div>
                    <div class="w-full">
                        <div class="w-full h-8 px-3 items-center flex">
                            <h6 class="text-gray-500 text-xs font-semibold leading-4">Menu</h6>
                        </div>
                        <ul class="flex-col gap-1 flex">
                            <li>
                                <button type="button" @click="goToAdmin">
                                    <div class="flex-col flex rounded-lg p-2">
                                        <div class="h-5 gap-3 flex">
                                            <div class="relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                    <g id="Swap">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 0 1-1.125-1.125v-3.75ZM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-8.25ZM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-2.25Z" />
                                                    </g>
                                                </svg>
                                            </div>
                                            <h2 class="text-gray-500 text-sm font-medium leading-snug">Panel</h2>
                                        </div>
                                    </div>
                                </button>
                            </li>
                            <li>
                                <button type="button" @click="goToDevelopers">
                                    <div class="flex-col gap-1 flex">
                                        <div class="flex-col flex rounded-lg p-2">
                                            <div class="h-5 gap-3 flex">
                                                <div class="relative">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                        <g id="Swap">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
                                                        </g>
                                                    </svg>
                                                </div>
                                                <h2 class="text-gray-500 text-sm font-medium leading-snug">Desarrolladores</h2>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </li>
                            <li>
                                <button type="button" @click="goToDevelopments">
                                    <div class="flex-col gap-1 flex">
                                        <div class="flex-col flex rounded-lg p-2">
                                            <div class="h-5 gap-3 flex">
                                                <div class="relative">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                        <g id="Swap">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                                                        </g>
                                                    </svg>
                                                </div>
                                                <h2 class="text-gray-500 text-sm font-medium leading-snug">Desarrollos</h2>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </li>
                            <li>
                                <button type="button" @click="goToLeads">
                                    <div class="flex-col gap-1 flex">
                                        <div class="flex-col flex rounded-lg p-2">
                                            <div class="h-5 gap-3 flex">
                                                <div class="relative">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                        <g id="Shop">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                                        </g>
                                                    </svg>
                                                </div>
                                                <h2 class="text-gray-500 text-sm font-medium leading-snug">Leads</h2>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </li>
                            <li>
                                <button type="button" @click="goToAdmin">
                                    <div class="flex-col gap-1 flex">
                                        <div class="flex-col flex rounded-lg p-2">
                                            <div class="h-5 gap-3 flex">
                                                <div class="relative">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                        <g id="Screenshot">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0-5.571 3-5.571-3" />
                                                        </g>
                                                    </svg>
                                                </div>
                                                <h2 class="text-gray-500 text-sm font-medium leading-snug">Admin</h2>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="sidebar--down">
                        <div class="h-8 px-3 items-center inline-flex">
                            <h6 class=" text-xs font-semibold leading-4">Cuenta</h6>
                        </div>
                        <ul class="flex-col gap-1 flex">
                            <li>
                                <button type="button" @click="goToProfile">
                                    <div class="flex-col gap-1 flex">
                                        <div class="flex-col flex rounded-lg p-2">
                                            <div class="h-5 gap-3 flex">
                                                <div class="relative">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                        <g id="User Circle">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                        </g>
                                                    </svg>
                                                </div>
                                                <h2 class="text-gray-500 text-sm font-medium leading-snug">Perfil</h2>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </li>
                            <li>
                                <button @click.prevent="logout" type="button">
                                    <div class="flex-col gap-1 flex">
                                        <div class="flex-col flex rounded-lg p-2">
                                            <div class="h-5 gap-3 flex">
                                                <div class="relative">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                        <g id="Logout">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                                                        </g>
                                                    </svg>
                                                </div>
                                                <h2 class="text-gray-500 text-sm font-medium leading-snug">Cerrar sessión</h2>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </aside>
            <div class="flex-1 overflow-y-auto p-4">
                <main class="min-h-full">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
