<template>
  <div>
    <ul class="flex flex-col">
      <li
          v-tooltip.left="'' + (mini ? 'اصلی' : '') + ''"
          :class="{'text-center': mini}"
          class="text-white opacity-70 text-xs px-3 mb-1 mt-5"
      >
        <span v-if="mini" class="rounded-full w-2 h-2 bg-white inline-block"></span>
        <span v-else>اصلی</span>
      </li>
      <li v-tooltip.left="'' + (mini ? 'پیشخوان' : '') + ''">
        <router-link
            :class="[mini ? 'justify-center px-2' : 'px-3']"
            :to="{name: 'admin.home'}"
            class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <WindowIcon
              :class="{'ml-2': !mini}"
              class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">پیشخوان</span>
        </router-link>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.USER, PERMISSIONS.READ)"
        v-tooltip.left="'' + (mini ? 'کاربران' : '') + ''"
      >
        <router-link
            :class="[mini ? 'justify-center px-2' : 'px-3']"
            :to="{name: 'admin.users'}"
            class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <UsersIcon
              :class="{'ml-2': !mini}"
              class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">کاربران</span>
        </router-link>
      </li>
    </ul>
  </div>

  <div v-if="userStore.hasPermission(PERMISSION_PLACES.PRODUCT, PERMISSIONS.READ)">
    <ul class="flex flex-col">
      <li
          v-tooltip.left="'' + (mini ? 'فروشگاه' : '') + ''"
          :class="{'text-center': mini}"
          class="text-white opacity-70 text-xs px-3 mb-1 mt-5"
      >
        <span v-if="mini" class="rounded-full w-2 h-2 bg-white inline-block"></span>
        <span v-else>فروشگاه</span>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.PAYMENT_METHOD, PERMISSIONS.READ)"
        v-tooltip.left="'' + (mini ? 'روش‌های پرداخت' : '') + ''"
      >
        <router-link
            :class="[mini ? 'justify-center px-2' : 'px-3']"
            :to="{name: 'admin.payment_methods'}"
            class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <CreditCardIcon
              :class="{'ml-2': !mini}"
              class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">روش‌های پرداخت</span>
        </router-link>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.SEND_METHOD, PERMISSIONS.READ)"
        v-tooltip.left="'' + (mini ? 'روش‌های ارسال' : '') + ''"
      >
        <router-link
            :class="[mini ? 'justify-center px-2' : 'px-3']"
            :to="{name: 'admin.send_methods'}"
            class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <CloudArrowUpIcon
              :class="{'ml-2': !mini}"
              class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">روش‌های ارسال</span>
        </router-link>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.COLOR, PERMISSIONS.READ)"
        v-tooltip.left="'' + (mini ? 'رنگ‌ها' : '') + ''"
      >
        <router-link
            :class="[mini ? 'justify-center px-2' : 'px-3']"
            :to="{name: 'admin.colors'}"
            class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <PaintBrushIcon
              :class="{'ml-2': !mini}"
              class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">رنگ‌ها</span>
        </router-link>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.BRAND, PERMISSIONS.READ)"
        v-tooltip.left="'' + (mini ? 'برندها' : '') + ''"
      >
        <router-link
            :class="[mini ? 'justify-center px-2' : 'px-3']"
            :to="{name: 'admin.brands'}"
            class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <TagIcon
              :class="{'ml-2': !mini}"
              class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">برندها</span>
        </router-link>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.CATEGORY, PERMISSIONS.READ)"
        v-tooltip.left="'' + (mini ? 'مدیریت دسته‌بندی‌ها' : '') + ''"
      >
        <router-link
            :class="[mini ? 'justify-center px-2' : 'px-3']"
            :to="{name: 'admin.categories'}"
            class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <Square2StackIcon
              :class="{'ml-2': !mini}"
              class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">مدیریت دسته‌بندی‌ها</span>
        </router-link>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.FESTIVAL, PERMISSIONS.READ)"
        v-tooltip.left="'' + (mini ? 'جشنواره‌ها' : '') + ''"
      >
        <router-link
            :class="[mini ? 'justify-center px-2' : 'px-3']"
            :to="{name: 'admin.festivals'}"
            class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <GiftIcon
              :class="{'ml-2': !mini}"
              class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">جشنواره‌ها</span>
        </router-link>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.UNIT, PERMISSIONS.READ)"
        v-tooltip.left="'' + (mini ? 'واحدها' : '') + ''"
      >
        <router-link
            :class="[mini ? 'justify-center px-2' : 'px-3']"
            :to="{name: 'admin.units'}"
            class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <HashtagIcon
              :class="{'ml-2': !mini}"
              class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">واحدها</span>
        </router-link>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.COUPON, PERMISSIONS.READ)"
        v-tooltip.left="'' + (mini ? 'کوپن‌های تخفیف' : '') + ''"
      >
        <router-link
            :class="[mini ? 'justify-center px-2' : 'px-3']"
            :to="{name: 'admin.coupons'}"
            class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <ReceiptPercentIcon
              :class="{'ml-2': !mini}"
              class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">کوپن‌های تخفیف</span>
        </router-link>
      </li>
      <li v-if="userStore.hasPermission(PERMISSION_PLACES.PRODUCT, PERMISSIONS.READ)">
        <sidebar-link-sub-admin :mini="mini">
          <template #link="{open}">
            <router-link
                v-tooltip.left="'' + (mini ? 'محصولات' : '') + ''"
                :class="{'justify-center w-full': mini, 'bg-white bg-opacity-10': open}"
                :to="{name: 'admin.products'}"
                class="rounded-lg py-2.5 px-3 flex items-center hover:bg-white hover:bg-opacity-10 transition grow"
            >
              <ArchiveBoxIcon
                  :class="{'ml-2': !mini}"
                  class="h-6 w-6 shrink-0"
              />
              <span v-if="!mini" class="grow">محصولات</span></router-link>
          </template>

          <template
            v-if="userStore.hasPermission(PERMISSION_PLACES.PRODUCT_ATTRIBUTE, PERMISSIONS.READ)"
            #children
          >
            <partial-sidebar-item-admin>
              <router-link
                  v-tooltip.left="'' + (mini ? 'ویژگی‌های جستجوی محصول' : '') + ''"
                  :class="[mini ? 'justify-center px-2' : 'px-3']"
                  :to="{name: 'admin.search.attrs'}"
                  class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
              >
                <ListBulletIcon v-if="mini" class="w-5 h-5"/>
                <span v-else
                      class="grow">ویژگی‌های جستجوی محصول</span>
              </router-link>
              <router-link
                  v-tooltip.left="'' + (mini ? 'دسته‌بندی‌ها و ویژگی‌های جستجوی' : '') + ''"
                  :class="[mini ? 'justify-center px-2' : 'px-3']"
                  :to="{name: 'admin.search.attrs.categories'}"
                  class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
              >
                <ListBulletIcon v-if="mini" class="w-5 h-5"/>
                <span v-else
                      class="grow">دسته‌بندی‌ها و ویژگی‌های جستجوی</span>
              </router-link>
            </partial-sidebar-item-admin>
          </template>
        </sidebar-link-sub-admin>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.PRODUCT_COMMENT, PERMISSIONS.READ)"
        v-tooltip.left="'' + (mini ? 'دیدگاه‌های محصولات' : '') + ''"
      >
        <router-link
          :class="[mini ? 'justify-center px-2' : 'px-3']"
          :to="{name: 'admin.products.comments'}"
          class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <ChatBubbleLeftRightIcon
            :class="{'ml-2': !mini}"
            class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">دیدگاه‌های محصولات</span>
        </router-link>
      </li>
    </ul>
  </div>

  <div
    v-if="userStore.hasPermission([
      PERMISSION_PLACES.ORDER,
      PERMISSION_PLACES.RETURN_ORDER_REQUEST,
    ], PERMISSIONS.READ)"
  >
    <ul class="flex flex-col">
      <li
          v-tooltip.left="'' + (mini ? 'مدیریت سفارشات' : '') + ''"
          :class="{'text-center': mini}"
          class="text-white opacity-70 text-xs px-3 mb-1 mt-5"
      >
        <span v-if="mini" class="rounded-full w-2 h-2 bg-white inline-block"></span>
        <span v-else>مدیریت سفارشات</span>
      </li>
      <li v-if="userStore.hasPermission(PERMISSION_PLACES.ORDER, PERMISSIONS.READ)">
        <sidebar-link-sub-admin :mini="mini">
          <template #link="{open}">
            <router-link
                v-tooltip.left="'' + (mini ? 'سفارشات ثبت شده' : '') + ''"
                :class="{'justify-center w-full': mini, 'bg-white bg-opacity-10': open}"
                :to="{name: 'admin.orders'}"
                class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition grow"
            >
              <ShoppingBagIcon
                  :class="{'ml-2': !mini}"
                  class="h-6 w-6 shrink-0"
              />
              <span v-if="!mini" class="grow">سفارشات ثبت شده</span>
            </router-link>
          </template>

          <template
            v-if="userStore.hasPermission(PERMISSION_PLACES.ORDER_BADGE, PERMISSIONS.READ)"
            #children
          >
            <partial-sidebar-item-admin>
              <router-link
                  v-tooltip.left="'' + (mini ? 'برچسب وضعیت سفارشات' : '') + ''"
                  :class="[mini ? 'justify-center px-2' : 'px-3']"
                  :to="{name: 'admin.orders.badges'}"
                  class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
              >
                <ListBulletIcon v-if="mini" class="w-5 h-5"/>
                <span v-else
                      class="grow">برچسب وضعیت سفارشات</span>
              </router-link>
            </partial-sidebar-item-admin>
          </template>
        </sidebar-link-sub-admin>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.RETURN_ORDER_REQUEST, PERMISSIONS.READ)"
        v-tooltip.left="'' + (mini ? 'سفارشات مرجوعی' : '') + ''"
      >
        <router-link
            :class="[mini ? 'justify-center px-2' : 'px-3']"
            :to="{name: 'admin.return_orders'}"
            class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <ArchiveBoxXMarkIcon
              :class="{'ml-2': !mini}"
              class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">سفارشات مرجوعی</span>
        </router-link>
      </li>
    </ul>
  </div>

  <div v-if="userStore.hasAnyRole([ROLES.DEVELOPER, ROLES.SUPER_ADMIN]) ||
   userStore.hasPermission([PERMISSION_PLACES.USER, PERMISSION_PLACES.PRODUCT, PERMISSION_PLACES.ORDER], PERMISSIONS.EXPORT)">
    <ul class="flex flex-col">
      <li
          v-tooltip.left="'' + (mini ? 'گزارش گیری' : '') + ''"
          :class="{'text-center': mini}"
          class="text-white opacity-70 text-xs px-3 mb-1 mt-5"
      >
        <span v-if="mini" class="rounded-full w-2 h-2 bg-white inline-block"></span>
        <span v-else>گزارش گیری</span>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.USER, PERMISSIONS.EXPORT)"
        v-tooltip.left="'' + (mini ? 'گزارش‌گیری از کاربران' : '') + ''"
      >
        <router-link
            :class="[mini ? 'justify-center px-2' : 'px-3']"
            :to="{name: 'admin.report.users'}"
            class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <RectangleStackIcon
              :class="{'ml-2': !mini}"
              class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">گزارش‌گیری از کاربران</span>
        </router-link>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.PRODUCT, PERMISSIONS.EXPORT)"
        v-tooltip.left="'' + (mini ? 'گزارش‌گیری از محصولات' : '') + ''"
      >
        <router-link
            :class="[mini ? 'justify-center px-2' : 'px-3']"
            :to="{name: 'admin.report.products'}"
            class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <RectangleStackIcon
              :class="{'ml-2': !mini}"
              class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">گزارش‌گیری از محصولات</span>
        </router-link>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.ORDER, PERMISSIONS.EXPORT)"
        v-tooltip.left="'' + (mini ? 'گزارش‌گیری از سفارشات' : '') + ''"
      >
        <router-link
            :class="[mini ? 'justify-center px-2' : 'px-3']"
            :to="{name: 'admin.report.orders'}"
            class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <RectangleStackIcon
              :class="{'ml-2': !mini}"
              class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">گزارش‌گیری از سفارشات</span>
        </router-link>
      </li>
    </ul>
  </div>

  <div
    v-if="userStore.hasPermission([
      PERMISSION_PLACES.BLOG,
      PERMISSION_PLACES.BLOG_COMMENT,
      PERMISSION_PLACES.STATIC_PAGE,
    ], PERMISSIONS.READ)"
  >
    <ul class="flex flex-col">
      <li
          v-tooltip.left="'' + (mini ? 'صفحات' : '') + ''"
          :class="{'text-center': mini}"
          class="text-white opacity-70 text-xs px-3 mb-1 mt-5"
      >
        <span v-if="mini" class="rounded-full w-2 h-2 bg-white inline-block"></span>
        <span v-else>صفحات</span>
      </li>
      <li v-if="userStore.hasPermission(PERMISSION_PLACES.BLOG, PERMISSIONS.READ)">
        <sidebar-link-sub-admin :mini="mini">
          <template #link="{open}">
            <router-link
                v-tooltip.left="'' + (mini ? 'بلاگ' : '') + ''"
                :class="{'justify-center w-full': mini, 'bg-white bg-opacity-10': open}"
                :to="{name: 'admin.blogs'}"
                class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition grow"
            >
              <DocumentTextIcon
                  :class="{'ml-2': !mini}"
                  class="h-6 w-6 shrink-0"
              />
              <span v-if="!mini" class="grow">بلاگ</span>
            </router-link>
          </template>

          <template
            v-if="userStore.hasPermission([
              PERMISSION_PLACES.BLOG_COMMENT_BADGE,
              PERMISSION_PLACES.BLOG_CATEGORY,
            ], PERMISSIONS.READ)"
            #children
          >
            <partial-sidebar-item-admin>
              <router-link
                v-if="userStore.hasPermission(PERMISSION_PLACES.BLOG_COMMENT_BADGE, PERMISSIONS.READ)"
                  v-tooltip.left="'' + (mini ? 'برچسب دیدگاه‌ها' : '') + ''"
                  :class="[mini ? 'justify-center px-2' : 'px-3']"
                  :to="{name: 'admin.blogs.badges'}"
                  class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
              >
                <ListBulletIcon v-if="mini" class="w-5 h-5"/>
                <span v-else
                      class="grow">برچسب دیدگاه‌ها</span>
              </router-link>
            </partial-sidebar-item-admin>
            <partial-sidebar-item-admin>
              <router-link
                v-if="userStore.hasPermission(PERMISSION_PLACES.BLOG_CATEGORY, PERMISSIONS.READ)"
                  v-tooltip.left="'' + (mini ? 'مدیریت دسته‌بندی‌ها' : '') + ''"
                  :class="[mini ? 'justify-center px-2' : 'px-3']"
                  :to="{name: 'admin.blogs.categories'}"
                  class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
              >
                <ListBulletIcon v-if="mini" class="w-5 h-5"/>
                <span v-else
                      class="grow">مدیریت دسته‌بندی‌ها</span>
              </router-link>
            </partial-sidebar-item-admin>
          </template>
        </sidebar-link-sub-admin>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.BLOG_COMMENT, PERMISSIONS.READ)"
        v-tooltip.left="'' + (mini ? 'دیدگاه‌های بلاگ' : '') + ''"
      >
        <router-link
          :class="[mini ? 'justify-center px-2' : 'px-3']"
          :to="{name: 'admin.blogs.comments'}"
          class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <ChatBubbleLeftRightIcon
            :class="{'ml-2': !mini}"
            class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">دیدگاه‌های بلاگ</span>
        </router-link>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.STATIC_PAGE, PERMISSIONS.READ)"
        v-tooltip.left="'' + (mini ? 'صفحات ثابت' : '') + ''"
      >
        <router-link
            :class="[mini ? 'justify-center px-2' : 'px-3']"
            :to="{name: 'admin.static_pages'}"
            class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <DocumentIcon
              :class="{'ml-2': !mini}"
              class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">صفحات ثابت</span>
        </router-link>
      </li>
    </ul>
  </div>

  <div
    v-if="userStore.hasPermission([
      PERMISSION_PLACES.CONTACT_US,
      PERMISSION_PLACES.COMPLAINT,
      PERMISSION_PLACES.FAQ,
      PERMISSION_PLACES.NEWSLETTER,
    ], PERMISSIONS.READ)"
  >
    <ul class="flex flex-col">
      <li
          v-tooltip.left="'' + (mini ? 'ارتباطات' : '') + ''"
          :class="{'text-center': mini}"
          class="text-white opacity-70 text-xs px-3 mb-1 mt-5"
      >
        <span v-if="mini" class="rounded-full w-2 h-2 bg-white inline-block"></span>
        <span v-else>ارتباطات</span>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.CONTACT_US, PERMISSIONS.READ)"
        v-tooltip.left="'' + (mini ? 'تماس‌ها' : '') + ''"
      >
        <router-link
            :class="[mini ? 'justify-center px-2' : 'px-3']"
            :to="{name: 'admin.contacts'}"
            class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <DevicePhoneMobileIcon
              :class="{'ml-2': !mini}"
              class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">تماس‌ها</span>
        </router-link>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.COMPLAINT, PERMISSIONS.READ)"
        v-tooltip.left="'' + (mini ? 'شکایات' : '') + ''"
      >
        <router-link
            :class="[mini ? 'justify-center px-2' : 'px-3']"
            :to="{name: 'admin.complaints'}"
            class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <ScaleIcon
              :class="{'ml-2': !mini}"
              class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">شکایات</span>
        </router-link>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.FAQ, PERMISSIONS.READ)"
        v-tooltip.left="'' + (mini ? 'سؤالات متداول' : '') + ''"
      >
        <router-link
            :class="[mini ? 'justify-center px-2' : 'px-3']"
            :to="{name: 'admin.faqs'}"
            class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <QuestionMarkCircleIcon
              :class="{'ml-2': !mini}"
              class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">سؤالات متداول</span>
        </router-link>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.NEWSLETTER, PERMISSIONS.READ)"
        v-tooltip.left="'' + (mini ? 'خبرنامه' : '') + ''"
      >
        <router-link
            :class="[mini ? 'justify-center px-2' : 'px-3']"
            :to="{name: 'admin.newsletters'}"
            class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <NewspaperIcon
              :class="{'ml-2': !mini}"
              class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">خبرنامه</span>
        </router-link>
      </li>
    </ul>
  </div>

  <div>
    <ul class="flex flex-col">
      <li
          v-tooltip.left="'' + (mini ? 'سایر' : '') + ''"
          :class="{'text-center': mini}"
          class="text-white opacity-70 text-xs px-3 mb-1 mt-5"
      >
        <span v-if="mini" class="rounded-full w-2 h-2 bg-white inline-block"></span>
        <span v-else>سایر</span>
      </li>
      <li
        v-if="userStore.hasPermission([
          PERMISSION_PLACES.CITY_POST_PRICE,
          PERMISSION_PLACES.WEIGHT_POST_PRICE,
        ], PERMISSIONS.READ)"
      >
        <sidebar-link-sub-admin :mini="mini">
          <template #link="{open}">
            <a
                v-tooltip.left="'' + (mini ? 'هزینه ارسال' : '') + ''"
                :class="{'justify-center': mini, 'bg-white bg-opacity-10': open}"
                class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition w-full"
            >
              <CurrencyDollarIcon
                  :class="{'ml-2': !mini}"
                  class="h-6 w-6 shrink-0"
              />
              <span v-if="!mini" class="grow">هزینه ارسال</span>
            </a>
          </template>

          <template #children>
            <partial-sidebar-item-admin>
              <router-link
                v-if="userStore.hasPermission(PERMISSION_PLACES.CITY_POST_PRICE, PERMISSIONS.READ)"
                  v-tooltip.left="'' + (mini ? 'هزینه ارسال برحسب مکان' : '') + ''"
                  :class="[mini ? 'justify-center px-2' : 'px-3']"
                  :to="{name: 'admin.post_prices.cities'}"
                  class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
              >
                <ListBulletIcon v-if="mini" class="w-5 h-5"/>
                <span v-else
                      class="grow">هزینه ارسال برحسب مکان</span>
              </router-link>
            </partial-sidebar-item-admin>
            <partial-sidebar-item-admin>
              <router-link
                v-if="userStore.hasPermission(PERMISSION_PLACES.WEIGHT_POST_PRICE, PERMISSIONS.READ)"
                  v-tooltip.left="'' + (mini ? 'هزینه ارسال برحسب وزن' : '') + ''"
                  :class="[mini ? 'justify-center px-2' : 'px-3']"
                  :to="{name: 'admin.post_prices.weights'}"
                  class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
              >
                <ListBulletIcon v-if="mini" class="w-5 h-5"/>
                <span v-else
                      class="grow">هزینه ارسال برحسب وزن</span>
              </router-link>
            </partial-sidebar-item-admin>
          </template>
        </sidebar-link-sub-admin>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.SLIDER, PERMISSIONS.READ)"
        v-tooltip.left="'' + (mini ? 'مدیریت اسلایدرها' : '') + ''"
      >
        <router-link
            :class="[mini ? 'justify-center px-2' : 'px-3']"
            :to="{name: 'admin.sliders'}"
            class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <ViewColumnsIcon
              :class="{'ml-2': !mini}"
              class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">مدیریت اسلایدرها</span>
        </router-link>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.MENU, PERMISSIONS.READ)"
        v-tooltip.left="'' + (mini ? 'مدیریت منو‌ها' : '') + ''"
      >
        <router-link
            :class="[mini ? 'justify-center px-2' : 'px-3']"
            :to="{name: 'admin.menus'}"
            class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <RectangleGroupIcon
              :class="{'ml-2': !mini}"
              class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">مدیریت منو‌ها</span>
        </router-link>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.FILE_MANAGER, PERMISSIONS.READ)"
        v-tooltip.left="'' + (mini ? 'مدیریت فایل‌ها' : '') + ''"
      >
        <router-link
            :class="[mini ? 'justify-center px-2' : 'px-3']"
            :to="{name: 'admin.file_manager'}"
            class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <FolderOpenIcon
              :class="{'ml-2': !mini}"
              class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">مدیریت فایل‌ها</span>
        </router-link>
      </li>
      <li
        v-if="userStore.hasPermission(PERMISSION_PLACES.SMS_LOG, PERMISSIONS.READ)"
        v-tooltip.left="'' + (mini ? 'لاگ پیامک' : '') + ''"
      >
        <router-link
            :class="[mini ? 'justify-center px-2' : 'px-3']"
            :to="{name: 'admin.sms_log'}"
            class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"
        >
          <EnvelopeIcon
              :class="{'ml-2': !mini}"
              class="h-6 w-6 shrink-0"
          />
          <span v-if="!mini" class="grow">لاگ پیامک</span>
        </router-link>
      </li>

      <!--      <li v-tooltip.left="'' + (mini ? 'راهنما' : '') + ''">-->
      <!--        <router-link-->
      <!--          :class="[mini ? 'justify-center px-2' : 'px-3']"-->
      <!--          :to="{name: 'admin.guides'}"-->
      <!--          class="rounded-lg py-2.5 flex hover:bg-white hover:bg-opacity-10 transition"-->
      <!--        >-->
      <!--          <FlagIcon-->
      <!--            :class="{'ml-2': !mini}"-->
      <!--            class="h-6 w-6 shrink-0"-->
      <!--          />-->
      <!--          <span v-if="!mini" class="grow">راهنما</span>-->
      <!--        </router-link>-->
      <!--      </li>-->
    </ul>
  </div>
