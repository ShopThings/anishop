<template>
  <ul ref="bgMovement" class="circles">
    <li class="bg-pink-400"></li>
    <li class="bg-pink-400"></li>
    <li class="bg-pink-400"></li>
    <li class="bg-pink-400"></li>
    <li class="bg-pink-400"></li>
    <li class="bg-pink-400"></li>
    <li class="bg-pink-400"></li>
    <li class="bg-pink-400"></li>
    <li class="bg-pink-400"></li>
    <li class="bg-pink-400"></li>
    <li class="bg-pink-400"></li>
    <li class="bg-pink-400"></li>
    <li class="bg-pink-400"></li>
    <li class="bg-pink-400"></li>
    <li class="bg-pink-400"></li>
  </ul>

  <div ref="mainPageContainer">
    <app-navigation-header
      container-class="text-center"
      title="بلاگ آیریا کالا"
    />

    <div class="p-3 grid grid-cols-3 gap-6">
      <div class="col-span-3 lg:col-span-2">
        <base-carousel
          v-slot="{slide, index}"
          v-model="slides"
          v-model:current="currentSlide"
          :class-name="carouselSettings.className"
          :has-navigation="carouselSettings.hasNavigation"
          :navigation-position="carouselSettings.navigationPosition"
          :navigation-display="carouselSettings.navigationDisplay"
          :has-pagination="carouselSettings.hasPagination"
          :breakpoints="carouselSettings.breakpoints"
          :autoplay="carouselSettings.autoplay"
          :wrap-around="carouselSettings.wrapAround"
          :effect="carouselSettings.effect"
        >
          <div class="bg-white rounded-lg relative">
            <base-lazy-image
              :lazy-src="slide.path"
              :alt="slide.title"
              class="rounded-lg h-[30rem] w-full !object-cover"
            />

            <div
              class="absolute z-[1] bg-gradient-to-t from-slate-950/70 bottom-0 w-full h-full rounded-b-lg pt-6 px-6 pb-16 text-shadow flex items-end">
              <div>
                <router-link
                  to="#"
                  class="text-xs rounded-full py-1.5 px-4 inline-block mb-3 bg-indigo-600 text-white hover:bg-indigo-500 transition"
                >
                  {{ slide.category }}
                </router-link>
                <h1 class="w-full text-right">

                  <router-link
                    to="#"
                    class="text-xl text-white leading-relaxed hover:text-opacity-80 transition"
                  >
                    {{ slide.title }}
                  </router-link>
                </h1>
              </div>
            </div>
          </div>
        </base-carousel>
      </div>

      <div class="col-span-3 lg:col-span-1 flex flex-col sm:flex-row lg:flex-col gap-6">
        <div class="relative w-full">
          <base-lazy-image
            lazy-src="/src/assets/blogs/b4.jpg"
            alt="بلاگ 1"
            class="rounded-lg h-[calc(15rem-12px)] w-full !object-cover"
          />

          <div
            class="absolute z-[1] bg-gradient-to-t from-slate-950/70 bottom-0 w-full h-full rounded-b-lg pt-6 px-6 pb-9 text-shadow flex items-end">
            <h1 class="w-full text-right">
              <router-link
                to="#"
                class="text-lg text-white leading-relaxed hover:text-opacity-80 transition"
              >
                داستان ساخت Civilization II؛ اثری که تصادفا آینده‌ی محتمل جهان را پیشگویی کرد
              </router-link>

              <router-link
                to="#"
                class="text-xs rounded-tr-lg rounded-bl-lg py-1.5 px-4 bg-indigo-600 text-white absolute left-0 bottom-0 hover:bg-indigo-500 transition"
              >
                خلاقیت
              </router-link>
            </h1>
          </div>
        </div>
        <div class="relative w-full">
          <base-lazy-image
            lazy-src="/src/assets/blogs/b5.jpg"
            alt="بلاگ 2"
            class="rounded-lg h-[calc(15rem-12px)] w-full !object-cover"
          />

          <div
            class="absolute z-[1] bg-gradient-to-t from-slate-950/70 bottom-0 w-full h-full rounded-b-lg pt-6 px-6 pb-9 text-shadow flex items-end">
            <h1 class="w-full text-right">
              <router-link
                to="#"
                class="text-lg text-white leading-relaxed hover:text-opacity-80 transition"
              >
                بررسی پاوربانک بی‌سیم سامسونگ EB-U1200؛ مناسب گوشی، ساعت و هدفون
              </router-link>

              <router-link
                to="#"
                class="text-xs rounded-tr-lg rounded-bl-lg py-1.5 px-4 bg-indigo-600 text-white absolute left-0 bottom-0 hover:bg-indigo-500 transition"
              >
                نوآوری
              </router-link>
            </h1>
          </div>
        </div>
      </div>
    </div>

    <div class="mb-6 p-3">
      <div class="flex flex-col lg:flex-row gap-3">
        <div class="grow">
          <partial-general-title
            container-class="mb-2 p-2"
            title="جدیدترین نوشته‌ها"
          />

          <div class="flex flex-wrap items-start">
            <div
              v-for="blog in latestBlogs"
              :key="blog.id"
              class="w-full md:w-1/2 xl:w-1/3 p-3"
            >
              <blog-card
                type="vertical"
                :blog="blog"
                container-class="border-0 rounded-lg supports-[backdrop-filter]:backdrop-blur-sm supports-[backdrop-filter]:bg-opacity-80"
              />
            </div>
          </div>

          <div class="mt-5 text-left p-3 flex items-center">
            <div class="h-0.5 rounded-full grow bg-slate-200"></div>
            <base-button
              type="link"
              :to="{name: 'blog.search'}"
              class="border-2 border-slate-200 px-5 !text-black bg-white bg-opacity-50 text-sm flex items-center gap-3 group shrink-0"
            >
              <ArrowLongRightIcon class="w-6 h-6 group-hover:translate-x-1 transition"/>
              <span>مشاهده تمامی نوشته‌ها</span>
            </base-button>
          </div>
        </div>

        <div class="shrink-0 lg:w-80 flex flex-col gap-6">
          <partial-card
            class="border-0 flex flex-col supports-[backdrop-filter]:backdrop-blur-sm supports-[backdrop-filter]:bg-opacity-80">
            <template #body>
              <partial-general-title
                type="side"
                title="دسته‌بندی‌های پرطرفدار"
              />

              <app-side-categories-blog/>
            </template>
          </partial-card>

          <partial-card
            class="border-0 flex flex-col supports-[backdrop-filter]:backdrop-blur-sm supports-[backdrop-filter]:bg-opacity-80">
            <template #body>
              <partial-general-title
                type="side"
                title="پربازدیدترین نوشته‌ها"
              />

              <div class="flex flex-col divide-y">
                <div
                  v-for="blog in latestBlogs"
                  :key="blog.id"
                  class="w-full py-2"
                >
                  <blog-card-small
                    container-class="border-0 !bg-transparent"
                    :blog="blog"
                  />
                </div>
              </div>
            </template>
          </partial-card>
        </div>
      </div>
    </div>

    <app-newsletter/>
  </div>
