<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const isActive = (path) => {
    return page.url.startsWith(`/${path}`);
};

const canAccessAdmin = () => {
    const user = page.props.auth?.user;
    if (!user) return false;
    const allowedRoles = ['Admin', 'DevAdmin'];
    return user.roles && user.roles.some(role => allowedRoles.includes(role.name));
};


// Generar iniciales para el avatar
const userInitials = computed(() => {
    const name = page.props.auth?.user?.name?.charAt(0) || '';
    const lastname = page.props.auth?.user?.lastname?.charAt(0) || '';
    return (name + lastname).toUpperCase();
});

// Colores predefinidos basados en el ID del usuario
const avatarColors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899'];
const avatarBgColor = computed(() => {
    const userId = page.props.auth?.user?.id || 1;
    return avatarColors[userId % avatarColors.length];
});

// Obtener la URL de la foto de perfil
const profilePhotoUrl = computed(() => {
    if (page.props.auth?.user?.profile_photo_path) {
        return `/storage/${page.props.auth.user.profile_photo_path}`;
    }
    return null;
});


defineProps({
    title: String,
});

//RUTA DASHBOARD
const goToDashboard = () => {
    router.visit('/dashboard');
};

//RUTA DEVELOPERS
const goToDevelopers = () => {
    router.visit('/developers');
};

//RUTA DEVELOPMENTS
const goToDevelopments = () => {
    router.visit('/development');
};

//RUTA LEADS
const goToLeads = () => {
    router.visit('/lead');
};

//RUTA ADMIN
const goToAdmin = () => {
    router.visit('/admin');
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

        <div class="body--global">
            <aside class="sidebar">
                <div class="sidebar--container">
                    <div class="sidebar--logo">
                        <a href="javascript:;">
                            <img src="/images/logo2.png" alt="Ram logo">
                        </a>
                        <a href="javascript:;" class="w-6 h-6 relative">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <g id="Menu">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.75 6.75h16.5M3.75 12h16.5M12 17.25h8.25" />
                                </g>
                            </svg>
                        </a>
                    </div>
                    <div class="w-full">
                        <div class="w-full h-8 px-3 items-center flex">
                            <h6 class="text-xs font-semibold leading-4">Menu</h6>
                        </div>
                        <ul class="flex-col gap-1 flex">
                            <li>
                                <button type="button" @click="goToDashboard">
                                    <div class="flex-col flex rounded-lg p-2">
                                        <div class="h-5 gap-3 flex">
                                            <div class="relative">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="size-5">
                                                    <g id="Swap">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 0 1-1.125-1.125v-3.75ZM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-8.25ZM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 0 1-1.125-1.125v-2.25Z" />
                                                    </g>
                                                </svg>
                                            </div>
                                            <h2 class="text-sm font-medium leading-snug">Panel</h2>
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
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-5">
                                                        <g id="Swap">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
                                                        </g>
                                                    </svg>
                                                </div>
                                                <h2 class="text-sm font-medium leading-snug">Promotores</h2>
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
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-5">
                                                        <g id="Swap">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                                                        </g>
                                                    </svg>
                                                </div>
                                                <h2 class="text-sm font-medium leading-snug">Proyectos</h2>
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
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-5">
                                                        <g id="Shop">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                                        </g>
                                                    </svg>
                                                </div>
                                                <h2 class="text-sm font-medium leading-snug">Leads</h2>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </li>
                            <li v-if="canAccessAdmin()">
                                <button type="button" @click="goToAdmin">
                                    <div class="flex-col gap-1 flex">
                                        <div class="flex-col flex rounded-lg p-2">
                                            <div class="h-5 gap-3 flex">
                                                <div class="relative">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-5">
                                                        <g id="Screenshot">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0-5.571 3-5.571-3" />
                                                        </g>
                                                    </svg>
                                                </div>
                                                <h2 class="text-sm font-medium leading-snug">Admin</h2>
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
                        <button type="button" @click="goToProfile"
                            class="w-full px-4 py-4 border-b border-white/10 bg-white/10 hover:bg-white/20 transition  text-left rounded-xl">
                            <div class="flex items-center gap-4">
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    <img v-if="profilePhotoUrl" :src="profilePhotoUrl"
                                        :alt="`Avatar de ${page.props.auth?.user?.name}`"
                                        class="w-11 h-11 rounded-full object-cover" />
                                    <div v-else
                                        class="w-11 h-11 rounded-full flex items-center justify-center text-white font-bold text-sm"
                                        :style="{ backgroundColor: avatarBgColor }">
                                        {{ userInitials }}
                                    </div>
                                </div>

                                <!-- Info usuario -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-white truncate">
                                        {{ page.props.auth?.user?.name || 'N/A' }}
                                        {{ page.props.auth?.user?.lastname || '' }}
                                    </p>
                                    <p class="text-xs text-white/80 truncate">
                                        {{ page.props.auth?.user?.roles?.[0]?.name || 'Sin rol' }}
                                    </p>
                                </div>

                                <!-- Botón salir -->
                                <button @click.stop.prevent="logout" type="button" class="flex-shrink-0 p-3 
                   bg-white/10 hover:bg-red-500/20 
                   rounded-full transition" title="Salir">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.8" stroke="currentColor" class="w-6 h-6 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                                    </svg>
                                </button>
                            </div>
                        </button>

                    </div>
                </div>
            </aside>
            <div class="body">
                <div class="body--container">
                    <main>
                        <slot />
                    </main>
                </div>
            </div>
        </div>
    </div>
</template>
