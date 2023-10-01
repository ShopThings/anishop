<template>
    <router-link
        v-if="type === 'link'"
        :to="to"
        :class="[
            'relative text-white cursor-pointer rounded-md border text-center',
            'py-2 px-3 text-base leading-7 transition hover:bg-opacity-90',
        ]"
        @click="checkButton"
    >
        <slot/>
    </router-link>
    <button
        v-else
        :type="type"
        :class="[
            'relative text-white cursor-pointer rounded-md border text-center',
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
            return ['submit', 'button', 'reset', 'link'].indexOf(value) !== -1;
        },
    },
    to: [String, Object],
})

const emit = defineEmits(['click'])

function checkButton(e) {
    emit('click', e)
}
</script>

<style scoped>

</style>
