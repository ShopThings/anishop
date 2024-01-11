<template>
  <VTransitionSlideFadeLeftX v-show="isOpen">
    <ul class="mr-3 block">
      <VTransitionSlideFadeLeftXGroup>
        <li
          v-for="(item, idx) in items"
          :key="idx"
        >
          <div
            class="flex gap-2 items-center cursor-pointer p-2 rounded-lg relative hover:bg-violet-100 transition"
            @click="clickHandler(idx, item)"
          >
            <VTransitionFade>
              <loader-circle
                v-if="isTreeLoading(idx)"
                main-container-klass="absolute w-full h-full top-0 left-0"
                big-circle-color="border-transparent"
                spinner-klass="!w-5 !h-5"
              />
            </VTransitionFade>

            <div class="ml-1">
              <base-checkbox
                :name="uniqueId('dir')"
                :show-label="false"
                v-model="itemsRef[idx].isChecked"
                size-class="w-5 h-5"
                @change="(value) => {if(value) emit('selection-change', item)}"
              />
            </div>

            <FolderOpenIcon v-if="isTreeOpen(idx)" class="h-6 w-6 shrink-0 text-indigo-600"/>
            <FolderIcon v-else class="h-6 w-6 shrink-0"/>

            <span class="grow" :class="{'text-indigo-600': isTreeOpen(idx)}">{{ item.name }}</span>

            <template v-if="hasTreeChildren(idx)">
              <ChevronDownIcon v-if="isTreeOpen(idx)" class="h-5 w-5 shrink-0"/>
              <ChevronLeftIcon v-else class="h-5 w-5 shrink-0"/>
            </template>
          </div>

          <partial-tree-directory
            v-if="hasTreeChildren(idx)"
            :open="isTreeOpen(idx)"
            :items="getTreeChildren(idx)"
            :disk="disk"
            @selection-change="(selected) => (emit('selection-change', selected))"
          />
          <div v-else-if="isTreeFetched(idx) && isTreeOpen(idx)" class="mr-3 my-2 text-sm text-gray-400">
            هیچ پوشه‌ای وجود ندارد
          </div>
        </li>
      </VTransitionSlideFadeLeftXGroup>
    </ul>
  </VTransitionSlideFadeLeftX>
</template>

<script setup>
import {ref} from "vue";
import {FolderIcon, FolderOpenIcon, ChevronDownIcon, ChevronLeftIcon} from '@heroicons/vue/24/outline';
import {FilemanagerAPI} from "@/service/APIFilemanager.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import VTransitionSlideFadeLeftXGroup from "@/transitions/VTransitionSlideFadeLeftXGroup.vue";
import VTransitionSlideFadeLeftX from "@/transitions/VTransitionSlideFadeLeftX.vue";
import {watchImmediate} from "@vueuse/core";
import BaseCheckbox from "@/components/base/BaseCheckbox.vue";
import uniqueId from "lodash.uniqueid";

const props = defineProps({
  open: {
    type: Boolean,
    default: true,
  },
  items: Array,
  disk: String,
})

const emit = defineEmits(['selection-change'])

const isOpen = ref(props.open)
const itemsRef = ref([])

watchImmediate(() => props.items, () => {
  itemsRef.value = props.items.map(() => ({
    children: [],
    open: false,
    loading: false,
    isFetched: false,
    isChecked: false,
  }))
})

function clickHandler(idx, item) {
  if (itemsRef.value[idx] && itemsRef.value[idx].isFetched) {
    itemsRef.value[idx].open = !itemsRef.value[idx].open
  } else {
    if (!itemsRef.value[idx]) {
      itemsRef.value[idx] = {
        children: [],
        open: false,
        loading: false,
        isFetched: false,
        isChecked: false,
      }
    }

    if (!itemsRef.value[idx].loading) {
      itemsRef.value[idx].loading = true

      FilemanagerAPI.fetchTree({
        path: item.full_path,
        disk: props.disk,
      }, {
        success(response) {
          itemsRef.value[idx].children = response.data
          itemsRef.value[idx].open = true
          itemsRef.value[idx].isFetched = true
        },
        finally() {
          itemsRef.value[idx].loading = false
        },
      })
    }
  }
}

function isTreeOpen(idx) {
  if (!itemsRef.value[idx]) return false
  return itemsRef.value[idx].open
}

function hasTreeChildren(idx) {
  if (!itemsRef.value[idx]) return 0
  return itemsRef.value[idx].children.length
}

function isTreeLoading(idx) {
  if (!itemsRef.value[idx]) return false
  return itemsRef.value[idx].loading
}

function isTreeFetched(idx) {
  if (!itemsRef.value[idx]) return false
  return itemsRef.value[idx].isFetched
}

function getTreeChildren(idx) {
  if (!itemsRef.value[idx]) return []
  return itemsRef.value[idx].children
}
</script>
