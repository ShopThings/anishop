<template>
  <base-dialog
    container-klass="max-w-7xl overflow-auto"
  >
    <template #button="{open}">
      <div
        v-if="!selectedFile || !selectedFile.value"
        @click="open"
        class="cursor-pointer w-20 h-20 rounded-lg border-2 border-dashed flex items-center justify-center border-slate-400 group hover:border-slate-500 transition relative"
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
          class="absolute w-4 h-4 text-white rounded-full bg-orange-400 top-4 left-3 z-[1] group-hover:bg-orange-500 transition"
        />
      </div>
      <div
        v-else
        class="relative inline-block"
      >
        <div
          @click="open"
          class="cursor-pointer w-20 h-20 rounded-lg border-2 flex items-center justify-center border-slate-300 group hover:border-slate-400 transition overflow-hidden bg-gray-100"
          :class="{
                    'bg-violet-500 !border-violet-500 hover:bg-violet-600': type === 'video',
                    'bg-sky-500 !border-sky-500 hover:bg-sky-600': type === 'doc',
                    'bg-emerald-500 !border-emerald-500 hover:bg-emerald-600': type === 'audio',
                }"
        >
          <base-lazy-image
            v-if="type === 'image'"
            :lazy-src="selectedFile?.image"
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
          :allow-multi-operation="false"
          :has-create-folder="false"
          :has-search="true"
          :has-uploader="false"
          storages="public"
          :allow-rename="true"
          :allow-move="true"
          :allow-delete="true"
          :extensions="extensions"
          @file-selected="changeSelectedFile"
        />

        <div class="sticky bottom-0 left-0 z-[1] border-t w-full p-3 mt-3 text-left">
          <base-button
            @click="close"
            class="!text-black bg-gray-100 px-6 ml-2 hover:bg-gray-200"
          >
            بستن
          </base-button>
          <base-button
            @click="checkFileSelection(close)"
            class="bg-primary px-5"
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
  PhotoIcon,
  DocumentTextIcon,
  MusicalNoteIcon,
  FilmIcon,
  QuestionMarkCircleIcon,
  PlusIcon,
  XMarkIcon,
} from "@heroicons/vue/24/outline"
import BaseFileManager from "./BaseFileManager.vue";
import BaseDialog from "./BaseDialog.vue";
import BaseLazyImage from "./BaseLazyImage.vue";
import BaseButton from "./BaseButton.vue";
import {useToast} from "vue-toastification";

const props = defineProps({
  type: {
    type: String,
    validator(value) {
      return ['image', 'video', 'audio', 'doc'].indexOf(value) !== -1
    },
  },
  selected: Object,
  hasClearButton: Boolean,
})

const emit = defineEmits(['file-selected', 'clear-selected-file', 'update:selected'])

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
    extensions.value = ['jpeg', 'png', 'jpg', 'gif', 'svg']
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

    emit('file-selected', selectedFile.value)

    close()
  }
}

function clearSelectedFile() {
  const res = emit('clear-selected-file', selectedFile.value)
  if (res !== false)
    selectedFile.value = null
}
</script>
