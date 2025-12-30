import { defineStore } from 'pinia';
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';

export const useNotificationStore = defineStore('notification', {
    state: () => ({
        notyf: null,
    }),

    actions: {
        // Inicializar Notyf (se llama en la app)
        initNotyf() {
            if (!this.notyf) {
                this.notyf = new Notyf({
                    duration: 3000, // 3 segundos
                    position: {
                        x: 'right',
                        y: 'top',
                    },
                    ripple: true,
                    container: document.body, // Montar en el body
                });
                
                // Mover el contenedor a document.body después de crearlo
                setTimeout(() => {
                    const container = document.querySelector('.notyf');
                    if (container && container.parentNode !== document.body) {
                        document.body.appendChild(container);
                    }
                }, 0);
            }
        },

        // Mostrar notificación de éxito
        success(message) {
            if (!this.notyf) this.initNotyf();
            this.notyf.success({
                message: message,
                duration: 999999, // Duración muy alta, se controla manualmente en los componentes
            });
        },

        // Mostrar notificación de error
        error(message) {
            if (!this.notyf) this.initNotyf();
            this.notyf.error({
                message: message,
                duration: 999999, // Duración muy alta, se controla manualmente en los componentes
            });
        },

        // Mostrar notificación personalizada
        notify(message, type = 'success') {
            if (!this.notyf) this.initNotyf();
            
            const config = {
                message: message,
                duration: 999999, // Duración muy alta, se controla manualmente en los componentes
                ripple: true,
            };

            if (type === 'success') {
                this.notyf.success(config);
            } else if (type === 'error') {
                this.notyf.error(config);
            }
        },
    },
});
