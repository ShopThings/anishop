<template>
  <div class="py-4 min-h-[38px] text-center text-slate-500 bg-white rounded-xl relative">
    <img
        v-if="image"
        :class="imageClass"
        :src="image"
        alt=":("
        class="block w-44 mx-auto"
    >
    <div class="mt-4 text-orange-300">{{ message ?? 'هیچ موردی وجود ندارد' }}</div>
  </div>
</template>

<script setup>
defineProps({
  image: String,
  imageClass: String,
  message: {
    type: String,
    default: 'هیچ موردی وجود ندارد',
  },
})
</script>
