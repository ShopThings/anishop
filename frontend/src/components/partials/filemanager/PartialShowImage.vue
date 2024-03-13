<template>
  <div v-if="!item">
    <div class="w-20 h-20 flex flex-col items-center justify-center gap-1.5 rounded-lg border">
      <XCircleIcon class="w-6 h-6 text-rose-300 shrink-0 hover:text-black transition"/>
      <span class="text-xs text-slate-400">فاقد تصویر</span>
    </div>
  </div>
  <template v-else>
    <div
        v-if="item.is_dir"
        class="w-16 h-16"
    >
      <FolderIcon
          :title="item.name"
          class="w-16 h-16 text-cyan-600 shrink-0 cursor-pointer hover:text-black transition"
          @click="emit('click-dir', item)"
      />
    </div>
    <div
        v-else-if="isImageExt(item.extension)"
        class="w-20 h-20"
    >
      <base-lazy-image
          :alt="item.name"
          :is-local="false"
          :lazy-src="item.full_path"
          :size="FileSizes.SMALL"
          :title="item.name"
          class="w-20 h-20 object-contain shrink-0 border rounded-lg cursor-pointer"
          @click="emit('click-image', item)"
      ></base-lazy-image>
    </div>
    <div
        v-else-if="isAudioExt(item.extension)"
        class="w-16 h-16"
    >
      <MusicalNoteIcon
          :title="item.name"
          class="w-16 h-16 text-cyan-600 shrink-0 hover:text-black transition"
          @click="emit('click-audio', item)"
      />
    </div>
    <div
        v-else-if="isVideoExt(item.extension)"
        class="w-16 h-16"
    >
      <FilmIcon
          :title="item.name"
          class="w-16 h-16 text-rose-600 shrink-0 hover:text-black transition"
          @click="emit('click-video', item)"
      />
    </div>
    <div
        v-else
        class="w-16 h-16"
    >
      <DocumentTextIcon
          :title="item.name"
          class="w-16 h-16 text-gray-700 shrink-0 hover:text-black transition"
          @click="emit('click-doc', item)"
      />
    </div>
  </template>
</template>

<script setup>
import {FileSizes, useFileList} from "@/composables/file-list.js";
import {
  DocumentTextIcon,
  FilmIcon,
  FolderIcon,
  MusicalNoteIcon,
  XCircleIcon,
} from "@heroicons/vue/24/outline/index.js";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";

defineProps({
  item: Object,
})
const emit = defineEmits(['click-dir', 'click-image', 'click-audio', 'click-video', 'click-doc'])

const {isImageExt, isAudioExt, isVideoExt} = useFileList()
</script>
