<template>
  <div :class="mainContainerKlass"
       class="flex justify-center items-center">
    <div :class="[
      'absolute top-0 left-0 w-full h-full rounded z-[2]',
      containerBgColor,
      containerKlass,
    ]">
    </div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[3]">
      <div :class="[
        'h-8 w-8 border-[3px] rounded-full animate-spin',
        smallCircleColor,
        bigCircleColor,
        spinnerKlass,
      ]"></div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  smallCircleColor: {
    type: String,
    default: 'border-t-indigo-700',
  },
  bigCircleColor: {
    type: String,
    default: 'border-indigo-200',
  },
  containerBgColor: {
    type: String,
    default: 'bg-blue-50/70',
  },
  spinnerKlass: String,
  containerKlass: String,
  mainContainerKlass: {
    type: String,
    default: 'relative h-full w-full',
  },
})
</script>