</template>

<script setup>
import {ref} from "vue";
import {ArrowLongRightIcon} from "@heroicons/vue/24/outline/index.js";
import BaseCarousel from "@/components/base/BaseCarousel.vue";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import PartialGeneralTitle from "@/components/partials/PartialGeneralTitle.vue";
import BlogCard from "@/components/blog/BlogCard.vue";
import AppNavigationHeader from "@/components/AppNavigationHeader.vue";
import BlogCardSmall from "@/components/blog/BlogCardSmall.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import {useResizeObserver} from "@vueuse/core";
import AppSideCategoriesBlog from "@/components/AppSideCategoriesBlog.vue";
import AppNewsletter from "@/components/AppNewsletter.vue";

const bgMovement = ref(null)
const mainPageContainer = ref(null)

useResizeObserver(mainPageContainer, () => {
  if (bgMovement.value) {
    bgMovement.value.style.height = mainPageContainer.value.offsetHeight + 'px'
  }
})

//----------------------------
// Top Slider
//----------------------------
const slides = ref([
  {
    title: 'بررسی ماوس لاجیتک G502 Hero؛ اسطوره‌ی قدیمی',
    path: '/src/assets/blogs/b1.jpg',
    category: 'تلنت (Talent)',
  },
  {
    title: 'بررسی اسپیکر جوی روم JR-ML03؛ یک اسپیکر آرامش‌بخش',
    path: '/src/assets/blogs/b2.jpg',
    category: 'تلنت (Talent)',
  },
  {
    title: 'راهنمای خرید بهترین هدست اونیکوما؛ ۱۵ محصول جذاب با قیمت عالی',
    path: '/src/assets/blogs/b3.jpg',
    category: 'اسپیکر و هدفون',
  },
])
const currentSlide = ref(0)

