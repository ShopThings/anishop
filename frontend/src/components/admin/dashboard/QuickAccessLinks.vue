<template>
  <h2 class="text-slate-400 mb-3 mt-6">
    دسترسی سریع
  </h2>

  <div
    class="quick-access-link-container grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 pb-3 max-h-96 lg:max-h-none overflow-auto my-custom-scrollbar"
    @mouseleave="mouseleaveHandler"
    @mousemove="mousemoveHandler"
    @touchend="mouseleaveHandler"
    @touchmove="mousemoveHandler"
  >
    <partial-quick-access-link
      v-if="userStore.hasPermission(PERMISSION_PLACES.USER, PERMISSIONS.READ)"
      :link="{name: 'admin.users'}"
      icon="UsersIcon"
      text="مدیریت کاربران"
    />

    <partial-quick-access-link
      v-if="userStore.hasPermission(PERMISSION_PLACES.COLOR, PERMISSIONS.READ)"
      :link="{name: 'admin.colors'}"
      icon="PaintBrushIcon"
      text="رنگ‌ها"
    />

    <partial-quick-access-link
      v-if="userStore.hasPermission(PERMISSION_PLACES.BRAND, PERMISSIONS.READ)"
      :link="{name: 'admin.brands'}"
      icon="TagIcon"
      text="برندها"
    />

    <partial-quick-access-link
      v-if="userStore.hasPermission(PERMISSION_PLACES.FESTIVAL, PERMISSIONS.READ)"
      :link="{name: 'admin.festivals'}"
      icon="GiftIcon"
      text="کوپن‌های تخفیف"
    />

    <partial-quick-access-link
      v-if="userStore.hasPermission(PERMISSION_PLACES.CATEGORY, PERMISSIONS.READ)"
      :link="{name: 'admin.categories'}"
      icon="Square2StackIcon"
      text="دسته‌بندی‌ها"
    />

    <partial-quick-access-link
      v-if="userStore.hasPermission(PERMISSION_PLACES.PRODUCT, PERMISSIONS.READ)"
      :link="{name: 'admin.products'}"
      icon="ArchiveBoxIcon"
      text="مدیریت محصولات"
    />

    <partial-quick-access-link
      v-if="userStore.hasPermission(PERMISSION_PLACES.COUPON, PERMISSIONS.READ)"
      :link="{name: 'admin.coupons'}"
      icon="ReceiptPercentIcon"
      text="جشنواره‌ها"
    />

    <partial-quick-access-link
      v-if="userStore.hasPermission(PERMISSION_PLACES.PRODUCT_COMMENT, PERMISSIONS.READ)"
      :link="{name: 'admin.products.comments'}"
      icon="ChatBubbleLeftRightIcon"
      text="دیدگاه‌های محصولات"
    />

    <partial-quick-access-link
      v-if="userStore.hasPermission(PERMISSION_PLACES.ORDER, PERMISSIONS.READ)"
      :link="{name: 'admin.orders'}"
      icon="ShoppingBagIcon"
      text="سفارشات"
    />

    <partial-quick-access-link
      v-if="userStore.hasPermission(PERMISSION_PLACES.ORDER_BADGE, PERMISSIONS.READ)"
      :link="{name: 'admin.return_orders'}"
      icon="ArchiveBoxXMarkIcon"
      text="سفارشات مرجوعی"
    />

    <partial-quick-access-link
      v-if="userStore.hasPermission(PERMISSION_PLACES.BLOG, PERMISSIONS.READ)"
      :link="{name: 'admin.blogs'}"
      icon="DocumentTextIcon"
      text="بلاگ"
    />

    <partial-quick-access-link
      v-if="userStore.hasPermission(PERMISSION_PLACES.BLOG_CATEGORY, PERMISSIONS.READ)"
      :link="{name: 'admin.blogs.categories'}"
      icon="ListBulletIcon"
      text="دسته‌بندی‌های بلاگ"
    />

    <partial-quick-access-link
      v-if="userStore.hasPermission(PERMISSION_PLACES.BLOG_COMMENT, PERMISSIONS.READ)"
      :link="{name: 'admin.blogs.comments'}"
      icon="ChatBubbleLeftRightIcon"
      text="دیدگاه‌های بلاگ"
    />

    <partial-quick-access-link
      v-if="userStore.hasPermission(PERMISSION_PLACES.STATIC_PAGE, PERMISSIONS.READ)"
      :link="{name: 'admin.static_pages'}"
      icon="DocumentIcon"
      text="صفحات ثابت"
    />

    <partial-quick-access-link
      v-if="userStore.hasPermission(PERMISSION_PLACES.SLIDER, PERMISSIONS.READ)"
      :link="{name: 'admin.sliders'}"
      icon="ViewColumnsIcon"
      text="اسلایدرها"
    />

    <partial-quick-access-link
      v-if="userStore.hasPermission(PERMISSION_PLACES.FILE_MANAGER, PERMISSIONS.READ)"
      :link="{name: 'admin.file_manager'}"
      icon="FolderOpenIcon"
      text="مدیریت فایل‌ها"
    />
  </div>
</template>

<script setup>
import PartialQuickAccessLink from "@/components/admin/dashboard/partials/PartialQuickAccessLink.vue";
import {PERMISSION_PLACES, PERMISSIONS, useAdminAuthStore} from "@/store/StoreUserAuth.js";
import {onMounted} from "vue";

const userStore = useAdminAuthStore()

const offset = 85
const angles = [] //in deg
let nearBy = []

function removeBorderOfElements(e) {
  let parent = e.target.classList.contains('quick-access-link-container')
    ? e.target
    : e.target.closest('.quick-access-link-container')

  if (!parent) return

  const children = parent.childNodes

  if (children?.length) {
    children.forEach((element) => {
      if (element.classList.contains('quick-access-link-btn')) {
        element.style.borderImage = null
      }
    })
  }
}

function mousemoveHandler(e) {
  let x = e.x // x position within the element.
  let y = e.y // y position within the element.

  if (e.changedTouches && e.changedTouches[0]) {
    x = e.changedTouches[0]?.x || x
    y = e.changedTouches[0]?.y || y
  }

  removeBorderOfElements(e)

  nearBy = angles.reduce((acc, rad) => {
    const cx = Math.floor(x + Math.cos(rad) * offset)
    const cy = Math.floor(y + Math.sin(rad) * offset)
    const element = document.elementFromPoint(cx, cy)

    if (element !== null) {
      if (element.classList.contains('quick-access-link-btn')) {
        const brect = element.getBoundingClientRect()
        const bx = x - brect.left //x position within the element.
        const by = y - brect.top //y position within the element.

        element.style.borderImage = `radial-gradient(${offset * 2}px ${
          offset * 2
        }px at ${bx}px ${by}px ,rgba(74, 222, 128, 0.9),rgba(74, 222, 128, 0.1), transparent) 9 / 3px / 0px stretch `

        return [...acc, element]
      }
    }
    return acc;
  }, []);
}

function mouseleaveHandler(e) {
  removeBorderOfElements(e)
}

onMounted(() => {
  for (let i = 0; i <= 360; i += 45) {
    angles.push((i * Math.PI) / 180)
  }
})
</script>
