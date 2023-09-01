<template>
    <div>
        <div class="text-black p-5 flex items-baseline">
            <div>
                <QuestionMarkCircleIcon
                    class="w-10 h-10 ml-2 text-yellow-600 inline-block bg-yellow-100 rounded-full p-1 shrink-0"
                />
            </div>

            <div class="grow">
                <span>{{ title || 'آیا از انجام عملیات مطمئن هستید؟' }}</span>

                <div
                    v-if="subTitle"
                    class="text-sm text-gray-500 mt-2 max-w-sm leading-relaxed"
                >
                    {{ subTitle }}
                </div>
            </div>
        </div>

        <div class="flex flex-wrap justify-start py-3 px-8 bg-slate-200 border-t-2 border-t-primary">
            <base-button
                @click="$emit('close-toast')"
                class="ml-3 border-gray-500 !text-gray-500 px-6 !py-1 hover:border-black hover:!text-black bg-white text-sm"
            >
                <span>خیر</span>
            </base-button>
            <base-button
                @click="accept"
                class="bg-primary border-primary px-3 !py-1 text-sm"
            >
                <span>انجام عملیات</span>
            </base-button>
        </div>
    </div>
</template>

<script setup>
import {QuestionMarkCircleIcon} from '@heroicons/vue/24/outline'
import BaseButton from "./BaseButton.vue";

defineProps({
    title: {
        type: String,
        default: 'آیا از انجام عملیات مطمئن هستید؟',
    },
    subTitle: String,
})
const emit = defineEmits(['accept', 'close-toast'])

function accept() {
    emit('close-toast')
    emit('accept')
}
</script>

<style scoped>

</style>
