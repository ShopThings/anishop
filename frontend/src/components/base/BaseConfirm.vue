<template>
    <div>
        <div class="text-lg text-black mb-3 p-3">
            <QuestionMarkCircleIcon class="w-12 h-12 mb-2 ml-2 text-yellow-500 inline-block"/>
            آیا از انجام عملیات مطمئن هستید؟
        </div>

        <div class="flex flex-wrap justify-center pb-3">
            <base-button
                @click="$emit('close-toast')"
                class="ml-3 border-gray-500 !text-gray-500 px-6 hover:border-black hover:!text-black"
            >
                <span>خیر</span>
            </base-button>
            <base-button
                @click="accept"
                class="bg-primary border-primary px-6"
            >
                <span>بلی</span>
            </base-button>
        </div>
    </div>
</template>

<script setup>
import {QuestionMarkCircleIcon} from '@heroicons/vue/24/outline'
import BaseButton from "./BaseButton.vue";

const emit = defineEmits(['accept', 'close-toast'])

function accept() {
    emit('close-toast')
    emit('accept')
}
</script>

<style scoped>

</style>
