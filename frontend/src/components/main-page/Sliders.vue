<template>
  <div
    v-for="(slider, idx) in sliders"
    :key="idx"
    class="px-3 mb-6 lg:mb-8"
  >
    <template v-if="slider.type === SLIDER_PLACES.MAIN_SLIDERS.value">
      <partial-general-title
        :title="slider.title"
        title-size="text-lg"
      >
        <template #extra>
          <router-link
            v-if="slider?.options?.show_all_link && slider.options.show_all_link.trim() !== ''"
            :to="slider.options?.show_all_link"
            class="text-sm text-blue-600 hover:opacity-80 transition"
          >
            مشاهده همه
          </router-link>
        </template>
      </partial-general-title>
      <product-carousel :products="slider.items"/>
    </template>

    <template v-else-if="slider.type === SLIDER_PLACES.MAIN_SLIDER_IMAGES.value">
      <div
        :class="gridImageClass[slider?.options?.beside_images ?? 1]"
        class="grid gap-4"
      >
        <div
          v-for="(item, idx) in slider.items"
          :key="idx"
        >
          <router-link
            :to="item?.link"
            class="block rounded-lg shadow"
          >
            <img
              :src="item?.image.path"
              alt=""
              class="w-full h-auto object-contain rounded-lg"
            />
          </router-link>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
import {ref} from "vue";
import PartialGeneralTitle from "../partials/PartialGeneralTitle.vue";
import ProductCarousel from "../product/ProductCarousel.vue";
import {SLIDER_PLACES} from "../../composables/constants.js";

