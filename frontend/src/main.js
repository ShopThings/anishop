import {createApp} from 'vue';
import { createHead } from '@unhead/vue'
import {createPinia} from 'pinia';
import Toast, {POSITION} from "vue-toastification";
import FloatingVue from 'floating-vue';
import VueLazyLoad from 'vue3-lazyload';
import Vue3ColorPicker from "vue3-colorpicker";
import Vue3PersianDatetimePicker from "vue3-persian-datetime-picker";
import VueEasyLightbox from "vue-easy-lightbox";

import "vue-toastification/dist/index.css";
import 'floating-vue/dist/style.css';
import 'overlayscrollbars/overlayscrollbars.css';
import '@imengyu/vue3-context-menu/lib/vue3-context-menu.css';
import "vue3-colorpicker/style.css";
import "@vueform/slider/themes/default.css";
import 'vue-easy-lightbox/external-css/vue-easy-lightbox.css'
import './main.css';

import router from "./router";
import App from './App.vue';
import ContextMenu from '@imengyu/vue3-context-menu';
import LayoutGuest from './components/layouts/LayoutGuest.vue';
import LayoutUser from './components/layouts/LayoutUser.vue';
import LayoutAdmin from './components/layouts/LayoutAdmin.vue';
import LayoutEmpty from "./components/layouts/LayoutEmpty.vue";

const head = createHead()
const pinia = createPinia()

createApp(App)
    .use(head)
    .use(pinia)
    .use(router)
    .use(FloatingVue)
    .use(VueLazyLoad)
    .use(ContextMenu)
    .use(Vue3ColorPicker)
    .use(VueEasyLightbox)
    .use(Vue3PersianDatetimePicker, {
        name: 'DatePicker',
        props: {
            dir: 'rtl',
            clearable: true,
            format: 'YYYY-MM-DD HH:mm',
            displayFormat: 'jYYYY-jMM-jDD HH:mm',
            editable: false,
            inputClass: 'block w-full rounded-md border-0 py-3 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
            placeholder: 'تاریخ مورد نظر خود را انتخاب نمایید',
            altFormat: 'YYYY-MM-DD HH:mm',
            color: '#4F46E5',
            autoSubmit: false,
            compactTime: true,
            type: 'datetime',
            localConfig: {
                ar: {
                    dow: 0,
                    dir: 'rtl',
                    displayFormat: vm => {
                        switch (vm.type) {
                            case 'date':
                                return 'YYYY/MM/DD'
                            case 'datetime':
                                return 'YYYY/MM/DD HH:mm'
                            case 'year':
                                return 'YYYY'
                            case 'month':
                                return 'MM'
                            case 'yearmonth':
                                return 'YY/MM'
                            case 'time':
                                return 'HH:mm'
                        }
                    },
                    lang: {
                        label: 'AR',
                        submit: 'اختيار',
                        cancel: 'إلغاء',
                        now: 'الآن',
                        nextMonth: 'الشهر القادم',
                        prevMonth: 'الشهر الماضي'
                    }
                },
                en: {
                    dow: 0,
                    dir: 'ltr',
                    displayFormat: vm => {
                        switch (vm.type) {
                            case 'date':
                                return 'YYYY/MM/DD'
                            case 'datetime':
                                return 'YYYY/MM/DD HH:mm'
                            case 'year':
                                return 'YYYY'
                            case 'month':
                                return 'MM'
                            case 'yearmonth':
                                return 'YY/MM'
                            case 'time':
                                return 'HH:mm'
                        }
                    }
                }
            },
        }
    })
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
