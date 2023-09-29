<template>
    <ul class="flex flex-wrap p-3 justify-center items-center">
        <li
            v-for="i in slidesCount"
            class=" h-4 rounded-full border hover:bg-blue-100 transition-all mx-0.5 cursor-pointer"
            :class="[
                currentSlide === (i - 1) ? 'w-8 border-blue-500 bg-blue-300' : 'w-4 bg-white'
            ]"
            @click="carousel.slideTo(i - 1)"
        ></li>
    </ul>
</template>

<script setup>
defineProps({
    carousel: {
        type: Object,
        required: true,
    },
    slideWidth: Number,
    currentSlide: Number,
    slidesCount: Number,
})
</script>

<style scoped>

</style>
