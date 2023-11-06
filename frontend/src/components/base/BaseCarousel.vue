<template>
    <div
        ref="carouselContainer"
    >
        <Carousel
            :mouse-drag="mouseDrag"
            :touch-drag="touchDrag"
            :items-to-show="itemsToShow"
            :items-to-scroll="itemsToScroll"
            :wrap-around="wrapAround"
            :transition="transition"
            :snap-align="snapAlign"
            :autoplay="autoplay"
            :breakpoints="breakpoints"
            :dir="dir"
            :pause-autoplay-on-hover="pauseAutoplayOnHover"
            :i18n="i18n"
            v-model="currentSlide"
            ref="carousel"
            @before-init="emit('before-init')"
            @init="emit('init')"
            @slide-start="emit('slide-start')"
            @slide-end="emit('slide-end')"
            @loop="emit('loop')"
        >
            <template #slides>
                <Slide v-for="(slide, idx) in slides" :key="idx">
                    <div v-if="useSlideEffect" class="carousel__item w-full">
                        <slot :slide="slide" :index="idx"></slot>
                    </div>
                    <template v-else>
                        <slot :slide="slide" :index="idx"></slot>
                    </template>
                </Slide>
            </template>

            <template #addons="{slideWidth, currentSlide, slidesCount}">
                <PartialCarouselNavigation
                    v-if="hasNavigation && slidesCount > 1 && carousel"
                    :carousel="carousel"
                    :slide-width="slideWidth"
                    :current-slide="currentSlide"
                    :slides-count="slidesCount"
                    :has-pagination="hasPagination"
                    :position="navigationPosition"
                    :display="navigationDisplay"
                    :always-show-buttons="alwaysShowNavigationButtons"
                    :items-to-scroll="itemsToScroll"
                    :breakpoints="breakpoints"
                />
                <PartialCarouselPagination
                    v-if="hasPagination && slidesCount > 1 && carousel"
                    :carousel="carousel"
                    :slide-width="slideWidth"
                    :current-slide="currentSlide"
                    :slides-count="slidesCount"
                    :items-to-scroll="itemsToScroll"
                    :breakpoints="breakpoints"
                />
            </template>
        </Carousel>
    </div>
</template>

<script setup>
import {computed, ref} from "vue";
import {useResizeObserver} from "@vueuse/core";
import {Carousel, Slide} from 'vue3-carousel'
import PartialCarouselNavigation from "../partials/PartialCarouselNavigation.vue";
import PartialCarouselPagination from "../partials/PartialCarouselPagination.vue";

const props = defineProps({
    modelValue: {
        type: Array,
        required: true,
    },
    current: {
        type: Number,
        default: 0,
    },
    useSlideEffect: Boolean,
    mouseDrag: {
        type: Boolean,
        default: true,
    },
    touchDrag: {
        type: Boolean,
        default: true,
    },
    hasNavigation: Boolean,
    navigationPosition: String,
    navigationDisplay: String,
    alwaysShowNavigationButtons: {
        type: Boolean,
        default: true,
    },
    hasPagination: Boolean,
    itemsToShow: {
        type: [Function, Number],
        default: 1,
    },
    itemsToScroll: {
        type: Number,
        default: 1,
    },
    snapAlign: {
        type: String,
        default: 'center',
        validator: (value) => {
            return ['center', 'start', 'end'].indexOf(value) !== -1
        },
    },
    autoplay: Number,
    wrapAround: Boolean,
    dir: {
        type: String,
        default: 'rtl',
        validator: (value) => {
            return ['rtl', 'ltr'].indexOf(value) !== -1
        },
    },
    pauseAutoplayOnHover: {
        type: Boolean,
        default: true,
    },
    transition: Number,
    breakpoints: {
        type: Object,
        default: () => {
            return {
                // 640px and up
                640: {
                    itemsToShow: 2.5,
                },
                // 1024px and up
                1024: {
                    itemsToShow: 3.5,
                },
                // 1280px and up
                1280: {
                    itemsToShow: 4.5,
                },
            }
        },
    },
})
const emit = defineEmits([
    'update:modelValue',
    'update:current',
    'before-init',
    'init',
    'slide-start',
    'slide-end',
    'loop',
])

const carouselContainer = ref(null)
const carousel = ref(null)

useResizeObserver(carouselContainer, () => {
    if (carousel.value)
        carousel.value.updateSlideWidth()
})

const slides = computed({
    get() {
        return props.modelValue
    },
    set(value) {
        emit('update:modelValue', value)
    }
})

const currentSlide = computed({
    get() {
        return props.current
    },
    set(value) {
        emit('update:current', value)
    }
})

const i18n = {
    ariaNextSlide: "اسلاید بعدی",
    ariaPreviousSlide: "اسلاید قبلی",
    ariaNavigateToSlide: "رفتن به اسلاید شماره {slideNumber}",
    ariaGallery: "گالری",
    itemXofY: "اسلاید شماره {currentSlide} از {slidesCount} اسلاید",
    iconArrowUp: "فلش رو به بالا",
    iconArrowDown: "فلش رو به پایین",
    iconArrowRight: "فلش به سمت راست",
    iconArrowLeft: "فلش به سمت چپ",
}

defineExpose({
    carousel,
})
</script>

<style scoped>
.carousel__slide {
    padding: 0;
}

.carousel__viewport {
    perspective: 2000px;
}

.carousel__track {
    transform-style: preserve-3d;
}

.carousel__slide--sliding {
    transition: 0.5s;
}

.carousel__item {
    transition: 0.5s;
}

.carousel__slide > .carousel__item {
    opacity: 0.9;
    transform: rotateY(-20deg) scale(0.88);
}

.carousel__slide--active ~ .carousel__slide > .carousel__item {
    transform: rotateY(20deg) scale(0.88);
}

.carousel__slide--prev > .carousel__item {
    opacity: 1;
    transform: rotateY(-10deg) scale(0.93);
}

.carousel__slide--next > .carousel__item {
    opacity: 1;
    transform: rotateY(10deg) scale(0.93);
}

.carousel__slide--active > .carousel__item {
    opacity: 1;
    transform: rotateY(0) scale(1);
}
</style>
