<template>
    <div
        ref="container"
        class="overflow-hidden min-h-[38px]"
        :class="[
            containerClass,
            extraContainerClass,
            useHeightAnimation ? 'transition-all' : '',
        ]"
    >
        <div ref="backContainer" class="relative">
            <Transition name="slide-fade-down-y">
                <slot v-if="activeTopText" name="backHeader">
                    <div class="flex items-center gap-2 justify-between bg-slate-200 py-1.5 px-2">
                        <span class="mx-auto text-sm text-slate-600">{{ activeTopText }}</span>
                        <div
                            v-if="showBackButton"
                            class="text-center p-1 rounded-full group shadow-lg bg-white cursor-pointer"
                            @click="back"
                        >
                            <ChevronLeftIcon
                                class="w-5 h-5 text-black group-hover:text-blue-600 transition"/>
                        </div>
                    </div>
                </slot>
            </Transition>
        </div>
        <div
            ref="scrollingContainer"
            :class="[
                'relative',
                useFixedHeight ? 'my-custom-scrollbar !overflow-x-hidden' : '',
            ]"
        >
            <template v-for="(panel, index) in panels" :key="index">
                <div
                    v-if="slots[panel]"
                    :class="[
                    'hidden w-full absolute top-0 right-0 z-[1]',
                    panelClass,
                ]"
                    :ref="(el) => (allPanels[panel] = el)"
                >
                    <slot :name="panel" :goTo="gotTo"></slot>
                </div>
            </template>
        </div>
    </div>
</template>

<script setup>
import {computed, nextTick, onMounted, ref, useSlots, watchEffect} from "vue";
import {ChevronLeftIcon} from "@heroicons/vue/24/outline/index.js";
import isFunction from "lodash.isfunction";
import {useResizeObserver} from "@vueuse/core";

const props = defineProps({
    panels: {
        type: Object,
        required: true,
    },
    activePanel: {
        type: String,
        required: true,
    },
    showBackButton: {
        type: Boolean,
        default: true,
    },
    useFixedHeight: Boolean,
    useHeightAnimation: {
        type: Boolean,
        default: true,
    },
    useSlideAnimation: {
        type: Boolean,
        default: true,
    },
    animationDuration: {
        type: Number,
        default: 200,
    },
    containerClass: {
        type: String,
        default: 'shadow-lg border rounded-lg bg-white',
    },
    extraContainerClass: String,
    panelClass: String,
})
const emit = defineEmits(['update:activePanel'])
const slots = useSlots()

const container = ref(null)
const scrollingContainer = ref(null)
const backContainer = ref(null)
const activePanel = computed({
    get() {
        return props.activePanel
    },
    set(value) {
        emit('update:activePanel', value)
    },
})
const activeTopText = ref(null)
const allPanels = {}
const history = []

const initialContainerHeight = ref(0)
watchEffect(() => {
    if (props.useFixedHeight && container.value)
        initialContainerHeight.value = container.value.getBoundingClientRect().height
})

function gotTo(panel, text) {
    if (!allPanels[panel]) return

    history.push({
        panel: activePanel.value,
        backText: activeTopText.value,
    })
    activeTopText.value = text

    showPanel()

    if (props.useSlideAnimation) {
        animatePanel(panel, false, () => {
            activePanel.value = panel
        })
    } else {
        allPanels[panel].classList.remove('hidden')
        allPanels[activePanel.value].classList.add('hidden')
        activePanel.value = panel
    }

    setHeight(panel, text)
}

function back() {
    const item = history.pop()

    if (!item?.panel || !allPanels[item.panel]) return

    showPanel()

    if (props.useSlideAnimation) {
        animatePanel(item.panel, true, () => {
            activePanel.value = item.panel
            activeTopText.value = item.backText
        })
    } else {
        allPanels[item.panel].classList.remove('hidden')
        allPanels[activePanel.value].classList.add('hidden')
        activePanel.value = item.panel
        activeTopText.value = item.backText
    }

    setHeight(item.panel, item.backText)
}

function showPanel() {
    if (!allPanels[activePanel.value]) return

    allPanels[activePanel.value].classList.remove('hidden')
    setHeight()
}

function setHeight(panel, text) {
    if (!panel) {
        panel = activePanel.value
        text = activeTopText.value
    }

    if (!allPanels[panel]) return

    nextTick(() => {
        // check if container need height animation
        if (props.useHeightAnimation)
            container.value.style.transitionDuration = props.animationDuration + 'ms'
        else
            container.value.style.transitionDuration = null

        // initial height of panel container
        if (props.useFixedHeight) {
            container.value.style.height = initialContainerHeight.value + 2 + 'px'
        } else {
            container.value.style.height = allPanels[panel].offsetHeight +
                (
                    text
                        ? backContainer.value.offsetHeight
                        : 0
                )
                + 2
                + 'px'
        }

        useResizeObserver(container, () => {
            scrollingContainer.value.style.height =
                (
                    container.value.offsetHeight -
                    (
                        text
                            ? backContainer.value.offsetHeight
                            : 0
                    )
                )
                + 'px'
        })
    })
}

