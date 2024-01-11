<template>
  <TransitionRoot appear :show="isOpen" as="template">
    <Dialog as="div" @close="closeModal" class="relative z-20">
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div @click.self="closeModal" class="fixed z-20 inset-0 bg-black bg-opacity-25"/>
      </TransitionChild>

      <div class="fixed z-20 inset-0 overflow-y-auto">
        <div
          class="flex min-h-full items-center justify-center p-4 text-center"
        >
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-90"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-90"
          >
            <DialogPanel :class="[
                            'relative w-full rounded-2xl bg-white text-right align-middle shadow-xl transition-all',
                            'max-h-[calc(100vh-2rem)]',
                             containerKlass,
                        ]">
              <div class="sticky top-0 bg-white z-[1]">
                <slot name="closeButton" :close="closeModal">
                  <base-button-close @click="closeModal"
                                     class="absolute top-0 left-0 translate-x-2/4 translate-y-2/4"/>
                </slot>

                <div v-if="slots['title']"
                     class="p-6 border-b">
                  <DialogTitle
                    as="h3"
                    class="text-lg font-medium leading-6 text-gray-900"
                  >
                    <slot name="title"></slot>
                  </DialogTitle>
                </div>
              </div>

              <div class="p-6">
                <slot name="body" :close="closeModal"></slot>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import {computed, useSlots, watch} from "vue";
import {TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle} from '@headlessui/vue';
import BaseButtonClose from "../base/BaseButtonClose.vue";

const slots = useSlots()
const props = defineProps({
  open: {
    type: Boolean,
    default: false,
  },
  containerKlass: {
    type: String,
    default: 'max-w-md overflow-hidden',
  },
})

const emit = defineEmits(['open', 'close', 'update:open'])

const isOpen = computed({
  get() {
    return props.open
  },
  set(value) {
    emit('update:open', value)
  }
})

watch(() => props.open, function () {
  if (props.open)
    openModal()
  else
    closeModal()
})

function closeModal() {
  isOpen.value = false
  emit('close')
}

function openModal() {
  isOpen.value = true
  emit('open')
}
</script>
