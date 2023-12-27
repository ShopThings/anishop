import {createRouter, createWebHistory} from "vue-router";
import PageNotFound from "../views/PageNotFound.vue";
import {useAdminAuthStore, useUserAuthStore} from "../store/StoreUserAuth.js";
import {adminRoutes} from "./admin-routes.js";
import {userRoutes} from "./user-routes.js";

const idRouteRegex = '(.*\\-\\d|\\d+)'

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
        meta: {layout: 'layout-empty'},
    },
    {
        path: '/signup',
        name: 'signup',
        component: () => import('../views/PageSignup.vue'),
        meta: {layout: 'layout-empty'},
    },
    {
        path: '/forget-password',
        name: 'forget_password',
        component: () => import('../views/PageForgetPassword.vue'),
        meta: {layout: 'layout-empty'},
    },
    {
        path: '/pages/:url([a-z]+[a-z\/\-][a-z]+)',
        name: 'pages',
        component: () => import('../views/PagePages.vue'),
        meta: {layout: 'layout-guest'},
    },

    {
        path: '/blog/',
        name: 'blogs',
        component: () => import('../views/PageBlogs.vue'),
        meta: {layout: 'layout-blog'},
    },
    {
        path: '/blog/search/:searchText',
        redirect: to => {
            return {
                name: 'blog.search',
                query: {
                    q: to.params.searchText,
                },
            }
        },
    },
    {
        path: '/blog/tag/:searchText',
        redirect: to => {
            return {
                name: 'blog.search',
                query: {
                    tag: to.params.searchText,
                },
            }
        },
    },
    {
        path: '/blog/search',
        name: 'blog.search',
        component: () => import('../views/PageSearchBlog.vue'),
        meta: {layout: 'layout-guest'},
    },
    {
        path: '/blog/:id' + idRouteRegex,
        name: 'blog.detail',
        component: () => import('../views/PageBlogDetail.vue'),
        meta: {layout: 'layout-blog'},
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
    {
        path: '/product/:id' + idRouteRegex,
        name: 'product.detail',
        component: () => import('../views/PageProductDetail.vue'),
        meta: {layout: 'layout-guest'},
    },

    {
        path: '/brands',
        name: 'brands',
        component: () => import('../views/PageBrands.vue'),
        meta: {layout: 'layout-guest'},
    },

    {
        path: '/faq',
        name: 'faq',
        component: () => import('../views/PageFaq.vue'),
        meta: {layout: 'layout-guest'},
    },
    {
        path: '/contact',
        name: 'contact',
        component: () => import('../views/PageContact.vue'),
        meta: {layout: 'layout-guest'},
    },

    {
        path: '/cart',
        name: 'cart',
        component: () => import('../views/PageCart.vue'),
        meta: {
            layout: 'layout-guest',
            title: 'سبر خرید',
            breadcrumb: [
                {
                    name: 'خانه',
                    link: 'home',
                },
                {
                    name: 'سبد خرید',
                },
            ],
        },
    },
    {
        path: '/checkout',
        name: 'checkout',
        component: () => import('../views/PageCheckout.vue'),
        meta: {
            // requiresAuth: true,
            layout: 'layout-guest',
        },
    },
    {
        path: '/result',
        name: 'result',
        component: () => import('../views/PageResult.vue'),
        meta: {layout: 'layout-guest'},
    },
    //

    {
        path: '/logout',
        name: 'logout',
        beforeEnter(to, from, next) {
            const store = useUserAuthStore()
            store.logout()
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
        const store = useUserAuthStore()
        const adminStore = useAdminAuthStore()

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
