<template>
  <div class="flex items-start gap-3">
    <div
      v-if="cardNumber && cardNumber >= 1"
      class="shrink-0 rounded-full border-2 border-black flex items-center justify-center size-7"
    >
      {{ cardNumber }}
    </div>

    <div class="flex flex-col flex-wrap items-center gap-3 w-full sm:flex-row">
      <div class="flex flex-col gap-2">
        <div class="flex flex-wrap items-center gap-3">
          <span class="font-iranyekan-light text-sm">مبلغ قابل پرداخت</span>
          <div class="flex flex-wrap items-center gap-2">
            <span class="font-iranyekan-bold">{{ numberFormat(price) }}</span>
            <span class="text-xs text-gray-400">تومان</span>
          </div>
        </div>

        <div
          v-if="methodTitle"
          class="flex flex-wrap items-center gap-3"
        >
          <span class="text-sm font-iranyekan-light text-slate-500">پرداخت از طریق</span>
          <div class="text-violet-400 text-sm">{{ methodTitle }}</div>
        </div>
      </div>

      <base-button
        :disabled="loading"
        class="bg-black border-black w-2/3 sm:w-auto sm:mr-auto !py-1 !px-6"
        type="button"
        @click="emit('click')"
      >
        <VTransitionFade>
          <loader-circle
            v-if="loading"
            big-circle-color="border-transparent"
            container-bg-color="bg-black"
            main-container-klass="absolute w-full h-full top-0 left-0"
            small-circle-color="border-t-white"
            spinner-klass="!w-6 !h-6"
          />
        </VTransitionFade>

        <span>پرداخت</span>
      </base-button>
    </div>
  </div>
</template>

<script setup>
import BaseButton from "@/components/base/BaseButton.vue";
import {numberFormat} from "@/composables/helper.js";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";

defineProps({
  cardNumber: Number,
  price: {
    type: Number,
    required: true,
  },
  methodTitle: String,
  loading: Boolean,
})
const emit = defineEmits(['click'])
</script>
