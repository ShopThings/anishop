<template>
    <form>
        <partial-card class="mb-3 p-3 relative">
            <template #body>
                <loader-dot-orbit
                    v-if="isSubmitting"
                    main-container-klass="absolute w-full h-full top-0 left-0 z-[2]"
                    container-bg-color="bg-blue-50 opacity-40"
                />

                <div class="p-2 flex flex-col items-center">
                    <partial-input-label
                        title="انتخاب تصویر شاخص"
                    />
                    <base-media-placeholder type="image"/>
                </div>

                <div class="flex flex-wrap">
                    <div class="w-full p-2 sm:w-1/2 xl:w-5/12">
                        <base-input label-title="نام محصول"
                                    placeholder="وارد نمایید"
                                    name="title">
                            <template #icon>
                                <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                            </template>
                        </base-input>
                    </div>
                    <div class="w-full p-2 sm:w-1/2 xl:w-2/12">
                        <partial-input-label title="واحد محصول"/>
                        <base-select-searchable
                            :options="units"
                            options-key="value"
                            options-text="name"
                            name="unit"
                            :is-loading="loadingGetUnits"
                            @change="(selected) => {selectedUnit = selected}"
                        />
                        <partial-input-error-message :error-message="errors.unit"/>
                    </div>
                    <div class="w-full p-2 sm:w-1/2 xl:w-2/12">
                        <partial-input-label title="برند"/>
                        <base-select-searchable
                            :options="brands"
                            options-key="value"
                            options-text="name"
                            name="brand"
                            :is-loading="loadingGetBrands"
                            @change="(selected) => {selectedBrand = selected}"
                        />
                        <partial-input-error-message :error-message="errors.brand"/>
                    </div>
                    <div class="w-full p-2 sm:w-1/2 xl:w-3/12">
                        <partial-input-label title="دسته‌بندی"/>
                        <base-select-searchable
                            :options="categories"
                            options-key="value"
                            options-text="name"
                            name="category"
                            :is-loading="loadingGetCategories"
                            @change="(selected) => {selectedCategory = selected}"
                        />
                        <partial-input-error-message :error-message="errors.category"/>
                    </div>
                </div>

                <div class="flex flex-wrap">
                    <div class="p-2 w-full sm:w-auto sm:grow">
                        <base-switch
                            label="موجود"
                            name="is_available"
                            :enabled="true"
                            sr-text="موجود/ناموجود بودن محصول"
                            @change="(status) => {availableStatus = status}"
                        />
                    </div>
                    <div class="p-2 w-full sm:w-auto sm:grow">
                        <base-switch
                            label="نمایش کلی محصول"
                            name="is_published"
                            :enabled="true"
                            sr-text="نمایش/عدم نمایش تمامی محصولات"
                            @change="(status) => {publishStatus = status}"
                        />
                    </div>
                    <div class="p-2 w-full sm:w-auto sm:grow">
                        <base-switch
                            label="اجازه ارسال دیدگاه"
                            name="is_commenting_allowed"
                            :enabled="true"
                            sr-text="اجازه/عدم اجازه ارسال دیدگاه"
                            @change="(status) => {allowCommentingStatus = status}"
                        />
                    </div>
                </div>

                <div class="p-2">
                    <partial-input-label title="کلمات کلیدی"/>
                    <vue3-tags-input
                        :tags="tags"
                        placeholder="کلمات کلیدی خود را وارد نمایید"
                        @on-tags-changed="(t) => {tags = t}"
                    />
                </div>

                <div class="p-2">
                    <partial-input-label title="ویژگی‌های سریع" class="mb-2"/>
                    <partial-baby-property-builder
                        v-model:properties="babyProps"
                        property-title-text="عنوان ویژگی"
                        tags-text="ویژگی‌ها"
                        new-button-text="ویژگی جدید"
                    />
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
                    :allow-prev-step="!isSubmitting"
                    :loading="isSubmitting"
                    @next="handleNextClick(options.next)"
                />
            </template>
        </partial-card>
    </form>
</template>

<script setup>
import {onMounted, ref} from "vue";
import PartialCard from "../../../../components/partials/PartialCard.vue";
import PartialStepyNextPrevButtons from "../../../../components/partials/PartialStepyNextPrevButtons.vue";
import BaseMediaPlaceholder from "../../../../components/base/BaseMediaPlaceholder.vue";
import PartialInputLabel from "../../../../components/partials/PartialInputLabel.vue";
import {ArrowLeftCircleIcon} from "@heroicons/vue/24/outline/index.js";
import PartialInputErrorMessage from "../../../../components/partials/PartialInputErrorMessage.vue";
import BaseInput from "../../../../components/base/BaseInput.vue";
import {useRequest} from "../../../../composables/api-request.js";
import {apiRoutes} from "../../../../router/api-routes.js";
import {useForm} from "vee-validate";
import yup from "../../../../validation/index.js";
import BaseSelectSearchable from "../../../../components/base/BaseSelectSearchable.vue";
import Vue3TagsInput from "vue3-tags-input";
import PartialBabyPropertyBuilder from "../../../../components/partials/PartialBabyPropertyBuilder.vue";
import BaseSwitch from "../../../../components/base/BaseSwitch.vue";
import LoaderDotOrbit from "../../../../components/base/loader/LoaderDotOrbit.vue";

defineProps({
    options: {
        type: Object,
        required: true,
    },
})

const canSubmit = ref(true)

const loadingGetUnits = ref(true)
const units = ref([])
const selectedUnit = ref(null)

const loadingGetBrands = ref(true)
const brands = ref([])
const selectedBrand = ref(null)

const loadingGetCategories = ref(true)
const categories = ref([])
const selectedCategory = ref(null)

const availableStatus = ref(true)
const publishStatus = ref(true)
const allowCommentingStatus = ref(true)

const babyProps = ref([{
    title: '',
    tags: [],
}])
const tags = ref([])

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

onMounted(() => {
    // useRequest(apiRoutes.admin.units.index, null, {
    //     success: (response) => {
    //         units.value = response.data
    //         loadingGetUnits.value = false
    //     },
    // })
    //
    // useRequest(apiRoutes.admin.brands.index, null, {
    //     success: (response) => {
    //         brands.value = response.data
    //         loadingGetBrands.value = false
    //     },
    // })
    //
    // useRequest(apiRoutes.admin.categories.index, null, {
    //     success: (response) => {
    //         categories.value = response.data
    //         loadingGetCategories.value = false
    //     },
    // })
})
</script>

<style scoped>

</style>
