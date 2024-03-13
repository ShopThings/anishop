<template>
  <div class="flex flex-col gap-1">
    <span class="text-sm leading-relax">{{ title }}</span>

    <loader-progress
        v-if="isLoading"
        bg-color="bg-white"
        class="!mb-0"
        container-bg-color="bg-transparent"
    />
  </div>


  <template v-if="showBackdrop">
    <Teleport to="body">
      <div
          class="slide-rotate-hor-t-fwd fixed z-[9] w-[100vw] h-[100vh] bg-black/30 top-0 left-0"
      ></div>
    </Teleport>
  </template>
</template>

<script setup>
import LoaderProgress from "@/components/base/loader/LoaderProgress.vue";

defineProps({
  title: {
    type: String,
    default: 'در حال انجام عملیات، لطفا صبر کنید...',
  },
  isLoading: {
    type: Boolean,
    default: true,
  },
  showBackdrop: {
    type: Boolean,
    default: false,
  },
})
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