function animatePanel(panel, reverse, animationEndCallback) {
    const allAnimationClasses = ['slide-left-reverse', 'slide-right', 'slide-left', 'slide-right-reverse']

    allPanels[activePanel.value].classList.remove('hidden', ...allAnimationClasses)
    allPanels[panel].classList.remove('hidden', ...allAnimationClasses)

    allPanels[activePanel.value].style.animationDuration = props.animationDuration + 'ms'
    allPanels[panel].style.animationDuration = props.animationDuration + 'ms'

    function endCallback() {
        allPanels[activePanel.value].classList.add('hidden')

        if (isFunction(animationEndCallback))
            animationEndCallback.call()

        allPanels[panel].removeEventListener("animationend", endCallback, false)
        allPanels[activePanel.value].removeEventListener("animationend", endCallback, false)

        allPanels[activePanel.value].classList.remove(...allAnimationClasses)
        allPanels[panel].classList.remove(...allAnimationClasses)
    }

    if (reverse === true) {
        allPanels[panel].classList.add('slide-left-reverse')
        allPanels[activePanel.value].classList.add('slide-right')

        allPanels[panel].addEventListener("animationend", endCallback, false)
    } else {
        allPanels[activePanel.value].classList.add('slide-left')
        allPanels[panel].classList.add('slide-right-reverse')

        allPanels[activePanel.value].addEventListener("animationend", endCallback, false)
    }

}

onMounted(() => {
    showPanel()
})

defineExpose({
    gotTo,
    back,
})
</script>

<style scoped>
/**
 * ----------------------------------------
 * animation slide-left
 * ----------------------------------------
 */

.slide-left-reverse {
    -webkit-animation: slide-left-reverse ease-out reverse both;
    animation: slide-left-reverse ease-out reverse both;
}

@-webkit-keyframes slide-left-reverse {
    0% {
        -webkit-transform: translateX(0);
        transform: translateX(0);
    }
    100% {
        -webkit-transform: translateX(-100%);
        transform: translateX(-100%);
    }
}

@keyframes slide-left-reverse {
    0% {
        -webkit-transform: translateX(0);
        transform: translateX(0);
    }
    100% {
        -webkit-transform: translateX(-100%);
        transform: translateX(-100%);
    }
}

/**
 * ----------------------------------------
 * animation slide-right reverse
 * ----------------------------------------
 */

.slide-right-reverse {
    -webkit-animation: slide-right-reverse ease-in reverse both;
    animation: slide-right-reverse ease-in reverse both;
}

@-webkit-keyframes slide-right-reverse {
    0% {
        -webkit-transform: translateX(0);
        transform: translateX(0);
    }
    100% {
        -webkit-transform: translateX(100%);
        transform: translateX(100%);
    }
}

@keyframes slide-right-reverse {
    0% {
        -webkit-transform: translateX(0);
        transform: translateX(0);
    }
    100% {
        -webkit-transform: translateX(100%);
        transform: translateX(100%);
    }
}

/**
 * ----------------------------------------
 * animation slide-right
 * ----------------------------------------
 */

.slide-right {
    -webkit-animation: slide-right ease-out both;
    animation: slide-right ease-out both;
}

@-webkit-keyframes slide-right {
    0% {
        -webkit-transform: translateX(0);
        transform: translateX(0);
    }
    100% {
        -webkit-transform: translateX(100%);
        transform: translateX(100%);
    }
}

@keyframes slide-right {
    0% {
        -webkit-transform: translateX(0);
        transform: translateX(0);
    }
    100% {
        -webkit-transform: translateX(100%);
        transform: translateX(100%);
    }
}

/**
 * ----------------------------------------
 * animation slide-left
 * ----------------------------------------
 */

.slide-left {
    -webkit-animation: slide-left ease-in both;
    animation: slide-left ease-in both;
}

@-webkit-keyframes slide-left {
    0% {
        -webkit-transform: translateX(0);
        transform: translateX(0);
    }
    100% {
        -webkit-transform: translateX(-100%);
        transform: translateX(-100%);
    }
}

@keyframes slide-left {
    0% {
        -webkit-transform: translateX(0);
        transform: translateX(0);
    }
    100% {
        -webkit-transform: translateX(-100%);
        transform: translateX(-100%);
    }
}

/**
 * ----------------------------------------
 * menu header animation
 * ----------------------------------------
 */

.slide-fade-down-y-enter-active {
    transition: all 0.2s ease-out;
}

.slide-fade-down-y-leave-active {
    width: 100%;
    position: absolute;
    transition: all 0.2s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-down-y-enter-from,
.slide-fade-down-y-leave-to {
    transform: translateY(-12px);
    opacity: 0;
}
</style>
