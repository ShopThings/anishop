<template>
  <div
    class="flex"
    :class="determineClass"
  >
    <button
      ref="nextButton"
      class="my-next-navigation-button border rounded-full shadow-lg bg-white group p-1"
      :class="[
                size === 'small' ? 'w-9 h-9' : 'w-12 h-12',
                dir === 'rtl' ? prevClassName : nextClassName,
                display === 'floating-sides'
                 ? 'absolute right-2 top-1/2 -translate-y-1/2'
                 : '',
            ]"
    >
      <ChevronRightIcon class="w-6 h-6 text-gray-500 group-hover:text-black transition mx-auto"/>
    </button>
    <button
      ref="prevButton"
      class="my-prev-navigation-button border rounded-full shadow-lg bg-white group p-1"
      :class="[
                size === 'small' ? 'w-9 h-9' : 'w-12 h-12',
                dir === 'rtl' ? nextClassName : prevClassName,
                display === 'floating-sides'
                 ? 'absolute left-2 top-1/2 -translate-y-1/2'
                 : 'mr-2',
            ]"
    >
      <ChevronLeftIcon class="w-6 h-6 text-gray-500 group-hover:text-black transition mx-auto"/>
    </button>
  </div>
</template>

<script setup>
import {ChevronLeftIcon, ChevronRightIcon} from "@heroicons/vue/24/outline/index.js"
import {computed, ref} from "vue";

const props = defineProps({
  nextClassName: {
    type: String,
    required: true,
  },
  prevClassName: {
    type: String,
    required: true,
  },
  position: {
    type: String,
    default: 'left',
    validator: (value) => {
      return ['left', 'right'].indexOf(value) !== -1
    },
  },
  display: {
    type: String,
    default: 'floating',
    validator: (value) => {
      return ['solid', 'floating', 'solid-sides', 'solid-center', 'floating-sides'].indexOf(value) !== -1
    },
  },
  size: {
    type: String,
    default: 'normal',
    validator: (value) => {
      return ['small', 'normal'].indexOf(value) !== -1
    },
  },
  dir: {
    type: String,
    default: 'rtl',
    validator: (value) => {
      return ['rtl', 'ltr'].indexOf(value) !== -1
    },
  },
})
const nextButton = ref(null)
const prevButton = ref(null)

const determineClass = computed(() => {
  let klass = ''

  switch (props.display) {
    case 'solid':
      klass += 'mt-3 '
      if (props.position === 'right') {
        klass += 'justify-start '
      } else {
        klass += 'justify-end '
      }
      break
    case 'floating':
      klass += 'absolute bottom-1 -translate-y-1/2 z-[1] '
      if (props.position === 'right') {
        klass += 'right-5 '
      } else {
        klass += 'left-5 '
      }
      break
    case 'solid-sides':
      klass += 'mt-3 justify-between '
      break
    case 'solid-center':
      klass += 'mt-3 justify-center '
      break
    case 'floating-sides':
      klass += 'absolute top-1/2 left-0 w-full z-[1] '
      break
  }

  return klass.trim()
})
</script>

<style>
.swiper-button-disabled {
  opacity: 0;
  visibility: hidden;
  user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  -webkit-user-select: none;
  transition: all ease-in-out .4s;
}

.my-next-navigation-button.swiper-button-disabled {
  transform: translate(-100%, -50%);
}

.my-prev-navigation-button.swiper-button-disabled {
  transform: translate(100%, -50%);
}

.swiper-button-hidden {
  display: none;
}
</style>
