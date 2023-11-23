<template>
    <partial-card>
        <template #header>
            ویرایش اسلایدر
        </template>
        <template #body>
            <div class="p-3">
                <base-loading-panel
                    :loading="loading"
                    type="form"
                >
                    <template #content>
                        <form @submit.prevent="onSubmit">
                            <div class="flex flex-wrap items-end">
                                <div class="p-2 w-full sm:w-1/2 lg:w-5/12">
                                    <base-input
                                        label-title="عنوان"
                                        placeholder="وارد نمایید"
                                        name="title"
                                        :value="slider?.title"
                                    >
                                        <template #icon>
                                            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                                <div class="p-2 w-full sm:w-1/2 lg:w-4/12">
                                    <partial-input-label title="محل قرارگیری"/>
                                    <base-select
                                        :options="sliderPlaces"
                                        options-key="value"
                                        options-text="text"
                                        @change="(selected) => {selectedSliderPlace = selected}"
                                    />
                                </div>
                                <div class="p-2 w-full sm:w-1/2 lg:w-3/12">
                                    <base-input
                                        type="number"
                                        :min="0"
                                        label-title="اولویت"
                                        placeholder="وارد نمایید"
                                        name="priority"
                                        :value="slider?.priority"
                                    >
                                        <template #icon>
                                            <HashtagIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                                <div class="p-2 w-full sm:w-auto">
                                    <base-switch
                                        label="نمایش اسلایدر"
                                        name="is_published"
                                        :enabled="true"
                                        sr-text="نمایش/عدم نمایش اسلایدر"
                                        @change="(status) => {publishStatus=status}"
                                    />
                                </div>
                            </div>
                            <VTransitionSlideFadeDownY mode="out-in">
                                <div
                                    v-if="selectedSliderPlace && selectedSliderPlace.value === SLIDER_PLACES.MAIN_SLIDER_IMAGES.value">
                                    <hr class="my-3">

                                    <div class="p-2 w-full sm:w-1/2">
                                        <base-input
                                            type="number"
                                            :min="1"
                                            :max="4"
                                            label-title="تعداد تصاویر کنار هم"
                                            placeholder="بین ۱ تا ۴ تصویر کنار هم"
                                            name="beside_images"
                                            :value="slider?.options?.beside_images"
                                        >
                                            <template #icon>
                                                <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                            </template>
                                        </base-input>
                                    </div>
                                </div>

                                <div
                                    v-else-if="selectedSliderPlace && selectedSliderPlace.value === SLIDER_PLACES.MAIN_SLIDERS.value"
                                >
                                    <hr class="my-3">

                                    <div class="flex flex-wrap items-end">
                                        <div class="p-2 w-full sm:w-1/3 lg:w-4/12">
                                            <partial-input-label title="برند"/>
                                            <base-select-searchable
                                                :options="brands"
                                                options-key="id"
                                                options-text="name"
                                                name="brand"
                                                :is-loading="searchBrandLoading"
                                                :is-local-search="false"
                                                placeholder="جستجوی برند..."
                                                @change="(selected) => {selectedBrand = selected}"
                                                @query="searchBrand"
                                            >
                                                <template #item="{item}">
                                                    <div class="flex items-center">
                                                        <span>{{ item.name }}</span>
                                                        <span
                                                            class="py-1 px-2 text-sm bg-blue-500 text-white mr-2"
                                                        >
                                                    {{ item.latin_name }}
                                                </span>
                                                    </div>
                                                </template>
                                            </base-select-searchable>
                                            <partial-input-error-message :error-message="errors.brand"/>
                                        </div>
                                        <div class="p-2 w-full sm:w-1/3 lg:w-5/12">
                                            <partial-input-label title="دسته‌بندی"/>
                                            <base-select-searchable
                                                :options="categories"
                                                options-key="id"
                                                options-text="name"
                                                name="category"
                                                :is-loading="searchCategoryLoading"
                                                :is-local-search="false"
                                                placeholder="جستجوی دسته‌بندی..."
                                                @change="(selected) => {selectedCategory = selected}"
                                                @query="searchCategory"
                                            >
                                                <template #item="{item}">
                                                    <div class="flex items-center">
                                                        <span>{{ item.name }}</span>
                                                        <span
                                                            class="py-1 px-2 text-sm bg-blue-500 text-white mr-2"
                                                        >
                                                    سطح
                                                    {{ item.level }}
                                                </span>
                                                    </div>
                                                </template>
                                            </base-select-searchable>
                                            <partial-input-error-message :error-message="errors.category"/>
                                        </div>
                                        <div class="p-2 w-full sm:w-1/3 lg:w-3/12">
                                            <partial-input-label title="نوع مرتب سازی"/>
                                            <base-select
                                                :options="orderBy"
                                                options-key="value"
                                                options-text="text"
                                                @change="(selected) => {selectedOrderBy = selected}"
                                            />
                                        </div>
                                    </div>

                                    <div class="p-2">
                                        <base-input
                                            label-title="لینک مشاهده همه"
                                            placeholder="وارد نمایید"
                                            name="link"
                                            :value="slider?.options?.show_all_link"
                                        >
                                            <template #icon>
                                                <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                            </template>
                                        </base-input>
                                    </div>

                                    <div class="flex flex-wrap items-center mt-4">
                                        <div class="p-2 w-full md:w-auto grow flex items-center">
                                            <partial-input-label class="shrink-0 ml-3" title="تعداد اسلایدها"/>
                                            <div class="grow">
                                                <base-range-slider
                                                    :min="0"
                                                    :max="30"
                                                    show-tooltip="always"
                                                    v-model="slideCount"
                                                />
                                            </div>
                                        </div>
                                        <div class="p-2 w-full md:w-auto shrink-0">
                                            <base-switch
                                                label="نمایش محصولات ویژه"
                                                name="is_special"
                                                :enabled="false"
                                                sr-text="نمایش/عدم نمایش محصولات ویژه"
                                                @change="(status) => {isSpecialStatus=status}"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </VTransitionSlideFadeDownY>

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

                                    <span class="ml-auto">ویرایش اسلایدر</span>
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
import {computed, onMounted, ref} from "vue";
import {useForm} from "vee-validate";
import yup from "../../../validation/index.js";
import PartialCard from "../../../components/partials/PartialCard.vue";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckIcon, HashtagIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import BaseSwitch from "../../../components/base/BaseSwitch.vue";
import BaseInput from "../../../components/base/BaseInput.vue";
import {SLIDER_PLACES} from "../../../composables/constants.js";
import PartialInputLabel from "../../../components/partials/PartialInputLabel.vue";
import BaseSelect from "../../../components/base/BaseSelect.vue";
import VTransitionSlideFadeDownY from "../../../transitions/VTransitionSlideFadeDownY.vue";
import PartialInputErrorMessage from "../../../components/partials/PartialInputErrorMessage.vue";
import BaseSelectSearchable from "../../../components/base/BaseSelectSearchable.vue";
import BaseRangeSlider from "../../../components/base/BaseRangeSlider.vue";
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import {useRequest} from "../../../composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "../../../router/api-routes.js";
import {useRoute} from "vue-router";
import {useToast} from "vue-toastification";

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

