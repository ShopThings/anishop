<template>
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
          ref="mainCategoriesItems"
          class="my-custom-scrollbar my-custom-scrollbar-light w-44 md:w-52 rounded-br-xl flex flex-col shrink-0 py-5 pr-1 bg-secondary text-right text-white"
          dir="ltr"
      >
        <li
            v-for="i in 10"
            v-if="isLoading"
            :key="i"
            class="w-3/4 h-6 mx-auto mb-6 rounded-md bg-white/30 animate-pulse"
        ></li>
        <li
            v-for="category in mainCategories"
            v-else
            :key="category.id"
        >
          <button
              :class="{'!bg-white text-black shadow-sm main-category-active !transition-none': activeMainMenu?.id === category.id}"
              class="w-full text-right font-iranyekan-bold leading-relaxed py-3 px-4 text-sm block rounded-r-full hover:bg-white hover:bg-opacity-75 hover:text-black transition"
              type="button"
              @click="() => {activeMainMenuId = category.id}"
          >
            {{ category.name }}
          </button>
        </li>
      </ul>

      <div
          ref="subCategoriesItems"
          class="grow h-full flex flex-col gap-4"
      >
        <div
            v-if="activeMainMenuId"
            class="w-full flex items-center gap-1.5 pt-8 px-4"
        >
          <span class="text-slate-400 text-sm">مشاهده تمامی محصولات در دسته‌بندی</span>
          <router-link
              :to="{name: 'search', query: {category: activeMainMenu?.id}}"
              class="text-orange-600 hover:text-opacity-80 transition text-sm font-iranyekan-bold"
              @click="() => {showCategoriesMenu = false}"
          >
            {{ activeMainMenu?.name }}
          </router-link>
        </div>

        <div
            class="my-custom-scrollbar w-full rounded-bl-xl text-right p-4 flex flex-wrap flex-row-reverse gap-1"
            dir="ltr"
        >
          <div
              v-if="isLoading || !activeMainMenu"
              class="flex flex-col gap-10 w-full"
          >
            <div class="flex flex-row-reverse gap-3 w-full animate-pulse pt-6">
              <div class="h-4 rounded-md w-40 bg-slate-200"></div>
              <div class="h-4 rounded-md w-16 bg-orange-200"></div>
            </div>
            <div class="flex gap-6 w-full">
              <div
                  v-for="i in 4"
                  :key="i"
                  class="flex flex-col gap-6 w-full"
              >
                <div
                    v-for="j in 8"
                    :key="j"
                    class="w-full h-6 rounded-md bg-slate-200 animate-pulse"
                ></div>
              </div>
            </div>
          </div>
          <div
              v-for="(category) in getChildCategories"
              v-else
              :key="category.id"
              class="px-2 w-auto shrink-0"
          >
            <router-link
                :to="{name: 'search', query: {category: category.id}}"
                class="block relative group pr-4 mb-4 text-sm"
                @click="() => {showCategoriesMenu = false}"
            >
              <div
                  class="absolute w-2.5 h-2.5 rounded-full border-2 border-cyan-500 right-0 top-1.5 transition"></div>
              <span class="font-iranyekan-bold group-hover:text-cyan-500 transition">{{
                  category.name
                }}</span>
            </router-link>

            <router-link
                v-for="childCategory in category.children"
                v-if="category.children"
                :key="childCategory.id"
                :to="{name: 'search', query: {category: childCategory.id}}"
                class="text-sm block mb-4 pr-4 text-slate-500 hover:text-slate-700 transition"
                @click="() => {showCategoriesMenu = false}"
            >
              {{ childCategory.name }}
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </VTransitionSlideFadeLeftX>

  <Teleport to="body">
    <VTransitionFade>
      <div
          v-if="showCategoriesMenu"
          class="hidden lg:block fixed z-[9] w-[100vw] h-[100vh] bg-black/30 top-0 left-0"
          @click="() => {showCategoriesMenu = false}"
      ></div>
    </VTransitionFade>
  </Teleport>
</template>

<script setup>
import VTransitionSlideFadeLeftX from "@/transitions/VTransitionSlideFadeLeftX.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {Bars4Icon} from "@heroicons/vue/24/outline/index.js";
import {computed, ref, watch} from "vue";
import {useWindowSize, watchImmediate} from "@vueuse/core";
import {findItemByKey} from "@/composables/helper.js";

const props = defineProps({
  categories: Array,
  isLoading: Boolean,
})

const showCategoriesMenu = ref(false)
const mainCategories = ref([])
const categories = ref([])
const activeMainMenuId = ref(null)
const activeMainMenu = computed(() => {
  const idx = mainCategories.value.findIndex((item) => (activeMainMenuId.value === item.id))
  return mainCategories.value[idx] || null
})

const getChildCategories = computed(() => {
  if (!activeMainMenuId.value) return []
  return findItemByKey(mainCategories.value, 'id', activeMainMenuId.value, false)?.children || []
})

watchImmediate(() => props.categories, () => {
  if (!props.categories) return

  mainCategories.value = props.categories

  if (mainCategories.value?.length) {
    activeMainMenuId.value = mainCategories.value[0]?.id
  }
})

//-----------------------------------------------
// Calculate height of categories panel
//-----------------------------------------------
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
</script>

<style scoped>
.main-category-active {
  position: relative;
  --rounded-size: 24px;
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
