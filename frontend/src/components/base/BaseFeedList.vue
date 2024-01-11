<template>
  <div
    :class="containerClass"
    class="relative"
  >
    <div class="flex items-start gap-3">
      <div class="z-[2]">
        <slot name="bullet">
          <div
            :class="bulletContainerClass"
            class="p-3 bg-white flex items-center justify-center"
          >
            <div
              :class="bulletClass"
              class="border-2 bg-slate-50 border-slate-200 w-3 h-3 rounded-full"
            ></div>
          </div>
        </slot>
      </div>
      <div
        v-if="!isLast"
        :class="lineClass"
        class="absolute right-5 top-0 w-0.5 translate-x-[3px] h-full bg-slate-200 -bottom-6 z-[1]"
      ></div>
      <slot name="item"></slot>
    </div>
  </div>
</template>

<script setup>
defineProps({
  containerClass: String,
  bulletContainerClass: String,
  bulletClass: String,
  lineClass: String,
  isLast: Boolean,
})
</script>
