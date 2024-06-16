<template>
  <div v-if="isOpen"
       :class="hasClose ? 'pl-10' : 'pl-4'"
       class="text-white pl-10 pr-4 py-4 border-0 rounded relative mb-4 bg-rose-500 text-right">
    <div class="flex">
      <div class="text-xl inline-block ml-3 align-middle">
        <XCircleIcon class="w-6 h-6"/>
      </div>
      <div class="align-middle grow text-sm">
        <slot></slot>
      </div>
    </div>
    <button v-if="hasClose"
            class="absolute bg-transparent text-2xl font-semibold leading-none left-0 top-0 mt-4 ml-3 outline-none focus:outline-none group"
            @click="closeAlert">
      <XMarkIcon class="w-5 h-5 group-hover:rotate-90 transition"/>
    </button>
  </div>
</template>

<script setup>
import {ref} from "vue";
import {XCircleIcon, XMarkIcon} from '@heroicons/vue/24/outline';

defineProps({
  hasClose: {
    type: Boolean,
    default: true,
  },
})

const emit = defineEmits(['close'])

const isOpen = ref(true)

function closeAlert() {
  isOpen.value = false
  emit('close')
}
</script>
