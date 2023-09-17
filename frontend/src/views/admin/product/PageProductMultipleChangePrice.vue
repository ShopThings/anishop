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

    <partial-card class="mt-3">
        <template #header>
            تغییر قیمت به صورت درصدی
        </template>
        <template #body>
            <div class="p-3">
                <form @submit.prevent="onSubmit">
                    <div class="sm:flex sm:flex-wrap sm:items-end sm:justify-between">
                        <div class="w-full p-2 sm:w-1/2">
                            <base-input
                                type="number"
                                :min="1"
                                :max="100"
                                label-title="درصد تغییر قیمت"
                                placeholder="وارد نمایید"
                                name="percentage"
                            >
                                <template #icon>
                                    <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                </template>
                            </base-input>
                        </div>
                        <div class="sm:grow sm:flex">
                            <div class="p-2">
                                <div class="flex items-center mb-4">
                                    <input
                                        checked
                                        id="increaseDecreaseRadio1"
                                        type="radio"
                                        name="increase_decrease_radio"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2"
                                        value="increase"
                                        v-model="increaseDecreaseRadio"
                                    >
                                    <label
                                        for="increaseDecreaseRadio1"
                                        class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                        افزایش قیمت
                                    </label>
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="flex items-center mb-4">
                                    <input
                                        id="increaseDecreaseRadio2"
                                        type="radio"
                                        name="increase_decrease_radio"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2"
                                        value="decrease"
                                        v-model="increaseDecreaseRadio"
                                    >
                                    <label
                                        for="increaseDecreaseRadio2"
                                        class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                        کاهش قیمت
                                    </label>
                                </div>
                            </div>
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

                            <span class="ml-auto">اعمال تغییرات قیمت</span>
                        </base-animated-button>
                    </div>
                </form>
            </div>
        </template>
    </partial-card>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import {useForm} from "vee-validate";
import {useRoute} from "vue-router";
import yup from "../../../validation/index.js";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import PartialCard from "../../../components/partials/PartialCard.vue";
import BaseInput from "../../../components/base/BaseInput.vue";
import BaseLazyImage from "../../../components/base/BaseLazyImage.vue";
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import BaseAccordion from "../../../components/base/BaseAccordion.vue";
import BaseButtonClose from "../../../components/base/BaseButtonClose.vue";
import {useRequest} from "../../../composables/api-request.js";
import {apiRoutes} from "../../../router/api-routes.js";

const route = useRoute()
const idsParam = computed(() => {
    return route.params.ids.split('\/')
})

const loading = ref(false)
const canSubmit = ref(true)

const increaseDecreaseRadio = ref('increase')
const products = ref([])

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
})
</script>

<style scoped>

</style>
