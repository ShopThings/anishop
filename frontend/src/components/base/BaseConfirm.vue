<template>
  <div>
    <div class="text-black p-5 flex items-baseline">
      <div>
        <QuestionMarkCircleIcon
            class="w-10 h-10 ml-2 text-yellow-600 inline-block bg-yellow-100 rounded-full p-1 shrink-0"
        />
      </div>

      <div class="grow">
        <span>{{ title || 'آیا از انجام عملیات مطمئن هستید؟' }}</span>

        <div
            v-if="subTitle"
            class="text-sm text-gray-500 mt-2 max-w-sm leading-relaxed"
        >
          {{ subTitle }}
        </div>
      </div>
    </div>

    <div class="flex flex-wrap justify-start py-3 px-8 bg-slate-50 border-t-2 border-slate-100">
      <button
          class="ml-3 border-gray-500 text-gray-500 px-6 py-2 hover:border-black hover:text-black bg-white text-sm rounded-md border transition"
          type="button"
          @click="close"
      >
        <span>خیر</span>
      </button>
      <button
          class="bg-primary text-white border-primary px-3 py-2 text-sm rounded-md border hover:bg-opacity-90 transition"
          type="button"
          @click="accept"
      >
        <span>انجام عملیات</span>
      </button>
    </div>

    <template v-if="showBackdrop">
      <Teleport to="body">
        <div
          class="slide-rotate-hor-t-fwd fixed z-10 w-[100vw] h-[100vh] bg-black/30 top-0 left-0"
            @click="close"
        ></div>
      </Teleport>
    </template>
  </div>
</template>

<script setup>
import {QuestionMarkCircleIcon} from '@heroicons/vue/24/outline'

defineProps({
  title: {
    type: String,
    default: 'آیا از انجام عملیات مطمئن هستید؟',
  },
  subTitle: String,
  showBackdrop: {
    type: Boolean,
    default: true,
  },
})
const emit = defineEmits(['accept', 'decline', 'close-toast'])

function accept() {
  emit('close-toast')
  emit('accept')
}

function close() {
  emit('close-toast')
  emit('decline')
}
</script>

<style scoped>
.slide-rotate-hor-t-fwd {
  --perspective: 300px;
  transform-style: preserve-3d;
  -webkit-animation: slide-rotate-hor-t-fwd 0.2s cubic-bezier(0.455, 0.030, 0.515, 0.955) reverse both;
  animation: slide-rotate-hor-t-fwd 0.2s cubic-bezier(0.455, 0.030, 0.515, 0.955) reverse both;
}

/**
 * ----------------------------------------
 * animation slide-rotate-hor-t-fwd
 * ----------------------------------------
 */
@-webkit-keyframes slide-rotate-hor-t-fwd {
  0% {
    -webkit-transform: perspective(var(--perspective)) translateY(0) translateZ(0) rotateX(0deg);
    transform: perspective(var(--perspective)) translateY(0) translateZ(0) rotateX(0deg);
    -webkit-transform-origin: bottom center;
    transform-origin: bottom center;
  }
  100% {
    -webkit-transform: perspective(var(--perspective)) translateY(-150px) translateZ(130px) rotateX(-90deg);
    transform: perspective(var(--perspective)) translateY(-150px) translateZ(130px) rotateX(-90deg);
    -webkit-transform-origin: bottom center;
    transform-origin: bottom center;
  }
}

@keyframes slide-rotate-hor-t-fwd {
  0% {
    -webkit-transform: perspective(var(--perspective)) translateY(0) translateZ(0) rotateX(0deg);
    transform: perspective(var(--perspective)) translateY(0) translateZ(0) rotateX(0deg);
    -webkit-transform-origin: bottom center;
    transform-origin: bottom center;
  }
  100% {
    -webkit-transform: perspective(var(--perspective)) translateY(-150px) translateZ(130px) rotateX(-90deg);
    transform: perspective(var(--perspective)) translateY(-150px) translateZ(130px) rotateX(-90deg);
    -webkit-transform-origin: bottom center;
    transform-origin: bottom center;
  }
}
</style>
