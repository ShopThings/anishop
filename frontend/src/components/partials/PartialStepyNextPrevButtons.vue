<template>
  <div class="px-2 py-1.5 flex flex-wrap justify-end flex-col-reverse sm:flex-row relative">
    <loader-progress
      v-if="loading"
      container-bg-color="!mt-0 !mb-2 bg-blue-50 opacity-70 !absolute top-0 left-1 !w-[calc(100%-.5rem)]"
    />

    <base-button
      v-if="currentStepIndex !== 0 && showPrevStepButton"
      :class="[
                        '!text-black my-1.5 sm:mx-1.5 px-6 w-full sm:w-auto flex items-center group',
                        !allowPrevStep ? '!cursor-not-allowed !text-opacity-50 border-gray-200' : 'border-gray-400 hover:bg-gray-100',
                    ]"
      @click="handlePrevClick"
    >
      <ArrowSmallRightIcon
        :class="[
                            'h-6 w-6 ml-auto sm:ml-2 transition',
                            allowPrevStep ? 'group-hover:translate-x-2' : '',
                        ]"
      />
      <span class="ml-auto text-sm">مرحله قبل</span>
    </base-button>

    <base-button
      v-if="currentStepIndex !== lastStep && showNextStepButton"
      :class="[
                        'bg-primary text-white my-1.5 sm:mx-1.5 px-6 w-full sm:w-auto flex items-center group',
                        !allowNextStep ? '!cursor-not-allowed !text-opacity-50 !bg-opacity-50' : '',
                    ]"
      @click="handleNextClick"
    >
      <span class="mr-auto text-sm">مرحله بعد</span>
      <ArrowSmallLeftIcon
        :class="[
                            'h-6 w-6 mr-auto sm:mr-2 transition',
                            allowNextStep ? 'group-hover:-translate-x-2' : '',
                        ]"
      />
    </base-button>
    <base-button
      v-else
      :class="[
                'bg-emerald-500 text-white my-1.5 sm:mx-1.5 px-6 w-full sm:w-auto flex items-center group',
                loading ? '!cursor-not-allowed !text-opacity-50 !bg-opacity-50' : '',
            ]"
      @click="handleFinishClick"
    >
      <span class="mr-auto text-sm">اتمام</span>
      <CheckIcon class="h-6 w-6 mr-auto sm:mr-2 group-hover:scale-105 transition"/>
    </base-button>
  </div>
</template>

<script setup>
import LoaderProgress from "../base/loader/LoaderProgress.vue";
import BaseButton from "../base/BaseButton.vue";
import {ArrowSmallRightIcon, ArrowSmallLeftIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";

const props = defineProps({
  currentStep: {
    type: String,
    required: true,
  },
  currentStepIndex: {
    type: Number,
    required: true,
  },
  lastStep: {
    type: Number,
    required: true,
  },
  allowNextStep: {
    type: Boolean,
    default: true,
  },
  allowPrevStep: {
    type: Boolean,
    default: true,
  },
  loading: Boolean,
  showPrevStepButton: {
    type: Boolean,
    default: true,
  },
  showNextStepButton: {
    type: Boolean,
    default: true,
  },
})
const emit = defineEmits(['next', 'prev', 'finish'])

function handlePrevClick() {
  if (!props.loading)
    emit('prev')
}

function handleNextClick() {
  if (!props.loading)
    emit('next')
}

function handleFinishClick() {
  if (!props.loading)
    emit('finish')
}
</script>
