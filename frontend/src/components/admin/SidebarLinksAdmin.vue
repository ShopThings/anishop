<template>
  <div>
    <ul class="flex flex-col">
      <li
          v-tooltip.left="'' + (mini ? 'اصلی' : '') + ''"
          class="text-white opacity-70 text-xs px-3 mb-1 mt-5"
          :class="{'text-center': mini}"
      >
        <span v-if="mini" class="rounded-full w-2 h-2 bg-white inline-block"></span>
        <span v-else>اصلی</span>
      </li>
      <li v-tooltip.left="'' + (mini ? 'پیشخوان' : '') + ''">
        <router-link :to="{name: 'admin.home'}"
                     class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                     :class="[mini ? 'justify-center px-2' : 'px-3']"
        >
          <WindowIcon
              class="h-6 w-6 shrink-0"
              :class="{'ml-2': !mini}"
          />
          <span v-if="!mini"
                class="grow">پیشخوان</span>
        </router-link>
      </li>
      <li v-tooltip.left="'' + (mini ? 'کاربران' : '') + ''">
        <router-link :to="{name: 'admin.users'}"
                     class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                     :class="[mini ? 'justify-center px-2' : 'px-3']"
        >
          <UsersIcon
              class="h-6 w-6 shrink-0"
              :class="{'ml-2': !mini}"
          />
          <span v-if="!mini"
                class="grow">کاربران</span>
        </router-link>
      </li>
    </ul>
  </div>

  <div>
    <ul class="flex flex-col">
      <li
          v-tooltip.left="'' + (mini ? 'فروشگاه' : '') + ''"
          class="text-white opacity-70 text-xs px-3 mb-1 mt-5"
          :class="{'text-center': mini}"
      >
        <span v-if="mini" class="rounded-full w-2 h-2 bg-white inline-block"></span>
        <span v-else>فروشگاه</span>
      </li>
      <li v-tooltip.left="'' + (mini ? 'روش‌های پرداخت' : '') + ''">
        <router-link :to="{name: 'admin.payment_methods'}"
                     class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                     :class="[mini ? 'justify-center px-2' : 'px-3']"
        >
          <CreditCardIcon
              class="h-6 w-6 shrink-0"
              :class="{'ml-2': !mini}"
          />
          <span v-if="!mini"
                class="grow">روش‌های پرداخت</span>
        </router-link>
      </li>
      <li v-tooltip.left="'' + (mini ? 'رنگ‌ها' : '') + ''">
        <router-link :to="{name: 'admin.colors'}"
                     class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                     :class="[mini ? 'justify-center px-2' : 'px-3']"
        >
          <PaintBrushIcon
              class="h-6 w-6 shrink-0"
              :class="{'ml-2': !mini}"
          />
          <span v-if="!mini"
                class="grow">رنگ‌ها</span>
        </router-link>
      </li>
      <li v-tooltip.left="'' + (mini ? 'برندها' : '') + ''">
        <router-link :to="{name: 'admin.brands'}"
                     class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                     :class="[mini ? 'justify-center px-2' : 'px-3']"
        >
          <TagIcon
              class="h-6 w-6 shrink-0"
              :class="{'ml-2': !mini}"
          />
          <span v-if="!mini"
                class="grow">برندها</span>
        </router-link>
      </li>
      <li v-tooltip.left="'' + (mini ? 'مدیریت دسته‌بندی‌ها' : '') + ''">
        <router-link :to="{name: 'admin.categories'}"
                     class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                     :class="[mini ? 'justify-center px-2' : 'px-3']"
        >
          <Square2StackIcon
              class="h-6 w-6 shrink-0"
              :class="{'ml-2': !mini}"
          />
          <span v-if="!mini"
                class="grow">مدیریت دسته‌بندی‌ها</span>
        </router-link>
      </li>
      <li v-tooltip.left="'' + (mini ? 'جشنواره‌ها' : '') + ''">
        <router-link :to="{name: 'admin.festivals'}"
                     class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                     :class="[mini ? 'justify-center px-2' : 'px-3']"
        >
          <GiftIcon
              class="h-6 w-6 shrink-0"
              :class="{'ml-2': !mini}"
          />
          <span v-if="!mini"
                class="grow">جشنواره‌ها</span>
        </router-link>
      </li>
      <li v-tooltip.left="'' + (mini ? 'واحدها' : '') + ''">
        <router-link :to="{name: 'admin.units'}"
                     class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                     :class="[mini ? 'justify-center px-2' : 'px-3']"
        >
          <HashtagIcon
              class="h-6 w-6 shrink-0"
              :class="{'ml-2': !mini}"
          />
          <span v-if="!mini"
                class="grow">واحدها</span>
        </router-link>
      </li>
      <li v-tooltip.left="'' + (mini ? 'کوپن‌های تخفیف' : '') + ''">
        <router-link :to="{name: 'admin.coupons'}"
                     class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                     :class="[mini ? 'justify-center px-2' : 'px-3']"
        >
          <ReceiptPercentIcon
              class="h-6 w-6 shrink-0"
              :class="{'ml-2': !mini}"
          />
          <span v-if="!mini"
                class="grow">کوپن‌های تخفیف</span>
        </router-link>
      </li>
      <li>
        <sidebar-link-sub-admin :mini="mini">
          <template #link="{open}">
            <router-link
                v-tooltip.left="'' + (mini ? 'محصولات' : '') + ''"
                :to="{name: 'admin.products'}"
                class="rounded-lg py-2.5 px-3 flex items-center hover:bg-white hover:bg-opacity-10 transition grow"
                :class="{'justify-center w-full': mini, 'bg-white bg-opacity-10': open}"
            >
              <ArchiveBoxIcon
                  class="h-6 w-6 shrink-0"
                  :class="{'ml-2': !mini}"
              />
              <span v-if="!mini" class="grow">محصولات</span>
            </router-link>
          </template>

          <template #children>
            <partial-sidebar-item-admin>
              <router-link
                  v-tooltip.left="'' + (mini ? 'ویژگی‌های جستجوی محصول' : '') + ''"
                  :to="{name: 'admin.search.attrs'}"
                  class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                  :class="[mini ? 'justify-center px-2' : 'px-3']"
              >
                <ListBulletIcon v-if="mini" class="w-5 h-5"/>
                <span v-else
                      class="grow">ویژگی‌های جستجوی محصول</span>
              </router-link>
              <router-link
                  v-tooltip.left="'' + (mini ? 'دسته‌بندی‌ها و ویژگی‌های جستجوی' : '') + ''"
                  :to="{name: 'admin.search.attrs.categories'}"
                  class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                  :class="[mini ? 'justify-center px-2' : 'px-3']"
              >
                <ListBulletIcon v-if="mini" class="w-5 h-5"/>
                <span v-else
                      class="grow">دسته‌بندی‌ها و ویژگی‌های جستجوی</span>
              </router-link>
            </partial-sidebar-item-admin>
          </template>
        </sidebar-link-sub-admin>
      </li>
    </ul>
  </div>

  <div>
    <ul class="flex flex-col">
      <li
          v-tooltip.left="'' + (mini ? 'مدیریت سفارشات' : '') + ''"
          class="text-white opacity-70 text-xs px-3 mb-1 mt-5"
          :class="{'text-center': mini}"
      >
        <span v-if="mini" class="rounded-full w-2 h-2 bg-white inline-block"></span>
        <span v-else>مدیریت سفارشات</span>
      </li>
      <li>
        <sidebar-link-sub-admin :mini="mini">
          <template #link="{open}">
            <router-link
                v-tooltip.left="'' + (mini ? 'سفارشات ثبت شده' : '') + ''"
                :to="{name: 'admin.orders'}"
                class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition grow"
                :class="{'justify-center w-full': mini, 'bg-white bg-opacity-10': open}"
            >
              <ShoppingBagIcon
                  class="h-6 w-6 shrink-0"
                  :class="{'ml-2': !mini}"
              />
              <span v-if="!mini"
                    class="grow">سفارشات ثبت شده</span>
            </router-link>
          </template>

          <template #children>
            <partial-sidebar-item-admin>
              <router-link
                  v-tooltip.left="'' + (mini ? 'برچسب وضعیت سفارشات' : '') + ''"
                  :to="{name: 'admin.orders.badges'}"
                  class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                  :class="[mini ? 'justify-center px-2' : 'px-3']"
              >
                <ListBulletIcon v-if="mini" class="w-5 h-5"/>
                <span v-else
                      class="grow">برچسب وضعیت سفارشات</span>
              </router-link>
            </partial-sidebar-item-admin>
          </template>
        </sidebar-link-sub-admin>
      </li>
      <li v-tooltip.left="'' + (mini ? 'سفارشات مرجوعی' : '') + ''">
        <router-link :to="{name: 'admin.return_orders'}"
                     class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                     :class="[mini ? 'justify-center px-2' : 'px-3']"
        >
          <ArchiveBoxXMarkIcon
              class="h-6 w-6 shrink-0"
              :class="{'ml-2': !mini}"
          />
          <span v-if="!mini"
                class="grow">سفارشات مرجوعی</span>
        </router-link>
      </li>
    </ul>
  </div>

  <div>
    <ul class="flex flex-col">
      <li
          v-tooltip.left="'' + (mini ? 'گزارش گیری' : '') + ''"
          class="text-white opacity-70 text-xs px-3 mb-1 mt-5"
          :class="{'text-center': mini}"
      >
        <span v-if="mini" class="rounded-full w-2 h-2 bg-white inline-block"></span>
        <span v-else>گزارش گیری</span>
      </li>
      <li v-tooltip.left="'' + (mini ? 'گزارش‌گیری از کاربران' : '') + ''">
        <router-link :to="{name: 'admin.report.users'}"
                     class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                     :class="[mini ? 'justify-center px-2' : 'px-3']"
        >
          <RectangleStackIcon
              class="h-6 w-6 shrink-0"
              :class="{'ml-2': !mini}"
          />
          <span v-if="!mini"
                class="grow">گزارش‌گیری از کاربران</span>
        </router-link>
      </li>
      <li v-tooltip.left="'' + (mini ? 'گزارش‌گیری از محصولات' : '') + ''">
        <router-link :to="{name: 'admin.report.products'}"
                     class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                     :class="[mini ? 'justify-center px-2' : 'px-3']"
        >
          <RectangleStackIcon
              class="h-6 w-6 shrink-0"
              :class="{'ml-2': !mini}"
          />
          <span v-if="!mini"
                class="grow">گزارش‌گیری از محصولات</span>
        </router-link>
      </li>
      <li v-tooltip.left="'' + (mini ? 'گزارش‌گیری از سفارشات' : '') + ''">
        <router-link :to="{name: 'admin.report.orders'}"
                     class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                     :class="[mini ? 'justify-center px-2' : 'px-3']"
        >
          <RectangleStackIcon
              class="h-6 w-6 shrink-0"
              :class="{'ml-2': !mini}"
          />
          <span v-if="!mini"
                class="grow">گزارش‌گیری از سفارشات</span>
        </router-link>
      </li>
    </ul>
  </div>

  <div>
    <ul class="flex flex-col">
      <li
          v-tooltip.left="'' + (mini ? 'صفحات' : '') + ''"
          class="text-white opacity-70 text-xs px-3 mb-1 mt-5"
          :class="{'text-center': mini}"
      >
        <span v-if="mini" class="rounded-full w-2 h-2 bg-white inline-block"></span>
        <span v-else>صفحات</span>
      </li>
      <li>
        <sidebar-link-sub-admin :mini="mini">
          <template #link="{open}">
            <router-link
                v-tooltip.left="'' + (mini ? 'بلاگ' : '') + ''"
                :to="{name: 'admin.blogs'}"
                class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition grow"
                :class="{'justify-center w-full': mini, 'bg-white bg-opacity-10': open}"
            >
              <DocumentTextIcon
                  class="h-6 w-6 shrink-0"
                  :class="{'ml-2': !mini}"
              />
              <span v-if="!mini"
                    class="grow">بلاگ</span>
            </router-link>
          </template>

          <template #children>
            <partial-sidebar-item-admin>
              <router-link
                  v-tooltip.left="'' + (mini ? 'برچسب دیدگاه‌ها' : '') + ''"
                  :to="{name: 'admin.blogs.badges'}"
                  class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                  :class="[mini ? 'justify-center px-2' : 'px-3']"
              >
                <ListBulletIcon v-if="mini" class="w-5 h-5"/>
                <span v-else
                      class="grow">برچسب دیدگاه‌ها</span>
              </router-link>
            </partial-sidebar-item-admin>
            <partial-sidebar-item-admin>
              <router-link
                  v-tooltip.left="'' + (mini ? 'مدیریت دسته‌بندی‌ها' : '') + ''"
                  :to="{name: 'admin.blogs.categories'}"
                  class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                  :class="[mini ? 'justify-center px-2' : 'px-3']"
              >
                <ListBulletIcon v-if="mini" class="w-5 h-5"/>
                <span v-else
                      class="grow">مدیریت دسته‌بندی‌ها</span>
              </router-link>
            </partial-sidebar-item-admin>
          </template>
        </sidebar-link-sub-admin>
      </li>
      <li v-tooltip.left="'' + (mini ? 'صفحات ثابت' : '') + ''">
        <router-link :to="{name: 'admin.static_pages'}"
                     class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                     :class="[mini ? 'justify-center px-2' : 'px-3']"
        >
          <DocumentIcon
              class="h-6 w-6 shrink-0"
              :class="{'ml-2': !mini}"
          />
          <span v-if="!mini"
                class="grow">صفحات ثابت</span>
        </router-link>
      </li>
    </ul>
  </div>

  <div>
    <ul class="flex flex-col">
      <li
          v-tooltip.left="'' + (mini ? 'ارتباطات' : '') + ''"
          class="text-white opacity-70 text-xs px-3 mb-1 mt-5"
          :class="{'text-center': mini}"
      >
        <span v-if="mini" class="rounded-full w-2 h-2 bg-white inline-block"></span>
        <span v-else>ارتباطات</span>
      </li>
      <li v-tooltip.left="'' + (mini ? 'تماس‌ها' : '') + ''">
        <router-link :to="{name: 'admin.contacts'}"
                     class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                     :class="[mini ? 'justify-center px-2' : 'px-3']"
        >
          <DevicePhoneMobileIcon
              class="h-6 w-6 shrink-0"
              :class="{'ml-2': !mini}"
          />
          <span v-if="!mini"
                class="grow">تماس‌ها</span>
        </router-link>
      </li>
      <li v-tooltip.left="'' + (mini ? 'شکایات' : '') + ''">
        <router-link :to="{name: 'admin.complaints'}"
                     class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                     :class="[mini ? 'justify-center px-2' : 'px-3']"
        >
          <ScaleIcon
              class="h-6 w-6 shrink-0"
              :class="{'ml-2': !mini}"
          />
          <span v-if="!mini"
                class="grow">شکایات</span>
        </router-link>
      </li>
      <li v-tooltip.left="'' + (mini ? 'سؤالات متداول' : '') + ''">
        <router-link :to="{name: 'admin.faqs'}"
                     class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                     :class="[mini ? 'justify-center px-2' : 'px-3']"
        >
          <QuestionMarkCircleIcon
              class="h-6 w-6 shrink-0"
              :class="{'ml-2': !mini}"
          />
          <span v-if="!mini"
                class="grow">سؤالات متداول</span>
        </router-link>
      </li>
      <li v-tooltip.left="'' + (mini ? 'خبرنامه' : '') + ''">
        <router-link :to="{name: 'admin.newsletters'}"
                     class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                     :class="[mini ? 'justify-center px-2' : 'px-3']"
        >
          <NewspaperIcon
              class="h-6 w-6 shrink-0"
              :class="{'ml-2': !mini}"
          />
          <span v-if="!mini"
                class="grow">خبرنامه</span>
        </router-link>
      </li>
    </ul>
  </div>

  <div>
    <ul class="flex flex-col">
      <li
          v-tooltip.left="'' + (mini ? 'سایر' : '') + ''"
          class="text-white opacity-70 text-xs px-3 mb-1 mt-5"
          :class="{'text-center': mini}"
      >
        <span v-if="mini" class="rounded-full w-2 h-2 bg-white inline-block"></span>
        <span v-else>سایر</span>
      </li>
      <li>
        <sidebar-link-sub-admin :mini="mini">
          <template #link="{open}">
            <a
                v-tooltip.left="'' + (mini ? 'هزینه ارسال' : '') + ''"
                class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition w-full"
                :class="{'justify-center': mini, 'bg-white bg-opacity-10': open}"
            >
              <CurrencyDollarIcon
                  class="h-6 w-6 shrink-0"
                  :class="{'ml-2': !mini}"
              />
              <span v-if="!mini"
                    class="grow">هزینه ارسال</span>
            </a>
          </template>

          <template #children>
            <partial-sidebar-item-admin>
              <router-link
                  v-tooltip.left="'' + (mini ? 'هزینه ارسال برحسب مکان' : '') + ''"
                  :to="{name: 'admin.post_prices.cities'}"
                  class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                  :class="[mini ? 'justify-center px-2' : 'px-3']"
              >
                <ListBulletIcon v-if="mini" class="w-5 h-5"/>
                <span v-else
                      class="grow">هزینه ارسال برحسب مکان</span>
              </router-link>
            </partial-sidebar-item-admin>
            <partial-sidebar-item-admin>
              <router-link
                  v-tooltip.left="'' + (mini ? 'هزینه ارسال برحسب وزن' : '') + ''"
                  :to="{name: 'admin.post_prices.weights'}"
                  class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                  :class="[mini ? 'justify-center px-2' : 'px-3']"
              >
                <ListBulletIcon v-if="mini" class="w-5 h-5"/>
                <span v-else
                      class="grow">هزینه ارسال برحسب وزن</span>
              </router-link>
            </partial-sidebar-item-admin>
          </template>
        </sidebar-link-sub-admin>
      </li>
      <li v-tooltip.left="'' + (mini ? 'مدیریت اسلایدرها' : '') + ''">
        <router-link :to="{name: 'admin.sliders'}"
                     class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                     :class="[mini ? 'justify-center px-2' : 'px-3']"
        >
          <ViewColumnsIcon
              class="h-6 w-6 shrink-0"
              :class="{'ml-2': !mini}"
          />
          <span v-if="!mini"
                class="grow">مدیریت اسلایدرها</span>
        </router-link>
      </li>
      <li v-tooltip.left="'' + (mini ? 'مدیریت منو‌ها' : '') + ''">
        <router-link :to="{name: 'admin.menus'}"
                     class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                     :class="[mini ? 'justify-center px-2' : 'px-3']"
        >
          <RectangleGroupIcon
              class="h-6 w-6 shrink-0"
              :class="{'ml-2': !mini}"
          />
          <span v-if="!mini"
                class="grow">مدیریت منو‌ها</span>
        </router-link>
      </li>
      <li v-tooltip.left="'' + (mini ? 'مدیریت فایل‌ها' : '') + ''">
        <router-link :to="{name: 'admin.file_manager'}"
                     class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                     :class="[mini ? 'justify-center px-2' : 'px-3']"
        >
          <FolderOpenIcon
              class="h-6 w-6 shrink-0"
              :class="{'ml-2': !mini}"
          />
          <span v-if="!mini"
                class="grow">مدیریت فایل‌ها</span>
        </router-link>
      </li>
      <li v-tooltip.left="'' + (mini ? 'راهنما' : '') + ''">
        <router-link :to="{name: 'admin.guides'}"
                     class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
                     :class="[mini ? 'justify-center px-2' : 'px-3']"
        >
          <FlagIcon
              class="h-6 w-6 shrink-0"
              :class="{'ml-2': !mini}"
          />
          <span v-if="!mini"
                class="grow">راهنما</span>
        </router-link>
      </li>
    </ul>
  </div>
