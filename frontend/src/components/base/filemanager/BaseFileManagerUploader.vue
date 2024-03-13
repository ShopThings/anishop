<template>
  <base-message :has-close="false" class="mb-3" type="warning">
    <div class="mb-2">
      فایل‌های مجاز برای آپلود باید دارای یکی از
      <strong class="underline decoration-black underline-offset-8">پسوندهای زیر</strong>
      باشد و سایر فایل‌ها با پسوندهای دیگر
      برای آپلود در
      نظر گرفته نمیشود.
    </div>
    <div class="flex flex-wrap justify-center">
      <code class="rounded bg-yellow-200 bg-opacity-80 text-black py-1 px-2 m-1 text-xs">jpeg</code>
      <code class="rounded bg-yellow-200 bg-opacity-80 text-black py-1 px-2 m-1 text-xs">png</code>
      <code class="rounded bg-yellow-200 bg-opacity-80 text-black py-1 px-2 m-1 text-xs">jpg</code>
      <code class="rounded bg-yellow-200 bg-opacity-80 text-black py-1 px-2 m-1 text-xs">gif</code>
      <code class="rounded bg-yellow-200 bg-opacity-80 text-black py-1 px-2 m-1 text-xs">svg</code>
      <code class="rounded bg-yellow-200 bg-opacity-80 text-black py-1 px-2 m-1 text-xs">csv</code>
      <code class="rounded bg-yellow-200 bg-opacity-80 text-black py-1 px-2 m-1 text-xs">txt</code>
      <code class="rounded bg-yellow-200 bg-opacity-80 text-black py-1 px-2 m-1 text-xs">xlx</code>
      <code class="rounded bg-yellow-200 bg-opacity-80 text-black py-1 px-2 m-1 text-xs">xlsx</code>
      <code class="rounded bg-yellow-200 bg-opacity-80 text-black py-1 px-2 m-1 text-xs">xls</code>
      <code class="rounded bg-yellow-200 bg-opacity-80 text-black py-1 px-2 m-1 text-xs">pdf</code>
      <code class="rounded bg-yellow-200 bg-opacity-80 text-black py-1 px-2 m-1 text-xs">docx</code>
      <code class="rounded bg-yellow-200 bg-opacity-80 text-black py-1 px-2 m-1 text-xs">doc</code>
      <code class="rounded bg-yellow-200 bg-opacity-80 text-black py-1 px-2 m-1 text-xs">mp3</code>
      <code class="rounded bg-yellow-200 bg-opacity-80 text-black py-1 px-2 m-1 text-xs">mp4</code>
    </div>
  </base-message>

  <base-message :has-close="false" class="mb-3" type="info">
    محدودیت سایز آپلود هر فایل
    <code class="rounded bg-sky-200 bg-opacity-80 text-black py-1 px-2 m-1 text-xs">512</code>
    مگابایت می‌باشد.
  </base-message>

  <div
      :class="{'border-blue-500': active}"
      class="bg-slate-200 py-6 px-4 rounded-lg mb-3 border-4 border-dashed border-slate-300 bg-opacity-60 relative overflow-hidden"
      @dragenter.prevent="setActive"
      @dragover.prevent="setActive"
      @dragleave.prevent="setInactive"
      @drop.prevent="onDrop"
  >
    <VTransitionSlideFadeDownY>
      <div v-if="active"
           class="z-[2] absolute w-full h-full top-0 left-0 flex bg-blue-300 bg-opacity-50 text-black justify-center items-center text-md">
        <span class="inline-block bg-white border rounded-full py-2 px-5 bg-opacity-60 shadow-lg">فایل‌های خود را اینجا رها کنید</span>
      </div>
    </VTransitionSlideFadeDownY>

    <div class="z-[1]">
      <InboxArrowDownIcon class="w-16 h-16 mx-auto text-gray-400 block"/>
      <div class="flex items-center justify-center mt-2 mb-3">
        <div class="flex border-b border-b-slate-400 border-dashed pb-3">
          <DocumentTextIcon class="h-6 w-6 text-slate-400 mx-2"/>
          <GifIcon class="h-6 w-6 text-slate-400 mx-2"/>
          <MusicalNoteIcon class="h-6 w-6 text-slate-400 mx-2"/>
          <PhotoIcon class="h-6 w-6 text-slate-400 mx-2"/>
          <FilmIcon class="h-6 w-6 text-slate-400 mx-2"/>
        </div>
      </div>
      <div class="text-sm text-center mt-3">
        <span class="text-slate-600 block sm:inline-block">فایل را کشیده و اینجا رها کنید</span>
        <span class="text-gray-400 block sm:inline-block my-3 sm:mx-3 sm:my-0">یا</span>
        <div class="block sm:inline-block">
          <input
              id="fileUpload"
              class="hidden"
              multiple
              type="file"
              @change="onChange"
          >
          <label
              class="inline-block rounded-md border-2 border-pink-600 bg-pink-200 bg-opacity-30 py-2 px-4 cursor-pointer hover:bg-opacity-50 transition"
              for="fileUpload"
          >
            انتخاب فایل
          </label>
        </div>
      </div>
    </div>
  </div>

  <div v-show="files.length > 0">
    <div class="block md:grid md:grid-cols-2 gap-3 mb-3">
      <div
          v-for="file of files"
          :key="file.id"
          class="flex p-3 bg-white mb-3 md:mb-0 rounded-lg shadow-md items-center relative"
      >
        <img
            v-if="isImageExt(file.extension)"
            :alt="file.file.name"
            :src="file.url"
            :title="file.file.name"
            class="w-20 h-20 object-contain shrink-0 border rounded-lg"
        />
        <MusicalNoteIcon
            v-else-if="isAudioExt(file.extension)"
            class="w-16 h-16 text-cyan-600 shrink-0"
        />
        <FilmIcon
            v-else-if="isVideoExt(file.extension)"
            class="w-16 h-16 text-rose-600 shrink-0"
        />
        <DocumentTextIcon
            v-else
            class="w-16 h-16 text-gray-700 shrink-0"
        />

        <span class="grow mx-3 overflow-hidden text-ellipsis text-gray-600">{{ file.file.name }}</span>
        <base-button-close
            v-tooltip.right="'حذف فایل'"
            class="shrink-0 px-1 h-full"
            @click="removeFile(file)"
        ></base-button-close>

        <div
            v-if="file.status !== UploadTypes.NOT_SET"
            :class="{
              'bg-sky-400': file.status === UploadTypes.UPLOADING,
              'bg-green-400': file.status === UploadTypes.SUCCESS,
              'bg-rose-400': file.status === UploadTypes.FAILED
            }"
            class="absolute w-full h-full top-0 left-0 bg-opacity-90 rounded-lg z-[1] flex flex-col items-center justify-center p-5"
        >
          <span v-if="file.status === UploadTypes.UPLOADING">در حال بارگذاری فایل...</span>
          <span v-else-if="file.status === UploadTypes.SUCCESS" class="text-lg flex items-center">
              <CheckCircleIcon class="h-10 w-10 text-green-800 ml-2"/>
              بارگذاری با موفقیت انجام شد
          </span>
          <div v-else-if="file.status === UploadTypes.FAILED" class="text-lg flex flex-col justify-center">
            <div class="flex items-center">
              <XCircleIcon class="h-10 w-10 text-red-800 ml-2"/>
              خطا در بارگذاری
            </div>
            <div
                v-if="file.errorMessage"
                class="text-xs text-black mt-2"
            >
              {{ file.errorMessage }}
            </div>
          </div>
          <div
              v-if="file.status === UploadTypes.UPLOADING"
              class="flex flex-row-reverse grow items-center w-full"
          >
            <div class="h-2 w-full grow rounded-full bg-white bg-opacity-80 shadow-md">
              <div :style="'width:' + file.progress + '%'" class="bg-blue-700 h-full rounded-full"></div>
            </div>
            <base-button-close
                v-tooltip.top="'لغو بارگذاری'"
                class="shrink-0 ml-3 bg-white rounded bg-opacity-70 shadow-md"
                @click="cancelUpload(file)"
            ></base-button-close>
          </div>
        </div>
      </div>
    </div>

    <div
        v-if="canUpload"
        class="mt-3 flex flex-col sm:flex-row md:block md:mt-0 text-left"
    >
      <base-button
          :disabled="!canUpload"
          class="text-sm bg-emerald-500 border-emerald-600 border-2 grow mb-3"
          @click="startUploadFiles"
      >
        آپلود تمامی فایل‌ها
      </base-button>
      <base-button
          :disabled="!canUpload"
          class="!text-black text-sm rounded-md border-2 border-rose-600 bg-rose-300 bg-opacity-20 py-2 px-4 mb-3 hover:bg-opacity-30 transition grow sm:mr-2"
          @click="clearUploadItems"
      >
        حذف تمامی فایل‌های آپلود
      </base-button>
    </div>
  </div>
