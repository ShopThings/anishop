<template>
  <partial-dialog
    v-model:open="isOpen"
    @close="$emit('close')"
  >
    <template #title>
      <div class="flex items-center">
        <FolderIcon class="h-6 w-6 ml-2"/>
        پوشه‌ها
      </div>
      <div class="flex flex-wrap justify-between items-center">
        <div class="text-xs text-gray-400 mt-2 grow">
          <span class="inline-block ml-2">مسیر:</span>
          <span dir="ltr" class="inline-block">root</span>
        </div>
        <div class="text-left mt-2 shrink-0">
          <base-animated-button
            v-tooltip.bottom="'بارگذاری مجدد'"
            class="bg-gray-200 !text-black !p-1"
            @click="reloadTree"
          >
            <template #icon="{klass}">
              <ArrowPathIcon class="h-4 w-4" :class="klass"/>
            </template>
          </base-animated-button>
        </div>
      </div>
    </template>

    <template #body>
      <div class="relative">
        <loader-circle
          v-if="isLoading"
          main-container-klass="absolute h-[calc(100%+1rem)] w-[calc(100%+1rem)] -top-2 -left-2"
        />

        <partial-tree-directory-search
          v-if="items.length"
          @search="getTree"
          @clear-filter="clearSearch"
        />

        <div
          v-if="slots['extra']"
          class="mb-4 flex flex-row-reverse justify-between"
        >
          <slot name="extra"></slot>
        </div>

        <div class="mt-5 mb-2 text-sm">
          لطفا پوشه برای انجام عملیات را انتخاب کنید:

          <span class="text-green-500 mr-1.5">{{ selectedDir }}</span>
        </div>

        <div
          v-if="items.length"
          class="-mr-3"
        >
          <partial-tree-directory
            :items="items"
            :disk="disk"
            @selection-change="changeSelectedDirectory"
          />
        </div>
        <div
          v-else
          class="text-gray-400"
        >
          هیچ پوشه‌ای وجود ندارد!
        </div>
      </div>
    </template>
  </partial-dialog>
</template>

<script setup>
import {ref, useSlots, watch} from "vue";
import {FolderIcon, ArrowPathIcon} from '@heroicons/vue/24/outline';
import PartialDialog from "@/components/partials/PartialDialog.vue";
import PartialTreeDirectory from "@/components/partials/filemanager/PartialTreeDirectory.vue";
import BaseAnimatedButton from "@//components/base/BaseAnimatedButton.vue";
import PartialTreeDirectorySearch from "@/components/partials/filemanager/PartialTreeDirectorySearch.vue";
import {FilemanagerAPI} from "@/service/APIFilemanager.js";
import {watchImmediate} from "@vueuse/core";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";

const props = defineProps({
  open: Boolean,
  disk: String,
})

const emit = defineEmits(['select-change', 'close'])
const slots = useSlots()

const isLoading = ref(false)
const isOpen = ref(props.open)
const items = ref([])
const selectedDir = ref('')

watch(() => props.open, () => {
  isOpen.value = props.open
})

function getTree(searchValue) {
  if (isLoading.value) return

  isLoading.value = true

  if (searchValue && searchValue.trim() !== '') {
    FilemanagerAPI.fetchTree({
      path: '',
      disk: props.disk,
      search: searchValue,
    }, {
      success(response) {
        items.value = response.data
        return false
      },
      error() {
        return false
      },
      finally() {
        isLoading.value = false
      },
    })
  } else {
    setTimeout(() => {
      new Promise((resolve) => {
        isLoading.value = false

        items.value = [
          {
            name: 'root',
            full_path: '',
          },
        ]

        resolve()
      })
    }, 500)
  }
}

watchImmediate([() => props.disk, isOpen], () => {
  if (isOpen.value) getTree()
})

function changeSelectedDirectory(item) {
  selectedDir.value = item.name

  console.log('lvl1:', item)

  emit('select-change', item)
}

function clearSearch(payload) {
  payload.resetField(payload.name)
  getTree()
}

function reloadTree() {
  getTree()
}
</script>
