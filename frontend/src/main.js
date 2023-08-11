import {createApp} from 'vue';
import {createPinia} from 'pinia';
import Toast, {POSITION} from "vue-toastification";
import FloatingVue from 'floating-vue';
import VueLazyLoad from 'vue3-lazyload';

import "vue-toastification/dist/index.css";
import 'floating-vue/dist/style.css';
import 'overlayscrollbars/overlayscrollbars.css';
import '@imengyu/vue3-context-menu/lib/vue3-context-menu.css';
import './main.css';

import router from "./router";
import App from './App.vue';
import ContextMenu from '@imengyu/vue3-context-menu';
import LayoutGuest from './components/layouts/LayoutGuest.vue';
import LayoutUser from './components/layouts/LayoutUser.vue';
import LayoutAdmin from './components/layouts/LayoutAdmin.vue';
import LayoutEmpty from "./components/layouts/LayoutEmpty.vue";

const pinia = createPinia()

createApp(App)
    .use(pinia)
    .use(router)
    .use(FloatingVue)
    .use(VueLazyLoad)
    .use(ContextMenu)
    .use(Toast, {
        transition: 'Vue-Toastification__fade',
        rtl: true,
        position: POSITION.TOP_LEFT,
        timeout: 5000,
        // Enqueues toasts of the same type, preventing duplicates
        filterToasts: (toasts) => {
            // Keep track of existing types
            const types = {};
            return toasts.reduce((aggToasts, toast) => {
                // Check if type was not seen before
                if (!types[toast.type] || parseInt(types[toast.type], 10) < 2) {
                    aggToasts.push(toast);
                    types[toast.type] = types[toast.type] ? (parseInt(types[toast.type], 10) + 1) : 1;
                }
                return aggToasts;
            }, []);
        }
    })
    .component('layout-guest', LayoutGuest)
    .component('layout-user', LayoutUser)
    .component('layout-admin', LayoutAdmin)
    .component('layout-empty', LayoutEmpty)
    .mount('#app')
