<template>
  <div
    v-if="countdown.isStarted.value || (!countdown.isStarted.value && !hideAfterEnd)"
    class="flex flex-row-reverse items-center justify-center"
  >
    <div
      v-if="showDays && showHours && showMinutes"
      :class="[
        type === 'minimal' ? 'items-center' : 'items-start',
      ]"
      class="flex "
    >
      <div class="flex flex-col gap-0.5 items-center justify-center">
          <span :class="[
                  type === 'minimal' ? 'p-0.5 min-w-8 h-8' : 'p-1 min-w-10 h-10',
                ]"
                class="rounded-lg bg-emerald-100 border-2 border-emerald-300 text-center text-lg">{{
              countdown.days.value
            }}</span>
        <span v-if="type !== 'minimal'" class="text-xs text-slate-500">روز</span>
      </div>
    </div>

    <div
      v-if="showHours && showMinutes"
      :class="[
        type === 'minimal' ? 'items-center' : 'items-start',
      ]"
      class="flex"
    >
      <div class="flex flex-col gap-0.5 items-center justify-center">
          <span :class="[
                  type === 'minimal' ? 'p-0.5 min-w-8 h-8' : 'p-1 min-w-10 h-10',
                ]"
                class="rounded-lg bg-emerald-100 border-2 border-emerald-300 text-center text-lg">{{
              countdown.hours.value
            }}</span>
        <span v-if="type !== 'minimal'" class="text-xs text-slate-500">ساعت</span>
      </div>
      <span v-if="showDays" :class="[
              type === 'minimal' ? '' : 'mt-1.5',
            ]"
            class="mx-1 text-lg">:</span>
    </div>

    <div
      v-if="showMinutes"
      :class="[
        type === 'minimal' ? 'items-center' : 'items-start',
      ]"
      class="flex"
    >
      <div class="flex flex-col gap-0.5 items-center justify-center">
          <span :class="[
                  type === 'minimal' ? 'p-0.5 min-w-8 h-8' : 'p-1 min-w-10 h-10',
                ]"
                class="rounded-lg bg-emerald-100 border-2 border-emerald-300 text-center text-lg">{{
              countdown.minutes.value
            }}</span>
        <span v-if="type !== 'minimal'" class="text-xs text-slate-500">دقیقه</span>
      </div>
      <span v-if="showHours" :class="[
              type === 'minimal' ? '' : 'mt-1.5',
            ]"
            class="mx-1 text-lg">:</span>
    </div>

    <div
      :class="[
        type === 'minimal' ? 'items-center' : 'items-start',
      ]"
      class="flex"
    >
      <div class="flex flex-col gap-0.5 items-center justify-center">
          <span :class="[
                  type === 'minimal' ? 'p-0.5 min-w-8 h-8' : 'p-1 min-w-10 h-10',
                ]"
                class="rounded-lg bg-emerald-100 border-2 border-emerald-300 text-center text-lg">{{
              countdown.seconds.value
            }}</span>
        <span v-if="type !== 'minimal'" class="text-xs text-slate-500">ثانیه</span>
      </div>
      <span v-if="showMinutes" :class="[
              type === 'minimal' ? '' : 'mt-1.5',
            ]"
            class="mx-1 text-lg">:</span>
    </div>
  </div>
</template>

<script setup>
import {watch} from "vue";
import {useCountdown} from "@/composables/countdown-timer.js";

const props = defineProps({
  type: {
    type: String,
    default: 'normal',
    validator: (value) => {
      return ['normal', 'minimal'].indexOf(value) !== -1
    },
  },
  duration: {
    type: Number,
    default: 0,
  },
  showDays: {
    type: Boolean,
    default: true,
  },
  showHours: {
    type: Boolean,
    default: true,
  },
  showMinutes: {
    type: Boolean,
    default: true,
  },
  hideAfterEnd: {
    type: Boolean,
    default: false,
  },
})
const emit = defineEmits(['ended'])

function getTimeFormat() {
  let format = []

  if (props.showMinutes) {
    if (props.showHours) {
      if (props.showDays) {
        format.push('d')
      }

      format.push('h')
    }

    format.push('m')
  }

  format.push('s')

  return format.join(':')
}

const countdown = useCountdown(props.duration, null, getTimeFormat())

countdown.start(() => {
  countdown.stop()

  emit('ended')
})

watch(() => props.duration, () => {
  countdown.changeDuration(props.duration)
  countdown.reset()
})

watch([
  () => props.showDays,
  () => props.showHours,
  () => props.showMinutes,
], () => {
  countdown.changeFormat(getTimeFormat())
})
</script>