</template>

<script setup>
import {
  CreditCardIcon, PaintBrushIcon, UsersIcon, WindowIcon,
  TagIcon, Square2StackIcon, GiftIcon, HashtagIcon, ReceiptPercentIcon,
  ArchiveBoxIcon, ShoppingBagIcon, ArchiveBoxXMarkIcon, RectangleStackIcon,
  DocumentTextIcon, DocumentIcon, DevicePhoneMobileIcon, ScaleIcon,
  QuestionMarkCircleIcon, NewspaperIcon, CurrencyDollarIcon, RectangleGroupIcon,
  ViewColumnsIcon, FolderOpenIcon, FlagIcon, ListBulletIcon,
} from "@heroicons/vue/24/outline/index.js"
import SidebarLinkSubAdmin from "./SidebarLinkSubAdmin.vue";
import PartialSidebarItemAdmin from "../partials/PartialSidebarItemAdmin.vue";

defineProps({
  mini: {
    type: Boolean,
    default: false,
  },
})
</script>

<style scoped>
.router-link-active,
.router-link-exact-active {
  position: relative;
  background: linear-gradient(45deg, transparent, rgba(255, 255, 255, .1), transparent, rgba(255, 255, 255, .1));
  color: #fff;
}

.router-link-active:before,
.router-link-exact-active:before {
  content: '';
  position: absolute;
  top: 50%;
  right: 0;
  width: .325rem;
  height: 50%;
  border-radius: 50rem 0 0 50rem;
  background-color: #fff;
  transform: translate(0, -50%);
}
</style>
