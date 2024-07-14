<template>
  <base-popover-side ref="sidebarContainer">
    <template #button>
      <button
        class="relative w-[40px] h-[40px] border-0 py-2 px-2 bg-transparent text-black rounded-lg hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all flex justify-between items-center"
        type="button"
      >
        <Bars3Icon class="h-6 w-6"/>
      </button>
    </template>
    <template #panel="{close}">
      <div
        ref="sidebarContainerHeader"
        class="mb-3 flex items-center"
      >
        <span class="ml-auto text-sm text-gray-400">منو</span>
        <button
          class="w-[40px] h-[40px] border-0 py-2 px-2 bg-transparent text-black rounded-lg hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all"
          type="button"
          @click="close"
        >
          <XMarkIcon class="h-6 w-6"/>
        </button>
      </div>

      <div
        ref="sidebarExtraContainerTop"
        class="mb-3 py-3 border-y"
      >
        <base-button
          :to="{name: 'home'}"
          class="w-full border-2 border-indigo-500 text-sm !text-black hover:bg-indigo-100 transition flex items-center gap-3 justify-center"
          type="link"
        >
          <span class="mx-auto">مشاهده فروشگاه</span>
          <ShoppingCartIcon class="w-6 h-6 text-indigo-400"/>
        </base-button>

        <div class="flex mt-3">
          <template v-if="userStore.getUser">
            <router-link
              :to="{name: 'user.home'}"
              class="flex w-full items-center rounded-md px-2 py-2 text-sm text-gray-900 hover:bg-primary hover:text-white transition"
            >
              <WindowIcon class="h-6 w-6 text-sky-400 ml-2"/>
              پیشخوان
            </router-link>
            <button
              class="flex w-full items-center rounded-md px-2 py-2 text-sm text-gray-900 hover:bg-primary hover:text-white mr-2 transition"
              type="button"
              @click="() => {close(); router.push({name: 'user.logout'});}"
            >
              <PowerIcon class="h-6 w-6 text-sky-400 ml-2"/>
              خروج
            </button>
          </template>
          <template v-else>
            <router-link
              :to="{name: 'login'}"
              class="flex w-full items-center rounded-md px-2 py-2 text-sm text-gray-900 hover:bg-primary hover:text-white transition"
            >
              <ArrowLeftEndOnRectangleIcon class="h-6 w-6 text-sky-400 ml-2"/>
              ورود
            </router-link>
            <router-link
              :to="{name: 'signup'}"
              class="flex w-full items-center rounded-md px-2 py-2 text-sm text-gray-900 hover:bg-primary hover:text-white mr-2 transition"
            >
              <UserPlusIcon class="h-6 w-6 text-sky-400 ml-2"/>
              ثبت نام
            </router-link>
          </template>
        </div>
      </div>

      <base-switcher-panel
        v-model:active-back-text="mobileActiveBackText"
        v-model:active-panel="mobileActivePanel"
        v-model:back-history="mobilePanelsBackHistory"
        v-model:fixed-height="calculateMobileMenuHeight"
        v-model:panels="mobilePanels"
        :use-fixed-height="true"
        back-extra-class="rounded-md !bg-amber-200"
        back-text-class=""
        container-class=""
      >
        <template
          v-for="(name, idx) in Object.keys(mobilePanels)"
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
                    <div class="h-5 bg-slate-200 rounded-md w-full"></div>
                    <span class="sr-only">در حال بارگذاری...</span>
                  </div>
                </div>
              </div>
            </template>

            <template #content>
              <div
                v-for="menu in data"
                :key="menu?.id"
                class="divide-y divide-slate-200"
              >
                <div class="flex items-center gap-2">
                  <a
                    class="w-full px-3 py-3.5 text-sm cursor-pointer flex items-center gap-3 justify-between hover:bg-slate-100 transition rounded-md"
                    href="javascript:void(0)"
                    @click.prevent="(e) => {mobilePanelChangeClickHandler(menu, goTo, e, close)}"
                  >
                    <span>{{ menu?.title }}</span>
                    <ChevronLeftIcon
                      v-if="menu?.children?.length"
                      class="w-5 h-5 shrink-0"
                    />
                  </a>

                  <router-link
                    v-if="menu?.children?.length && menu?.link"
                    v-tooltip.bottom="'مشاهده'"
                    :to="menu?.link || ''"
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
import {ArrowTopRightOnSquareIcon, Bars3Icon, ChevronLeftIcon, XMarkIcon,} from "@heroicons/vue/24/solid/index.js";
import BasePopoverSide from "@/components/base/BasePopoverSide.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import {
  ArrowLeftEndOnRectangleIcon,
  PowerIcon,
  ShoppingCartIcon,
  UserPlusIcon,
  WindowIcon
} from "@heroicons/vue/24/outline/index.js";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import BaseSwitcherPanel from "@/components/base/BaseSwitcherPanel.vue";
import {useSwitcherPanel} from "@/composables/switcher-panel.js";
import {watchImmediate} from "@vueuse/core";
import {useRouter} from "vue-router";
import {useUserAuthStore} from "@/store/StoreUserAuth.js";

const props = defineProps({
  menu: Object,
  isLoading: Boolean,
})

const router = useRouter()
const userStore = useUserAuthStore()

//------------------------------------
// Mobile Menu Panel Operations
//------------------------------------
const mobileMenuLoadings = reactive({})
const mobileSwitcher = useSwitcherPanel({
  panels: {
    main: null,
  },
  activePanel: 'main',
})
const mobilePanels = mobileSwitcher.panels
const mobileActivePanel = mobileSwitcher.activePanel
const mobileActiveBackText = mobileSwitcher.activeBackText
const mobilePanelsBackHistory = mobileSwitcher.panelsBackHistory
const mobilePanelChangeMonitor = mobileSwitcher.panelChangeMonitor

mobilePanelChangeMonitor(() => {
  for (let p of Object.keys(mobilePanels)) {
    if (!mobileMenuLoadings[p]) {
      mobileMenuLoadings[p] = {
        loading: true,
      }
    }
  }
})

function mobilePanelChangeClickHandler(menu, goTo, evt) {
  evt.preventDefault()

  if (!menu) return

  if (!menu?.children?.length) {
    if (menu.link) {
      router.push(menu.link)
      close()
    }

    return
  }

  const panelName = 'm' + menu.id
  if (!mobilePanels[panelName]) {
    mobilePanels[panelName] = {}

    new Promise((resolve) => {
      setTimeout(() => {
        mobilePanels[panelName] = menu.children
        mobileMenuLoadings[panelName].loading = false
        resolve()
      }, 1000)
    })
  }

  nextTick(() => {
    goTo(panelName, menu.title)
  })
}

watchImmediate(() => props.menu, () => {
  if (!props.menu || mobilePanels.main) return

  mobilePanels.main = props.menu?.items
  mobileMenuLoadings['main'].loading = false
})

//------------------------------------
// Mobile Menu Height Calculation
//------------------------------------
const sidebarContainer = ref(null)
const sidebarContainerHeader = ref(null)
const sidebarExtraContainerTop = ref(null)
const calculateMobileMenuHeight = computed(() => {
  if (!sidebarContainer.value || !sidebarExtraContainerTop.value) return 0

  return sidebarContainer.value.container.el.lastChild.offsetHeight
    - sidebarContainerHeader.value.offsetHeight - sidebarExtraContainerTop.value.offsetHeight - 40
})
</script>
