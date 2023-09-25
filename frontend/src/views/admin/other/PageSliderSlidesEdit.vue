<template>
    <partial-card>
        <template #header>
            ویرایش اسلایدهای اسلایدر
            <span
                v-if="slider?.id"
                class="text-teal-600"
            >{{ slider?.title }}</span>
        </template>
        <template #body>
            <div class="p-3">
                <base-loading-panel
                    :loading="loading"
                    type="form"
                >
                    <template #content>
                        <form @submit.prevent="onSubmit">
                            <div class="p-2">
                                <draggable
                                    v-if="slides && slides.length"
                                    item-key="id"
                                    tag="ul"
                                    :animation="200"
                                    :list="slides"
                                    :group="{ name: 'slides' }"
                                    handle=".handle"
                                    ghost-class="ghost"
                                >
                                    <template #item="{ element, index }">
                                        <li class="pt-6 px-2 border-2 border-dashed border-slate-300 rounded-lg mb-3">
                                            <div class="mt-2 relative mb-4">
                                                <base-button
                                                    class="!absolute top-0 left-0 -translate-y-8 -translate-x-1/4 bg-rose-500 !p-1 z-[1]"
                                                    @click="removeSlideHandler(index)"
                                                >
                                                    <TrashIcon class="h-5 w-5"/>
                                                </base-button>

                                                <base-button
                                                    v-tooltip.left="'برای جابجایی بکشید'"
                                                    :class="[
                                                    'handle cursor-grab active:cursor-grabbing !px-8 sm:!px-10 !rounded-t-none !rounded-br-none',
                                                    '!absolute top-0 right-0 -translate-y-8 translate-x-2 bg-gray-100 !py-1 z-[1]',
                                                    'border-b-2 border-l-2 !border-t-none !border-r-none',
                                                ]"
                                                >
                                                    <Bars2Icon class="h-6 w-6 text-gray-500"/>
                                                </base-button>

                                                <div class="p-2 flex flex-col items-center">
                                                    <partial-input-label
                                                        title="انتخاب تصویر"
                                                    />
                                                    <base-media-placeholder
                                                        type="image"
                                                        :selected="element?.image"
                                                    />
                                                </div>

                                                <div class="flex flex-wrap">
                                                    <div class="p-2 w-full lg:w-1/2">
                                                        <base-input
                                                            label-title="عنوان"
                                                            placeholder="وارد نمایید"
                                                            :name="'title' + element.id"
                                                            :value="element?.title"
                                                            @input="(v) => {element.title = v}"
                                                        >
                                                            <template #icon>
                                                                <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                                            </template>
                                                        </base-input>
                                                    </div>
                                                    <div class="p-2 w-full lg:w-1/2">
                                                        <base-input
                                                            label-title="زیرعنوان"
                                                            placeholder="وارد نمایید"
                                                            :name="'sub_title' + element.id"
                                                            :value="element?.sub_title"
                                                            @input="(v) => {element.sub_title = v}"
                                                        >
                                                            <template #icon>
                                                                <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                                            </template>
                                                        </base-input>
                                                    </div>
                                                    <div class="p-2 w-full">
                                                        <base-input
                                                            label-title="لینک"
                                                            placeholder="وارد نمایید"
                                                            :name="'link' + element.id"
                                                            :value="element?.link"
                                                            @input="(v) => {element.link = v}"
                                                        >
                                                            <template #icon>
                                                                <CurrencyDollarIcon class="h-6 w-6 text-gray-400"/>
                                                            </template>
                                                        </base-input>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </template>
                                </draggable>

                                <div class="mt-3 mb-1">
                                    <base-button
                                        class="!text-orange-600 border-orange-400 w-full sm:w-auto flex items-center hover:bg-orange-50 mr-auto"
                                        @click="handleNewSlideClick"
                                    >
                                        <span class="mr-auto text-sm">ساخت اسلاید</span>
                                        <PlusIcon class="h-6 w-6 mr-auto sm:mr-2"/>
                                    </base-button>
                                </div>
                            </div>

                            <div class="px-2 py-3">
                                <base-animated-button
                                    type="submit"
                                    class="bg-emerald-500 text-white mr-auto px-6 w-full sm:w-auto"
                                    :disabled="isSubmitting"
                                >
                                    <VTransitionFade>
                                        <loader-circle
                                            v-if="isSubmitting"
                                            main-container-klass="absolute w-full h-full top-0 left-0"
                                            big-circle-color="border-transparent"
                                        />
                                    </VTransitionFade>

                                    <template #icon="{klass}">
                                        <CheckIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
                                    </template>

                                    <span class="ml-auto">ویرایش اسلایدها</span>
                                </base-animated-button>
                            </div>
                        </form>
                    </template>
                </base-loading-panel>
            </div>
        </template>
    </partial-card>
</template>

<script setup>
import {computed, onMounted, reactive, ref} from "vue";
import {useForm} from "vee-validate";
import yup from "../../../validation/index.js";
import draggable from "vuedraggable";
import PartialCard from "../../../components/partials/PartialCard.vue";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import {
    CheckIcon,
    CurrencyDollarIcon,
    ArrowLeftCircleIcon,
    PlusIcon,
    Bars2Icon, TrashIcon
} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import BaseInput from "../../../components/base/BaseInput.vue";
import {useRequest} from "../../../composables/api-request.js";
import {apiRoutes} from "../../../router/api-routes.js";
import PartialInputErrorMessage from "../../../components/partials/PartialInputErrorMessage.vue";
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import {useToast} from "vue-toastification";
import {useRoute} from "vue-router";
import uniqueId from "lodash.uniqueid";
import LoaderDotOrbit from "../../../components/base/loader/LoaderDotOrbit.vue";
import PartialInputLabel from "../../../components/partials/PartialInputLabel.vue";
import BaseMediaPlaceholder from "../../../components/base/BaseMediaPlaceholder.vue";
import BaseButton from "../../../components/base/BaseButton.vue";

const route = useRoute()
const toast = useToast()
const idParam = computed(() => {
    const id = parseInt(route.params.id, 10)
    if (isNaN(id)) return route.params.id
    return id
})

const loading = ref(false)
const canSubmit = ref(true)

const slider = ref(null)
const slides = reactive([
    {
        id: parseInt(uniqueId()),
        title: '',
        sub_title: '',
        link: '',
        image: null,
    },
])

const {handleSubmit, errors, isSubmitting} = useForm({
    validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
    if (!canSubmit.value) return
})

function removeSlideHandler(idx) {
    if (slides[idx])
        slides.splice(idx, 1)
}

function handleNewSlideClick() {
    slides.push({
        id: parseInt(uniqueId()),
        title: '',
        sub_title: '',
        link: '',
        image: null,
    })
}

onMounted(() => {
    // useRequest(apiReplaceParams(apiRoutes.admin.sliders.show, {slider: idParam.value}), null, {
    //     success: (response) => {
    //         slider.value = response.data
    //         slides.value = response.data.items
    //
    //         loading.value = false
    //     },
    // })
})
</script>

<style scoped>
.ghost {
    opacity: 0.5;
    background: rgb(226 232 240 / var(--tw-bg-opacity));
    border-radius: .5rem;
}
</style>