const carouselSettings = {
  className: 'blog-slider',
  effect: 'coverflow',
  wrapAround: true,
  autoplay: 10000,
  hasNavigation: true,
  navigationPosition: 'right',
  navigationDisplay: 'floating-sides',
  hasPagination: true,
  breakpoints: {},
}

//----------------------------

//----------------------------
// Latest Blogs
//----------------------------
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
.circles {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  overflow: hidden;
  z-index: -1;
  inset: 0;
}

.circles li {
  position: absolute;
  display: block;
  list-style: none;
  width: 20px;
  height: 20px;
  animation: animate 35s linear infinite;
  top: -150px;
  z-index: -1;

}

.circles li:nth-child(1) {
  left: 25%;
  width: 80px;
  height: 80px;
  animation-delay: 0s;
}


.circles li:nth-child(2) {
  left: 10%;
  width: 20px;
  height: 20px;
  animation-delay: 2s;
  animation-duration: 12s;
}

.circles li:nth-child(3) {
  left: 70%;
  width: 20px;
  height: 20px;
  animation-delay: 4s;
}

.circles li:nth-child(4) {
  left: 40%;
  width: 60px;
  height: 60px;
  animation-delay: 0s;
  animation-duration: 18s;
}

.circles li:nth-child(5) {
  left: 65%;
  width: 20px;
  height: 20px;
  animation-delay: 0s;
}

.circles li:nth-child(6) {
  left: 75%;
  width: 110px;
  height: 110px;
  animation-delay: 3s;
}

.circles li:nth-child(7) {
  left: 35%;
  width: 150px;
  height: 150px;
  animation-delay: 7s;
}

.circles li:nth-child(8) {
  left: 50%;
  width: 25px;
  height: 25px;
  animation-delay: 15s;
  animation-duration: 45s;
}

.circles li:nth-child(9) {
  left: 20%;
  width: 15px;
  height: 15px;
  animation-delay: 2s;
  animation-duration: 35s;
}

.circles li:nth-child(10) {
  left: 85%;
  width: 150px;
  height: 150px;
  animation-delay: 0s;
  animation-duration: 11s;
}

.circles li:nth-child(11) {
  left: 95%;
  width: 80px;
  height: 80px;
  animation-delay: 1s;
}


.circles li:nth-child(12) {
  left: 4%;
  width: 20px;
  height: 20px;
  animation-delay: 2s;
  animation-duration: 8s;
}

.circles li:nth-child(13) {
  left: 70%;
  width: 20px;
  height: 20px;
  animation-delay: 6s;
}

.circles li:nth-child(14) {
  left: 18%;
  width: 40px;
  height: 40px;
  animation-delay: 0s;
  animation-duration: 12s;
}

.circles li:nth-child(15) {
  left: 60%;
  width: 20px;
  height: 20px;
  animation-delay: 3s;
}


@keyframes animate {
  0% {
    transform: translateY(0) rotate(0deg);
    opacity: 1;
    border-radius: 0;
  }

  100% {
    transform: translateY(8000px) rotate(720deg);
    opacity: 0;
    border-radius: 50%;
  }
}
</style>
