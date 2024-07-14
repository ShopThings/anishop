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
          <button
              class="w-[40px] h-[40px] border-0 py-2 px-2 bg-transparent text-black rounded-lg hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all"
              type="button"
              @click="close">
            <XMarkIcon class="h-6 w-6"/>
          </button>
        </div>
      </div>

      <base-switcher-panel
          v-model:active-back-text="activeBackText"
          v-model:active-panel="activePanel"
          v-model:back-history="panelsBackHistory"
          v-model:fixed-height="calculateMobileMenuHeight"
          v-model:panels="panels"
          :use-fixed-height="true"
          back-extra-class="rounded-md !bg-amber-200"
          back-text-class=""
          container-class=""
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
                    v-for="index in 12"
                    :key="index"
                >
                  <div
                      class="animate-pulse"
                      role="status"
                  >
                    <div class="h-6 bg-slate-200 rounded-md w-full"></div>
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
                  <a
                      class="w-full px-3 py-3.5 text-sm cursor-pointer flex items-center gap-3 justify-between hover:bg-slate-100 transition rounded-md"
                      href="javascript:void(0)"
                      @click.prevent="(e) => {panelChangeClickHandler(category, goTo, e, close)}"
                  >
                    <span>{{ category.name }}</span>
                    <ChevronLeftIcon
                        v-if="category?.children?.length"
                        class="w-5 h-5 shrink-0"
                    />
                  </a>

                  <router-link
                      v-if="category?.children?.length"
                      v-tooltip.bottom="'مشاهده تمامی محصولات در دسته‌بندی'"
                      :to="{name: 'search', query: {category: category.id}}"
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
</template>

<script setup>
import {computed, nextTick, reactive, ref} from "vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import BaseSwitcherPanel from "@/components/base/BaseSwitcherPanel.vue";
import {ArrowTopRightOnSquareIcon, ChevronLeftIcon, XMarkIcon} from "@heroicons/vue/24/solid/index.js";
import BasePopoverSide from "@/components/base/BasePopoverSide.vue";
import {Bars4Icon} from "@heroicons/vue/24/outline/index.js";
import {useSwitcherPanel} from "@/composables/switcher-panel.js";
import {watchImmediate} from "@vueuse/core";
import {useRouter} from "vue-router";

const props = defineProps({
  categories: Array,
  isLoading: Boolean,
})

const router = useRouter()

//------------------------------------
// Mobile Menu Preparation & Actions
//------------------------------------
const menuCategories = ref([])
const mobileMenuLoadings = reactive({})
const {
  panels,
  activePanel,
  activeBackText,
  panelsBackHistory,
  panelChangeMonitor
} = useSwitcherPanel({
  panels: {
    main: menuCategories.value,
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

function panelChangeClickHandler(category, goTo, evt, close) {
  evt.preventDefault()

  if (!category?.children?.length) {
    router.push({name: 'search', query: {category: category.id}})
    close()

    return
  }

  const panelName = 'p' + category.id
  if (!panels[panelName]) {
    panels[panelName] = {}

    new Promise((resolve) => {
      setTimeout(() => {
        panels[panelName] = category?.children || []
        mobileMenuLoadings[panelName].loading = false
        resolve()
      }, 1000)
    })
  }

  nextTick(() => {
    goTo(panelName, category.name)
  })
}

watchImmediate(() => props.categories, () => {
  if (!props.categories) return

  menuCategories.value = props.categories
  panels.main = menuCategories.value
  mobileMenuLoadings['main'].loading = false
})

//------------------------------------
// Mobile Menu Height Calculation
//------------------------------------
const sidebarContainer = ref(null)
const sidebarExtraContainerTop = ref(null)
const calculateMobileMenuHeight = computed(() => {
  if (!sidebarContainer.value || !sidebarExtraContainerTop.value) return 0

  return sidebarContainer.value.container.el.lastChild.offsetHeight - sidebarExtraContainerTop.value.offsetHeight - 40
})
</script>
