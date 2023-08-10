<template>
    <div>
        <label v-if="labelTitle && labelTitle.length"
               class="mb-1 text-right text-black flex justify-between items-center"
               :for="labelId"
        >
            <span class="text-sm">{{ labelTitle }}</span>
            <span v-if="isOptional" class="text-sm text-gray-400">(اختیاری)</span>
        </label>
        <label v-else-if="hasLabelSlot"
               class="mb-1 text-right text-black flex justify-between items-center"
               :for="labelId"
        >
            <div class="text-sm">
                <slot name="label"></slot>
            </div>
            <span v-if="isOptional" class="text-sm text-gray-400">(اختیاری)</span>
        </label>

        <div :class="isTypePassword ? 'flex' : ''">
            <div class="flex grow relative">
                <div v-if="hasIconSlot"
                     class="absolute h-full w-10 flex justify-center items-center select-none pointer-events-none"
                >
                    <slot name="icon"/>
                </div>
                <input
                    ref="inp"
                    :id="labelId"
                    :value="value"
                    :name="name"
                    :type="type"
                    :placeholder="placeholder"
                    :class="[
                        'block w-full rounded-md border-0 py-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300',
                        'placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
                        klass,
                        isVisiblePassword ? '!ring-amber-500' : '',
                        hasIconSlot ? 'pr-10' : '',
                        ]"
                    @change="handleChange"
                    @input="checkInput($event)"
                    @blur="checkInput($event)"
                    @keydown="checkInput($event)"
                    @keyup="checkInput($event)"
                />
            </div>
            <button v-if="isTypePassword"
                    type="button"
                    :class="[
                        'mr-2 rounded border-0 ring-1 ring-gray-300 text-rose-600',
                        'min-w-[48px] group transition-all',
                        isVisiblePassword ? '!ring-amber-500' : '',
                        ]"
                    @click="togglePasswordVisibility"
            >
                <EyeSlashIcon v-if="isVisiblePassword"
                              class="w-6 h-6 mx-auto group-active:w-5 group-active:h-5 transition-all"
                />
                <EyeIcon v-if="!isVisiblePassword"
                         class="w-6 h-6 mx-auto group-active:w-5 group-active:h-5 transition-all"
                />
            </button>
        </div>
        <VTransitionSlideFadeLeftX>
            <div v-if="errorMessage" class="flex items-center p-1 text-rose-500">
                <MinusSmallIcon class="h-4 w-4 ml-1"/>
                <span class="block text-right text-sm">{{ errorMessage }}</span>
            </div>
        </VTransitionSlideFadeLeftX>
    </div>
</template>

<script setup>
import {computed, onMounted, ref, useSlots} from "vue"
import {EyeIcon, EyeSlashIcon, MinusSmallIcon} from "@heroicons/vue/24/outline"
import uniqueId from 'lodash.uniqueid'
import {useField} from "vee-validate";
import VTransitionSlideFadeLeftX from "../../transitions/VTransitionSlideFadeLeftX.vue";

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    type: {
        type: String,
        default: 'text',
    },
    placeholder: String,
    klass: String,
    labelTitle: String,
    isOptional: {
        type: Boolean,
        default: false,
    },
})

const emit = defineEmits([
    'input', 'blur', 'keydown', 'keyup', 'mount',
])

const slots = useSlots()

const hasIconSlot = !!slots['icon']
const hasLabelSlot = !!slots['label']
const isVisiblePassword = ref(false)
const inp = ref()
const labelId = ref(null)

const {value, errorMessage, handleChange} = useField(() => props.name);

const isTypePassword = computed(() => {
    return props.type === 'password'
})

function togglePasswordVisibility() {
    inp.value.type = !isVisiblePassword.value ? 'text' : props.type
    inp.value.focus()
    isVisiblePassword.value = !isVisiblePassword.value
}

function checkInput(event) {
    emit(event.type, event.target.value || '')
}

defineExpose({
    input: inp,
})

onMounted(() => {
    labelId.value = uniqueId(props.name)
    emit('mount', {input: inp.value})
});
</script>

<style scoped>

</style>
