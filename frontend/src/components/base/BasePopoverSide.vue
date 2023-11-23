<template>
    <Popover ref="container" class="relative" v-slot="{close, open}">
        <PopoverButton ref="button" as="button" :class="btnClass">
            <slot name="button" :close="close" :open="open"></slot>
        </PopoverButton>
        <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0"
            enter-to="opacity-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100"
            leave-to="opacity-0"
        >
            <PopoverOverlay
                class="fixed z-[19] inset-0 bg-black bg-opacity-25"
                @click.self="close"
            />
        </TransitionChild>

        <template v-if="position === 'left'">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0 -translate-x-20"
                enter-to="opacity-100 translate-x-0"
                leave="duration-200 ease-in"
                leave-from="opacity-100 translate-x-0"
                leave-to="opacity-0 -translate-x-20"
            >
                <PopoverPanel
                    class="fixed z-20 bg-white w-4/5 h-screen top-0 left-0 sm:w-80"
                    :class="panelClass"
                >
                    <slot name="panel" :close="close" :open="open"></slot>
                </PopoverPanel>
            </TransitionChild>
        </template>
        <template v-else>
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0 translate-x-20"
                enter-to="opacity-100 translate-x-0"
                leave="duration-200 ease-in"
                leave-from="opacity-100 translate-x-0"
                leave-to="opacity-0 translate-x-20"
            >
                <PopoverPanel
                    class="fixed z-20 bg-white w-4/5 h-screen top-0 right-0 sm:w-80"
                    :class="panelClass"
                >
                    <slot name="panel" :close="close" :open="open"></slot>
                </PopoverPanel>
            </TransitionChild>
        </template>

    </Popover>
</template>

<script setup>
import {ref, watchEffect} from "vue";
import {
    Popover,
    PopoverButton,
    PopoverOverlay,
    PopoverPanel,
    TransitionChild,
} from "@headlessui/vue";

const props = defineProps({
    open: {
        type: Boolean,
        default: false,
    },
    btnClass: String,
    panelClass: {
        type: String,
        default: 'py-3 pr-6 pl-3',
    },
    position: {
        type: String,
        default: 'right',
        validator: (value) => {
            return ['right', 'left'].indexOf(value) !== -1;
        },
    },
})
const emit = defineEmits(['update:open'])

const container = ref(null)
const button = ref(null)

watchEffect(() => {
    if (props.open && button.value && button.value?.el) {
        button.value.el.click()
    }
})

defineExpose({
    container,
})
</script>

<style scoped>

</style>
