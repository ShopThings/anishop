<template>
  <ul v-show="open" class="mr-3 block">
    <li
        v-for="(item, idx) in items"
        :key="itemsRef[idx].id"
    >
      <div class="flex gap-1 5 items-center">
        <base-checkbox
            v-model="itemsRef[idx].isChecked"
            :name="uniqueId('dir')"
            :show-label="false"
            size-class="w-5 h-5"
            @change="(value) => {dirCheckboxClickHandler(value, item, itemsRef[idx].id)}"
        />

        <div
            class="w-full flex gap-2 items-center cursor-pointer p-2 rounded-lg relative hover:bg-violet-100 transition"
            @click="clickHandler(idx, item)"
        >
          <VTransitionFade>
            <loader-circle
                v-if="isTreeLoading(idx)"
                big-circle-color="border-transparent"
                main-container-klass="absolute w-full h-full top-0 left-0"
                spinner-klass="!w-5 !h-5"
            />
          </VTransitionFade>

          <FolderOpenIcon v-if="isTreeOpen(idx)" class="h-6 w-6 shrink-0 text-indigo-600"/>
          <FolderIcon v-else class="h-6 w-6 shrink-0"/>

          <span :class="{'text-indigo-600': isTreeOpen(idx)}" class="grow">{{ item.name }}</span>

          <template v-if="hasTreeChildren(idx)">
            <ChevronLeftIcon :class="{'-rotate-90': isTreeOpen(idx)}" class="h-5 w-5 shrink-0 transition"/>
          </template>
        </div>
      </div>

      <VTransitionSlideFadeLeftX mode="out-in">
        <div v-if="isTreeOpen(idx)">
          <partial-tree-directory
              v-if="hasTreeChildren(idx)"
              :ref="(r) => {itemsRef[idx].childRef = r}"
              :disk="disk"
              :items="getTreeChildren(idx)"
              :open="isTreeOpen(idx)"
              @selection-change="childDirCheckboxClickHandler"
          />
          <div v-else-if="isTreeFetched(idx)" class="mr-3 my-2 text-sm text-gray-400">
            هیچ پوشه‌ای وجود ندارد
          </div>
        </div>
      </VTransitionSlideFadeLeftX>
    </li>
  </ul>
</template>

<script setup>
import {ref} from "vue";
import {FolderIcon, FolderOpenIcon, ChevronLeftIcon} from '@heroicons/vue/24/outline';
import {FilemanagerAPI} from "@/service/APIFilemanager.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {watchImmediate} from "@vueuse/core";
import BaseCheckbox from "@/components/base/BaseCheckbox.vue";
import uniqueId from "lodash.uniqueid";
import VTransitionSlideFadeLeftX from "@/transitions/VTransitionSlideFadeLeftX.vue";

const props = defineProps({
  open: {
    type: Boolean,
    default: true,
  },
  items: Array,
  disk: String,
})

const emit = defineEmits(['selection-change'])

const itemsRef = ref([])

watchImmediate(() => props.items, () => {
  itemsRef.value = props.items.map(() => ({
    id: uniqueId('child-'),
    children: [],
    open: false,
    loading: false,
    isFetched: false,
    isChecked: false,
    childRef: ref(null),
  }))
})

function clickHandler(idx, item) {
  if (itemsRef.value[idx] && itemsRef.value[idx].isFetched) {
    itemsRef.value[idx].open = !itemsRef.value[idx].open
  } else {
    if (!itemsRef.value[idx]) {
      itemsRef.value[idx] = {
        id: uniqueId('child-'),
        children: [],
        open: false,
        loading: false,
        isFetched: false,
        isChecked: false,
        childRef: ref(null),
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

function dirCheckboxClickHandler(checked, item, id) {
  if (!checked) return

  emit('selection-change', item, id)

  itemsRef.value.map((i) => {
    if (id !== i.id)
      i.isChecked = false

    if (i.childRef)
      i.childRef.clearSelectedItems(i.id)
  })
}

function childDirCheckboxClickHandler(item, except) {
  emit('selection-change', item, except)

  itemsRef.value.map((i) => {
    i.isChecked = false

    if (i.childRef)
      i.childRef.clearSelectedItems(except)
  })
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

defineExpose({
  clearSelectedItems(except) {
    itemsRef.value.map((item) => {
      if (item.id !== except)
        item.isChecked = false;

      if (item.childRef)
        item.childRef.clearSelectedItems(except);
    });
  },
})
</script>
