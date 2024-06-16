<template>
  <div class="flex justify-center items-center">
    <div class="grow relative min-h-[48px] flex items-center justify-center">
      <template v-if="hasCaptcha">
        <input :value="props.modelValue" name="key" type="hidden">
        <img
          :src="captcha.image"
          alt="captcha image"
          class="mx-auto"
        >
      </template>
      <loader-circle v-else/>
    </div>
    <button
      v-tooltip.right="'دریافت کد جدید'"
      :class="[
          'mr-2 rounded-full border-0 ring-1 ring-gray-300 text-rose-600 bg-white shrink-0',
          'min-w-[48px] min-h-[48px] group transition-all relative',
          !canLoad ? 'cursor-not-allowed bg-gray-50' : 'cursor-pointer'
      ]"
      :disabled="!canLoad"
      type="button" @click="getCaptcha"
    >
      <ArrowPathRoundedSquareIcon
        :class="[
            'w-6 h-6 mx-auto transition-all',
            canLoad ? 'group-active:w-5 group-active:h-5 group-hover:rotate-90' : '',
        ]"
      />

      <span
        :class="[invalidateTimer.minutesWithoutPadding.value !== 0 || invalidateTimer.secondsWithoutPadding.value !== 0 ? 'text-green-600 border-green-400' : 'text-red-600 border-red-300']"
        class="absolute -bottom-4 left-1/2 -translate-x-1/2 bg-white rounded-full border text-xs py-0.5 px-1 min-w-11">{{
          invalidateTimer.minutes.value
        }}:{{ invalidateTimer.seconds.value }}</span>
    </button>
  </div>
</template>

<script setup>
import {onMounted, reactive, ref} from "vue"
import {ArrowPathRoundedSquareIcon} from '@heroicons/vue/24/outline'
import LoaderCircle from "./loader/LoaderCircle.vue"
import {apiRoutes} from "@/router/api-routes.js"
import {useRequest} from "@/composables/api-request.js"
import {useCountdown} from "@/composables/countdown-timer.js";

const props = defineProps({
  modelValue: String,
})
const emit = defineEmits(['update:modelValue'])

const captcha = reactive({
  image: null,
  key: null,
})
const hasCaptcha = ref(captcha.image !== null)
const canLoad = ref(true)

const invalidateTimer = useCountdown(60)

function getCaptcha() {
  if (!canLoad.value) return

  canLoad.value = false

  captcha.image = null
  captcha.key = null
  hasCaptcha.value = false

  invalidateTimer.stop()

  useRequest(apiRoutes.captcha, null, {
      success: (response) => {
        captcha.image = response.img
        captcha.key = response.key
        emit('update:modelValue', response.key)
        hasCaptcha.value = true

        invalidateTimer.start(() => {
          invalidateTimer.stop()
        })

        return false
      },
      finally: () => {
        canLoad.value = true
      },
    }
  )
}

defineExpose({
  getCaptcha,
})

onMounted(() => {
  getCaptcha()
})
</script>
