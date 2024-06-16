<template>
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
      :storages="Storages.public"
      @file-selected="changeSelectedFile"
    >
    </base-file-manager>

    <div class="sticky bottom-0 left-0 z-[1] border-t w-full p-3 mt-3 text-left bg-white">
      <base-button
        class="!text-black bg-gray-100 px-6 ml-2 hover:bg-gray-200"
        @click="closeWindowPopup"
      >
        بستن
      </base-button>
      <base-button
        class="bg-primary px-5"
        @click="checkFileSelection"
      >
        انتخاب فایل
      </base-button>
    </div>
  </div>
</template>

<script setup>
import BaseFileManager from "@/components/base/BaseFileManager.vue";
import {ref} from "vue";
import {useRoute} from "vue-router";
import BaseButton from "@/components/base/BaseButton.vue";
import {useToast} from "vue-toastification";
import {apiRoutes} from "@/router/api-routes.js";
import {Storages} from "@/composables/file-list.js";

const route = useRoute()
const toast = useToast()

const extensions = ref([])
const selectedFile = ref(null)

function validExtensions() {
  const type = route.query.type || null
  extensions.value = []

  if (type === 'image')
    extensions.value = ['jpeg', 'png', 'jpg', 'webp', 'gif', 'svg']
  else if (type === 'video')
    extensions.value = ['mp4']
  else if (type === 'audio')
    extensions.value = ['mp3']
  else if (type === 'doc')
    extensions.value = ['csv', 'txt', 'xlx', 'xlsx', 'xls', 'pdf', 'docx', 'doc']
}

validExtensions()

function changeSelectedFile(file) {
  selectedFile.value = file
}

function checkFileSelection() {
  if (!selectedFile.value || !selectedFile.value.full_path) {
    toast.warning('ابتدا فایل خود را انتخاب نمایید.')
    return
  }

  const fileSrc = import.meta.env.VITE_API_BASE_URL + apiRoutes.showFile + '?file=' + selectedFile.value.full_path
  top.tinymce.activeEditor.windowManager.getParams().onInsert(fileSrc)

  closeWindowPopup()
}

function closeWindowPopup() {
  top.tinymce.activeEditor.windowManager.close()
}
</script>
