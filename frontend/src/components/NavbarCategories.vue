<template>
  <base-popover-side
    ref="sidebarContainer"
    btn-class="w-full lg:hidden"
  >
    <template #button>
      <div class="flex items-center gap-2 cursor-pointer group">
        <Bars4Icon class="w-6 h-6 group-hover:text-primary transition"/>
        <span class="font-iranyekan-bold group-hover:text-primary transition select-none">دسته‌بندی‌ها</span>
      </div>
    </template>

    <template #panel="{close}">
      <div ref="sidebarExtraContainerTop">
        <div class="mb-3 flex items-center">
          <span class="ml-auto text-sm text-gray-400">دسته‌بندی محصولات</span>
          <button @click="close" type="button"
                  class="w-[40px] h-[40px] border-0 py-2 px-2 bg-transparent text-black rounded-lg hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all">
            <XMarkIcon class="h-6 w-6"/>
          </button>
        </div>
      </div>

      <base-switcher-panel
        v-model:active-panel="activePanel"
        v-model:active-back-text="activeBackText"
        v-model:panels="panels"
        v-model:back-history="panelsBackHistory"
        v-model:fixed-height="calculateMobileMenuHeight"
        :use-fixed-height="true"
        container-class=""
        back-extra-class="rounded-md !bg-amber-200"
        back-text-class=""
      >
        <template
          v-for="(name, idx) in Object.keys(panels)"
          :key="idx"
          #[name]="{data, goTo}"
        >
          <base-loading-panel :loading="mobileMenuLoadings[name].loading">
            <template #loader>
              <div class="space-y-6 px-4 py-3">
                <div
                  v-for="index in 13"
                  :key="index"
                >
                  <div
                    role="status"
                    class="animate-pulse"
                  >
                    <div class="h-6 bg-slate-200 rounded-md dark:bg-slate-700 w-full"></div>
                    <span class="sr-only">در حال بارگذاری...</span>
                  </div>
                </div>
              </div>
            </template>

            <template #content>
              <div
                v-for="category in data"
                :key="category.id"
                class="divide-y divide-slate-200"
              >
                <div class="flex items-center gap-2">
                  <router-link
                    to=""
                    class="w-full px-3 py-3.5 text-sm cursor-pointer flex items-center gap-3 justify-between hover:bg-slate-100 transition rounded-md"
                    @click="(e) => {
                          if(category?.hasChildren || category?.children?.length)
                              panelChangeClickHandler(category, goTo, e)
                          else
                              close()
                      }"
                  >
                    <span>{{ category.name }}</span>
                    <ChevronLeftIcon
                      v-if="category?.hasChildren || category?.children?.length"
                      class="w-5 h-5 shrink-0"
                    />
                  </router-link>

                  <router-link
                    v-if="category?.hasChildren || category?.children?.length"
                    v-tooltip.bottom="'مشاهده تمامی محصولات در دسته‌بندی'"
                    to=""
                    class="shrink-0 h-full w-8 py-3.5 group"
                    @click="close()"
                  >
                    <ArrowTopRightOnSquareIcon
                      class="w-5 h-5 text-amber-600 group-hover:text-amber-300 mx-auto transition"/>
                  </router-link>
                </div>
              </div>
            </template>
          </base-loading-panel>
        </template>
      </base-switcher-panel>
    </template>
  </base-popover-side>

  <div
    class="hidden lg:flex lg:items-center lg:gap-2 cursor-pointer group"
    @click="() => {showCategoriesMenu = !showCategoriesMenu}"
  >
    <Bars4Icon class="w-6 h-6 group-hover:text-primary transition"/>
    <span class="font-iranyekan-bold group-hover:text-primary transition select-none">دسته‌بندی‌ها</span>
  </div>

  <VTransitionSlideFadeLeftX>
    <div
      v-if="showCategoriesMenu"
      class="absolute top-[30px] right-0 w-full z-[4] max-w-5xl hidden lg:flex lg:items-stretch rounded-b-xl overflow-hidden bg-white shadow-lg"
    >
      <ul
        dir="ltr"
        class="my-custom-scrollbar my-custom-scrollbar-light w-44 md:w-52 rounded-br-xl flex flex-col shrink-0 py-5 pr-1 bg-primary text-right text-white"
        ref="mainCategoriesItems"
      >
        <li
          v-for="category in mainCategories"
          :key="category.id"
        >
          <button
            type="button"
            class="w-full text-right font-iranyekan-bold leading-relaxed py-3 px-4 text-sm block rounded-r-full hover:bg-white hover:bg-opacity-75 hover:text-black transition"
            :class="{'!bg-white text-black shadow-sm main-category-active': activeMainMenu.id === category.id}"
          >
            {{ category.name }}
          </button>
        </li>
      </ul>

      <div
        dir="ltr"
        class="my-custom-scrollbar grow rounded-bl-xl px-4 py-4 h-full flex flex-row-reverse flex-wrap text-right"
        ref="subCategoriesItems"
      >
        <div dir="rtl" class="w-full">
          <div class="flex items-center gap-1.5 mb-6">
            <span class="text-slate-400 text-sm">مشاهده تمامی محصولات در دسته‌بندی</span>
            <router-link
              to=""
              class="text-orange-600 hover:text-opacity-80 transition text-sm font-iranyekan-bold"
            >
              {{ activeMainMenu.name }}
            </router-link>
          </div>
        </div>

        <div
          v-for="(column, idx) in categories[activeMainMenuId]"
          :key="idx"
          class="px-2 grow"
        >
          <template
            v-for="category in column"
            :key="category.id"
          >
            <router-link
              to=""
              class="block relative group pr-4 mb-4 text-sm"
            >
              <div
                class="absolute w-2.5 h-2.5 rounded-full border-2 border-cyan-500 right-0 top-1.5 transition"></div>
              <span class="font-iranyekan-bold group-hover:text-cyan-500 transition">{{
                  category.name
                }}</span>
            </router-link>

            <router-link
              v-if="category.children"
              v-for="childCategory in category.children"
              :key="childCategory.id"
              to=""
              class="text-sm block mb-4 pr-4 text-slate-500 hover:text-slate-700 transition"
            >
              {{ childCategory.name }}
            </router-link>
          </template>

        </div>
      </div>
    </div>
  </VTransitionSlideFadeLeftX>

  <Teleport to="body">
    <VTransitionFade>
      <div
        v-if="showCategoriesMenu"
        class="hidden lg:block fixed z-[9] w-[100vw] h-[100vh] bg-black/30 top-0 left-0"
        @click="() => {showCategoriesMenu = !showCategoriesMenu}"
      ></div>
    </VTransitionFade>
  </Teleport>
