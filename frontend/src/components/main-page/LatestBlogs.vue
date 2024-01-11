<template>
  <partial-general-title
    type="side"
    title="آخرین نوشته‌ها"
    title-size="text-xl"
    container-class="mb-5 mt-6 p-2"
  />

  <div class="flex flex-wrap">
    <div
      v-for="(blog, idx) in latestBlogs"
      :key="idx"
      class="w-full lg:w-1/3 p-3 rounded-lg"
    >
      <blog-card-main :blog="blog"/>
    </div>
  </div>
</template>

<script setup>
import {ref} from "vue";
import BlogCardMain from "../blog/BlogCardMain.vue";
import PartialGeneralTitle from "../partials/PartialGeneralTitle.vue";

const latestBlogs = ref([
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
])
</script>
