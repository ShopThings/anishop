import {createRouter, createWebHistory} from "vue-router";
import PageNotFound from "../views/PageNotFound.vue";
import {useAdminStore, useUserStore} from "../store/StoreUserAuth.js";
import {adminRoutes} from "./admin-routes.js";
import {userRoutes} from "./user-routes.js";
import {useRequest} from "../composables/api-request.js";
import {apiRoutes} from "./api-routes.js";

const routes = [
    {...adminRoutes},
    {
        path: '/admin/file-manager/editor',
        name: 'admin.file_manager.editor',
        component: () => import('../views/admin/PageFileManagerEditor.vue'),
        meta: {
            title: 'مدیریت فایل‌ها',
            requiresAuth: true,
            isAdminRoute: true,
        },
        layout: 'layout-empty',
    },

    {...userRoutes},

    // guest routes
    {
        path: '/home',
        alias: [''],
        name: 'home',
        component: () => import('../views/PageHome.vue'),
        meta: {layout: 'layout-guest'},
    },
    {
        path: '/login',
        name: 'login',
        component: () => import('../views/PageLogin.vue'),
        meta: {layout: 'layout-guest'},
    },
    {
        path: '/signup',
        name: 'signup',
        component: () => import('../views/PageSignup.vue'),
        meta: {layout: 'layout-guest'},
    },
    {
        path: '/forget-password',
        children: [
            {
                path: 'step1',
                alias: [''],
                name: 'forget_password.step1',
                component: () => import('../views/PageForgetPasswordStep1.vue'),
            },
        ],
        meta: {layout: 'layout-guest'},
    },
    {
        // /search/screens -> /search?q=screens
        path: '/search/:searchText',
        redirect: to => {
            return {
                name: 'search',
                query: {
                    q: to.params.searchText,
                },
            }
        },
    },
    {
        path: '/search',
        name: 'search',
        component: () => import('../views/PageSearch.vue'),
        meta: {layout: 'layout-guest'},
    },
    //

    {
        path: '/logout',
        name: 'logout',
        beforeEnter(to, from, next) {
            const store = useUserStore()
            useRequest(apiRoutes.user.logout, {method: 'POST'}, {
                success: () => {
                    store.$reset()
                },
            })
            if (from.meta.requiresAuth) {
                return next('/');
            }
            location.reload();
        },
    },

    {
        path: "/:pathMatch(.*)*",
        name: "not.found",
        component: PageNotFound,
    },
];

const index = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        return savedPosition || {top: 0}
    },
});

index.beforeEach((to, from, next) => {
    if (
        to.matched.some(record => record.meta.requiresAuth) &&
        to.name !== 'login' && to.name !== 'admin.login' &&
        to.name !== 'logout' && to.name !== 'admin.logout'
    ) {
        const store = useUserStore()
        const adminStore = useAdminStore()

        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (to.matched.some(record => record.meta.isAdminRoute)) {
            if (!adminStore.getToken) {
                adminStore.$reset()
                next({
                    name: 'admin.login',
                    query: {redirect: to.fullPath}
                })
            } else {
                next()
            }
        } else if (!store.getToken) {
            store.$reset()
            next({
                name: 'login',
                query: {redirect: to.fullPath}
            })
        } else {
            next()
        }
    } else {
        next() // make sure to always call next()!
    }
})

export default index;