</template>

<script setup>
import {computed, nextTick, onMounted, reactive, ref, watch} from "vue";
import VTransitionSlideFadeLeftX from "../transitions/VTransitionSlideFadeLeftX.vue";
import VTransitionFade from "../transitions/VTransitionFade.vue";
import {Bars4Icon} from "@heroicons/vue/24/outline/index.js";
import {useWindowSize} from "@vueuse/core";
import BasePopoverSide from "./base/BasePopoverSide.vue";
import {XMarkIcon, ChevronLeftIcon, ArrowTopRightOnSquareIcon} from "@heroicons/vue/24/solid/index.js";
import BaseSwitcherPanel from "./base/BaseSwitcherPanel.vue";
import BaseLoadingPanel from "./base/BaseLoadingPanel.vue";
import {useSwitcherPanel} from "../composables/switcher-panel.js";

const mainCategoriesItems = ref(null)
const subCategoriesItems = ref(null)

const {width, height} = useWindowSize()

setHeight()
watch([width, height, mainCategoriesItems, subCategoriesItems], () => {
  setHeight()
})

function setHeight() {
  if (mainCategoriesItems.value && subCategoriesItems.value) {
    const h = Math.min(height.value - 100, 500)
    mainCategoriesItems.value.style.height = `${h}px`
    subCategoriesItems.value.style.height = `${h}px`
  }
}