const products = ref([
  {
    id: 1,
    brand_id: 2,
    category_id: 5,
    title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
    brand: {
      name: 'سامسونگ',
    },
    category: {
      name: 'موبایل',
    },
    image: {
      name: 'samsung_s23_ultra',
      path: '/src/assets/product/g1.jpg',
    },
    festivals: [
      {
        festival_id: 1,
        product_id: 1,
        discount_percentage: 2,
      },
    ],
    products: [
      {
        id: 1,
        color_name: 'مشکی',
        color_hex: '#000000',
        size: null,
        guarantee: null,
        price: 49500000,
        discounted_price: 48500000,
        stock_count: 8,
        max_cart_count: 4,
        is_available: true,
        is_special: false,
        show_coming_soon: false,
        show_call_for_more: false,
      },
    ],
  },
  {
    id: 2,
    brand_id: 2,
    category_id: 5,
    title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
    brand: {
      name: 'سامسونگ',
    },
    category: {
      name: 'موبایل',
    },
    image: {
      name: 'samsung_s23_ultra',
      path: '/src/assets/product/g2.jpg',
    },
    festivals: [
      {
        festival_id: 1,
        product_id: 1,
        discount_percentage: 2,
      },
    ],
    products: [
      {
        id: 1,
        color_name: 'مشکی',
        color_hex: '#000000',
        size: 'XL',
        guarantee: 'گارانتی ۱۸ ماهه شرکتی',
        price: 49500000,
        discounted_price: 48500000,
        stock_count: 8,
        max_cart_count: 4,
        is_available: true,
        is_special: false,
        show_coming_soon: false,
        show_call_for_more: false,
      },
      {
        id: 2,
        color_name: 'مشکی',
        color_hex: '#000000',
        size: 'XLL',
        guarantee: 'گارانتی ۱۸ ماهه شرکتی',
        price: 49500000,
        discounted_price: 48500000,
        stock_count: 10,
        max_cart_count: 4,
        is_available: true,
        is_special: false,
        show_coming_soon: true,
        show_call_for_more: false,
      },
      {
        id: 3,
        color_name: 'کرم',
        color_hex: '#F3ECE2',
        size: 'L',
        guarantee: 'گارانتی ۱۸ ماهه شرکتی',
        price: 49700000,
        discounted_price: 48700000,
        stock_count: 5,
        max_cart_count: 3,
        is_available: true,
        is_special: true,
        show_coming_soon: false,
        show_call_for_more: false,
      },
      {
        id: 4,
        color_name: 'بنفش',
        color_hex: '#E7DBE5',
        size: 'L',
        guarantee: 'گارانتی ۲۴ ماهه شرکتی',
        price: 49950000,
        discounted_price: 48950000,
        stock_count: 5,
        max_cart_count: 2,
        is_available: true,
        is_special: false,
        show_coming_soon: false,
        show_call_for_more: false,
      },
    ],
  },
  {
    id: 3,
    brand_id: 2,
    category_id: 5,
    title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
    brand: {
      name: 'سامسونگ',
    },
    category: {
      name: 'موبایل',
    },
    image: {
      name: 'samsung_s23_ultra',
      path: '/src/assets/product/g3.jpg',
    },
    festivals: [
      {
        festival_id: 1,
        product_id: 1,
        discount_percentage: 2,
      },
    ],
    products: [
      {
        id: 1,
        color_name: 'مشکی',
        color_hex: '#000000',
        size: 'XL',
        guarantee: 'گارانتی ۱۸ ماهه شرکتی',
        price: 49500000,
        discounted_price: 48500000,
        stock_count: 8,
        max_cart_count: 4,
        is_available: true,
        is_special: false,
        show_coming_soon: false,
        show_call_for_more: false,
      },
      {
        id: 2,
        color_name: 'مشکی',
        color_hex: '#000000',
        size: 'XLL',
        guarantee: 'گارانتی ۱۸ ماهه شرکتی',
        price: 49500000,
        discounted_price: 48500000,
        stock_count: 10,
        max_cart_count: 4,
        is_available: true,
        is_special: false,
        show_coming_soon: true,
        show_call_for_more: false,
      },
      {
        id: 3,
        color_name: 'کرم',
        color_hex: '#F3ECE2',
        size: 'L',
        guarantee: 'گارانتی ۱۸ ماهه شرکتی',
        price: 49700000,
        discounted_price: 48700000,
        stock_count: 5,
        max_cart_count: 3,
        is_available: true,
        is_special: true,
        show_coming_soon: false,
        show_call_for_more: false,
      },
      {
        id: 4,
        color_name: 'بنفش',
        color_hex: '#E7DBE5',
        size: 'L',
        guarantee: 'گارانتی ۲۴ ماهه شرکتی',
        price: 49950000,
        discounted_price: 48950000,
        stock_count: 5,
        max_cart_count: 2,
        is_available: true,
        is_special: false,
        show_coming_soon: false,
        show_call_for_more: false,
      },
    ],
  },
  {
    id: 4,
    brand_id: 2,
    category_id: 5,
    title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
    brand: {
      name: 'سامسونگ',
    },
    category: {
      name: 'موبایل',
    },
    image: {
      name: 'samsung_s23_ultra',
      path: '/src/assets/product/g4.jpg',
    },
    festivals: [
      {
        festival_id: 1,
        product_id: 1,
        discount_percentage: 2,
      },
    ],
    products: [
      {
        id: 1,
        color_name: 'مشکی',
        color_hex: '#000000',
        size: null,
        guarantee: null,
        price: 49500000,
        discounted_price: 48500000,
        stock_count: 8,
        max_cart_count: 4,
        is_available: true,
        is_special: false,
        show_coming_soon: false,
        show_call_for_more: false,
      },
    ],
  },
  {
    id: 5,
    brand_id: 2,
    category_id: 5,
    title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
    brand: {
      name: 'سامسونگ',
    },
    category: {
      name: 'موبایل',
    },
    image: {
      name: 'samsung_s23_ultra',
      path: '/src/assets/product/g5.jpg',
    },
    festivals: [
      {
        festival_id: 1,
        product_id: 1,
        discount_percentage: 2,
      },
    ],
    products: [
      {
        id: 1,
        color_name: null,
        color_hex: null,
        size: 'XL',
        guarantee: null,
        price: 49500000,
        discounted_price: 48500000,
        stock_count: 8,
        max_cart_count: 4,
        is_available: true,
        is_special: false,
        show_coming_soon: false,
        show_call_for_more: true,
      },
      {
        id: 2,
        color_name: 'مشکی',
        color_hex: '#000000',
        size: 'XLL',
        guarantee: 'گارانتی ۱۸ ماهه شرکتی',
        price: 49500000,
        discounted_price: 48500000,
        stock_count: 0,
        max_cart_count: 4,
        is_available: true,
        is_special: false,
        show_coming_soon: false,
        show_call_for_more: false,
      },
      {
        id: 3,
        color_name: 'کرم',
        color_hex: '#F3ECE2',
        size: 'L',
        guarantee: 'گارانتی ۱۸ ماهه شرکتی',
        price: 49700000,
        discounted_price: 48700000,
        stock_count: 5,
        max_cart_count: 3,
        is_available: true,
        is_special: true,
        show_coming_soon: false,
        show_call_for_more: false,
      },
      {
        id: 4,
        color_name: 'بنفش',
        color_hex: '#E7DBE5',
        size: 'L',
        guarantee: 'گارانتی ۲۴ ماهه شرکتی',
        price: 49950000,
        discounted_price: 48950000,
        stock_count: 5,
        max_cart_count: 2,
        is_available: true,
        is_special: false,
        show_coming_soon: false,
        show_call_for_more: false,
      },
    ],
  },
  {
    id: 6,
    brand_id: 2,
    category_id: 5,
    title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
    brand: {
      name: 'سامسونگ',
    },
    category: {
      name: 'موبایل',
    },
    image: {
      name: 'samsung_s23_ultra',
      path: '/src/assets/product/g6.jpg',
    },
    festivals: [
      {
        festival_id: 1,
        product_id: 1,
        discount_percentage: 2,
      },
    ],
    products: [
      {
        id: 1,
        color_name: 'مشکی',
        color_hex: '#000000',
        size: 'XL',
        guarantee: 'گارانتی ۱۸ ماهه شرکتی',
        price: 49500000,
        discounted_price: 48500000,
        stock_count: 8,
        max_cart_count: 4,
        is_available: true,
        is_special: false,
        show_coming_soon: false,
        show_call_for_more: false,
      },
      {
        id: 2,
        color_name: 'مشکی',
        color_hex: '#000000',
        size: 'XLL',
        guarantee: 'گارانتی ۱۸ ماهه شرکتی',
        price: 49500000,
        discounted_price: 48500000,
        stock_count: 10,
        max_cart_count: 4,
        is_available: true,
        is_special: false,
        show_coming_soon: true,
        show_call_for_more: false,
      },
      {
        id: 3,
        color_name: 'کرم',
        color_hex: '#F3ECE2',
        size: 'L',
        guarantee: 'گارانتی ۱۸ ماهه شرکتی',
        price: 49700000,
        discounted_price: 48700000,
        stock_count: 5,
        max_cart_count: 3,
        is_available: true,
        is_special: true,
        show_coming_soon: false,
        show_call_for_more: false,
      },
      {
        id: 4,
        color_name: 'بنفش',
        color_hex: '#E7DBE5',
        size: 'L',
        guarantee: 'گارانتی ۲۴ ماهه شرکتی',
        price: 49950000,
        discounted_price: 48950000,
        stock_count: 5,
        max_cart_count: 2,
        is_available: true,
        is_special: false,
        show_coming_soon: false,
        show_call_for_more: false,
      },
    ],
  },
])

