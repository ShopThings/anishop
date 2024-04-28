<template>
  <div
    :class="{'loading-container': true, loading: isLoading, visible: isVisible}"
    dir="ltr"
  >
    <div
      ref="loaderRef"
      :style="{ width: progress + '%' }" class="loader"
    >
      <div class="light"></div>
    </div>
    <div class="glow"></div>
  </div>
</template>

<script setup>
import {onBeforeUnmount, ref} from "vue"
import random from 'lodash.random'
import {usePageLoaderStore} from "@/store/StorePageLoader.js";
import {watchImmediate} from "@vueuse/core";

const props = defineProps({
  // Assume that loading will complete under this amount of time.
  defaultDuration: {
    type: Number,
    default: 8000,
    validator: (value) => {
      return value >= 0
    },
  },
  // How frequently to update
  defaultInterval: {
    type: Number,
    default: 1000,
    validator: (value) => {
      return value >= 0
    },
  },
  // 0 - 1. Add some variation to how much the bar will grow at each interval
  variation: {
    type: Number,
    default: 0.5,
    validator: (value) => {
      return value >= 0.0 && value <= 1.0
    },
  },
  // 0 - 100. Where the progress bar should start from.
  startingPoint: {
    type: Number,
    default: 5,
    validator: (value) => {
      return value >= 0 && value <= 100
    },
  },
  // Limiting how far the progress bar will get to before loading is complete
  endingPoint: {
    type: Number,
    default: 90,
    validator: (value) => {
      return value >= 0 && value <= 100
    },
  },
})

const loadingStore = usePageLoaderStore()

const loaderRef = ref(null)

const isLoading = ref(true)
const isVisible = ref(false)
const progress = ref(props.startingPoint)
const timeoutId = ref(null)

function clearTimeoutId() {
  if (timeoutId.value) {
    clearTimeout(timeoutId.value)
  }

  timeoutId.value = null
}

function changeLoaderDelay(delay) {
  if (loaderRef.value?.style) {
    loaderRef.value.style.setProperty('--delay', `${delay}ms`)
  }
}

function start() {
  isLoading.value = true
  isVisible.value = true
  progress.value = props.startingPoint

  loop()
}

async function loop() {
  clearTimeoutId()

  if (progress.value >= props.endingPoint) {
    return
  }

  changeLoaderDelay(2200)

  const size = (props.endingPoint - props.startingPoint) / (props.defaultDuration / props.defaultInterval)
  const randomIncrement = random(size * (1 - props.variation), size * (1 + props.variation));
  const p = Math.ceil(progress.value + randomIncrement);

  // console.log(`Current progress: ${progress.value}, New progress: ${p}, Random increment: ${randomIncrement}`);

  progress.value = Math.min(p, props.endingPoint)

  timeoutId.value = setTimeout(
    loop,
    random(props.defaultInterval * (1 - props.variation), props.defaultInterval * (1 + props.variation))
  )
}

function stop() {
  changeLoaderDelay(200)

  progress.value = 100

  clearTimeoutId()

  setTimeout(() => {
    isLoading.value = false
  }, 300)

  setTimeout(() => {
    if (!isLoading.value) {
      isVisible.value = false
    }
  }, 450)
}

watchImmediate(() => loadingStore.isLoading, () => {
  if (loadingStore.isLoading) {
    if (!isLoading.value) {
      start()
    }
  } else {
    stop()
  }
})

onBeforeUnmount(() => {
  clearTimeoutId()
})
</script>

<style scoped>
.loading-container {
  font-size: 0; /* remove space */
  position: fixed;
  top: 0;
  left: 0;
  height: 5px;
  width: 100%;
  opacity: 0;
  display: none;
  z-index: 100;
  transition: opacity 200ms;
}

.loading-container.visible {
  display: block;
}

.loading-container.loading {
  opacity: 1;
}

.loader {
  --delay: 200ms;

  background: linear-gradient(90deg, rgba(131, 29, 240, 1) 15%, rgba(35, 214, 214, 1) 100%);
  display: inline-block;
  height: 100%;
  width: 50%;
  overflow: hidden;
  border-radius: 0 0 5px 0;
  transition: var(--delay) width ease-out;
}

.loader > .light {
  float: right;
  height: 100%;
  width: 180px;
  background-image: linear-gradient(to right, transparent, #23d6d6, transparent);
  animation: loading-animation 3.5s ease-in infinite;
}

.glow {
  display: inline-block;
  height: 100%;
  width: 30px;
  margin-left: -30px;
  border-radius: 0 0 5px 0;
  box-shadow: 0 0 10px #23d6d6;
}

@keyframes loading-animation {
  0% {
    margin-right: 100%;
  }
  50% {
    margin-right: 100%;
  }
  100% {
    margin-right: -10%;
  }
}
</style>
