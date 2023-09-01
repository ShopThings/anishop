<template>
    <button :type="type"
            :class="[
                'relative text-white cursor-pointer rounded-md border',
                'py-2 px-3 text-base leading-7 transition hover:bg-opacity-90',
            ]"
            @click="checkButton"
    >
        <slot/>
    </button>
</template>

<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
        validator(value) {
            return ['submit', 'button', 'reset'].indexOf(value) !== -1;
        },
    },
})

const emit = defineEmits(['click'])

function checkButton() {
    emit('click')
}
</script>

<style scoped>

</style>
