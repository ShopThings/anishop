<template>
  <form
    ref="redirectionForm"
    :action="action"
    :method="method"
    class="text-center my-6 h-80 flex flex-col items-center justify-center"
  >
    <p class="font-iranyekan-bold text-lg">
      هدایت به درگاه ایمن جهت پرداخت
    </p>
    <p class="font-iranyekan-light mt-1">
      در صورتی که به درگاه هدایت نشدید، لینک زیر را کلیک کنید
      <span class="font-iranyekan-bold text-lg text-amber-600">{{ countdown.secondsWithoutPadding.value }}</span>
      ثانیه...
    </p>

    <input
      v-for="(value, name, key) in inputs"
      :key="key"
      :name="name"
      :value="value"
      type="hidden"
    >

    <base-button
      class="bg-black border-black !px-6 mt-5"
      type="submit"
    >
      کلیک کنید
    </base-button>
  </form>
</template>

<script setup>
import {onMounted, ref} from "vue";
import {useCountdown} from "@/composables/countdown-timer.js";
import BaseButton from "@/components/base/BaseButton.vue";

const props = defineProps({
  action: String,
  inputs: Object,
  method: String,
})

const redirectionForm = ref(null)
const countdown = useCountdown(10)

onMounted(() => {
  countdown.start(() => {
    countdown.stop()
    redirectionForm.value.submit()
  })
})
</script>
