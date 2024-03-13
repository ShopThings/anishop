<template>
  <VTransitionSlideFadeDownY mode="out-in">
    <div v-if="loading">
      <template v-if="slots['loader']">
        <slot name="loader"></slot>
      </template>
      <template v-else>
        <div class="px-3 py-6">
          <component :is="component"/>
          <div v-if="loadingText"
               class="text-center mt-5 text-gray-400 text-sm">{{ loadingText }}
          </div>
        </div>
      </template>
    </div>

    <div v-else>
      <slot name="content"></slot>
    </div>
  </VTransitionSlideFadeDownY>
</template>

<script setup>
import VTransitionSlideFadeDownY from "@/transitions/VTransitionSlideFadeDownY.vue";
import {capitalize, shallowRef, useSlots, watchEffect} from "vue";

const props = defineProps({
  type: {
    type: String,
    default: 'content',
    validator(value) {
      return [
        'form', 'table', 'chart', 'content',
        'circle', 'progress', 'dot-orbit',
        'text', 'list', 'list-single',
      ].indexOf(value) !== -1
    },
  },
  loading: {
    type: Boolean,
    default: true,
  },
  loadingText: String,
})
const slots = useSlots()

const component = shallowRef(null)
import('./loader/LoaderCircle.vue').then(val => {
  component.value = val.default
})

watchEffect(() => {
  let nameArr = props.type.split('-')
  const componentName = 'Loader' + nameArr.map(value => capitalize(value)).join('')
  import(`./loader/${componentName}.vue`).then(val => {
    component.value = val.default
  })
})
</script>
