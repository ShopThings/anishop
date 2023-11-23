<template>
    <div class="flex justify-center content-center">
        <div class="grow relative min-h-[48px]">
            <div v-if="hasCaptcha">
                <input type="hidden" name="key" :value="props.modelValue">
                <img v-if="hasCaptcha"
                     :src="captcha.image"
                     alt="captcha image"
                     class="mx-auto">
            </div>
            <loader-circle v-else/>
        </div>
        <button type="button"
                :class="[
                    'mr-2 rounded-full border-0 ring-1 ring-gray-300 text-rose-600 bg-white',
                    'min-w-[48px] min-h-[48px] group transition-all relative',
                    !canLoad ? 'cursor-not-allowed bg-gray-50' : 'cursor-pointer'
                ]"
                @click="getCaptcha" :disabled="!canLoad"
        >
            <ArrowPathRoundedSquareIcon
                :class="[
                    'w-6 h-6 mx-auto transition-all',
                    canLoad ? 'group-active:w-5 group-active:h-5 group-hover:rotate-90' : '',
                ]"/>
        </button>
    </div>
</template>

<script setup>
import {onMounted, reactive, ref} from "vue"
import {ArrowPathRoundedSquareIcon} from '@heroicons/vue/24/outline'
import LoaderCircle from "./loader/LoaderCircle.vue"
import {apiRoutes} from "../../router/api-routes.js"
import {useRequest} from "../../composables/api-request.js"

const props = defineProps({
    modelValue: String,
})
const emit = defineEmits(['update:modelValue'])

const captcha = reactive({
    image: null,
    key: null,
})
const hasCaptcha = ref(captcha.image !== null)
const canLoad = ref(true)

function getCaptcha() {
    if (canLoad.value) {
        canLoad.value = false
        //
        captcha.image = null
        captcha.key = null
        hasCaptcha.value = false

        useRequest(apiRoutes.captcha, null, {
                success: (response) => {
                    captcha.image = response.img
                    captcha.key = response.key
                    emit('update:modelValue', response.key)
                    hasCaptcha.value = true
                    return false
                },
                finally: () => {
                    canLoad.value = true
                },
            }
        )
    }
}

defineExpose({
    getCaptcha,
})

onMounted(() => {
    getCaptcha()
})
</script>

<style scoped>

</style>
