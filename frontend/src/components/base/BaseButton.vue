<template>
  <router-link
      v-if="type === 'link'"
      :class="[
            defaultClass,
            'relative inline-block cursor-pointer text-center',
            'py-2 px-3 text-base leading-7 transition',
        ]"
      :to="to"
      @click="checkButton"
  >
    <slot/>
  </router-link>
  <button
      v-else
      :class="[
            defaultClass,
            'relative cursor-pointer text-center',
            'py-2 px-3 text-base leading-7 transition',
        ]"
      :type="type"
      @click="checkButton"
  >
    <slot/>
  </button>
</template>

<script setup>
defineProps({
  type: {
    type: String,
    default: 'button',
    validator(value) {
      return ['submit', 'button', 'reset', 'link'].indexOf(value) !== -1;
    },
  },
  to: [String, Object],
  defaultClass: {
    type: String,
    default: 'text-white rounded-md border hover:bg-opacity-90',
  },
})

const emit = defineEmits(['click'])

function checkButton(e) {
  emit('click', e)
}
</script>
