<template>
    <slot name="button" :open="openModal"></slot>

    <partial-dialog
        @open="openModal"
        @close="closeModal"
        v-model:open="isOpen"
        :container-klass="containerKlass"
    ></partial-dialog>
</template>

<script setup>
import PartialDialog from "../partials/PartialDialog.vue";
import {computed, watch} from "vue";

const props = defineProps({
    open: Boolean,
    containerKlass: String,
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

<style scoped>

</style>
