<template>
    <div v-if="isOpen"
         class="text-white pl-10 pr-4 py-4 border-0 rounded relative mb-4 bg-amber-500 text-right"
         :class="hasClose ? 'pl-10' : 'pl-4'">
        <div class="flex">
            <span class="text-xl inline-block ml-3 align-middle">
                <BellAlertIcon class="w-6 h-6"/>
            </span>
            <span class="inline-block align-middle grow text-sm">
                <slot></slot>
            </span>
        </div>
        <button v-if="hasClose"
                @click="closeAlert"
                class="absolute bg-transparent text-2xl font-semibold leading-none left-0 top-0 mt-4 ml-3 outline-none focus:outline-none group">
            <XMarkIcon class="w-5 h-5 group-hover:rotate-90 transition"/>
        </button>
    </div>
</template>

<script setup>
import {BellAlertIcon, XMarkIcon} from '@heroicons/vue/24/outline';
import {ref} from "vue";

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

<style scoped>

</style>
