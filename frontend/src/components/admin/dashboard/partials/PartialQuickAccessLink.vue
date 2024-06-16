<template>
  <router-link
    v-if="isObject(link) || link?.toString().trim() !== ''"
    :class="btnClass"
    :to="link"
    class="quick-access-link-btn flex lg:flex-col gap-2 items-center py-1 sm:py-2 lg:py-4 px-3 relative z-[2] bg-white border-[3px] border-transparent focus:border-indigo-400 group transition overflow-hidden shadow"
    @click="emit('click')"
    @mouseleave.self="mouseleaveHandler"
    @mousemove.self="mousemoveHandler"
    @touchend.self="mouseleaveHandler"
    @touchmove.self="mousemoveHandler"
  >
    <component
      :is="outline[icon]"
      class="pointer-events-none size-9 md:size-10 lg:size-12 text-slate-300 group-hover:text-indigo-400 group-focus:text-indigo-400 transition"
    />

    <span
      class="pointer-events-none text-sm md:text-base text-slate-500 group-hover:text-slate-600 group-focus:text-slate-600 transition">{{
        text
      }}</span>
  </router-link>
  <button
    v-else
    :class="btnClass"
    class="quick-access-link-btn flex lg:flex-col gap-2 items-center py-1 sm:py-2 lg:py-4 px-3 relative z-[2] bg-white border-[3px] border-transparent focus:border-green-400 group transition overflow-hidden shadow"
    type="button"
    @click="emit('click')"
    @mouseleave.self="mouseleaveHandler"
    @mousemove.self="mousemoveHandler"
    @touchend.self="mouseleaveHandler"
    @touchmove.self="mousemoveHandler"
  >
    <component
      :is="outline[icon]"
      class="pointer-events-none size-9 md:size-10 lg:size-12 text-green-400 group-hover:text-green-500 group-focus:text-green-400 transition"
    />

    <span
      class="pointer-events-none text-sm md:text-base text-slate-500 group-hover:text-slate-600 group-focus:text-slate-600 transition">{{
        text
      }}</span>
  </button>
</template>

<script setup>
import * as outline from "@heroicons/vue/24/outline/index.js";
import isObject from "lodash.isobject";

defineProps({
  text: {
    type: String,
    required: true,
  },
  icon: {
    type: String,
    required: true,
  },
  link: [String, Object],
  btnClass: [String, Object],
})
const emit = defineEmits(['click'])

function mouseleaveHandler(e) {
  e.target.style.background = "white"
  e.target.style.borderImage = null
}

function mousemoveHandler(e) {
  let clientX = e.clientX
  let clientY = e.clientY

  if (e.changedTouches && e.changedTouches[0]) {
    clientX = e.changedTouches[0].clientX
    clientY = e.changedTouches[0].clientY
  }

  const rect = e.target.getBoundingClientRect();
  const x = clientX - rect.left; // x position within the element.
  const y = clientY - rect.top; // y position within the element.
  e.target.style.background = `radial-gradient(70% 90% at ${x}px ${y}px, rgba(59, 130, 246, 0.3), rgba(255, 255, 255, 0.15))`;
  e.target.style.borderImage = `radial-gradient(20% 75% at ${x}px ${y}px, rgba(37, 99, 237, 0.9), rgba(37, 99, 237, 0.1)) 1 / 3px / 0px stretch`;
}
</script>
