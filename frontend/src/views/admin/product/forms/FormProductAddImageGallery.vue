<template>
    <form>
        <partial-card class="mb-3 p-3 relative">
            <template #body>
                <loader-dot-orbit
                    v-if="isSubmitting"
                    main-container-klass="absolute w-full h-full top-0 left-0 z-[2]"
                    container-bg-color="bg-blue-50 opacity-40"
                />

                <div class="flex items-end">
                    <div class="grow flex flex-wrap">
                        <TransitionGroup name="fade-group">
                            <div
                                v-for="(image, idx) in images"
                                :key="idx"
                            >
                                <div class="p-4 flex flex-col relative">
                                    <partial-builder-remove-btn
                                        v-if="images.length > 1"
                                        class="bottom-0 top-auto !-translate-x-0"
                                        @click="handleRemoveImage(idx)"
                                    />

                                    <partial-input-label
                                        title="انتخاب تصویر"
                                    />
                                    <base-media-placeholder
                                        type="image"
                                        :selected="image"
                                    />
                                </div>
                            </div>
                        </TransitionGroup>
                    </div>

                    <div class="shrink-0">
                        <base-button
                            v-tooltip.top-end="'افزودن تصویر جدید'"
                            class="!rounded-full border-2 border-dashed p-4 w-16 h-16 flex items-center justify-center border-orange-400"
                            @click="handleNewImageClick"
                        >
                            <PlusIcon class="w-6 h-6 text-gray-500"/>
                        </base-button>
                    </div>
                </div>
            </template>
        </partial-card>

        <partial-card>
            <template #body>
                <partial-stepy-next-prev-buttons
                    :current-step="options.currentStep"
                    :current-step-index="options.currentStepIndex"
                    :last-step="options.lastStep"
                    :allow-next-step="!isSubmitting"
                    :allow-prev-step="false"
                    :show-prev-step-button="false"
                    :loading="isSubmitting"
                    @next="handleNextClick(options.next)"
                />
            </template>
        </partial-card>
    </form>
</template>

<script setup>
import {ref} from "vue";
import {PlusIcon} from "@heroicons/vue/24/outline/index.js"
import PartialCard from "../../../../components/partials/PartialCard.vue";
import PartialStepyNextPrevButtons from "../../../../components/partials/PartialStepyNextPrevButtons.vue";
import {useForm} from "vee-validate";
import yup from "../../../../validation/index.js";
import BaseMediaPlaceholder from "../../../../components/base/BaseMediaPlaceholder.vue";
import PartialInputLabel from "../../../../components/partials/PartialInputLabel.vue";
import BaseButton from "../../../../components/base/BaseButton.vue";
import PartialBuilderRemoveBtn from "../../../../components/partials/PartialBuilderRemoveBtn.vue";
import LoaderDotOrbit from "../../../../components/base/loader/LoaderDotOrbit.vue";

defineProps({
    options: {
        type: Object,
        required: true,
    },
})
const emit = defineEmits(['add', 'remove'])

const canSubmit = ref(true)

//------------------------
// Product new instance
//------------------------
const images = ref([
    {
        image: null,
    }
])

function handleNewImageClick() {
    images.value.push({
        image: null,
    })

    emit('add', images.value)
}

function handleRemoveImage(idx) {
    images.value.splice(idx, 1)

    emit('remove', images.value)
}

//------------------------

let nextFn = null

function handleNextClick(next) {
    onSubmit()
    nextFn = next
}

const {handleSubmit, errors, isSubmitting} = useForm({
    validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
    if (!canSubmit.value) return

    return new Promise((resolve) => {
        setTimeout(() => {
            resolve()
            if (nextFn)
                nextFn()
        }, 2000)
    })
})
</script>

<style scoped>
.fade-group-enter-active,
.fade-group-leave-active {
    transition: opacity 0.3s ease;
    transition-delay: .05s;
}

.fade-group-enter-from,
.fade-group-leave-to {
    opacity: 0;
}
</style>
