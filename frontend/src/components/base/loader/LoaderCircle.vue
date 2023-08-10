<template>
    <div class="flex justify-center items-center"
         :class="mainContainerKlass">
        <div :class="[
            'absolute top-0 left-0 w-full h-full rounded z-[1]',
            containerBgColor,
            containerKlass,
            ]">
        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[2]">
            <div :class="[
                    'h-6 w-6 border-4 rounded-full animate-spin',
                    smallCircleColor,
                    bigCircleColor,
                    spinnerKlass,
                ]"></div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    smallCircleColor: {
        type: String,
        default: 'border-t-indigo-700',
    },
    bigCircleColor: {
        type: String,
        default: 'border-indigo-200',
    },
    containerBgColor: {
        type: String,
        default: 'bg-blue-50 opacity-70',
    },
    spinnerKlass: String,
    containerKlass: String,
    mainContainerKlass: {
        type: String,
        default: 'relative h-full w-full',
    },
})
</script>
