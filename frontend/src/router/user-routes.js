import {useUserAuthStore} from "@/store/StoreUserAuth.js";
import {isValidInternalRedirectLink} from "@/composables/helper.js";
import {useCartStore} from "@/store/StoreUserCart.js";

const slugRouteRegex = '([^\\\/\.]+)'
const codeRegex = '([\\d\\w\-\_]+)'

export const userRoutes = {
  path: '/user',
  children: [
    {
      path: 'logout',
      name: 'user.logout',
      beforeEnter: async (to, from, next) => {
        const store = useUserAuthStore()
        const cartStore = useCartStore()

        let route = null

        // Check if the user is authenticated
        if (!store.getUser) {
          // User is authenticated, continue navigation to the original destination
          return next(from)
        }

        // Logout the user
        await store.logout({
          success() {
            // User is logged out, determine the redirect route
            if (from.meta.requiresAuth) {
              // If the original route requires authentication, redirect to log in
              route = {name: 'login'}

              if (to.query.redirect && isValidInternalRedirectLink(to.query.redirect)) {
                route.query = {redirect: to.query.redirect}
              }
            }

            cartStore.saveToLocalStorage()
          },
        })

        if (route) {
          location.reload()
        }
        return next(from)
      },
      meta: {
        noNeedRouteWaiting: true,
      },
    },
    {
      path: 'home',
      alias: [''],
      name: 'user.home',
      component: () => import('@/views/user/PageHome.vue'),
      meta: {
        title: 'پیشخوان',
      },
    },

    {
      path: 'addresses',
      children: [
        {
          path: '',
          name: 'user.addresses',
          component: () => import('@/views/user/PageAddresses.vue'),
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
          component: () => import('@/views/user/PageAddressAdd.vue'),
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
      component: () => import('@/views/user/PageAddressEdit.vue'),
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
      component: () => import('@/views/user/PageComments.vue'),
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
      path: 'comment/:id' + codeRegex,
      children: [
        {
          path: '',
          name: 'user.comment.detail',
          component: () => import('@/views/user/PageCommentDetail.vue'),
          meta: {
            title: 'جزئیات دیدگاه محصول',
            breadcrumb: [
              {
                name: 'دیدگاه‌ها',
                link: 'users.comments',
              },
              {
                name: 'دیدگاه شما برای محصول',
              },
            ],
          },
        },
        {
          path: 'blog',
          name: 'user.comment.detail.blog',
          component: () => import('@/views/user/PageCommentDetailBlog.vue'),
          meta: {
            title: 'جزئیات دیدگاه بلاگ',
            breadcrumb: [
              {
                name: 'دیدگاه‌ها',
                link: 'users.comments',
              },
              {
                name: 'دیدگاه شما برای بلاگ',
              },
            ],
          },
        },
      ],
    },
    {
      path: 'comment/:slug' + slugRouteRegex + '/add',
      name: 'user.comment.add',
      component: () => import('@/views/user/PageCommentAdd.vue'),
    },

    {
      path: 'favorite-products',
      name: 'user.favorite_products',
      component: () => import('@/views/user/PageFavoriteProducts.vue'),
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
      component: () => import('@/views/user/PageProfile.vue'),
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
      component: () => import('@/views/user/PageOrders.vue'),
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
      path: 'order/:code' + codeRegex,
      name: 'user.order.detail',
      component: () => import('@/views/user/PageOrderDetail.vue'),
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
      component: () => import('@/views/user/PageReturnOrders.vue'),
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
      path: 'return-order/:code' + codeRegex,
      name: 'user.return_order.detail',
      component: () => import('@/views/user/PageReturnOrderDetail.vue'),
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
      path: 'contacts',
      name: 'user.contacts',
      component: () => import('@/views/user/PageContact.vue'),
      meta: {
        title: 'تماس‌های من',
        breadcrumb: [
          {
            name: 'مشاهده تماس‌های من',
          },
        ],
      },
    },
    {
      path: 'contact/:id(\\d+)',
      name: 'user.contact.detail',
      component: () => import('@/views/user/PageContactDetail.vue'),
      meta: {
        title: 'جزئیات تماس',
        breadcrumb: [
          {
            name: 'تماس‌های من',
            link: 'user.contacts',
          },
          {
            name: 'جزئیات تماس',
          },
        ],
      },
    },

    {
      path: 'notifications',
      name: 'user.notifications',
      component: () => import('@/views/user/PageNotifications.vue'),
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
    requiresAuth: true,
    layout: 'layout-user',
  },
}
