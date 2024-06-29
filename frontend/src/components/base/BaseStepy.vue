<template>
  <div class="flex flex-wrap items-center justify-center">
    <template
      v-for="(step, key, idx) in steps"
    >
      <div
        v-if="idx > 0"
        class="w-2 h-2 rounded-full bg-indigo-400 m-6"
      ></div>
      <div
        :class="[
            'rounded-full border p-3 relative flex justify-center bg-white',
            currStepIdx === idx ? 'border-indigo-600 bg-indigo-50' : 'border-slate-300',
            idx < currStepIdx ? 'bg-emerald-100' : '',
            allowChangeStepsByClick ? 'cursor-pointer' : '',
            stepBtnClass,
        ]"
        @click="handleStepClick(step, key, idx)"
      >
        <component
          :is="outline[step.icon]"
          v-if="step.icon"
          :class="[
              'w-6 h-6 transition',
              currStepIdx === idx ? 'text-indigo-600' : 'text-slate-400',
              idx < currStepIdx ? 'opacity-60 scale-90' : '',
              step?.iconClass,
          ]"
        />
        <outline.CheckCircleIcon
          v-if="idx < currStepIdx"
          class="w-10 h-10 text-emerald-500 absolute left-1/2 top-1/2 -translate-y-1/2 -translate-x-1/2 z-[1]"
        />
        <label
          v-if="step.text"
          :class="[
              'absolute left-1/2 top-full translate-y-full -translate-x-1/2 text-xs whitespace-nowrap',
              currStepIdx === idx ? 'text-indigo-600' : '',
              idx > currStepIdx ? 'text-slate-500' : '',
              step?.textClass,
          ]"
        >{{ step.text }}</label>
      </div>
    </template>
  </div>

  <VTransitionSlideFadeDownY mode="out-in">
    <template
      is="div"
      v-for="(step, idx) in Object.keys(steps)"
      :key="step"
    >
      <template v-if="simple">
        <div
          v-if="currStepIdx === idx"
          :class="stepContainerClass"
        >
          <slot
            :allowNextStep="allowNext"
            :allowPrevStep="allowPrev"
            :currentStep="currStep"
            :currentStepIndex="currStepIdx"
            :lastStep="lastStep"
            :name="step"
            :next="handleNextClick"
            :prev="handlePrevClick"
            :step="steps.step"
          ></slot>
        </div>
      </template>
      <template v-else>
        <partial-card
          v-if="currStepIdx === idx"
          :class="stepContainerClass"
        >
          <template #body>
            <div class="p-3">
              <slot
                :allowNextStep="allowNext"
                :allowPrevStep="allowPrev"
                :currentStep="currStep"
                :currentStepIndex="currStepIdx"
                :lastStep="lastStep"
                :name="step"
                :next="handleNextClick"
                :prev="handlePrevClick"
                :step="steps.step"
              ></slot>
            </div>
          </template>
        </partial-card>
      </template>
    </template>
  </VTransitionSlideFadeDownY>

  <partial-card
    v-if="!manual"
    class="mt-3"
  >
    <template #body>
      <partial-stepy-next-prev-buttons
        :allow-next-step="allowNext"
        :allow-prev-step="allowPrev"
        :current-step="currStep"
        :current-step-index="currStepIdx"
        :last-step="lastStep"
        :loading="loading"
        @finish="handleFinishClick"
        @next="handleNextClick"
        @prev="handlePrevClick"
      />
    </template>
  </partial-card>
</template>

<script setup>
import {computed} from "vue";
import * as outline from "@heroicons/vue/24/outline/index.js";
import PartialCard from "@/components/partials/PartialCard.vue";
import VTransitionSlideFadeDownY from "@/transitions/VTransitionSlideFadeDownY.vue";
import PartialStepyNextPrevButtons from "@/components/partials/PartialStepyNextPrevButtons.vue";
import isFunction from "lodash.isfunction";

const props = defineProps({
  steps: Object,
  prevCheckFn: Function,
  nextCheckFn: Function,
  currentStep: {
    type: String,
    required: true,
  },
  allowChangeStepsByClick: {
    type: Boolean,
    default: true,
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
  manual: Boolean,
  simple: Boolean,
  stepContainerClass: {
    type: String,
    default: 'mt-6',
  },
  stepBtnClass: {
    type: String,
    default: 'my-6',
  },
})
const emit = defineEmits([
  'next', 'prev', 'finish',
  'update:currentStep',
  'update:allowNextStep',
  'update:allowPrevStep'
])

const stepsKeys = computed(() => {
  return Object.keys(props.steps)
})
const currStep = computed({
  get() {
    return props.currentStep
  },
  set(value) {
    emit('update:currentStep', value)
  },
})
const currStepIdx = computed({
  get() {
    return stepsKeys.value.indexOf(currStep.value)
  },
  set(value) {
    currStep.value = stepsKeys.value[value]
  },
})
const allowNext = computed({
  get() {
    return props.allowNextStep
  },
  set(value) {
    emit('update:allowNextStep', value)
  },
})
const allowPrev = computed({
  get() {
    return props.allowPrevStep
  },
  set(value) {
    emit('update:allowPrevStep', value)
  },
})
const lastStep = computed(() => {
  return stepsKeys.value.length - 1
})

function handleStepClick(step, key, idx) {
  if (!props.allowChangeStepsByClick) return

  if (currStepIdx.value > idx && !allowPrev.value) return
  if (currStepIdx.value < idx && !allowNext.value) return

  const res = step?.events?.click(key, idx)

  if (false !== res)
    currStepIdx.value = idx
}

function handlePrevClick() {
  if (currStepIdx.value - 1 < 0 || !allowPrev.value) return

  emit('prev', currStep.value, currStepIdx.value)

  let res = true
  if (isFunction(props.prevCheckFn))
    res = props.prevCheckFn(currStep.value, currStepIdx.value)

  if (false !== res)
    currStepIdx.value = currStepIdx.value - 1
}

function handleNextClick() {
  if (currStepIdx.value + 1 > lastStep.value || !allowNext.value) return

  emit('next', currStep.value, currStepIdx.value)

  let res = true
  if (isFunction(props.nextCheckFn))
    res = props.nextCheckFn(currStep.value, currStepIdx.value)

  if (false !== res)
    currStepIdx.value = currStepIdx.value + 1
}

function handleFinishClick() {
  emit('finish', currStep.value, currStepIdx.value)
}
</script>
