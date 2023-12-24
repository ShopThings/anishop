<template>
  <app-navigation-header title="جستجوی بلاگ"/>

  <div class="mb-6 p-3">
    <div class="flex flex-col lg:flex-row-reverse gap-6 sticky-container">
      <div class="grow">
        <base-paginator
            container-class="flex flex-wrap"
            item-container-class="w-full xl:w-1/2 ml-[-1px] mt-[-1px]"
            pagination-theme="modern"
            :per-page="16"
            :show-search="true"
            :order="blogOrder"
            v-model:items="blogs"
            :is-local="true"
        >
          <template #empty>
            <partial-empty-rows
                image="/empty-statuses/empty-blog.svg"
                image-class="w-60"
                message="هیچ نوشته‌ای پیدا نشد!"
            />
          </template>

          <template #item="{item}">
            <blog-card
                :blog="item"
                container-class=""
            />
          </template>

          <template #loading>
            <div class="border-b">
              <loader-list-single-blog size="large"/>
            </div>
          </template>
        </base-paginator>
      </div>

      <Vue3StickySidebar
          class="shrink-0 lg:w-80"
          containerSelector=".sticky-container"
          innerWrapperSelector='.sidebar__inner'
          :top-spacing="114"
          :bottom-spacing="20"
          :min-width="1024"
      >
        <div class="flex flex-col gap-6">
          <partial-card
              class="border-0 flex flex-col">
            <template #body>
              <partial-general-title
                  type="side"
                  title="دسته‌بندی‌های پرطرفدار"
              />

              <app-side-categories-blog/>
            </template>
          </partial-card>

          <partial-card
              class="border-0 flex flex-col">
            <template #body>
              <partial-general-title
                  type="side"
                  title="آرشیو نوشته‌ها"
              />

              <div class="flex flex-col divide-y">
                <router-link
                    to="#"
                    class="text-sm font-iranyekan-light flex items-center px-3 py-3 hover:bg-slate-50 transition gap-3 group"
                >
                  <ArchiveBoxIcon
                      class="w-5 h-5 text-slate-300 group-hover:text-violet-500"></ArchiveBoxIcon>
                  <span>اردیبهشت ۱۴۰۲</span>
                </router-link>
                <router-link
                    to="#"
                    class="text-sm font-iranyekan-light flex items-center px-3 py-3 hover:bg-slate-50 transition gap-3 group"
                >
                  <ArchiveBoxIcon
                      class="w-5 h-5 text-slate-300 group-hover:text-violet-500"/>
                  <span>خرداد ۱۴۰۲</span>
                </router-link>
                <router-link
                    to="#"
                    class="text-sm font-iranyekan-light flex items-center px-3 py-3 hover:bg-slate-50 transition gap-3 group"
                >
                  <ArchiveBoxIcon
                      class="w-5 h-5 text-slate-300 group-hover:text-violet-500"/>
                  <span>تیر ۱۴۰۲</span>
                </router-link>
                <router-link
                    to="#"
                    class="text-sm font-iranyekan-light flex items-center px-3 py-3 hover:bg-slate-50 transition gap-3 group"
                >
                  <ArchiveBoxIcon
                      class="w-5 h-5 text-slate-300 group-hover:text-violet-500"/>
                  <span>مرداد ۱۴۰۲</span>
                </router-link>
                <router-link
                    to="#"
                    class="text-sm font-iranyekan-light flex items-center px-3 py-3 hover:bg-slate-50 transition gap-3 group"
                >
                  <ArchiveBoxIcon
                      class="w-5 h-5 text-slate-300 group-hover:text-violet-500"/>
                  <span>شهریور ۱۴۰۲</span>
                </router-link>
                <router-link
                    to="#"
                    class="text-sm font-iranyekan-light flex items-center px-3 py-3 hover:bg-slate-50 transition gap-3 group"
                >
                  <ArchiveBoxIcon
                      class="w-5 h-5 text-slate-300 group-hover:text-violet-500"></ArchiveBoxIcon>
                  <span>مهر ۱۴۰۲</span>
                </router-link>
              </div>
            </template>
          </partial-card>
        </div>
      </Vue3StickySidebar>
    </div>
  </div>
</template>

<script setup>
import {ref} from "vue";
import PartialGeneralTitle from "../components/partials/PartialGeneralTitle.vue";
import AppSideCategoriesBlog from "../components/AppSideCategoriesBlog.vue";
import PartialCard from "../components/partials/PartialCard.vue";
import {ArchiveBoxIcon} from "@heroicons/vue/24/outline/index.js";
import Vue3StickySidebar from "vue3-sticky-sidebar";
import BasePaginator from "../components/base/BasePaginator.vue";
import PartialEmptyRows from "../components/partials/PartialEmptyRows.vue";
import BlogCard from "../components/blog/BlogCard.vue";
import LoaderListSingleBlog from "../components/base/loader/LoaderListSingleBlog.vue";
import AppNavigationHeader from "../components/AppNavigationHeader.vue";
import {BLOG_ORDER_TYPES} from "../composables/constants.js";

