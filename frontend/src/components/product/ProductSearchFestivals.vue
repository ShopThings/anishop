<template>
  <div class="flex items-center p-3 gap-2 relative">
    <GiftIcon class="size-7 text-rose-500"/>
    <span class="font-iranyekan-bold text-xl">جشنواره‌ها</span>

    <SparklesIcon class="size-12 absolute rotate-12 text-amber-300 left-0 top-0 z-[1] animate-wiggle"/>
  </div>

  <div
      v-if="festivalsLoading"
      class="flex items-center justify-center my-3"
  >
    <loader3-dot/>
  </div>
  <div
    v-else-if="festivals?.length"
      class="divide-y divide-slate-200 p-2.5"
  >
    <button
        v-for="festival in festivals"
        :key="festival.id"
        type="button"
        class="flex items-center justify-between gap-3 w-full p-3 hover:bg-slate-100 transition"
        @click="emit('select', festival)"
    >
      <span class="font-iranyekan-bold">{{ festival.title }}</span>
      <ArrowLeftIcon class="size-6 text-cyan-600 shrink-0"/>
    </button>
  </div>
</template>

<script setup>
import {onMounted, ref} from "vue";
import {ArrowLeftIcon, GiftIcon, SparklesIcon} from "@heroicons/vue/24/outline/index.js"
import {HomeFestivalAPI} from "@/service/APIHomePages.js";
import Loader3Dot from "@/components/base/loader/Loader3Dot.vue";

const emit = defineEmits(['loaded', 'select'])

const festivals = ref(null)
const festivalsLoading = ref(true)

onMounted(() => {
  HomeFestivalAPI.fetchAll({
    success(response) {
      festivals.value = response.data
    },
    error() {
      return false
    },
    finally() {
      festivalsLoading.value = false
      emit('loaded', !!festivals.value?.length)
    },
  })
})
</script>
