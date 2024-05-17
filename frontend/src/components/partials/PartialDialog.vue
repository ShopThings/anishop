<template>
  <TransitionRoot :show="isOpen" appear as="template">
    <Dialog as="div" class="relative z-20" @close="closeModal">
      <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
      >
        <div class="fixed z-20 inset-0 bg-black bg-opacity-25" @click.self="closeModal"/>
      </TransitionChild>

      <div class="fixed z-20 inset-0 overflow-y-auto">
        <div
          :class="dialogContainerClass"
          class="flex min-h-full justify-center p-4 text-center"
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
            <DialogPanel
              :class="[
                    'relative w-full rounded-2xl bg-white text-right align-middle shadow-xl transition-all',
                    'max-h-[calc(100vh-2rem)] overflow-hidden my-custom-scrollbar',
                     containerKlass,
                ]"
            >
              <div class="sticky top-0 bg-white z-[1]">
                <slot :close="closeModal" name="closeButton">
                  <base-button-close
                    class="absolute top-0 left-0 translate-x-2/4 translate-y-2/4"
                    @click="closeModal"
                  />
                </slot>

                <div v-if="slots['title']" class="p-6 border-b">
                  <DialogTitle
                      as="h3"
                      class="text-lg font-medium leading-6 text-gray-900"
                  >
                    <slot name="title"></slot>
                  </DialogTitle>
                </div>
              </div>

              <div class="p-6">
                <slot :close="closeModal" name="body"></slot>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import {computed, useSlots} from "vue";
import {Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot} from '@headlessui/vue';
import BaseButtonClose from "@/components/base/BaseButtonClose.vue";

const slots = useSlots()
const props = defineProps({
  open: {
    type: Boolean,
    default: false,
  },
  dialogContainerClass: {
    type: [String, Object],
    default: 'items-center',
  },
  containerKlass: {
    type: [String, Object],
    default: 'max-w-md overflow-hidden',
  },
})

const emit = defineEmits(['open', 'close', 'update:open'])

const isOpen = computed({
  get() {
    return props.open
  },
  set(value) {
    if (value) emit('open')
    emit('update:open', value)
  }
})

function closeModal() {
  isOpen.value = false
  emit('close')
}
</script>
