import { defineStore } from 'pinia'
import { Notyf } from 'notyf'
import 'notyf/notyf.min.css'

export const useNotificationStore = defineStore('notification', {
    state: () => ({
        notyf: null,
    }),

    actions: {
        init() {
            if (this.notyf) return

            this.notyf = new Notyf({
                duration: 3000,
                position: {
                    x: 'right',
                    y: 'top',
                },
                ripple: true,
                dismissible: false,
            })
        },

        success(message) {
            this.init()
            this.notyf.success(message)
        },

        error(message) {
            this.init()
            this.notyf.error(message)
        },
    },
})
