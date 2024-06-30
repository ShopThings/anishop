import {createRouter, createWebHistory} from "vue-router";
import PageNotFound from "@/views/PageNotFound.vue";
import {useAdminAuthStore, useUserAuthStore} from "@/store/StoreUserAuth.js";
import {adminRoutes} from "./admin-routes.js";
import {userRoutes} from "./user-routes.js";
import {HomeBlogAPI, HomeProductAPI} from "@/service/APIHomePages.js";
import isObject from "lodash.isobject";
import {useRequest} from "@/composables/api-request.js";
import {apiRoutes} from "@/router/api-routes.js";
import {TransitionPresets} from "vue3-page-transition";
import {useSafeLocalStorage} from "@/composables/safe-local-storage.js";
import {usePageLoaderStore} from "@/store/StorePageLoader.js";
import {nextTick} from "vue";

const slugRouteRegex = '([^\\\/\.]+)'

const routes = [
  {...adminRoutes},
  {
    path: '/admin/file-manager/editor',
    name: 'admin.file_manager.editor',
    component: () => import('@/views/admin/PageFileManagerEditor.vue'),
    meta: {
      title: 'مدیریت فایل‌ها',
      requiresAuth: true,
      isAdminRoute: true,
      layout: 'layout-empty',
    },
  },

  {...userRoutes},

  // guest routes
  {
    path: '/home',
    alias: [''],
    name: 'home',
    component: () => import('@/views/PageHome.vue'),
    meta: {
      title: 'صفحه اصلی',
      layout: 'layout-guest',
    },
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/PageLogin.vue'),
    meta: {
      title: 'ورود',
      layout: 'layout-empty-guest',
    },
  },
  {
    path: '/signup',
    name: 'signup',
    component: () => import('@/views/PageSignup.vue'),
    meta: {
      title: 'ثبت نام',
      layout: 'layout-empty-guest',
    },
  },
  {
    path: '/forget-password',
    name: 'forget_password',
    component: () => import('@/views/PageForgetPassword.vue'),
    meta: {
      title: 'فراموشی کلمه عبور',
      layout: 'layout-empty-guest',
    },
  },
  {
    path: '/pages/:url([a-zA-Z]+[a-zA-Z\/\-]*[a-zA-Z]+)',
    name: 'pages',
    component: () => import('@/views/PagePages.vue'),
    meta: {layout: 'layout-guest'},
  },

  {
    path: '/blog',
    name: 'blogs',
    beforeEnter: async (to, from, next) => {
      const id = to.query?.id;
      let route = null;

      if (id) {
        try {
          const response = await HomeBlogAPI.fetchByIdMinified(id);
          const item = response.data;

          route = {
            name: 'blog.detail',
            params: {
              slug: item.slug
            }
          };
        } catch (error) {
          // do nothing
        }
      }

      if (route) {
        next(route);
      } else {
        next();
      }
    },
    component: () => import('@/views/PageBlogs.vue'),
    meta: {
      title: 'بلاگ',
      layout: 'layout-blog',
    },
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
    component: () => import('@/views/PageSearchBlog.vue'),
    meta: {
      title: 'جستجوی بلاگ',
      layout: 'layout-blog',
    },
  },
  {
    path: '/blog/:slug' + slugRouteRegex,
    name: 'blog.detail',
    component: () => import('@/views/PageBlogDetail.vue'),
    meta: {
      title: 'جزئیات بلاگ',
      layout: 'layout-blog',
    },
  },

  {
    path: '/product',
    name: 'product',
    beforeEnter: async (to, from, next) => {
      const id = to.query?.id;

      if (id) {
        try {
          const response = await HomeProductAPI.fetchByIdMinified(id);
          const item = response.data;

          next({
            name: 'product.detail',
            params: {
              slug: item.slug
            }
          });
        } catch (error) {
          next({name: 'not-found'});
        }
      } else {
        next({name: 'not-found'});
      }
    },
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
    component: () => import('@/views/PageSearch.vue'),
    meta: {
      title: 'جستجوی محصول',
      layout: 'layout-guest',
    },
  },
  {
    path: '/product/:slug' + slugRouteRegex,
    name: 'product.detail',
    component: () => import('@/views/PageProductDetail.vue'),
    meta: {
      title: 'جزئیات محصول',
      layout: 'layout-guest',
    },
  },

  {
    path: '/brands',
    name: 'brands',
    component: () => import('@/views/PageBrands.vue'),
    meta: {
      title: 'برندها',
      layout: 'layout-guest',
    },
  },

  {
    path: '/faq',
    name: 'faq',
    component: () => import('@/views/PageFaq.vue'),
    meta: {
      title: 'سؤالات متداول',
      layout: 'layout-guest',
    },
  },
  {
    path: '/contact',
    name: 'contact',
    component: () => import('@/views/PageContact.vue'),
    meta: {
      title: 'تماس با ما',
      layout: 'layout-guest',
    },
  },

  {
    path: '/cart',
    name: 'cart',
    component: () => import('@/views/PageCart.vue'),
    meta: {
      title: 'سبد خرید',
      breadcrumb: [
        {
          name: 'خانه',
          link: 'home',
        },
        {
          name: 'سبد خرید',
        },
      ],
      layout: 'layout-guest',
    },
  },
  {
    path: '/checkout',
    name: 'checkout',
    component: () => import('@/views/PageCheckout.vue'),
    meta: {
      requiresAuth: true,
      title: 'بازنگری و پرداخت',
      layout: 'layout-guest',
    },
  },
  {
    path: '/purchase-result',
    name: 'result',
    component: () => import('@/views/PageResult.vue'),
    meta: {
      title: 'نتیجه پرداخت',
      layout: 'layout-guest',
    },
  },
  //

  {
    path: '/maintenance',
    name: 'maintenance',
    component: () => import('@/views/PageMaintenance.vue'),
    meta: {
      title: 'در دست تعمیر',
      layout: 'layout-empty-free-guest',
      appearance: {
        name: TransitionPresets.flipY,
        overlayBgClassName: '!bg-cool',
      },
    },
  },

  {
    path: "/:pathMatch(.*)*",
    name: "not-found",
    component: PageNotFound,
    meta: {
      title: 'صفحه مورد نظر پیدا نشد!',
      layout: 'layout-empty-free-guest',
    },
  },
];

