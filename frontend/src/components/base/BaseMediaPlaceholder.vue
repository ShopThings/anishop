<template>
  <base-dialog container-klass="layout-max-w w-full">
    <template #button="{open}">
      <div
          v-if="!selectedFile"
          v-tooltip.left-start="'برای تغییر فایل کلیک کنید/ضربه بزنید'"
          class="cursor-pointer w-20 h-20 rounded-lg border-2 border-dashed flex items-center justify-center border-slate-400 group hover:border-slate-500 transition relative"
          @click="open"
      >
        <PhotoIcon
            v-if="type === 'image'"
            class="w-10 h-10 text-slate-400 group-hover:text-slate-500 transition"
        />
        <FilmIcon
            v-else-if="type === 'video'"
            class="w-10 h-10 text-slate-400 group-hover:text-slate-500 transition"
        />
        <DocumentTextIcon
            v-else-if="type === 'doc'"
            class="w-10 h-10 text-slate-400 group-hover:text-slate-500 transition"
        />
        <MusicalNoteIcon
            v-else-if="type === 'audio'"
            class="w-10 h-10 text-slate-400 group-hover:text-slate-500 transition"
        />
        <QuestionMarkCircleIcon
            v-else
            class="w-10 h-10 text-slate-400 group-hover:text-slate-500 transition"
        />
        <PlusIcon
          class="absolute w-4 h-4 text-white rounded-full bg-orange-400 top-4 left-3 group-hover:bg-orange-500 transition"
        />
      </div>
      <div
          v-else
          class="relative inline-block"
      >
        <div
            v-tooltip.left-start="'برای تغییر فایل کلیک کنید/ضربه بزنید'"
            :class="{
              'bg-violet-500 !border-violet-500 hover:bg-violet-600': type === 'video',
              'bg-sky-500 !border-sky-500 hover:bg-sky-600': type === 'doc',
              'bg-emerald-500 !border-emerald-500 hover:bg-emerald-600': type === 'audio',
          }"
            class="cursor-pointer w-20 h-20 rounded-lg border-2 flex items-center justify-center border-slate-300 group hover:border-slate-400 transition overflow-hidden bg-gray-100"
            @click="open"
        >
          <base-lazy-image
              v-if="type === 'image'"
              :key="selectedFile.full_path"
              :alt="selectedFile?.name"
              :is-local="false"
              :lazy-src="selectedFile?.full_path"
              :size="FileSizes.SMALL"
          />
          <FilmIcon
              v-else-if="type === 'video'"
              class="w-12 h-12 text-violet-300 group-hover:text-violet-200 group-hover:w-10 group-hover:h-10 transition-all"
          />
          <DocumentTextIcon
              v-else-if="type === 'doc'"
              class="w-12 h-12 text-sky-300 group-hover:text-sky-200 group-hover:w-10 group-hover:h-10 transition-all"
          />
          <MusicalNoteIcon
              v-else-if="type === 'audio'"
              class="w-12 h-12 text-emerald-300 group-hover:text-emerald-200 group-hover:w-10 group-hover:h-10 transition-all"
          />
          <QuestionMarkCircleIcon
              v-else
              class="w-12 h-12 text-amber-300 group-hover:text-amber-200 group-hover:w-10 group-hover:h-10 transition-all"
          />
        </div>

        <XMarkIcon
            v-if="hasClearButton"
            v-tooltip.bottom-end="'پاک کردن'"
            class="absolute w-6 h-6 left-0 top-0 -translate-x-1/3 -translate-y-1/3 p-1 border rounded-md text-black transition cursor-pointer shadow z-[1] bg-pink-100 border-pink-300 hover:bg-pink-500 hover:text-white"
            @click="clearSelectedFile"
        />
      </div>
    </template>

    <template #title>
      انتخاب تصویر
    </template>

    <template #body="{close}">
      <div class="relative">
        <base-file-manager
            :allow-delete="true"
            :allow-move="true"
            :allow-multi-operation="false"
            :allow-rename="true"
            :extensions="extensions"
            :has-create-folder="false"
            :has-search="true"
            :has-uploader="false"
            :selectable-files="true"
            storages="public"
            @file-selected="changeSelectedFile"
        />

        <div class="sticky bottom-0 left-0 z-[1] border-t w-full p-3 mt-3 text-left bg-white/85">
          <base-button
              class="!text-black bg-gray-100 px-6 ml-2 hover:bg-gray-200"
              @click="close"
          >
            بستن
          </base-button>
          <base-button
              class="bg-primary px-5"
              @click="checkFileSelection(close)"
          >
            انتخاب فایل
          </base-button>
        </div>
      </div>
    </template>
  </base-dialog>
</template>

<script setup>
import {computed, ref, watch} from "vue";
import {
  DocumentTextIcon,
  FilmIcon,
  MusicalNoteIcon,
  PhotoIcon,
  PlusIcon,
  QuestionMarkCircleIcon,
  XMarkIcon,
} from "@heroicons/vue/24/outline"
import BaseFileManager from "./BaseFileManager.vue";
import BaseDialog from "./BaseDialog.vue";
import BaseLazyImage from "./BaseLazyImage.vue";
import BaseButton from "./BaseButton.vue";
import {useToast} from "vue-toastification";
import {FileSizes} from "@/composables/file-list.js";
import isFunction from "lodash.isfunction";

const props = defineProps({
  type: {
    type: String,
    validator(value) {
      return ['image', 'video', 'audio', 'doc'].indexOf(value) !== -1
    },
  },
  selected: Object,
  hasClearButton: Boolean,
  clearCheckFn: Function,
})

// 'file-changed' emits when a previous file is different from new selected file
// 'file-selected' emits when a file change in any situation
const emit = defineEmits(['update:selected', 'file-changed', 'file-selected', 'clear-selected-file'])

const toast = useToast()

const extensions = ref([])
const tmpSelectedFile = ref(null)
const selectedFile = computed({
  get() {
    return props.selected
  },
  set(value) {
    emit('update:selected', value)
  },
})

function validExtensions() {
  extensions.value = []

  if (props.type === 'image')
    extensions.value = ['jpeg', 'png', 'jpg', 'webp', 'gif', 'svg']
  else if (props.type === 'video')
    extensions.value = ['mp4']
  else if (props.type === 'audio')
    extensions.value = ['mp3']
  else if (props.type === 'doc')
    extensions.value = ['csv', 'txt', 'xlx', 'xlsx', 'xls', 'pdf', 'docx', 'doc']
}

validExtensions()

watch(() => props.type, () => {
  validExtensions()
})

watch(selectedFile, (newValue, oldValue) => {
  emit('file-selected', selectedFile.value)

  if (newValue?.full_path !== oldValue?.full_path) {
    emit('file-changed', selectedFile.value)
  }
})

function changeSelectedFile(file) {
  tmpSelectedFile.value = file
}

function checkFileSelection(close) {
  if (close) {
    if (!tmpSelectedFile.value) {
      toast.warning('ابتدا فایل خود را انتخاب نمایید.')
      return
    }

    selectedFile.value = tmpSelectedFile.value
    tmpSelectedFile.value = null

    close()
  }
}

function clearSelectedFile() {
  emit('clear-selected-file', selectedFile.value)

  let res = true
  if (isFunction(props.clearCheckFn))
    res = props.clearCheckFn(selectedFile.value)

  if (res !== false)
    selectedFile.value = null
}
</script>
