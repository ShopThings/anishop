<template>
    <VTransitionSlideFadeDownY mode="out-in">
        <div v-if="loading" class="px-3 py-6">
            <component :is="component"/>
            <span v-if="loadingText"
                  class="text-center block mt-3 text-gray-400 text-sm">{{ loadingText }}</span>
        </div>

        <div v-else>
            <slot name="content"></slot>
        </div>
    </VTransitionSlideFadeDownY>
</template>

<script setup>
import VTransitionSlideFadeDownY from "../../transitions/VTransitionSlideFadeDownY.vue";
import {capitalize, shallowRef, watchEffect} from "vue";

const props = defineProps({
    type: {
        type: String,
        default: 'content',
        validator(value) {
            return ['form', 'table', 'chart', 'content', 'circle', 'progress', 'dot-orbit'].indexOf(value) !== -1
        },
    },
    loading: {
        type: Boolean,
        default: true,
    },
    loadingText: String,
})

const component = shallowRef(null)
import('./loader/LoaderCircle.vue').then(val => {
    component.value = val.default
})

watchEffect(() => {
    let nameArr = props.type.split('-')
    const componentName = 'Loader' + nameArr.map(value => capitalize(value)).join('')
    import(`./loader/${componentName}.vue`).then(val => {
        component.value = val.default
    })
})
</script>

<style scoped>

</style>
