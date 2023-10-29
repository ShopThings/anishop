import {useUserStore} from "../store/StoreUserAuth.js";
import {useRequest} from "../composables/api-request.js";
import {apiRoutes} from "./api-routes.js";

export const userRoutes = {
    path: '/user',
    children: [
        {
            path: 'logout',
            name: 'user.logout',
            beforeEnter(to, from, next) {
                const store = useUserStore()
                useRequest(apiRoutes.user.logout, {method: 'POST'}, {
                    success: () => {
                        store.$reset()
                    },
                })
                if (from.meta.requiresAuth) {
                    const pushObj = {name: 'user.login'}

                    if (to.query.redirect) pushObj.query = {redirect: to.query.redirect}

                    return next(pushObj);
                }
                location.reload();
            },
        },
        {
            path: 'home',
            alias: [''],
            name: 'user.home',
            component: () => import('../views/user/PageHome.vue'),
            meta: {
                title: 'داشبورد',
            },
        },

        {
            path: 'addresses',
            children: [
                {
                    path: '',
                    name: 'user.addresses',
                    component: () => import('../views/user/PageAddresses.vue'),
                    meta: {
                        title: 'مدیریت آدرس‌ها',
                        breadcrumb: [
                            {
                                name: 'آدرس‌ها',
                            },
                        ],
                    },
                },
                {
                    path: 'new',
                    name: 'user.address.add',
                    component: () => import('../views/user/PageAddressAdd.vue'),
                    meta: {
                        title: 'افزودن آدرس',
                        breadcrumb: [
                            {
                                name: 'آدرس‌ها',
                                link: 'user.addresses',
                            },
                            {
                                name: 'افزودن آدرس',
                            },
                        ],
                    },
                },
            ],
        },
        {
            path: 'address/:id(\\d+)',
            name: 'user.address.edit',
            component: () => import('../views/user/PageAddressEdit.vue'),
            meta: {
                title: 'ویرایش آدرس',
                breadcrumb: [
                    {
                        name: 'آدرس‌ها',
                        link: 'user.addresses',
                    },
                    {
                        name: 'ویرایش آدرس',
                    },
                ],
            },
        },

        {
            path: 'comments',
            name: 'user.comments',
            component: () => import('../views/user/PageComments.vue'),
            meta: {
                title: 'مشاهده دیدگاه‌ها',
                breadcrumb: [
                    {
                        name: 'دیدگاه‌ها',
                    },
                ],
            },
        },
        {
            path: 'comment/:id(\\d+)',
            name: 'user.comment.detail',
            component: () => import('../views/user/PageCommentDetail.vue'),
            meta: {
                title: 'جزئیات دیدگاه',
                breadcrumb: [
                    {
                        name: 'دیدگاه‌ها',
                        link: 'users.comments',
                    },
                    {
                        name: 'دیدگاه شما',
                    },
                ],
            },
        },

        {
            path: 'favorite-products',
            name: 'user.favorite_products',
            component: () => import('../views/user/PageFavoriteProducts.vue'),
            meta: {
                title: 'محصولات مورد علاقه من',
                breadcrumb: [
                    {
                        name: 'مورد علاقه‌ها',
                    },
                ],
            },
        },

        {
            path: 'profile',
            name: 'user.profile',
            component: () => import('../views/user/PageProfile.vue'),
            meta: {
                title: 'اطلاعات حساب',
                breadcrumb: [
                    {
                        name: 'مشاهده اطلاعات حساب',
                    },
                ],
            },
        },

        {
            path: 'orders',
            name: 'user.orders',
            component: () => import('../views/user/PageOrders.vue'),
            meta: {
                title: 'مدیریت سفارشات',
                breadcrumb: [
                    {
                        name: 'سفارشات',
                    },
                ],
            },
        },
        {
            path: 'order/:code(\\d+)',
            name: 'user.order.detail',
            component: () => import('../views/user/PageOrderDetail.vue'),
            meta: {
                title: 'جزئیات سفارش',
                breadcrumb: [
                    {
                        name: 'سفارشات',
                        link: 'users.orders',
                    },
                    {
                        name: 'جزئیات سفارش',
                    },
                ],
            },
        },

        {
            path: 'return-orders',
            name: 'user.return_orders',
            component: () => import('../views/user/PageReturnOrders.vue'),
            meta: {
                title: 'مدیریت سفارشات مرجوع شده',
                breadcrumb: [
                    {
                        name: 'سفارشات مرجوع شده',
                    },
                ],
            },
        },
        {
            path: 'return-order/:code(\\d+)',
            name: 'user.return_order.detail',
            component: () => import('../views/user/PageReturnOrderDetail.vue'),
            meta: {
                title: 'جزئیات سفارش مرجوع شده',
                breadcrumb: [
                    {
                        name: 'سفارشات مرجوع شده',
                        link: 'users.return_orders',
                    },
                    {
                        name: 'جزئیات سفارش مرجوع شده',
                    },
                ],
            },
        },

        {
            path: 'notifications',
            name: 'user.notifications',
            component: () => import('../views/user/PageNotifications.vue'),
            meta: {
                title: 'اعلانات',
                breadcrumb: [
                    {
                        name: 'مشاهده اعلانات',
                    },
                ],
            },
        },
    ],
    meta: {
        requiresAuth: false,
        layout: 'layout-user',
    },
}
