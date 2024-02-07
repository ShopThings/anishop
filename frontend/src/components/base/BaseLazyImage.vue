<template>
  <img
    v-if="!actualSrc"
    :src="loadingSrc"
    :alt="alt"
    class="app-image"
  >
  <img
    v-else
    v-lazy="{ src: actualSrc, loading: loadingSrc }"
    :alt="alt"
    class="app-image"
  >
</template>

<script setup>
import {ref} from "vue";
import {FileSizes} from "@/composables/file-list.js";
import {apiRoutes} from "@/router/api-routes.js";

const props = defineProps({
  lazySrc: String,
  alt: {
    type: String,
    default: '',
  },
  isLocal: {
    type: Boolean,
    default: true, // "true" to show local images and "false" to show image from server
  },
  size: {
    type: String,
    default: () => {
      return FileSizes.ORIGINAL
    },
    validator: (value) => {
      return Object.values(FileSizes).indexOf(value) !== -1
    },
  },
  loadingSrc: {
    type: String,
    default: '/image-placeholder.jpg',
  },
})

const actualSrc = ref(null)
if (props.isLocal) {
  actualSrc.value = props.lazySrc
} else {
  actualSrc.value = import.meta.env.VITE_API_BASE_URL + apiRoutes.showFile + '?file=' + props.lazySrc + '&size=' + props.size
}
</script>

<style scoped>
.app-image {
  max-width: 100%;
  max-height: 100%;
  vertical-align: middle;
  object-fit: contain;
}

/*.app-image[lazy=loading] {}*/
</style>
