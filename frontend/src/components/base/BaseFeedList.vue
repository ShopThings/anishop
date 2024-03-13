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
              class="p-4 relative"
          >
            <div
                :class="bulletClass"
                class="absolute top-1/2 left-1/2 -translate-x-[calc(50%+2px)] -translate-y-[calc(50%+2px)] border-2 bg-slate-50 border-slate-200 w-3 h-3 rounded-full white-shadow"
            ></div>
          </div>
        </slot>
      </div>
      <div
          v-if="!isLast"
          :class="lineClass"
          class="absolute right-5 top-3 w-0.5 translate-x-[3px] h-full bg-slate-200 -bottom-6 z-[1]"
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

<style scoped>
.white-shadow {
  box-shadow: 0 0 0 5px #ffffff;
}
</style>
