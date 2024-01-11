<template>
  <Menu>
    <MenuButton @click="changeMenuOpenStatus" ref="button" :class="btnClass">
      <slot name="button"></slot>
    </MenuButton>
    <VTransitionSlideFadeUpY>
      <slot name="items"></slot>
    </VTransitionSlideFadeUpY>
  </Menu>
</template>

<script setup>
import {onMounted, ref} from "vue"
import {Menu, MenuButton} from '@headlessui/vue'
import VTransitionSlideFadeUpY from "../../transitions/VTransitionSlideFadeUpY.vue"

const props = defineProps({
  open: {
    type: Boolean,
    default: false,
  },
  btnClass: String,
})
const emit = defineEmits(['open'])

const isOpen = ref(props.open)
const button = ref(null)

function changeMenuOpenStatus() {
  isOpen.value = !isOpen.value
  emit('open')
}

onMounted(() => {
  if (isOpen.value) button.value.el.click()
})
</script>