const showCategoriesMenu = ref(false)
const mainCategories = ref([
  {
    id: 1,
    name: 'موبایل',
    hasChildren: true,
  },
  {
    id: 2,
    name: 'کالای دیجیتال',
    hasChildren: false,
  },
  {
    id: 3,
    name: 'خانه و آشپزخانه',
    hasChildren: false,
  },
  {
    id: 4,
    name: 'مد و پوشاک',
    hasChildren: false,
  },
  {
    id: 5,
    name: 'کالاهای سوپرمارکتی',
    hasChildren: false,
  },
  {
    id: 6,
    name: 'کتاب، لوازم تحریر و هنر',
    hasChildren: false,
  },
  {
    id: 7,
    name: 'اسباب بازی، کودک و نوزاد',
    hasChildren: false,
  },
  {
    id: 8,
    name: 'زیبایی و سلامت',
    hasChildren: false,
  },
  {
    id: 9,
    name: 'ورزش و سفر',
    hasChildren: false,
  },
  {
    id: 10,
    name: 'ابزارآلات و تجهیزات',
    hasChildren: false,
  },
  {
    id: 11,
    name: 'خودرو و موتور سیکلت',
    hasChildren: false,
  },
  {
    id: 12,
    name: 'محصولات بومی و محلی',
    hasChildren: false,
  },
])
const categories = ref({
  1: [
    [
      {
        id: 13,
        name: 'برندهای مختلف گوشی موبایل',
        children: [
          {
            id: 18,
            name: 'گوشی اپل',
          },
          {
            id: 19,
            name: 'گوشی سامسونگ',
          },
          {
            id: 20,
            name: 'گوشی شیائومی',
          },
          {
            id: 21,
            name: 'گوشی نوکیا',
          },
          {
            id: 22,
            name: 'گوشی هواوی',
          },
          {
            id: 23,
            name: 'گوشی آنر',
          },
          {
            id: 24,
            name: 'گوشی موتورولا',
          },
          {
            id: 25,
            name: 'گوشی ریلمی',
          },
          {
            id: 26,
            name: 'گوشی ناتینگ',
          },
          {
            id: 27,
            name: 'گوشی آرد',
          },
          {
            id: 28,
            name: 'گوشی جی پلاس',
          },
          {
            id: 29,
            name: 'گوشی وان پلاس',
          },
          {
            id: 30,
            name: 'گوشی جی ال ایکس',
          },
        ],
      },
    ],
    [
      {
        id: 14,
        name: 'گوشی بر اساس قیمت',
        children: [
          {
            id: 31,
            name: 'گوشی تا 2 ملیون تومان',
          },
          {
            id: 32,
            name: 'گوشی تا 5 ملیون تومان',
          },
          {
            id: 33,
            name: 'گوشی تا 7 ملیون تومان',
          },
          {
            id: 34,
            name: 'گوشی تا 15 ملیون تومان',
          },
          {
            id: 35,
            name: 'گوشی بالای 15 ملیون تومان',
          },
        ],
      },
      {
        id: 15,
        name: 'گوشی بر اساس حافظه داخلی',
        children: [
          {
            id: 36,
            name: 'گوشی تا 16 گیگابایت',
          },
          {
            id: 37,
            name: 'گوشی تا 32 گیگابایت',
          },
          {
            id: 38,
            name: 'گوشی تا 64 گیگابایت',
          },
          {
            id: 39,
            name: 'گوشی تا 128 گیگابایت',
          },
          {
            id: 40,
            name: 'گوشی تا 256 گیگابایت',
          },
          {
            id: 41,
            name: 'گوشی تا 1 ترابایت',
          },
        ],
      },
    ],
    [
      {
        id: 16,
        name: 'رزولوشن عکس',
        children: [
          {
            id: 42,
            name: 'گوشی تا 13 مگاپیکسل',
          },
          {
            id: 43,
            name: 'گوشی تا 16 مگاپیکسل',
          },
          {
            id: 44,
            name: 'گوشی تا 48 مگاپیکسل',
          },
          {
            id: 45,
            name: 'گوشی تا 64 مگاپیکسل',
          },
          {
            id: 46,
            name: 'گوشی تا 108 مگاپیکسل',
          },
        ],
      },
    ],
    [
      {
        id: 17,
        name: 'گوشی براساس کاربری',
        children: [
          {
            id: 47,
            name: 'گوشی اقتصادی',
          },
          {
            id: 48,
            name: 'گوشی میانرده',
          },
          {
            id: 49,
            name: 'گوشی دانش‌آموزی',
          },
          {
            id: 50,
            name: 'گوشی گیمینگ',
          },
          {
            id: 51,
            name: 'گوشی پرچمدار',
          },
          {
            id: 52,
            name: 'گوشی ضدآب',
          },
          {
            id: 53,
            name: 'گوشی مناسب عکاسی',
          },
          {
            id: 54,
            name: 'گوشی 5G',
          },
          {
            id: 55,
            name: 'دوسیمکارت',
          },
          {
            id: 56,
            name: 'اندروید (android)',
          },
          {
            id: 57,
            name: 'IOS',
          },
          {
            id: 58,
            name: 'سایر سیستم عامل‌ها',
          },
        ],
      },
    ],
  ]
})
const activeMainMenuId = ref(1)
const activeMainMenu = computed(() => {
  const idx = mainCategories.value.findIndex((item) => (activeMainMenuId.value === item.id))
  return mainCategories.value[idx]
})


