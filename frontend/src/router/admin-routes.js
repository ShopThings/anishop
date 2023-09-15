import {useAdminStore} from "../store/StoreUserAuth.js";
import {useRequest} from "../composables/api-request.js";
import {apiRoutes} from "./api-routes.js";

export const adminRoutes = {
    path: '/admin',
    children: [
        {
            path: 'login',
            name: 'admin.login',
            component: () => import('../views/admin/PageLogin.vue'),
            meta: {
                layout: 'layout-empty',
            },
        },
        {
            path: 'logout',
            name: 'admin.logout',
            beforeEnter(to, from, next) {
                const store = useAdminStore()
                useRequest(apiRoutes.admin.logout, {method: 'POST'}, {
                    success: () => {
                        store.$reset()
                    },
                })
                if (from.meta.requiresAuth) {
                    const pushObj = {name: 'admin.login'}

                    if (to.query.redirect) pushObj.query = {redirect: to.query.redirect}

                    return next(pushObj);
                }
                location.reload();
            },
        },
        {
            path: 'home',
            alias: [''],
            name: 'admin.home',
            component: () => import('../views/admin/PageHome.vue'),
        },
        {
            path: 'users',
            children: [
                {
                    path: '',
                    name: 'admin.users',
                    component: () => import('../views/admin/user/PageUsers.vue'),
                    meta: {
                        title: 'مدیریت کاربران',
                        breadcrumb: [
                            {
                                name: 'کاربران',
                            },
                        ],
                    },
                },
                {
                    path: 'new',
                    name: 'admin.user.add',
                    component: () => import('../views/admin/user/PageUserAdd.vue'),
                    meta: {
                        title: 'افزودن کاربر',
                        breadcrumb: [
                            {
                                name: 'کاربران',
                                link: 'admin.users',
                            },
                            {
                                name: 'افزودن کاربر',
                            },
                        ],
                    },
                },
            ],
        },
        {
            path: 'user/:id(\\d+)',
            name: 'admin.user',
            children: [
                {
                    path: 'profile',
                    alias: [''],
                    name: 'admin.user.profile',
                    component: () => import('../views/admin/user/PageUserProfile.vue'),
                    meta: {
                        title: 'پروفایل کاربر',
                        breadcrumb: [
                            {
                                name: 'کاربران',
                                link: 'admin.users',
                            },
                            {
                                name: 'مشاهده پروفایل',
                            },
                        ],
                    },
                },
                {
                    path: 'addresses',
                    name: 'admin.user.addresses',
                    component: () => import('../views/admin/user/PageUserAddresses.vue'),
                    meta: {
                        title: 'آدرس‌های کاربر',
                        breadcrumb: [
                            {
                                name: 'کاربران',
                                link: 'admin.users',
                            },
                            {
                                name: 'مشاهده پروفایل',
                                link: 'admin.user.profile',
                                params: ['id'],
                            },
                            {
                                name: 'مشاهده آدرس‌ها',
                            },
                        ],
                    },
                },
                {
                    path: 'purchases',
                    name: 'admin.user.purchases',
                    component: () => import('../views/admin/user/PageUserPurchases.vue'),
                    meta: {
                        title: 'سفارشات کاربر',
                        breadcrumb: [
                            {
                                name: 'کاربران',
                                link: 'admin.users',
                            },
                            {
                                name: 'مشاهده پروفایل',
                                link: 'admin.user.profile',
                                params: ['id'],
                            },
                            {
                                name: 'مشاهده سفارشات',
                            },
                        ],
                    },
                },
                {
                    path: 'carts',
                    name: 'admin.user.carts',
                    component: () => import('../views/admin/user/PageUserCarts.vue'),
                    meta: {
                        title: 'سبد خرید کاربر',
                        breadcrumb: [
                            {
                                name: 'کاربران',
                                link: 'admin.users',
                            },
                            {
                                name: 'مشاهده پروفایل',
                                link: 'admin.user.profile',
                                params: ['id'],
                            },
                            {
                                name: 'مشاهده سبدهای خرید',
                            },
                        ],
                    },
                },
                {
                    path: 'favorite-products',
                    name: 'admin.user.favorite_products',
                    component: () => import('../views/admin/user/PageUserFavoriteProducts.vue'),
                    meta: {
                        title: 'محصولات مورد علاقه کاربر',
                        breadcrumb: [
                            {
                                name: 'کاربران',
                                link: 'admin.users',
                            },
                            {
                                name: 'مشاهده پروفایل',
                                link: 'admin.user.profile',
                                params: ['id'],
                            },
                            {
                                name: 'مشاهده محصولات مورد علاقه',
                            },
                        ],
                    },
                },
            ],
        },

        {
            path: 'payment-methods',
            children: [
                {
                    path: '',
                    name: 'admin.payment_methods',
                    component: () => import('../views/admin/PagePaymentMethods.vue'),
                    meta: {
                        title: 'روش‌های پرداخت',
                        breadcrumb: [
                            {
                                name: 'روش‌های پرداخت',
                            },
                        ],
                    },
                },
                {
                    path: 'new',
                    name: 'admin.payment_method.add',
                    component: () => import('../views/admin/PagePaymentMethodAdd.vue'),
                    meta: {
                        title: 'ایجاد روش پرداخت',
                        breadcrumb: [
                            {
                                name: 'روش‌های پرداخت',
                                link: 'admin.payment_methods',
                            },
                            {
                                name: 'ایجاد روش پرداخت',
                            },
                        ],
                    },
                },
            ],
        },
        {
            path: 'payment-method/:id(\\d+)',
            name: 'admin.payment_method.edit',
            component: () => import('../views/admin/PagePaymentMethodEdit.vue'),
            meta: {
                title: 'ویرایش روش پرداخت',
                breadcrumb: [
                    {
                        name: 'روش‌های پرداخت',
                        link: 'admin.payment_methods',
                    },
                    {
                        name: 'ویرایش روش پرداخت',
                    },
                ],
            },
        },

        {
            path: 'colors',
            children: [
                {
                    path: '',
                    name: 'admin.colors',
                    component: () => import('../views/admin/product/PageColors.vue'),
                    meta: {
                        title: 'مدیریت رنگ‌ها',
                        breadcrumb: [
                            {
                                name: 'رنگ‌ها',
                            },
                        ],
                    },
                },
                {
                    path: 'new',
                    name: 'admin.color.add',
                    component: () => import('../views/admin/product/PageColorAdd.vue'),
                    meta: {
                        title: 'افزودن رنگ',
                        breadcrumb: [
                            {
                                name: 'مدیریت رنگ‌ها',
                                link: 'admin.colors',
                            },
                            {
                                name: 'افزودن رنگ',
                            },
                        ],
                    },
                },
            ],
        },
        {
            path: 'color/:id(\\d+)',
            name: 'admin.color.edit',
            component: () => import('../views/admin/product/PageColorEdit.vue'),
            meta: {
                title: 'ویرایش رنگ',
                breadcrumb: [
                    {
                        name: 'مدیریت رنگ‌ها',
                        link: 'admin.colors',
                    },
                    {
                        name: 'ویرایش رنگ',
                    },
                ],
            },
        },

        {
            path: 'brands',
            children: [
                {
                    path: '',
                    name: 'admin.brands',
                    component: () => import('../views/admin/product/PageBrands.vue'),
                    meta: {
                        title: 'مدیریت برندها',
                        breadcrumb: [
                            {
                                name: 'برندها',
                            },
                        ],
                    },
                },
                {
                    path: 'new',
                    name: 'admin.brand.add',
                    component: () => import('../views/admin/product/PageBrandAdd.vue'),
                    meta: {
                        title: 'ایجاد برند',
                        breadcrumb: [
                            {
                                name: 'برندها',
                                link: 'admin.brands',
                            },
                            {
                                name: 'ایجاد برند',
                            },
                        ],
                    },
                },
            ],
        },
        {
            path: 'brand/:id(\\d+)',
            name: 'admin.brand.edit',
            component: () => import('../views/admin/product/PageBrandEdit.vue'),
            meta: {
                title: 'ویرایش برند',
                breadcrumb: [
                    {
                        name: 'برندها',
                        link: 'admin.brands',
                    },
                    {
                        name: 'ویرایش برند',
                    },
                ],
            },
        },

        {
            path: 'categories',
            children: [
                {
                    path: '',
                    name: 'admin.categories',
                    component: () => import('../views/admin/product/PageCategories.vue'),
                    meta: {
                        title: 'مدیریت دسته‌بندی‌ها',
                        breadcrumb: [
                            {
                                name: 'دسته‌بندی‌ها',
                            },
                        ],
                    },
                },
                {
                    path: 'new',
                    name: 'admin.category.add',
                    component: () => import('../views/admin/product/PageCategoryAdd.vue'),
                    meta: {
                        title: 'افزودن دسته‌بندی',
                        breadcrumb: [
                            {
                                name: 'دسته‌بندی‌ها',
                                link: 'admin.categories',
                            },
                            {
                                name: 'افزودن دسته‌بندی',
                            },
                        ],
                    },
                },
                {
                    path: 'image',
                    name: 'admin.category_images',
                    component: () => import('../views/admin/product/PageCategoryImages.vue'),
                    meta: {
                        title: 'تصاویر دسته‌بندی‌ها',
                        breadcrumb: [
                            {
                                name: 'دسته‌بندی‌ها',
                                link: 'admin.categories',
                            },
                            {
                                name: 'تصاویر دسته‌بندی‌ها',
                            },
                        ],
                    },
                },
            ],
        },
        {
            path: 'category/:id(\\d+)',
            name: 'admin.category.edit',
            component: () => import('../views/admin/product/PageCategoryEdit.vue'),
            meta: {
                title: 'ویرایش دسته‌بندی',
                breadcrumb: [
                    {
                        name: 'دسته‌بندی‌ها',
                        link: 'admin.categories',
                    },
                    {
                        name: 'ویرایش دسته‌بندی',
                    },
                ],
            },
        },

        {
            path: 'festivals',
            children: [
                {
                    path: '',
                    name: 'admin.festivals',
                    component: () => import('../views/admin/shop/PageFestivals.vue'),
                    meta: {
                        title: 'مدیریت جشنواره‌ها',
                        breadcrumb: [
                            {
                                name: 'جشنواره‌ها',
                            },
                        ],
                    },
                },
                {
                    path: 'new',
                    name: 'admin.festival.add',
                    component: () => import('../views/admin/shop/PageFestivalAdd.vue'),
                    meta: {
                        title: 'ایجاد جشنواره',
                        breadcrumb: [
                            {
                                name: 'جشنواره‌ها',
                                link: 'admin.festivals',
                            },
                            {
                                name: 'ایجاد جشنواره',
                            },
                        ],
                    },
                },
            ],
        },
        {
            path: 'festival/:id(\\d+)',
            children: [
                {
                    path: '',
                    name: 'admin.festival.edit',
                    component: () => import('../views/admin/shop/PageFestivalEdit.vue'),
                    meta: {
                        title: 'ویرایش جشنواره',
                        breadcrumb: [
                            {
                                name: 'جشنواره‌ها',
                                link: 'admin.festivals',
                            },
                            {
                                name: 'ویرایش جشنواره',
                            },
                        ],
                    },
                },
                {
                    path: 'products',
                    name: 'admin.festival.products',
                    component: () => import('../views/admin/shop/PageFestivalProducts.vue'),
                    meta: {
                        title: 'ویرایش محصولات جشنواره',
                        breadcrumb: [
                            {
                                name: 'جشنواره‌ها',
                                link: 'admin.festivals',
                            },
                            {
                                name: 'ویرایش محصولات جشنواره',
                            },
                        ],
                    },
                },
            ],
        },

        {
            path: 'units',
            children: [
                {
                    path: '',
                    name: 'admin.units',
                    component: () => import('../views/admin/product/PageUnits.vue'),
                    meta: {
                        title: 'مدیریت واحد محصول',
                        breadcrumb: [
                            {
                                name: 'واحدها',
                            },
                        ],
                    },
                },
                {
                    path: 'new',
                    name: 'admin.unit.add',
                    component: () => import('../views/admin/product/PageUnitAdd.vue'),
                    meta: {
                        title: 'افزودن واحد محصول',
                        breadcrumb: [
                            {
                                name: 'واحدها',
                                link: 'admin.units',
                            },
                            {
                                name: 'افزودن واحد محصول',
                            },
                        ],
                    },
                },
            ],
        },
        {
            path: 'unit/:id(\\d+)',
            name: 'admin.unit.edit',
            component: () => import('../views/admin/product/PageUnitEdit.vue'),
            meta: {
                title: 'ویرایش واحد',
                breadcrumb: [
                    {
                        name: 'واحدها',
                        link: 'admin.units',
                    },
                    {
                        name: 'ویرایش واحد',
                    },
                ],
            },
        },

        {
            path: 'coupons',
            children: [
                {
                    path: '',
                    name: 'admin.coupons',
                    component: () => import('../views/admin/shop/PageCoupons.vue'),
                    meta: {
                        title: 'مدیریت کوپن‌های تخفیف',
                        breadcrumb: [
                            {
                                name: 'کوپن‌های تخفیف',
                            },
                        ],
                    },
                },
                {
                    path: 'new',
                    name: 'admin.coupon.add',
                    component: () => import('../views/admin/shop/PageCouponAdd.vue'),
                    meta: {
                        title: 'ایجاد کوپن تخفیف',
                        breadcrumb: [
                            {
                                name: 'کوپن‌های تخفیف',
                                link: 'admin.coupons',
                            },
                            {
                                name: 'ایجاد کوپن تخفیف',
                            },
                        ],
                    },
                },
            ],
        },
        {
            path: 'coupon/:id(\\d+)',
            name: 'admin.coupon.edit',
            component: () => import('../views/admin/shop/PageCouponEdit.vue'),
            meta: {
                title: 'ویرایش کوپن',
                breadcrumb: [
                    {
                        name: 'کوپن‌های تخفیف',
                        link: 'admin.coupons',
                    },
                    {
                        name: 'ویرایش کوپن',
                    },
                ],
            },
        },

        {
            path: 'products',
            children: [
                {
                    path: '',
                    name: 'admin.products',
                    component: () => import('../views/admin/product/PageProducts.vue'),
                    meta: {
                        title: 'مدیریت محصولات',
                        breadcrumb: [
                            {
                                name: 'محصولات',
                            },
                        ],
                    },
                },
                {
                    path: 'new',
                    name: 'admin.product.add',
                    component: () => import('../views/admin/product/PageProductAdd.vue'),
                    meta: {
                        title: 'ایجاد محصول',
                        breadcrumb: [
                            {
                                name: 'محصولات',
                                link: 'admin.products',
                            },
                            {
                                name: 'ایجاد محصول جدید',
                            },
                        ],
                    },
                },
                {
                    path: ':ids([\\d\/]+)',
                    children: [
                        {
                            path: 'change-price',
                            name: 'admin.products.change.price',
                            component: () => import('../views/admin/product/PageProductMultipleChangePrice.vue'),
                            meta: {
                                title: 'تغییر دسته‌جمعی قیمت محصولات',
                                breadcrumb: [
                                    {
                                        name: 'محصولات',
                                        link: 'admin.products',
                                    },
                                    {
                                        name: 'تغییر دسته‌جمعی قیمت محصولات',
                                    },
                                ],
                            },
                        },
                        {
                            path: 'change-info',
                            name: 'admin.products.change.info',
                            component: () => import('../views/admin/product/PageProductMultipleChangeInfo.vue'),
                            meta: {
                                title: 'تغییر دسته‌جمعی مشخصات محصولات',
                                breadcrumb: [
                                    {
                                        name: 'محصولات',
                                        link: 'admin.products',
                                    },
                                    {
                                        name: 'تغییر دسته‌جمعی مشخصات محصولات',
                                    },
                                ],
                            },
                        },
                    ],
                },
            ],
        },
        {
            path: 'product/:id(\\d+)',
            children: [
                {
                    path: '',
                    name: 'admin.product.edit',
                    component: () => import('../views/admin/product/PageProductEdit.vue'),
                    meta: {
                        title: 'ویرایش محصول',
                        breadcrumb: [
                            {
                                name: 'محصولات',
                                link: 'admin.products',
                            },
                            {
                                name: 'ویرایش محصول',
                            },
                        ],
                    },
                },
                {
                    path: 'detail',
                    name: 'admin.product.detail',
                    component: () => import('../views/admin/product/PageProductDetail.vue'),
                    meta: {
                        title: 'جزئیات محصول',
                        breadcrumb: [
                            {
                                name: 'محصولات',
                                link: 'admin.products',
                            },
                            {
                                name: 'جزئیات محصول',
                            },
                        ],
                    },
                },
                {
                    path: 'attribute',
                    name: 'admin.product.attrs.edit',
                    component: () => import('../views/admin/product/PageProductAttributeEdit.vue'),
                    meta: {
                        title: 'ویرایش ویژگی جستجو محصول',
                        breadcrumb: [
                            {
                                name: 'محصولات',
                                link: 'admin.products',
                            },
                            {
                                name: 'ویرایش ویژگی جستجو محصول',
                            },
                        ],
                    },
                },
                {
                    path: 'comments',
                    name: 'admin.product.comments',
                    component: () => import('../views/admin/product/PageProductComments.vue'),
                    meta: {
                        title: 'نظرات محصول',
                        breadcrumb: [
                            {
                                name: 'محصولات',
                                link: 'admin.products',
                            },
                            {
                                name: 'نظرات محصول',
                            },
                        ],
                    },
                },
                {
                    path: 'comment/:detail(\\d+)',
                    name: 'admin.product.comment.detail',
                    component: () => import('../views/admin/product/PageProductCommentDetail.vue'),
                    meta: {
                        title: 'جزئیات نظر محصول',
                        breadcrumb: [
                            {
                                name: 'محصولات',
                                link: 'admin.products',
                            },
                            {
                                name: 'نظرات محصول',
                                link: 'admin.product.comments',
                                params: ['id'],
                            },
                            {
                                name: 'جزئیات نظر',
                            },
                        ],
                    },
                },
            ],
        },

        {
            path: 'attributes',
            children: [
                {
                    path: '',
                    name: 'admin.search.attrs',
                    component: () => import('../views/admin/product/PageSearchAttributes.vue'),
                    meta: {
                        title: 'ویژگی‌های جستجو',
                        breadcrumb: [
                            {
                                name: 'ویژگی‌های جستجو',
                            },
                        ],
                    },
                },
                {
                    path: 'new',
                    name: 'admin.search.attr.add',
                    component: () => import('../views/admin/product/PageAttributeAdd.vue'),
                    meta: {
                        title: 'ایجاد ویژگی جستجو',
                        breadcrumb: [
                            {
                                name: 'ویژگی‌های جستجو',
                                link: 'admin.search.attrs',
                            },
                            {
                                name: 'ایجاد ویژگی جستجو',
                            },
                        ],
                    },
                },
            ],
        },

        {
            path: 'attribute/:id(\\d+)',
            children: [
                {
                    path: '',
                    name: 'admin.search.attr.edit',
                    component: () => import('../views/admin/product/PageAttributeEdit.vue'),
                    meta: {
                        title: 'ویرایش ویژگی جستجو',
                        breadcrumb: [
                            {
                                name: 'ویژگی‌های جستجو',
                                link: 'admin.search.attrs',
                            },
                            {
                                name: 'ویرایش ویژگی جستجو',
                            },
                        ],
                    },
                },
                {
                    path: 'values',
                    name: 'admin.search.attr.values',
                    component: () => import('../views/admin/product/PageSearchAttributesValues.vue'),
                    meta: {
                        title: 'مقادیر ویژگی‌های جستجو',
                        breadcrumb: [
                            {
                                name: 'ویژگی‌های جستجو',
                                link: 'admin.search.attrs',
                            },
                            {
                                name: 'مقادیر ویژگی‌های جستجو',
                            },
                        ],
                    },
                },
                {
                    path: 'values/new',
                    name: 'admin.search.attr.value.new',
                    component: () => import('../views/admin/product/PageSearchAttributeValueAdd.vue'),
                    meta: {
                        title: 'ایجاد مقدار ویژگی جستجو',
                        breadcrumb: [
                            {
                                name: 'ویژگی‌های جستجو',
                                link: 'admin.search.attrs',
                            },
                            {
                                name: 'مقادیر ویژگی‌های جستجو',
                                link: 'admin.search.attr.values',
                                params: ['id'],
                            },
                            {
                                name: 'ایجاد مقدار ویژگی جستجو',
                            },
                        ],
                    },
                },
            ],
        },

        {
            path: 'attribute/:id(\\d+)/value/:val(\\d+)',
            children: [
                {
                    path: '',
                    name: 'admin.search.attr.value.edit',
                    component: () => import('../views/admin/product/PageSearchAttributeValueEdit.vue'),
                    meta: {
                        title: 'ویرایش مقدار ویژگی جستجو',
                        breadcrumb: [
                            {
                                name: 'ویژگی‌های جستجو',
                                link: 'admin.search.attrs',
                            },
                            {
                                name: 'مقادیر ویژگی‌های جستجو',
                                link: 'admin.search.attr.values',
                                params: ['id'],
                            },
                            {
                                name: 'ویرایش مقدار ویژگی جستجو',
                            },
                        ],
                    },
                },
            ],
        },

        {
            path: 'orders',
            children: [
                {
                    path: '',
                    name: 'admin.orders',
                    component: () => import('../views/admin/order/PageOrders.vue'),
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
                    path: 'badges',
                    name: 'admin.orders.badges',
                    component: () => import('../views/admin/order/PageOrdersBadges.vue'),
                    meta: {
                        title: 'مدیریت برچسب سفارشات',
                        breadcrumb: [
                            {
                                name: 'برچسب سفارشات',
                            },
                        ],
                    },
                },
                {
                    path: 'badges/new',
                    name: 'admin.order.badge.add',
                    component: () => import('../views/admin/order/PageOrderBadgeAdd.vue'),
                    meta: {
                        title: 'افزودن برچسب سفارش',
                        breadcrumb: [
                            {
                                name: 'سفارشات',
                                link: 'admin.orders',
                            },
                            {
                                name: 'برچسب سفارشات',
                                link: 'admin.orders.badges',
                            },
                            {
                                name: 'افزودن برچسب سفارش',
                            },
                        ],
                    },
                },
            ],
        },
        {
            path: 'order/:id(\\d+)',
            children: [
                {
                    path: '',
                    name: 'admin.order.detail',
                    alias: ['detail'],
                    component: () => import('../views/admin/order/PageOrderDetail.vue'),
                    meta: {
                        title: 'جزئیات سفارش',
                        breadcrumb: [
                            {
                                name: 'سفارشات',
                                link: 'admin.orders',
                            },
                            {
                                name: 'جزئیات سفارش',
                            },
                        ],
                    },
                },
                {
                    path: 'badge',
                    name: 'admin.order.badge.edit',
                    component: () => import('../views/admin/order/PageOrderBadgeEdit.vue'),
                    meta: {
                        title: 'ویرایش برچسب سفارش',
                        breadcrumb: [
                            {
                                name: 'سفارشات',
                                link: 'admin.orders',
                            },
                            {
                                name: 'برچسب سفارشات',
                                link: 'admin.orders.badges',
                            },
                            {
                                name: 'ویرایش برچسب',
                            },
                        ],
                    },
                },
            ],
        },

        {
            path: 'return-orders',
            name: 'admin.return_orders',
            component: () => import('../views/admin/order/PageReturnOrders.vue'),
            meta: {
                title: 'مدیریت سفارشات مرجوعی',
                breadcrumb: [
                    {
                        name: 'سفارشات مرجوعی',
                    },
                ],
            },
        },
        {
            path: 'return-order/:id(\\d+)',
            name: 'admin.return_order.detail',
            component: () => import('../views/admin/order/PageReturnOrderDetail.vue'),
            meta: {
                title: 'جزئیات سفارش مرجوعی',
                breadcrumb: [
                    {
                        name: 'سفارشات مرجوعی',
                        link: 'admin.return_orders',
                    },
                    {
                        name: 'جزئیات سفارش مرجوعی',
                    },
                ],
            },
        },

        {
            path: 'report',
            children: [
                {
                    path: 'users',
                    name: 'admin.report.users',
                    component: () => import('../views/admin/report/PageReportUsers.vue'),
                    meta: {
                        title: 'گزارش‌گیری از کاربران',
                        breadcrumb: [
                            {
                                name: 'گزارش‌گیری کاربران',
                            },
                        ],
                    },
                },
                {
                    path: 'products',
                    name: 'admin.report.products',
                    component: () => import('../views/admin/report/PageReportProducts.vue'),
                    meta: {
                        title: 'گزارش‌گیری از محصولات',
                        breadcrumb: [
                            {
                                name: 'گزارش‌گیری محصولات',
                            },
                        ],
                    },
                },
                {
                    path: 'orders',
                    name: 'admin.report.orders',
                    component: () => import('../views/admin/report/PageReportOrders.vue'),
                    meta: {
                        title: 'گزارش‌گیری از سفارشات',
                        breadcrumb: [
                            {
                                name: 'گزارش‌گیری سفارشات',
                            },
                        ],
                    },
                },
            ],
        },

        {
            path: 'blogs',
            children: [
                {
                    path: '',
                    name: 'admin.blogs',
                    component: () => import('../views/admin/page/PageBlogs.vue'),
                    meta: {
                        title: 'مدیریت بلاگ‌ها',
                        breadcrumb: [
                            {
                                name: 'بلاگ‌ها',
                            },
                        ],
                    },
                },
                {
                    path: 'new',
                    name: 'admin.blog.add',
                    component: () => import('../views/admin/page/PageBlogAdd.vue'),
                    meta: {
                        title: 'ایجاد بلاگ',
                        breadcrumb: [
                            {
                                name: 'بلاگ‌ها',
                                link: 'admin.blogs',
                            },
                            {
                                name: 'ایجاد بلاگ',
                            },
                        ],
                    },
                },
                {
                    path: 'badges',
                    name: 'admin.blogs.badges',
                    component: () => import('../views/admin/page/PageBlogsBadges.vue'),
                    meta: {
                        title: 'برچسب نظرات',
                        breadcrumb: [
                            {
                                name: 'بلاگ‌ها',
                                link: 'admin.blogs',
                            },
                            {
                                name: 'برچسب نظرات',
                            },
                        ],
                    },
                },
                {
                    path: 'badges/new',
                    name: 'admin.blog.badge.add',
                    component: () => import('../views/admin/page/PageBlogBadgeAdd.vue'),
                    meta: {
                        title: 'افزودن برچسب نظر',
                        breadcrumb: [
                            {
                                name: 'بلاگ‌ها',
                                link: 'admin.blogs',
                            },
                            {
                                name: 'برچسب‌ها',
                                link: 'admin.blogs.badges',
                            },
                            {
                                name: 'افزودن برچسب',
                            },
                        ],
                    },
                },
                {
                    path: 'categories',
                    name: 'admin.blogs.categories',
                    component: () => import('../views/admin/page/PageBlogsCategories.vue'),
                    meta: {
                        title: 'مدیریت دسته‌بندی‌های بلاگ',
                        breadcrumb: [
                            {
                                name: 'دسته‌بندی‌های بلاگ',
                            },
                        ],
                    },
                },
                {
                    path: 'categories/new',
                    name: 'admin.blog.category.add',
                    component: () => import('../views/admin/page/PageBlogCategoryAdd.vue'),
                    meta: {
                        title: 'افزودن دسته‌بندی بلاگ',
                        breadcrumb: [
                            {
                                name: 'دسته‌بندی‌های بلاگ',
                                link: 'admin.blogs.categories',
                            },
                            {
                                name: 'افزودن دسته‌بندی',
                            },
                        ],
                    },
                },
            ],
        },
        {
            path: 'blog/:id(\\d+)',
            children: [
                {
                    path: '',
                    name: 'admin.blog.edit',
                    component: () => import('../views/admin/page/PageBlogEdit.vue'),
                    meta: {
                        title: 'ویرایش بلاگ',
                        breadcrumb: [
                            {
                                name: 'بلاگ‌ها',
                                link: 'admin.blogs',
                            },
                            {
                                name: 'ویرایش بلاگ',
                            },
                        ],
                    },
                },
                {
                    path: 'badge',
                    name: 'admin.blog.badge.edit',
                    component: () => import('../views/admin/page/PageBlogBadgeEdit.vue'),
                    meta: {
                        title: 'ویرایش برچسب نظر بلاگ',
                        breadcrumb: [
                            {
                                name: 'بلاگ‌ها',
                                link: 'admin.blogs',
                            },
                            {
                                name: 'برچسب‌ها',
                                link: 'admin.blogs.badges',
                            },
                            {
                                name: 'ویرایش برچسب',
                            },
                        ],
                    },
                },
                {
                    path: 'category',
                    name: 'admin.blog.category.edit',
                    component: () => import('../views/admin/page/PageBlogCategoryEdit.vue'),
                    meta: {
                        title: 'ویرایش دسته‌بندی بلاگ',
                        breadcrumb: [
                            {
                                name: 'دسته‌بندی‌های بلاگ',
                                link: 'admin.blogs.categories',
                            },
                            {
                                name: 'ویرایش دسته‌بندی',
                            },
                        ],
                    },
                },
                {
                    path: 'comments',
                    children: [
                        {
                            path: '',
                            name: 'admin.blog.comments',
                            component: () => import('../views/admin/page/PageBlogComments.vue'),
                            meta: {
                                title: 'نظرات بلاگ',
                                breadcrumb: [
                                    {
                                        name: 'بلاگ‌ها',
                                        link: 'admin.blogs',
                                    },
                                    {
                                        name: 'لیست نظرات بلاگ',
                                    },
                                ],
                            },
                        },
                        {
                            path: ':detail(\\d+)',
                            name: 'admin.blog.comment.detail',
                            component: () => import('../views/admin/page/PageBlogCommentDetail.vue'),
                            meta: {
                                title: 'جزئیات نظر',
                                breadcrumb: [
                                    {
                                        name: 'بلاگ‌ها',
                                        link: 'admin.blogs',
                                    },
                                    {
                                        name: 'نظرات بلاگ',
                                        link: 'admin.blog.comments',
                                    },
                                    {
                                        name: 'جزئیات نظر',
                                    },
                                ],
                            },
                        },
                    ],
                },
            ],
        },

        {
            path: 'static-pages',
            children: [
                {
                    path: '',
                    name: 'admin.static_pages',
                    component: () => import('../views/admin/page/PageStaticPages.vue'),
                    meta: {
                        title: 'مدیریت صفحات ایستا',
                        breadcrumb: [
                            {
                                name: 'صفحات ایستا',
                            },
                        ],
                    },
                },
                {
                    path: 'new',
                    name: 'admin.static_page.add',
                    component: () => import('../views/admin/page/PageStaticPageAdd.vue'),
                    meta: {
                        title: 'ایجاد صفحه ایستا',
                        breadcrumb: [
                            {
                                name: 'صفحات ایستا',
                                link: 'admin.static_pages',
                            },
                            {
                                name: 'ایجاد صفحه ایستا',
                            },
                        ],
                    },
                },
            ],
        },
        {
            path: 'static-page/:id(\\d+)',
            name: 'admin.static_page.edit',
            component: () => import('../views/admin/page/PageStaticPageEdit.vue'),
            meta: {
                title: 'ویرایش صفحه ایستا',
                breadcrumb: [
                    {
                        name: 'صفحات ایستا',
                        link: 'admin.static_pages',
                    },
                    {
                        name: 'ویرایش صفحه ایستا',
                    },
                ],
            },
        },

        {
            path: 'contacts',
            name: 'admin.contacts',
            component: () => import('../views/admin/PageContacts.vue'),
            meta: {
                title: 'تماس‌ها',
                breadcrumb: [
                    {
                        name: 'تماس‌ها',
                    },
                ],
            },
        },
        {
            path: 'contact/:id(\\d+)',
            name: 'admin.contact.detail',
            alias: ['contact/:id(\\d+)/detail'],
            component: () => import('../views/admin/PageContactDetail.vue'),
            meta: {
                title: 'جزئیات تماس',
                breadcrumb: [
                    {
                        name: 'تماس‌ها',
                        link: 'admin.contacts',
                    },
                    {
                        name: 'جزئیات تماس',
                    },
                ],
            },
        },

        {
            path: 'complaints',
            name: 'admin.complaints',
            component: () => import('../views/admin/PageComplaints.vue'),
            meta: {
                title: 'شکایات',
                breadcrumb: [
                    {
                        name: 'شکایات',
                    },
                ],
            },
        },
        {
            path: 'complaint/:id(\\d+)',
            name: 'admin.complaint.detail',
            alias: ['/admin/complaint/:id(\\d+)/detail'],
            component: () => import('../views/admin/PageComplaintDetail.vue'),
            meta: {
                title: 'جزئیات شکایت',
                breadcrumb: [
                    {
                        name: 'شکایات',
                        link: 'admin.complaints',
                    },
                    {
                        name: 'جزئیات شکایت',
                    },
                ],
            },
        },

        {
            path: 'faqs',
            children: [
                {
                    path: '',
                    name: 'admin.faqs',
                    component: () => import('../views/admin/PageFaqs.vue'),
                    meta: {
                        title: 'سؤالات متداول',
                        breadcrumb: [
                            {
                                name: 'سؤالات متداول',
                            },
                        ],
                    },
                },
                {
                    path: 'new',
                    name: 'admin.faq.add',
                    component: () => import('../views/admin/PageFaqAdd.vue'),
                    meta: {
                        title: 'افزودن سؤال',
                        breadcrumb: [
                            {
                                name: 'سؤالات متداول',
                                link: 'admin.faqs',
                            },
                            {
                                name: 'افزودن سؤال',
                            },
                        ],
                    },
                },
            ],
        },
        {
            path: 'faq/:id(\\d+)',
            name: 'admin.faq.edit',
            component: () => import('../views/admin/PageFaqEdit.vue'),
            meta: {
                title: 'ویرایش سؤال',
                breadcrumb: [
                    {
                        name: 'سؤالات متداول',
                        link: 'admin.faqs',
                    },
                    {
                        name: 'ویرایش سؤال',
                    },
                ],
            },
        },

        {
            path: 'newsletters',
            name: 'admin.newsletters',
            component: () => import('../views/admin/PageNewsletters.vue'),
            meta: {
                title: 'کاربران خبرنامه',
                breadcrumb: [
                    {
                        name: 'کاربران خبرنامه',
                    },
                ],
            },
        },

        {
            path: 'sliders',
            children: [
                {
                    path: '',
                    name: 'admin.sliders',
                    component: () => import('../views/admin/other/PageSliders.vue'),
                    meta: {
                        title: 'مدیریت اسلایدرها',
                        breadcrumb: [
                            {
                                name: 'اسلایدرها',
                            },
                        ],
                    },
                },
                {
                    path: 'new',
                    name: 'admin.slider.add',
                    component: () => import('../views/admin/other/PageSliderAdd.vue'),
                    meta: {
                        title: 'افزودن اسلایدر',
                        breadcrumb: [
                            {
                                name: 'اسلایدرها',
                                link: 'admin.sliders',
                            },
                            {
                                name: 'افزودن اسلایدر',
                            },
                        ],
                    },
                },
            ],
        },
        {
            path: 'slider/:id(\\d+)',
            name: 'admin.slider.edit',
            component: () => import('../views/admin/other/PageSliderEdit.vue'),
            meta: {
                title: 'ویرایش اسلایدر',
                breadcrumb: [
                    {
                        name: 'اسلایدرها',
                        link: 'admin.sliders',
                    },
                    {
                        name: 'ویرایش اسلایدر',
                    },
                ],
            },
        },

        {
            path: 'menus',
            children: [
                {
                    path: '',
                    name: 'admin.menus',
                    component: () => import('../views/admin/other/PageMenus.vue'),
                    meta: {
                        title: 'مدیریت منوها',
                        breadcrumb: [
                            {
                                name: 'منوها',
                            },
                        ],
                    },
                },
                {
                    path: 'new',
                    name: 'admin.menu.add',
                    component: () => import('../views/admin/other/PageMenuAdd.vue'),
                    meta: {
                        title: 'افزودن منو',
                        breadcrumb: [
                            {
                                name: 'منوها',
                                link: 'admin.menus',
                            },
                            {
                                name: 'افزودن منو',
                            },
                        ],
                    },
                },
            ],
        },
        {
            path: 'menu/:id(\\d+)',
            name: 'admin.menu.edit',
            component: () => import('../views/admin/other/PageMenuEdit.vue'),
            meta: {
                title: 'ویرایش منو',
                breadcrumb: [
                    {
                        name: 'منوها',
                        link: 'admin.menus',
                    },
                    {
                        name: 'ویرایش منو',
                    },
                ],
            },
        },

        {
            path: 'post-prices',
            children: [
                {
                    path: '',
                    name: 'admin.post_prices',
                    component: () => import('../views/admin/other/PagePostPrices.vue'),
                    meta: {
                        title: 'مدیریت هزینه ارسال',
                        breadcrumb: [
                            {
                                name: 'هزینه‌های ارسال',
                            },
                        ],
                    },
                },

                {
                    path: 'cities',
                    children: [
                        {
                            path: '',
                            name: 'admin.post_prices.cities',
                            component: () => import('../views/admin/other/PagePostPricesCities.vue'),
                            meta: {
                                title: 'مدیریت هزینه ارسال شهرستان',
                                breadcrumb: [
                                    {
                                        name: 'هزینه‌های ارسال',
                                        link: 'admin.post_prices',
                                    },
                                    {
                                        name: 'هزینه ارسال شهرستان',
                                    },
                                ],
                            },
                        },
                        {
                            path: 'new',
                            name: 'admin.post_price.city.add',
                            component: () => import('../views/admin/other/PagePostPriceCityAdd.vue'),
                            meta: {
                                title: 'افزودن هزینه ارسال شهرستان',
                                breadcrumb: [
                                    {
                                        name: 'هزینه‌های ارسال',
                                        link: 'admin.post_prices',
                                    },
                                    {
                                        name: 'هزینه ارسال شهرستان',
                                        link: 'admin.post_prices.cities',
                                    },
                                    {
                                        name: 'افزودن هزینه ارسال شهرستان',
                                    },
                                ],
                            },
                        },
                    ],
                },

                {
                    path: 'weights',
                    children: [
                        {
                            path: '',
                            name: 'admin.post_prices.weights',
                            component: () => import('../views/admin/other/PagePostPricesWeights.vue'),
                            meta: {
                                title: 'هزینه ارسال بر حسب وزن',
                                breadcrumb: [
                                    {
                                        name: 'هزینه‌های ارسال',
                                        link: 'admin.post_prices',
                                    },
                                    {
                                        name: 'هزینه ارسال بر حسب وزن',
                                    },
                                ],
                            },
                        },
                        {
                            path: 'new',
                            name: 'admin.post_price.weight.add',
                            component: () => import('../views/admin/other/PagePostPriceWeightAdd.vue'),
                            meta: {
                                title: 'افزودن هزینه ارسال بر حسب وزن',
                                breadcrumb: [
                                    {
                                        name: 'هزینه‌های ارسال',
                                        link: 'admin.post_prices',
                                    },
                                    {
                                        name: 'هزینه ارسال بر حسب وزن',
                                        link: 'admin.post_prices.weights',
                                    },
                                    {
                                        name: 'افزودن هزینه ارسال بر حسب وزن',
                                    },
                                ],
                            },
                        },
                    ],
                },
            ],
        },
        {
            path: 'post-price/:id(\\d+)',
            children: [
                {
                    path: 'city',
                    name: 'admin.post_price.city.edit',
                    component: () => import('../views/admin/other/PagePostPriceCityEdit.vue'),
                    meta: {
                        title: 'ویرایش هزینه ارسال شهرستان',
                        breadcrumb: [
                            {
                                name: 'هزینه‌های ارسال',
                                link: 'admin.post_prices',
                            },
                            {
                                name: 'هزینه ارسال شهرستان',
                                link: 'admin.post_prices.cities',
                            },
                            {
                                name: 'ویرایش هزینه ارسال شهرستان',
                            },
                        ],
                    },
                },
                {
                    path: 'weight',
                    name: 'admin.post_price.weight.edit',
                    component: () => import('../views/admin/other/PagePostPriceWeightEdit.vue'),
                    meta: {
                        title: 'ویرایش هزینه ارسال بر حسب وزن',
                        breadcrumb: [
                            {
                                name: 'هزینه‌های ارسال',
                                link: 'admin.post_prices',
                            },
                            {
                                name: 'هزینه ارسال بر حسب وزن',
                                link: 'admin.post_prices.weights',
                            },
                            {
                                name: 'ویرایش هزینه ارسال بر حسب وزن',
                            },
                        ],
                    },
                },
            ],
        },

        {
            path: 'file-manager',
            name: 'admin.file_manager',
            component: () => import('../views/admin/PageFileManager.vue'),
            meta: {
                title: 'مدیریت فایل‌ها',
                breadcrumb: [
                    {
                        name: 'فایل‌ها',
                    },
                ],
            },
        },

        {
            path: 'guides',
            name: 'admin.guides',
            component: () => import('../views/admin/PageGuides.vue'),
            meta: {
                title: 'راهنما',
                breadcrumb: [
                    {
                        name: 'راهنما',
                    },
                ],
            },
        },

        {
            path: 'settings',
            name: 'admin.settings',
            component: () => import('../views/admin/PageSettings.vue'),
            meta: {
                title: 'مدیریت تنظیمات',
                breadcrumb: [
                    {
                        name: 'تنظیمات',
                    },
                ],
            },
        },
    ],
    meta: {
        requiresAuth: true,
        isAdminRoute: true,
        layout: 'layout-admin',
    },
}
