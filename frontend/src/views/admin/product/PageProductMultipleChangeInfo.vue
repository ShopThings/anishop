<template>
    <base-loading-panel
        :loading="loading"
        type="content"
    >
        <template #content>
            <base-accordion
                btn-class="bg-white border-2 border-blue-400 hover:shadow-lg focus-visible:ring-blue-800"
                panel-class="max-h-96 overflow-auto"
            >
                <template #button>
                    محصولات انتخاب شده
                </template>

                <template #panel>
                    <div class="grid grid-cols-1 gap-3">
                        <partial-card>
                            <template #body>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <router-link
                                            :to="{name: 'admin.product.detail', params: {id: 1}}"
                                            target="_blank"
                                            class="p-2 shrink-0"
                                        >
                                            <base-lazy-image
                                                alt="تصویر محصول"
                                                lazy-src="/src/assets/products/p1.jpg"
                                                class="!w-20 ml-3 mb-0 h-auto hover:scale-95 transition shrink-0"
                                            />
                                        </router-link>
                                        <router-link
                                            :to="{name: 'admin.product.detail', params: {id: 1}}"
                                            target="_blank"
                                            class="px-3 py-2 text-primary hover:text-opacity-90"
                                        >
                                            لپتاپ خیلی باحال و کاربردی عمو فردوس
                                        </router-link>
                                    </div>
                                    <base-button-close
                                        v-tooltip.right="'حذف از لیست'"
                                        class="mx-3"
                                        @click="removeFromListHandler(0)"
                                    />
                                </div>
                            </template>
                        </partial-card>
                        <partial-card>
                            <template #body>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <router-link
                                            :to="{name: 'admin.product.detail', params: {id: 1}}"
                                            target="_blank"
                                            class="p-2 shrink-0"
                                        >
                                            <base-lazy-image
                                                alt="تصویر محصول"
                                                lazy-src="/src/assets/products/p2.jpg"
                                                class="!w-20 ml-3 mb-0 h-auto hover:scale-95 transition shrink-0"
                                            />
                                        </router-link>
                                        <router-link
                                            :to="{name: 'admin.product.detail', params: {id: 1}}"
                                            target="_blank"
                                            class="px-3 py-2 text-primary hover:text-opacity-90"
                                        >
                                            لپتاپ خیلی باحال و کاربردی عمو فردوس
                                        </router-link>
                                    </div>
                                    <base-button-close
                                        v-tooltip.right="'حذف از لیست'"
                                        class="mx-3"
                                        @click="removeFromListHandler(1)"
                                    />
                                </div>
                            </template>
                        </partial-card>
                        <partial-card>
                            <template #body>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <router-link
                                            :to="{name: 'admin.product.detail', params: {id: 1}}"
                                            target="_blank"
                                            class="p-2 shrink-0"
                                        >
                                            <base-lazy-image
                                                alt="تصویر محصول"
                                                lazy-src="/src/assets/products/p3.jpg"
                                                class="!w-20 ml-3 mb-0 h-auto hover:scale-95 transition shrink-0"
                                            />
                                        </router-link>
                                        <router-link
                                            :to="{name: 'admin.product.detail', params: {id: 1}}"
                                            target="_blank"
                                            class="px-3 py-2 text-primary hover:text-opacity-90"
                                        >
                                            لپتاپ خیلی باحال و کاربردی عمو فردوس که قابلیت بهره‌گیری در بازی‌ها با
                                            گرافیک
                                            بسیار زیاد را دارا می‌باشد.
                                        </router-link>
                                    </div>
                                    <base-button-close
                                        v-tooltip.right="'حذف از لیست'"
                                        class="mx-3"
                                        @click="removeFromListHandler(2)"
                                    />
                                </div>
                            </template>
                        </partial-card>
                        <partial-card>
                            <template #body>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <router-link
                                            :to="{name: 'admin.product.detail', params: {id: 1}}"
                                            target="_blank"
                                            class="p-2 shrink-0"
                                        >
                                            <base-lazy-image
                                                alt="تصویر محصول"
                                                lazy-src="/src/assets/products/p4.jpg"
                                                class="!w-20 ml-3 mb-0 h-auto hover:scale-95 transition shrink-0"
                                            />
                                        </router-link>
                                        <router-link
                                            :to="{name: 'admin.product.detail', params: {id: 1}}"
                                            target="_blank"
                                            class="px-3 py-2 text-primary hover:text-opacity-90"
                                        >
                                            لپتاپ خیلی باحال و کاربردی عمو فردوس
                                        </router-link>
                                    </div>
                                    <base-button-close
                                        v-tooltip.right="'حذف از لیست'"
                                        class="mx-3"
                                        @click="removeFromListHandler(3)"
                                    />
                                </div>
                            </template>
                        </partial-card>
                        <partial-card>
                            <template #body>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <router-link
                                            :to="{name: 'admin.product.detail', params: {id: 1}}"
                                            target="_blank"
                                            class="p-2 shrink-0"
                                        >
                                            <base-lazy-image
                                                alt="تصویر محصول"
                                                lazy-src="/src/assets/products/p5.jpg"
                                                class="!w-20 ml-3 mb-0 h-auto hover:scale-95 transition shrink-0"
                                            />
                                        </router-link>
                                        <router-link
                                            :to="{name: 'admin.product.detail', params: {id: 1}}"
                                            target="_blank"
                                            class="px-3 py-2 text-primary hover:text-opacity-90"
                                        >
                                            لپتاپ خیلی باحال و کاربردی عمو فردوس
                                        </router-link>
                                    </div>
                                    <base-button-close
                                        v-tooltip.right="'حذف از لیست'"
                                        class="mx-3"
                                        @click="removeFromListHandler(4)"
                                    />
                                </div>
                            </template>
                        </partial-card>
                    </div>
                </template>
            </base-accordion>
        </template>
    </base-loading-panel>

    <form @submit.prevent="onSubmit">
        <partial-card class="mt-3">
            <template #header>
                مشخصات اولیه
            </template>
            <template #body>
                <div class="p-3">
                    <div class="flex flex-wrap">
                        <div class="w-full p-2 sm:w-1/2 xl:w-3/12">
                            <partial-input-label title="واحد محصول"/>
                            <div class="flex">
                                <div class="grow">
                                    <base-select-searchable
                                        :options="units"
                                        options-key="value"
                                        options-text="name"
                                        name="unit"
                                        :is-loading="loadingGetUnits"
                                        @change="(selected) => {selectedUnit = selected}"
                                    />
                                </div>
                                <base-button-close
                                    v-tooltip.right="'حذف انتخاب'"
                                    class="shrink-0 mr-2 bg-orange-50 rounded px-2 border border-orange-200"
                                    @click="selectedUnit = null"
                                />
                            </div>
                            <partial-input-error-message :error-message="errors.unit"/>
                        </div>
                        <div class="w-full p-2 sm:w-1/2 xl:w-4/12">
                            <partial-input-label title="برند"/>
                            <div class="flex">
                                <div class="grow">
                                    <base-select-searchable
                                        :options="brands"
                                        options-key="value"
                                        options-text="name"
                                        name="brand"
                                        :is-loading="loadingGetBrands"
                                        @change="(selected) => {selectedBrand = selected}"
                                    />
                                </div>
                                <base-button-close
                                    v-tooltip.right="'حذف انتخاب'"
                                    class="shrink-0 mr-2 bg-orange-50 rounded px-2 border border-orange-200"
                                    @click="selectedBrand = null"
                                />
                            </div>
                            <partial-input-error-message :error-message="errors.brand"/>
                        </div>
                        <div class="w-full p-2 sm:w-1/2 xl:w-5/12">
                            <partial-input-label title="دسته‌بندی"/>
                            <div class="flex">
                                <div class="grow">
                                    <base-select-searchable
                                        :options="categories"
                                        options-key="value"
                                        options-text="name"
                                        name="category"
                                        :is-loading="loadingGetCategories"
                                        @change="(selected) => {selectedCategory = selected}"
                                    />
                                </div>
                                <base-button-close
                                    v-tooltip.right="'حذف انتخاب'"
                                    class="shrink-0 mr-2 bg-orange-50 rounded px-2 border border-orange-200"
                                    @click="selectedCategory = null"
                                />
                            </div>
                            <partial-input-error-message :error-message="errors.category"/>
                        </div>
                    </div>
                </div>
            </template>
        </partial-card>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mt-3">
            <partial-card>
                <template #body>
                    <div class="bg-gray-50 py-2 px-4 rounded-t-lg border-b text-sm flex items-center justify-between">
                        <label for="dismissAvailability" class="ml-2 cursor-pointer grow">
                            عدم در نظر گرفتن موجود بودن
                        </label>
                        <input
                            id="dismissAvailability"
                            type="checkbox"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                            v-model="dismissAvailableStatus"
                        >
                    </div>

                    <div class="p-2 w-full sm:w-auto sm:grow">
                        <base-switch
                            label="موجود"
                            name="is_available"
                            :enabled="true"
                            sr-text="موجود/ناموجود بودن محصول"
                            @change="(status) => {availableStatus = status}"
                        />
                    </div>
                </template>
            </partial-card>

            <partial-card>
                <template #body>
                    <div class="bg-gray-50 py-2 px-4 rounded-t-lg border-b text-sm flex items-center justify-between">
                        <label for="dismissPublish" class="ml-2 cursor-pointer grow">
                            عدم در نظر گرفتن وضعیت نمایش
                        </label>
                        <input
                            id="dismissPublish"
                            type="checkbox"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                            v-model="dismissPublishStatus"
                        >
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
                </template>
            </partial-card>

            <partial-card>
                <template #body>
                    <div class="bg-gray-50 py-2 px-4 rounded-t-lg border-b text-sm flex items-center justify-between">
                        <label for="dismissCommenting" class="ml-2 cursor-pointer grow">
                            عدم در نظر گرفتن اجازه ارسال نظر
                        </label>
                        <input
                            id="dismissCommenting"
                            type="checkbox"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                            v-model="dismissAllowCommentingStatus"
                        >
                    </div>

                    <div class="p-2 w-full sm:w-auto sm:grow">
                        <base-switch
                            label="اجازه ارسال نظر"
                            name="is_commenting_allowed"
                            :enabled="true"
                            sr-text="اجازه/عدم اجازه ارسال نظر"
                            @change="(status) => {allowCommentingStatus = status}"
                        />
                    </div>
                </template>
            </partial-card>
        </div>

        <partial-card class="mt-3">
            <template #body>
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

                        <span class="ml-auto">ثبت مشخصات</span>
                    </base-animated-button>
                </div>
            </template>
        </partial-card>
    </form>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import yup from "../../../validation/index.js";