</template>

<script setup>
import {
  InboxArrowDownIcon, DocumentTextIcon, GifIcon, MusicalNoteIcon, PhotoIcon,
  CheckCircleIcon, XCircleIcon, FilmIcon
} from '@heroicons/vue/24/outline';
import {onMounted, onUnmounted, ref} from "vue";
import {useFileList, UploadTypes} from "@/composables/file-list.js";
import BaseButtonClose from "../BaseButtonClose.vue";
import BaseMessage from "../BaseMessage.vue";
import VTransitionSlideFadeDownY from "@/transitions/VTransitionSlideFadeDownY.vue";
import BaseButton from "../BaseButton.vue";
import {useRequest} from "@/composables/api-request.js";
import {apiRoutes} from "@/router/api-routes.js";
import {useConfirmToast} from "@/composables/toast-helper.js";
import {useToast} from "vue-toastification";

const props = defineProps({
  path: {
    type: String,
    required: true,
  },
  disk: {
    type: String,
    required: true,
  },
})

const emit = defineEmits(['upload-complete'])

const toast = useToast()
const {files, addFiles, removeFile, isImageExt, isAudioExt, isVideoExt} = useFileList()

const active = ref(false)
let inActiveTimeout = null

const canUpload = ref(true)

function startUploadFiles() {
  if (!canUpload.value) {
    toast.info('در حال عملیات بارگذاری می‌باشد، لطفا بعد از اتمام عملیات مجددا تلاش نمایید.')
  }

  canUpload.value = false
  let counter = 0;
  let uploadCheckInterval = null;

  // make some props local to prevent unwanted change of uploading path and disk
  const disk = props.disk
  const path = props.path

  files.value.forEach((file) => {
    const controller = new AbortController();

    file.status = UploadTypes.UPLOADING
    file.errorMessage = null

    let formData = new FormData()
    formData.append('disk', disk)
    formData.append('path', path)
    formData.append('file', file.file)

    useRequest(apiRoutes.admin.files.upload, {
      method: 'POST',
      headers: {'Content-Type': 'multipart/form-data'},
      onUploadProgress: progressEvent => {
        file.progress = Math.round((progressEvent.loaded / progressEvent.total) * 100);
      },
      signal: controller.signal,
      data: formData,
    }, {
      success: () => {
        file.status = UploadTypes.SUCCESS
        return false
      },
      error: (error) => {
        file.status = UploadTypes.FAILED
        file.errorMessage = error.message || 'خطای نامشخص'
        return false
      },
      finally: () => {
        counter++;
      },
    })
    file.abort = controller
  })

  uploadCheckInterval = setInterval(() => {
    if (counter >= files.value.length) {
      canUpload.value = true
      clearInterval(uploadCheckInterval)

      setTimeout(() => {
        files.value = files.value.filter((file) => {
          return file.status !== UploadTypes.SUCCESS
        }).map((file) => {
          file.status = null
          return file
        })
      }, 3000)

      emit('upload-complete')
    }
  }, 1000)
}

function clearUploadItems() {
  if (!canUpload.value) return

  useConfirmToast(() => {
    files.value = []
  })
}

function cancelUpload(file) {
  useConfirmToast(() => {
    if (file.abort) {
      file.abort.abort()
      setTimeout(() => {
        removeFile(file)
      }, 500)
    }
  })
}

function setActive() {
  clearTimeout(inActiveTimeout)
  active.value = true
}

function setInactive() {
  inActiveTimeout = setTimeout(() => {
    active.value = false
  }, 50)
}

function onDrop(e) {
  setInactive()
  if (e.dataTransfer?.files)
    addFiles([...e.dataTransfer.files])
}

function onChange(e) {
  if (e.target?.files)
    addFiles([...e.target.files])
}

function preventDefaults(e) {
  e.preventDefault()
}

const events = ['dragenter', 'dragover', 'dragleave', 'drop']

onMounted(() => {
  events.forEach((eventName) => {
    document.body.addEventListener(eventName, preventDefaults)
  })
})

onUnmounted(() => {
  events.forEach((eventName) => {
    document.body.removeEventListener(eventName, preventDefaults)
  })
})
</script>
