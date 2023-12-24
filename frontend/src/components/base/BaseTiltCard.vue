<template>
  <div
      class="tilt-card transition duration-[50ms]"
      ref="tiltCard"
      @mousemove="handleCardMouseMovement"
      @mouseleave="handleCardMouseLeave"
      @touchmove="handleCardMouseMovement"
      @touchend="handleCardMouseLeave"
  >
    <slot></slot>
  </div>
</template>

<script setup>
import {ref} from "vue";

const props = defineProps({
  moveRatio: {
    type: Number,
    default: 12,
  },
  axis: {
    type: String,
    validator: (value) => {
      return ['x', 'y', 'xy'].indexOf(value) !== -1
    },
  },
})

const tiltCard = ref(null)

function handleCardMouseMovement(e) {
  if (!tiltCard.value) return

  let pageX = e.pageX
  let pageY = e.pageY

  if (e.changedTouches && e.changedTouches[0]) {
    pageX = e.changedTouches[0].pageX
    pageY = e.changedTouches[0].pageY
  }


  const target = tiltCard.value
  const xPos = ((pageX - target.offsetLeft) / target.offsetWidth) - 0.5;
  const yPos = ((pageY - target.offsetTop) / target.offsetHeight) - 0.5;

  if (props.axis === 'y' || props.axis === 'xy')
    target.style.setProperty('--mouse-y', (xPos * props.moveRatio) + 'deg')
  if (props.axis === 'x' || props.axis === 'xy')
    target.style.setProperty('--mouse-x', (yPos * props.moveRatio) + 'deg')
}

function handleCardMouseLeave() {
  if (!tiltCard.value) return

  const target = tiltCard.value

  target.style.setProperty('--mouse-y', '0deg')
  target.style.setProperty('--mouse-x', '0deg')
}
</script>

<style scoped>
.tilt-card {
  --mouse-x: 0deg;
  --mouse-y: 0deg;
  transform-style: preserve-3d;
  transform: perspective(800px) rotateX(var(--mouse-x)) rotateY(var(--mouse-y));
}
</style>