//----------------------------
// Search Blogs
//----------------------------

const blogOrder = []

// create orders
let counter = 1
for (const t in BLOG_ORDER_TYPES) {
  if (BLOG_ORDER_TYPES.hasOwnProperty(t)) {
    console.log(t)
    blogOrder.push({
      id: counter++,
      key: BLOG_ORDER_TYPES[t].value,
      text: BLOG_ORDER_TYPES[t].text,
    })
  }
}
//

const blogs = ref([
  {
    id: 9,
    title: 'بررسی ماوس لاجیتک G502 Hero؛ اسطوره‌ی قدیمی',
    image: {
      path: '/src/assets/blogs/b9.jpg'
    },
    category: {
      name: 'تعمیر و نگهداری',
    },
    created_at: '۲۵ مهر ۱۴۰۲',
    creator: {
      first_name: 'محمد مهدی',
      last_name: 'دهقان منشادی',
    },
  },
  {
    id: 8,
    title: 'بررسی اسپیکر جوی روم JR-ML03؛ یک اسپیکر آرامش‌بخش',
    image: {
      path: '/src/assets/blogs/b8.jpg'
    },
    category: {
      name: 'تلنت (Talent)',
    },
    created_at: '۲۱ مهر ۱۴۰۲',
    creator: {
      first_name: 'سعید',
      last_name: 'گرامی فر',
    },
  },
  {
    id: 7,
    title: 'راهنمای خرید بهترین هدست اونیکوما؛ ۱۵ محصول جذاب با قیمت عالی',
    image: {
      path: '/src/assets/blogs/b7.jpg'
    },
    category: {
      name: 'اسپیکر و هدفون',
    },
    created_at: '۱۵ شهریور ۱۴۰۲',
    creator: {
      first_name: 'اصغر',
      last_name: 'فرهادی',
    },
  },
  {
    id: 6,
    title: 'بررسی ماوس لاجیتک G502 Hero؛ اسطوره‌ی قدیمی',
    image: {
      path: '/src/assets/blogs/b6.jpg'
    },
    category: {
      name: 'تعمیر و نگهداری',
    },
    created_at: '۲۵ مهر ۱۴۰۲',
    creator: {
      first_name: 'محمد مهدی',
      last_name: 'دهقان منشادی',
    },
  },
  {
    id: 5,
    title: 'بررسی اسپیکر جوی روم JR-ML03؛ یک اسپیکر آرامش‌بخش',
    image: {
      path: '/src/assets/blogs/b5.jpg'
    },
    category: {
      name: 'تلنت (Talent)',
    },
    created_at: '۲۱ مهر ۱۴۰۲',
    creator: {
      first_name: 'سعید',
      last_name: 'گرامی فر',
    },
  },
  {
    id: 4,
    title: 'راهنمای خرید بهترین هدست اونیکوما؛ ۱۵ محصول جذاب با قیمت عالی',
    image: {
      path: '/src/assets/blogs/b4.jpg'
    },
    category: {
      name: 'اسپیکر و هدفون',
    },
    created_at: '۱۵ شهریور ۱۴۰۲',
    creator: {
      first_name: 'اصغر',
      last_name: 'فرهادی',
    },
  },
  {
    id: 3,
    title: 'بررسی ماوس لاجیتک G502 Hero؛ اسطوره‌ی قدیمی',
    image: {
      path: '/src/assets/blogs/b3.jpg'
    },
    category: {
      name: 'تعمیر و نگهداری',
    },
    created_at: '۲۵ مهر ۱۴۰۲',
    creator: {
      first_name: 'محمد مهدی',
      last_name: 'دهقان منشادی',
    },
  },
  {
    id: 2,
    title: 'بررسی اسپیکر جوی روم JR-ML03؛ یک اسپیکر آرامش‌بخش',
    image: {
      path: '/src/assets/blogs/b2.jpg'
    },
    category: {
      name: 'تلنت (Talent)',
    },
    created_at: '۲۱ مهر ۱۴۰۲',
    creator: {
      first_name: 'سعید',
      last_name: 'گرامی فر',
    },
  },
  {
    id: 1,
    title: 'راهنمای خرید بهترین هدست اونیکوما؛ ۱۵ محصول جذاب با قیمت عالی',
    image: {
      path: '/src/assets/blogs/b1.jpg'
    },
    category: {
      name: 'اسپیکر و هدفون',
    },
    created_at: '۱۵ شهریور ۱۴۰۲',
    creator: {
      first_name: 'اصغر',
      last_name: 'فرهادی',
    },
  },
])

//----------------------------
</script>

<style scoped>

</style>