//------------------------------------
// Mobile Menu Preparation & Actions
//------------------------------------
const menuCategories = ref({
  1: [
    {
      id: 13,
      name: 'برندهای مختلف گوشی موبایل',
      children: [
        {
          id: 18,
          name: 'گوشی اپل',
        },
        {
          id: 19,
          name: 'گوشی سامسونگ',
        },
        {
          id: 20,
          name: 'گوشی شیائومی',
        },
        {
          id: 21,
          name: 'گوشی نوکیا',
        },
        {
          id: 22,
          name: 'گوشی هواوی',
        },
        {
          id: 23,
          name: 'گوشی آنر',
        },
        {
          id: 24,
          name: 'گوشی موتورولا',
        },
        {
          id: 25,
          name: 'گوشی ریلمی',
        },
        {
          id: 26,
          name: 'گوشی ناتینگ',
        },
        {
          id: 27,
          name: 'گوشی آرد',
        },
        {
          id: 28,
          name: 'گوشی جی پلاس',
        },
        {
          id: 29,
          name: 'گوشی وان پلاس',
        },
        {
          id: 30,
          name: 'گوشی جی ال ایکس',
        },
      ],
    },
    {
      id: 14,
      name: 'گوشی بر اساس قیمت',
      children: [
        {
          id: 31,
          name: 'گوشی تا 2 ملیون تومان',
        },
        {
          id: 32,
          name: 'گوشی تا 5 ملیون تومان',
        },
        {
          id: 33,
          name: 'گوشی تا 7 ملیون تومان',
        },
        {
          id: 34,
          name: 'گوشی تا 15 ملیون تومان',
        },
        {
          id: 35,
          name: 'گوشی بالای 15 ملیون تومان',
        },
      ],
    },
    {
      id: 15,
      name: 'گوشی بر اساس حافظه داخلی',
      children: [
        {
          id: 36,
          name: 'گوشی تا 16 گیگابایت',
        },
        {
          id: 37,
          name: 'گوشی تا 32 گیگابایت',
        },
        {
          id: 38,
          name: 'گوشی تا 64 گیگابایت',
        },
        {
          id: 39,
          name: 'گوشی تا 128 گیگابایت',
        },
        {
          id: 40,
          name: 'گوشی تا 256 گیگابایت',
        },
        {
          id: 41,
          name: 'گوشی تا 1 ترابایت',
        },
      ],
    },
    {
      id: 16,
      name: 'رزولوشن عکس',
      children: [
        {
          id: 42,
          name: 'گوشی تا 13 مگاپیکسل',
        },
        {
          id: 43,
          name: 'گوشی تا 16 مگاپیکسل',
        },
        {
          id: 44,
          name: 'گوشی تا 48 مگاپیکسل',
        },
        {
          id: 45,
          name: 'گوشی تا 64 مگاپیکسل',
        },
        {
          id: 46,
          name: 'گوشی تا 108 مگاپیکسل',
        },
      ],
    },
    {
      id: 17,
      name: 'گوشی براساس کاربری',
      children: [
        {
          id: 47,
          name: 'گوشی اقتصادی',
        },
        {
          id: 48,
          name: 'گوشی میانرده',
        },
        {
          id: 49,
          name: 'گوشی دانش‌آموزی',
        },
        {
          id: 50,
          name: 'گوشی گیمینگ',
        },
        {
          id: 51,
          name: 'گوشی پرچمدار',
        },
        {
          id: 52,
          name: 'گوشی ضدآب',
        },
        {
          id: 53,
          name: 'گوشی مناسب عکاسی',
        },
        {
          id: 54,
          name: 'گوشی 5G',
        },
        {
          id: 55,
          name: 'دوسیمکارت',
        },
        {
          id: 56,
          name: 'اندروید (android)',
        },
        {
          id: 57,
          name: 'IOS',
        },
        {
          id: 58,
          name: 'سایر سیستم عامل‌ها',
        },
      ],
    },
  ],
  13: [
    {
      id: 18,
      name: 'گوشی اپل',
    },
    {
      id: 19,
      name: 'گوشی سامسونگ',
    },
    {
      id: 20,
      name: 'گوشی شیائومی',
    },
    {
      id: 21,
      name: 'گوشی نوکیا',
    },
    {
      id: 22,
      name: 'گوشی هواوی',
    },
    {
      id: 23,
      name: 'گوشی آنر',
    },
    {
      id: 24,
      name: 'گوشی موتورولا',
    },
    {
      id: 25,
      name: 'گوشی ریلمی',
    },
    {
      id: 26,
      name: 'گوشی ناتینگ',
    },
    {
      id: 27,
      name: 'گوشی آرد',
    },
    {
      id: 28,
      name: 'گوشی جی پلاس',
    },
    {
      id: 29,
      name: 'گوشی وان پلاس',
    },
    {
      id: 30,
      name: 'گوشی جی ال ایکس',
    },
  ],
  14: [
    {
      id: 31,
      name: 'گوشی تا 2 ملیون تومان',
    },
    {
      id: 32,
      name: 'گوشی تا 5 ملیون تومان',
    },
    {
      id: 33,
      name: 'گوشی تا 7 ملیون تومان',
    },
    {
      id: 34,
      name: 'گوشی تا 15 ملیون تومان',
    },
    {
      id: 35,
      name: 'گوشی بالای 15 ملیون تومان',
    },
  ],
  15: [
    {
      id: 36,
      name: 'گوشی تا 16 گیگابایت',
    },
    {
      id: 37,
      name: 'گوشی تا 32 گیگابایت',
    },
    {
      id: 38,
      name: 'گوشی تا 64 گیگابایت',
    },
    {
      id: 39,
      name: 'گوشی تا 128 گیگابایت',
    },
    {
      id: 40,
      name: 'گوشی تا 256 گیگابایت',
    },
    {
      id: 41,
      name: 'گوشی تا 1 ترابایت',
    },
  ],
  16: [
    {
      id: 42,
      name: 'گوشی تا 13 مگاپیکسل',
    },
    {
      id: 43,
      name: 'گوشی تا 16 مگاپیکسل',
    },
    {
      id: 44,
      name: 'گوشی تا 48 مگاپیکسل',
    },
    {
      id: 45,
      name: 'گوشی تا 64 مگاپیکسل',
    },
    {
      id: 46,
      name: 'گوشی تا 108 مگاپیکسل',
    },
  ],
  17: [
    {
      id: 47,
      name: 'گوشی اقتصادی',
    },
    {
      id: 48,
      name: 'گوشی میانرده',
    },
    {
      id: 49,
      name: 'گوشی دانش‌آموزی',
    },
    {
      id: 50,
      name: 'گوشی گیمینگ',
    },
    {
      id: 51,
      name: 'گوشی پرچمدار',
    },
    {
      id: 52,
      name: 'گوشی ضدآب',
    },
    {
      id: 53,
      name: 'گوشی مناسب عکاسی',
    },
    {
      id: 54,
      name: 'گوشی 5G',
    },
    {
      id: 55,
      name: 'دوسیمکارت',
    },
    {
      id: 56,
      name: 'اندروید (android)',
    },
    {
      id: 57,
      name: 'IOS',
    },
    {
      id: 58,
      name: 'سایر سیستم عامل‌ها',
    },
  ],
})