const gridImageClass = {
  1: 'grid-cols-1',
  2: 'grid-cols-1 md:grid-cols-2',
  3: 'grid-cols-1 md:grid-cols-3',
  4: 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
}
const sliders = ref([
  {
    title: 'تصویر دوتایی',
    type: SLIDER_PLACES.MAIN_SLIDER_IMAGES.value,
    items: [
      {
        image: {
          path: '/src/assets/banners/m2-1.jpg',
        },
        link: '#',
      },
      {
        image: {
          path: '/src/assets/banners/m2-2.jpg',
        },
        link: '#',
      },
    ],
    options: {
      beside_images: '2',
    },
  },
  {
    title: 'محصولات ویژه',
    type: SLIDER_PLACES.MAIN_SLIDERS.value,
    items: products,
  },
  {
    title: 'جدیدترین محصولات',
    type: SLIDER_PLACES.MAIN_SLIDERS.value,
    items: products,
    options: {
      show_all_link: '#',
    },
  },
  {
    title: 'تصویر چهارتایی',
    type: SLIDER_PLACES.MAIN_SLIDER_IMAGES.value,
    items: [
      {
        image: {
          path: '/src/assets/banners/m3-1.jpg',
        },
        link: '#',
      },
      {
        image: {
          path: '/src/assets/banners/m3-2.gif',
        },
        link: '#',
      },
      {
        image: {
          path: '/src/assets/banners/m3-3.jpg',
        },
        link: '#',
      },
      {
        image: {
          path: '/src/assets/banners/m3-4.jpg',
        },
        link: '#',
      },
    ],
    options: {
      beside_images: '4',
    },
  },
  {
    title: 'سامسونگ',
    type: SLIDER_PLACES.MAIN_SLIDERS.value,
    items: products,
  },
  {
    title: 'اسنوا',
    type: SLIDER_PLACES.MAIN_SLIDERS.value,
    items: products,
  },
  {
    title: 'تصویر تکی',
    type: SLIDER_PLACES.MAIN_SLIDER_IMAGES.value,
    items: [
      {
        image: {
          path: '/src/assets/banners/m1.jpg',
        },
        link: '#',
      },
    ],
    options: {
      beside_images: '1',
    },
  },
])

</script>