const sliderPlaces = [
    {
        value: SLIDER_PLACES.MAIN.value,
        text: SLIDER_PLACES.MAIN.text,
    },
    {
        value: SLIDER_PLACES.MAIN_SLIDERS.value,
        text: SLIDER_PLACES.MAIN_SLIDERS.text,
    },
    {
        value: SLIDER_PLACES.MAIN_SLIDER_IMAGES.value,
        text: SLIDER_PLACES.MAIN_SLIDER_IMAGES.text,
    },
]

const selectedSliderPlace = ref(null)
const publishStatus = ref(true)

const categories = ref([])
const searchCategoryLoading = ref(true)
const selectedCategory = ref(null)

function searchCategory(query) {
    // searchCategoryLoading.value = true
    // useRequest(apiRoutes.admin.categories.index, {
    //     data: {
    //         query,
    //     },
    // }, {
    //     success: (response) => {
    //         categories.value = response.data
    //     },
    //     finally: () => {
    //         searchCategoryLoading.value = false
    //     }
    // })
}

const brands = ref([])
const searchBrandLoading = ref(true)
const selectedBrand = ref(null)

function searchBrand(query) {
    // searchBrandLoading.value = true
    // useRequest(apiRoutes.admin.brands.index, {
    //     data: {
    //         query,
    //     },
    // }, {
    //     success: (response) => {
    //         brands.value = response.data
    //     },
    //     finally: () => {
    //         searchBrandLoading.value = false
    //     }
    // })
}

const orderBy = [
    {
        value: 'asc',
        text: 'صعودی',
    },
    {
        value: 'desc',
        text: 'نزولی',
    },
]
const selectedOrderBy = ref(null)

const slideCount = ref(10)
const isSpecialStatus = ref(false)

const {handleSubmit, errors, isSubmitting} = useForm({
    validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
    if (!canSubmit.value) return
})

onMounted(() => {
    // useRequest(apiReplaceParams(apiRoutes.admin.sliders.show, {slider: idParam.value}), null, {
    //     success: (response) => {
    //         slider.value = response.data
    //         publishStatus.value = response.data.is_published
    //         selectedSliderPlace.value = {
    //             value: response.data.slider_place.place_in,
    //             text: response.data.slider_place.title,
    //         }
    //
    //         if (resopnse.data.slider_place.place_in === SLIDER_PLACES.MAIN_SLIDERS.value) {
    //             useRequest(apiReplaceParams(apiRoutes.admin.brands.show, {brand: response.data.options.brand_id}), null, {
    //                 success: (response) => {
    //                     selectedBrand.value = response.data
    //                 },
    //             })
    //             useRequest(apiReplaceParams(apiRoutes.admin.categories.show, {category: response.data.options.category_id}), null, {
    //                 success: (response) => {
    //                     selectedCategory.value = response.data
    //                 },
    //             })
    //
    //             selectedOrderBy.value = response.data.options.order_by
    //             slideCount.value = response.data.options.count
    //             isSpecialStatus.value = response.data.options.is_special
    //         }
    //
    //         loading.value = false
    //     },
    // })
})
</script>

<style scoped>

</style>