const mobileMenuLoadings = reactive({})
const {
  panels,
  activePanel,
  activeBackText,
  panelsBackHistory,
  panelChangeMonitor
} = useSwitcherPanel({
  panels: {
    main: mainCategories,
  },
  activePanel: 'main',
})

panelChangeMonitor(() => {
  for (let p of Object.keys(panels)) {
    if (!mobileMenuLoadings[p]) {
      mobileMenuLoadings[p] = {
        loading: true,
      }
    }
  }
})

function panelChangeClickHandler(category, goTo, evt) {
  evt.preventDefault()

  const panelName = 'p' + category.id
  if (!panels[panelName]) {
    panels[panelName] = {}

    new Promise((resolve) => {
      setTimeout(() => {
        panels[panelName] = menuCategories.value[category.id]
        mobileMenuLoadings[panelName].loading = false
        resolve()
      }, 2000)
    })
  }

  nextTick(() => {
    goTo(panelName, category.name)
  })
}

onMounted(() => {
  new Promise((resolve) => {
    setTimeout(() => {
      mobileMenuLoadings['main'].loading = false
      resolve()
    }, 2000)
  })
})

//------------------------------------


//------------------------------------
// Mobile Menu Height Calculation
//------------------------------------
const sidebarContainer = ref(null)
const sidebarExtraContainerTop = ref(null)
const calculateMobileMenuHeight = computed(() => {
  if (!sidebarContainer.value || !sidebarExtraContainerTop.value) return 0

  return sidebarContainer.value.container.el.lastChild.offsetHeight - sidebarExtraContainerTop.value.offsetHeight - 40
})

//------------------------------------
</script>

<style scoped>
.main-category-active {
  position: relative;
  --rounded-size: 32px;
}

.main-category-active::after,
.main-category-active::before {
  content: '';
  position: absolute;
  background-color: transparent;
  width: var(--rounded-size);
  height: var(--rounded-size);
  left: 0;
  z-index: 1;
}

.main-category-active::after {
  border-radius: 50% 0 0 0;
  top: 100%;
  box-shadow: -7px -7px 0 0 #fff;
}

.main-category-active::before {
  border-radius: 0 0 0 50%;
  bottom: 100%;
  box-shadow: -7px 7px 0 0 #fff;
}
</style>
