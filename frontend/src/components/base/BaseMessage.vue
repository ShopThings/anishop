<template>
    <component :is="component" :hasClose="hasClose">
        <slot></slot>
    </component>
</template>

<script setup>
import {capitalize, shallowRef, watchEffect} from "vue";

const props = defineProps({
    type: {
        type: String,
        default: 'default',
        validator(value) {
            return ['default', 'info', 'error', 'success', 'warning'].indexOf(value.toLowerCase()) !== -1
        },
    },
    hasClose: {
        type: Boolean,
        default: true,
    },
})

const component = shallowRef(null)

watchEffect(() => {
    const componentName = 'Partial' + capitalize(props.type) + 'Message'
    import(`../partials/message/${componentName}.vue`).then(val => {
        component.value = val.default
    })
})
</script>

<style scoped>

</style>