//------------------------------------------------------------------------------
const index = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (to.name === 'admin.file_manager') {
      return false;
    }

    return savedPosition || {top: 0}
  },
});

//------------------------------------------------------------------------------
async function checkMaintenanceGuard(to) {
  let secret = null
  if (to.query?.secret) {
    secret = to.query.secret;
  } else {
    const storedSecret = useSafeLocalStorage.getItem('maintenance_secret');
    if (storedSecret) {
      secret = storedSecret;
    }
  }

  // If maintenance mode is active in frontend, just return true
  if (import.meta.env.VITE_IN_MAINTENANCE_MODE === 'true') {
    const bypassCode = import.meta.env.VITE_BYPASS_MAINTENANCE_MODE_CODE
    return !(secret && bypassCode && bypassCode === secret);
  }

  try {
    const data = {};

    if (secret) {
      data.maintenance_secret = to.query.secret;
    }

    const response = await useRequest(apiRoutes.maintenance, {
      method: 'POST',
      data,
    }, {silent: true});

    const shouldRemove = !response?.in_maintenance_mode;
    if (data.maintenance_secret && !shouldRemove) {
      useSafeLocalStorage.setItem('maintenance_secret', data.maintenance_secret);
    } else {
      useSafeLocalStorage.removeItem('maintenance_secret');
    }

    return false; // Continue navigation
  } catch (error) {
    useSafeLocalStorage.removeItem('maintenance_secret');
    return true; // Prevent navigation
  }
}

function checkLoginGuard(to) {
  if (
    to.matched.some(record => record.meta.requiresAuth) &&
    to.name !== 'login' && to.name !== 'admin.login' &&
    to.name !== 'user.logout' && to.name !== 'admin.logout'
  ) {
    const store = useUserAuthStore()
    const adminStore = useAdminAuthStore()

    // this route requires auth, check if is logged in,
    // if not, redirect to login page.
    if (to.matched.some(record => record.meta.isAdminRoute)) {
      if (!adminStore.getToken) {
        adminStore.$reset()
        return {
          name: 'admin.login',
          query: {redirect: to.fullPath}
        }
      }
    } else if (!store.getToken) {
      store.$reset()
      return {
        name: 'login',
        query: {redirect: to.fullPath}
      }
    }
  }

  return null
}

//------------------------------------------------------------------------------
// Page loading progress bar operations
//------------------------------------------------------------------------------
function startPageLoading(to) {
  if (to.meta?.noNeedRouteWaiting) return

  const loadingStore = usePageLoaderStore()

  endPageLoading()

  return nextTick(() => {
    loadingStore.setLoading(true)
  })
}

function endPageLoading() {
  const loadingStore = usePageLoaderStore()
  loadingStore.setLoading(false)
}

//------------------------------------------------------------------------------
index.beforeEach(async (to, from, next) => {
  await startPageLoading(to)

  let maintenance = await checkMaintenanceGuard(to)

  if (maintenance) {
    next({name: 'maintenance'})
  } else {
    if (to.name !== 'maintenance') {
      let result = checkLoginGuard(to)

      if (isObject(result)) {
        next(result)
      } else {
        next() // make sure to always call next()!
      }
    } else {
      next({name: 'not-found'})
    }
  }
})

index.beforeResolve((to, from, next) => {
  endPageLoading()

  next()
})

export default index;
