<template>
  <base-loading-panel :loading="loading">
    <template #loader>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-1 sm:gap-2">
        <div
          v-for="i in 12"
          :key="i"
          class="flex items-center gap-3 bg-white px-3 py-3 rounded-full border border-slate-200 animate-pulse"
        >
          <div class="w-2/3 h-3 rounded bg-slate-200"></div>
          <div class="w-[1px] h-6 rounded bg-slate-200 mr-auto"></div>
          <div class="w-7 h-3 rounded bg-slate-200"></div>
        </div>
      </div>
    </template>

    <template #content>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-1 sm:gap-2">
        <template v-if="userStore.hasPermission(PERMISSION_PLACES.USER, PERMISSIONS.READ)">
          <partial-counting-badge
            :count="countingItems?.users || 0"
            icon="UsersIcon"
            text="کاربران"
          />

          <partial-counting-badge
            :count="countingItems?.admin_users || 0"
            icon="UsersIcon"
            text="کاربران ادمین"
          />

          <partial-counting-badge
            :count="countingItems?.regular_users || 0"
            icon="UsersIcon"
            text="کاربران عادی"
          />
        </template>

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.PAYMENT_METHOD, PERMISSIONS.READ)"
          :count="countingItems?.payment_methods || 0"
          icon="CreditCardIcon"
          text="روش پرداخت"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.SEND_METHOD, PERMISSIONS.READ)"
          :count="countingItems?.send_methods || 0"
          icon="CloudArrowUpIcon"
          text="روش ارسال"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.COLOR, PERMISSIONS.READ)"
          :count="countingItems?.colors || 0"
          icon="PaintBrushIcon"
          text="رنگ"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.BRAND, PERMISSIONS.READ)"
          :count="countingItems?.brands || 0"
          icon="TagIcon"
          text="برند"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.CATEGORY, PERMISSIONS.READ)"
          :count="countingItems?.categories || 0"
          icon="Square2StackIcon"
          text="دسته‌بندی"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.FESTIVAL, PERMISSIONS.READ)"
          :count="countingItems?.festivals || 0"
          icon="GiftIcon"
          text="جشنواره"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.UNIT, PERMISSIONS.READ)"
          :count="countingItems?.units || 0"
          icon="HashtagIcon"
          text="واحد محصول"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.COUPON, PERMISSIONS.READ)"
          :count="countingItems?.coupons || 0"
          icon="ReceiptPercentIcon"
          text="کوپن تخفیف"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.PRODUCT, PERMISSIONS.READ)"
          :count="countingItems?.products || 0"
          icon="ArchiveBoxIcon"
          text="محصول"
        />

        <template v-if="userStore.hasPermission(PERMISSION_PLACES.PRODUCT_ATTRIBUTE, PERMISSIONS.READ)">
          <partial-counting-badge
            :count="countingItems?.product_attributes || 0"
            icon="ListBulletIcon"
            text="ویژگی جستجوی محصول"
          />

          <partial-counting-badge
            :count="countingItems?.product_attribute_categories || 0"
            icon="ListBulletIcon"
            text="ویژگی جستجو به دسته‌بندی"
          />
        </template>

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.PRODUCT_COMMENT, PERMISSIONS.READ)"
          :count="countingItems?.product_comments || 0"
          icon="ChatBubbleLeftRightIcon"
          text="دیدگاه محصول"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.ORDER, PERMISSIONS.READ)"
          :count="countingItems?.orders || 0"
          icon="ShoppingBagIcon"
          text="سفارش"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.ORDER_BADGE, PERMISSIONS.READ)"
          :count="countingItems?.order_badges || 0"
          icon="ListBulletIcon"
          text="برچسب وضعیت سفارش"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.RETURN_ORDER_REQUEST, PERMISSIONS.READ)"
          :count="countingItems?.return_orders || 0"
          icon="ArchiveBoxXMarkIcon"
          text="سفارش مرجوعی"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.BLOG, PERMISSIONS.READ)"
          :count="countingItems?.blogs || 0"
          icon="DocumentTextIcon"
          text="بلاگ"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.BLOG_COMMENT_BADGE, PERMISSIONS.READ)"
          :count="countingItems?.blog_comment_badges || 0"
          icon="ListBulletIcon"
          text="برجسب دیدگاه"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.BLOG_CATEGORY, PERMISSIONS.READ)"
          :count="countingItems?.blog_categories || 0"
          icon="ListBulletIcon"
          text="دسته‌بندی بلاگ"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.BLOG_COMMENT, PERMISSIONS.READ)"
          :count="countingItems?.blog_comments || 0"
          icon="ChatBubbleLeftRightIcon"
          text="دیدگاه بلاگ"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.STATIC_PAGE, PERMISSIONS.READ)"
          :count="countingItems?.static_pages || 0"
          icon="DocumentIcon"
          text="صفحه ثابت"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.CONTACT_US, PERMISSIONS.READ)"
          :count="countingItems?.contacts || 0"
          icon="DevicePhoneMobileIcon"
          text="تماس"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.COMPLAINT, PERMISSIONS.READ)"
          :count="countingItems?.complaints || 0"
          icon="ScaleIcon"
          text="شکایت"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.FAQ, PERMISSIONS.READ)"
          :count="countingItems?.faqs || 0"
          icon="QuestionMarkCircleIcon"
          text="سؤال متداول"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.NEWSLETTER, PERMISSIONS.READ)"
          :count="countingItems?.newsletters || 0"
          icon="NewspaperIcon"
          text="عضو خبرنامه"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.CITY_POST_PRICE, PERMISSIONS.READ)"
          :count="countingItems?.city_post_prices || 0"
          icon="CurrencyDollarIcon"
          text="هزینه ارسال(مکان)"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.WEIGHT_POST_PRICE, PERMISSIONS.READ)"
          :count="countingItems?.weigh_post_prices || 0"
          icon="CurrencyDollarIcon"
          text="هزینه اسال(وزن)"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.SLIDER, PERMISSIONS.READ)"
          :count="countingItems?.sliders || 0"
          icon="ViewColumnsIcon"
          text="اسلایدر"
        />

        <partial-counting-badge
          v-if="userStore.hasPermission(PERMISSION_PLACES.MENU, PERMISSIONS.READ)"
          :count="countingItems?.menus || 0"
          icon="RectangleGroupIcon"
          text="منو"
        />
      </div>
    </template>
  </base-loading-panel>
</template>

<script setup>
import {onMounted, ref} from "vue";
import PartialCountingBadge from "@/components/admin/dashboard/partials/PartialCountingBadge.vue";
import {PERMISSION_PLACES, PERMISSIONS, useAdminAuthStore} from "@/store/StoreUserAuth.js";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {AdminPanelDashboardAPI} from "@/service/APIAdminPanel.js";

const userStore = useAdminAuthStore()

const countingItems = ref(null)
const loading = ref(true)

onMounted(() => {
  AdminPanelDashboardAPI.getDashboardCounting({
    success(response) {
      countingItems.value = response.data
      loading.value = false
    },
  })
})
</script>