import {useForm} from "vee-validate";
import {useRoute} from "vue-router";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import PartialCard from "../../../components/partials/PartialCard.vue";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import BaseInput from "../../../components/base/BaseInput.vue";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import BaseAccordion from "../../../components/base/BaseAccordion.vue";
import BaseButtonClose from "../../../components/base/BaseButtonClose.vue";
import BaseLazyImage from "../../../components/base/BaseLazyImage.vue";
import {useRequest} from "../../../composables/api-request.js";
import {apiRoutes} from "../../../router/api-routes.js";
import BaseSelectSearchable from "../../../components/base/BaseSelectSearchable.vue";
import PartialInputErrorMessage from "../../../components/partials/PartialInputErrorMessage.vue";
import PartialInputLabel from "../../../components/partials/PartialInputLabel.vue";
import BaseSwitch from "../../../components/base/BaseSwitch.vue";

const route = useRoute()
const idsParam = computed(() => {
    return route.params.ids.split('\/')
})

const loading = ref(false)
const canSubmit = ref(true)

const products = ref([])

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

const dismissAvailableStatus = ref(false)
const dismissPublishStatus = ref(false)
const dismissAllowCommentingStatus = ref(false)

function removeFromListHandler(idx) {
    products.value.splice(idx, 1)
}

const {handleSubmit, errors, isSubmitting} = useForm({
    validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
    if (!canSubmit.value) return
})

onMounted(() => {
    // useRequest(apiRoutes.admin.products.index, {
    //     data: {
    //         ids: idsParam.value,
    //     }
    // }, {
    //     success: (response) => {
    //         products.value = response.data
    //
    //         loading.value = false
    //     },
    // })
    //
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
