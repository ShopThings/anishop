<template>
  <div class="relative">
    <template v-if="isLoading">
      <div
          v-for="idx in 4"
          :key="idx"
          class="animate-pulse"
      >
        <div class="w-20 rounded bg-slate-300 h-3 mt-3"></div>
      </div>
    </template>
    <template v-else-if="menu">
      <div
          v-for="item in menu.items"
          :key="item.id"
          :data-nav-menu-id="'menu_item_' + item.id"
          class="relative"
          @click="(e) => {toggleChildrenHandler(item, e)}"
      >
        <a
            :href="item.link || 'javascript:void(0)'"
            class="text-black hover:opacity-75 transition w-full peer flex gap-2 items-center"
        >
          {{ item.title }}

          <template v-if="item.children?.length">
            <ChevronDownIcon
                :class="{
                  'rotate-180': item.id === selectedSwitcher
                }"
                class="size-4 mr-auto transition"
            />
          </template>
        </a>
        <span
            class="w-0 h-0.5 bg-sky-400 rounded-full absolute -bottom-2 right-0 peer-hover:w-5 transition-all"></span>

        <base-closable-panel
            v-if="item?.children?.length && desktopSwitchers[item.id]"
            :close-callback="closeChildrenChecker"
            :container-class="[
                'absolute min-w-72 right-0 z-10 top-[calc(100%+0.325rem+1px)] transition',
                item.id === selectedSwitcher ? 'visible opacity-100' : 'invisible opacity-0 translate-y-3',
            ]"
            @click="(e) => {e.stopPropagation()}"
        >
          <base-switcher-panel
              :ref="(el) => (desktopSwitcherRefs[item.id] = el)"
              v-model:active-back-text="desktopSwitchers[item.id].activeBackText"
              v-model:active-panel="desktopSwitchers[item.id].activePanel"
              v-model:back-history="desktopSwitchers[item.id].panelsBackHistory"
              v-model:panels="desktopSwitchers[item.id].panels"
              back-extra-class="rounded-md !bg-amber-200"
              back-text-class=""
              extra-container-class="max-h-72"
          >
            <template
                v-for="(name, idx) in Object.keys(desktopSwitchers[item.id].panels)"
                :key="idx"
                #[name]="{data, goTo}"
            >
              <base-loading-panel
                  v-if="desktopMenuLoadings[item.id]"
                  :loading="desktopMenuLoadings[item.id][name].loading"
              >
                <template #loader>
                  <div class="space-y-6 px-4 py-3">
                    <div
                        v-for="index in 6"
                        :key="index"
                    >
                      <div
                          class="animate-pulse"
                          role="status"
                      >
                        <div class="w-20 h-3 mt-3 bg-slate-300 rounded-md dark:bg-slate-700"></div>
                        <span class="sr-only">در حال بارگذاری...</span>
                      </div>
                    </div>
                  </div>
                </template>

                <template #content>
                  <div
                      v-for="childItem in data"
                      :key="childItem?.id"
                      class="divide-y divide-slate-200"
                  >
                    <div class="flex items-center gap-2">
                      <a
                          class="w-full px-3 py-3.5 text-sm cursor-pointer flex items-center gap-3 justify-between hover:bg-slate-100 transition rounded-md"
                          href="javascript:void(0)"
                          @click.prevent="(e) => {desktopPanelChangeClickHandler(item, childItem, goTo, e)}"
                      >
                        <span>{{ childItem?.title }}</span>
                        <ChevronLeftIcon
                            v-if="childItem?.children?.length"
                            class="w-5 h-5 shrink-0"
                        />
                      </a>

                      <router-link
                          v-if="childItem?.children?.length && childItem?.link"
                          v-tooltip.bottom="'مشاهده'"
                          :to="childItem?.link || ''"
                          class="shrink-0 h-full w-8 py-3.5 group"
                          @click="closeChildrenHandler"
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
        </base-closable-panel>
      </div>
    </template>
  </div>
</template>

<script setup>
import {nextTick, reactive, ref} from "vue";
import {ChevronDownIcon} from "@heroicons/vue/24/outline/index.js";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import BaseSwitcherPanel from "@/components/base/BaseSwitcherPanel.vue";
import BaseClosablePanel from "@/components/base/BaseClosablePanel.vue";
import {ArrowTopRightOnSquareIcon, ChevronLeftIcon} from "@heroicons/vue/24/solid/index.js";
import {watchImmediate} from "@vueuse/core";
import {useSwitcherPanel} from "@/composables/switcher-panel.js";
import {findItemByKey} from "@/composables/helper.js";
import {useRouter} from "vue-router";