</template>

<script setup>
import {
  ArchiveBoxIcon,
  ArchiveBoxXMarkIcon,
  ChatBubbleLeftRightIcon,
  CloudArrowUpIcon,
  CreditCardIcon,
  CurrencyDollarIcon,
  DevicePhoneMobileIcon,
  DocumentIcon,
  DocumentTextIcon,
  EnvelopeIcon,
  FolderOpenIcon,
  GiftIcon,
  HashtagIcon,
  ListBulletIcon,
  NewspaperIcon,
  PaintBrushIcon,
  QuestionMarkCircleIcon,
  ReceiptPercentIcon,
  RectangleGroupIcon,
  RectangleStackIcon,
  ScaleIcon,
  ShoppingBagIcon,
  Square2StackIcon,
  TagIcon,
  UsersIcon,
  ViewColumnsIcon,
  WindowIcon,
} from "@heroicons/vue/24/outline/index.js"
import SidebarLinkSubAdmin from "./SidebarLinkSubAdmin.vue";
import PartialSidebarItemAdmin from "@/components/partials/PartialSidebarItemAdmin.vue";
import {PERMISSION_PLACES, PERMISSIONS, ROLES, useAdminAuthStore} from "@/store/StoreUserAuth.js";

defineProps({
  mini: {
    type: Boolean,
    default: false,
  },
})

const userStore = useAdminAuthStore()
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
