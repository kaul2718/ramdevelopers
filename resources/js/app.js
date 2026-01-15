import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createPinia } from 'pinia';

// Aplicar tema guardado antes de renderizar
const applyInitialTheme = () => {
    const savedTheme = localStorage.getItem('theme');
    const html = document.documentElement;
    
    if (savedTheme === 'light') {
        html.classList.add('light');
        html.classList.remove('dark');
    } else if (savedTheme === 'dark') {
        html.classList.remove('light');
        html.classList.add('dark');
    } else {
        // Usar preferencia del sistema
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            html.classList.remove('light');
            html.classList.add('dark');
        } else {
            html.classList.add('light');
            html.classList.remove('dark');
        }
    }
};

// Aplicar tema antes de que la app se monte
applyInitialTheme();

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(createPinia())
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