const props = defineProps({
  menu: Object,
  isLoading: Boolean,
})

const router = useRouter()

//------------------------------------
// Desktop Menu Panel Operations
//------------------------------------
const desktopMenuLoadings = reactive({})
const selectedSwitcher = ref(null)
const desktopSwitchers = reactive({})
const desktopSwitcherRefs = reactive({})

function closeChildrenHandler() {
  selectedSwitcher.value = null
}

function toggleChildrenHandler(menu, e) {
  if (!menu?.children?.length) return

  if (!selectedSwitcher.value) {
    selectedSwitcher.value = menu.id
  } else {
    closeChildrenHandler()
  }
}

function closeChildrenChecker(e) {
  let menuItem = e.target.closest('[data-nav-menu-id]') || e.target

  if (
      selectedSwitcher.value &&
      !!menuItem.dataset['navMenuId'] &&
      menuItem.dataset['navMenuId'] === 'menu_item_' + selectedSwitcher.value
  ) {
    return
  }

  closeChildrenHandler()
}

/*
 * implementation process is as below structure:
 * 🔹 we have menu items like:
 *   [
 *     ...,
 *     title: ...,
 *     items: [
 *       ...,
 *       title: ...,
 *       children: [
 *         ...,
 *         title: ...,
 *         children: [...], // and so on
 *         ...,
 *       ],
 *       ...,
 *     ],
 *     ...
 *   ]
 * 🔹 remove first layer of items to show it in top
 * 🔹 because of that, we should have separate switcher panel for each base item
 *    and it should store according to base items' id
 * 🔹 so we have some structure like:
 *   [
 *     baseItemId1: switcherInstance,
 *     baseItemId2: switcherInstance,
 *     ...,
 *   ]
 *   ✔ Example:
 *   [
 *     1: {switcher object},
 *     2: {switcher object},
 *     ...,
 *   ]
 */

watchImmediate(() => props.menu, () => {
  if (!props.menu?.items) return

  for (let i of props.menu.items) {
    desktopSwitchers[i.id] = useSwitcherPanel({
      panels: {
        main: null,
      },
      activePanel: 'main',
    })
  }
})

watchImmediate(desktopSwitchers, () => {
  for (let s in desktopSwitchers) {
    if (desktopSwitchers.hasOwnProperty(s)) {
      desktopSwitchers[s].panelChangeMonitor(() => {
        for (let p of Object.keys(desktopSwitchers[s].panels)) {
          if (!desktopMenuLoadings[s]) {
            desktopMenuLoadings[s] = {}
          }

          if (!desktopMenuLoadings[s][p]) {
            desktopMenuLoadings[s][p] = {
              loading: true,
            }
          }
        }

        if (!desktopSwitchers[s].panels.main) {
          desktopSwitchers[s].panels.main = findItemByKey(props.menu.items, 'id', s, false)?.children || []
          desktopMenuLoadings[s].main.loading = false
        }
      })
    }
  }
})

function desktopPanelChangeClickHandler(menu, item, goTo, evt) {
  evt.preventDefault()

  if (!item?.children?.length) {
    if (item.link) {
      router.push(item.link)
      closeChildrenHandler()
    }

    return
  }

  if (!menu?.id || !item || !desktopSwitchers[menu?.id]) return

  const panelName = 'm' + item.id
  if (!desktopSwitchers[menu.id].panels[panelName]) {
    desktopSwitchers[menu.id].panels[panelName] = {}

    new Promise((resolve) => {
      setTimeout(() => {
        desktopSwitchers[menu.id].panels[panelName] = item.children
        desktopMenuLoadings[menu.id][panelName].loading = false
        resolve()
      }, 1000)
    }).then(() => {
      /*
       * 📍 [IMPORTANT NOTE]
       *   timeout is 300 milliseconds, and it is the time of animations/transition end of loading panel
       *   (it is 250 apparently) so any change for duration of loading panel will break height calculation
       *   of switcher panel
       */
      setTimeout(() => {
        if (desktopSwitcherRefs[menu.id]) {
          desktopSwitcherRefs[menu.id].calculateHeight()
        }
      }, 300)
    })
  }

  nextTick(() => {
    goTo(panelName, item.title)
  })
}
</script>
