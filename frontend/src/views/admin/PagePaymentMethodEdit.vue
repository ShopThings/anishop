<template>
    <partial-card>
        <template #header>
            ویرایش روش پرداخت -
            <span
                v-if="paymentMethod?.id"
                class="text-teal-600"
            >{{ paymentMethod?.title }}</span>
        </template>
        <template #body>
            <div class="p-3">
                <base-loading-panel
                    :loading="loading"
                    type="form"
                >
                    <template #content>
                        <form @submit.prevent="onSubmit">
                            <div class="flex flex-wrap items-end justify-between">
                                <div class="p-2">
                                    <partial-input-label
                                        title="انتخاب تصویر"
                                    />
                                    <base-media-placeholder
                                        type="image"
                                        :selected="paymentMethod?.image"
                                    />
                                </div>

                                <div class="p-2">
                                    <base-switch
                                        label="عدم نمایش روش پرداخت"
                                        on-label="نمایش روش پرداخت"
                                        name="is_published"
                                        :enabled="paymentMethod?.is_published"
                                        sr-text="نمایش/عدم نمایش روش پرداخت"
                                        @change="(status) => {publishStatus=status}"
                                    />
                                </div>
                            </div>

                            <div class="flex flex-wrap">
                                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                    <base-input
                                        label-title="عنوان روش پرداخت"
                                        placeholder="عنوان را وارد نمایید"
                                        name="title"
                                        :value="paymentMethod?.title"
                                    >
                                        <template #icon>
                                            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                    <partial-input-label title="نوع روش پرداخت"/>
                                    <base-select
                                        :options="paymentTypes"
                                        options-key="value"
                                        options-text="name"
                                        name="type"
                                        :selected="selectedPaymentType"
                                        @change="paymentTypeChange"
                                    >
                                        <template #item="{item, selected}">
                                            <div class="flex items-center">
                                                <base-lazy-image
                                                    :lazy-src="item.image"
                                                    :alt="item.name"
                                                    class="!w-14 h-auto ml-3"
                                                />
                                                <span
                                                    :class="{'text-primary': selected}"
                                                >{{ item.name }}</span>
                                            </div>
                                        </template>
                                    </base-select>
                                    <partial-input-error-message :error-message="errors.type"/>
                                </div>
                            </div>

                            <template
                                v-if="selectedPaymentType"
                            >
                                <hr class="my-3">

                                <VTransitionSlideFadeDownY mode="out-in">
                                    <div
                                        v-if="selectedPaymentType.value === 'behpardakht'"
                                        class="flex flex-wrap"
                                    >
                                        <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                            <base-input
                                                label-title="شماره ترمینال"
                                                placeholder="وارد نمایید"
                                                name="terminal_id"
                                                :value="paymentMethod?.options?.terminal_id"
                                            >
                                                <template #icon>
                                                    <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                                </template>
                                            </base-input>
                                        </div>
                                        <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                            <base-input
                                                label-title="نام کاربری"
                                                placeholder="وارد نمایید"
                                                name="username"
                                                :value="paymentMethod?.options?.username"
                                            >
                                                <template #icon>
                                                    <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                                </template>
                                            </base-input>
                                        </div>
                                        <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                            <base-input label-title="کلمه عبور"
                                                        placeholder="وارد نمایید"
                                                        name="password"
                                                        type="password">
                                                <template #icon>
                                                    <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                                </template>
                                            </base-input>
                                        </div>
                                    </div>
                                    <div
                                        v-else-if="selectedPaymentType.value === 'idpay'"
                                        class="flex flex-wrap"
                                    >
                                        <div class="w-full p-2 sm:w-1/2">
                                            <base-input
                                                label-title="کلید API"
                                                placeholder="وارد نمایید"
                                                name="api_key"
                                                :value="paymentMethod?.options?.api_key"
                                            >
                                                <template #icon>
                                                    <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                                </template>
                                            </base-input>
                                        </div>
                                    </div>
                                    <div
                                        v-else-if="selectedPaymentType.value === 'irankish'"
                                        class="flex flex-wrap"
                                    >
                                        <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                            <base-input
                                                label-title="شماره ترمینال"
                                                placeholder="وارد نمایید"
                                                name="terminal_id"
                                                :value="paymentMethod?.options?.terminal_id"
                                            >
                                                <template #icon>
                                                    <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                                </template>
                                            </base-input>
                                        </div>
                                        <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                            <base-input label-title="کلمه عبور"
                                                        placeholder="وارد نمایید"
                                                        name="password"
                                                        type="password">
                                                <template #icon>
                                                    <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                                </template>
                                            </base-input>
                                        </div>
                                        <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                            <base-input
                                                label-title="شناسه پذیرنده"
                                                placeholder="وارد نمایید"
                                                name="acceptor_id"
                                                :value="paymentMethod?.options?.acceptor_id"
                                            >
                                                <template #icon>
                                                    <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                                </template>
                                            </base-input>
                                        </div>
                                        <div class="w-full p-2">
                                            <base-textarea
                                                name="public_key"
                                                placeholder="وارد نمایید"
                                                label-title="کلید عمومی"
                                                :value="paymentMethod?.options?.public_key"
                                            >
                                                <template #icon>
                                                    <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
                                                </template>
                                            </base-textarea>
                                        </div>
                                    </div>
                                    <div
                                        v-else-if="selectedPaymentType.value === 'parsian'"
                                        class="flex flex-wrap"
                                    >
                                        <div class="w-full p-2 sm:w-1/2">
                                            <base-input label-title="رمز پذیرنده"
                                                        placeholder="وارد نمایید"
                                                        name="password"
                                                        type="password">
                                                <template #icon>
                                                    <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                                </template>
                                            </base-input>
                                        </div>
                                    </div>
                                    <div
                                        v-else-if="selectedPaymentType.value === 'sadad'"
                                        class="flex flex-wrap"
                                    >
                                        <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                            <base-input label-title="کلید"
                                                        placeholder="وارد نمایید"
                                                        name="password"
                                                        type="password">
                                                <template #icon>
                                                    <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                                </template>
                                            </base-input>
                                        </div>
                                        <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                            <base-input
                                                label-title="شماره ترمینال"
                                                placeholder="وارد نمایید"
                                                name="terminal_id"
                                                :value="paymentMethod?.options?.terminal_id"
                                            >
                                                <template #icon>
                                                    <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                                </template>
                                            </base-input>
                                        </div>
                                        <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                            <base-input
                                                label-title="شماره مرچنت"
                                                placeholder="وارد نمایید"
                                                name="merchant_id"
                                                :value="paymentMethod?.options?.merchant_id"
                                            >
                                                <template #icon>
                                                    <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                                </template>
                                            </base-input>
                                        </div>
                                    </div>
                                    <div
                                        v-else-if="selectedPaymentType.value === 'sepehr'"
                                        class="flex flex-wrap"
                                    >
                                        <div class="w-full p-2 sm:w-1/2">
                                            <base-input
                                                label-title="شماره ترمینال"
                                                placeholder="وارد نمایید"
                                                name="terminal_id"
                                                :value="paymentMethod?.options?.terminal_id"
                                            >
                                                <template #icon>
                                                    <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                                </template>
                                            </base-input>
                                        </div>
                                    </div>
                                    <div
                                        v-else-if="selectedPaymentType.value === 'zarinpal'"
                                        class="flex flex-wrap"
                                    >
                                        <div class="w-full p-2 sm:w-1/2">
                                            <base-input
                                                label-title="شماره مرچنت"
                                                placeholder="وارد نمایید"
                                                name="merchant_id"
                                                :value="paymentMethod?.options?.merchant_id"
                                            >
                                                <template #icon>
                                                    <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                                </template>
                                            </base-input>
                                        </div>
                                    </div>
                                </VTransitionSlideFadeDownY>
                            </template>

                            <div class="px-2 py-3">
                                <base-animated-button
                                    type="submit"
                                    class="bg-emerald-500 text-white mr-auto px-6 w-full sm:w-auto"
                                    :disabled="isSubmitting"
                                >
                                    <VTransitionFade>
                                        <loader-circle
                                            v-if="isSubmitting"
                                            main-container-klass="absolute w-full h-full"
                                            big-circle-color="border-transparent"
                                        />
                                    </VTransitionFade>

                                    <template #icon="{klass}">
                                        <CheckIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
                                    </template>

                                    <span class="ml-auto">ویرایش روش پرداخت</span>
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
import VTransitionFade from "../../transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckIcon, InformationCircleIcon} from "@heroicons/vue/24/outline/index.js";
import PartialInputLabel from "../../components/partials/PartialInputLabel.vue";
import BaseLazyImage from "../../components/base/BaseLazyImage.vue";
import PartialCard from "../../components/partials/PartialCard.vue";
import BaseSelect from "../../components/base/BaseSelect.vue";
import PartialInputErrorMessage from "../../components/partials/PartialInputErrorMessage.vue";
import LoaderCircle from "../../components/base/loader/LoaderCircle.vue";
import BaseMediaPlaceholder from "../../components/base/BaseMediaPlaceholder.vue";
import BaseSwitch from "../../components/base/BaseSwitch.vue";
import BaseInput from "../../components/base/BaseInput.vue";
import BaseAnimatedButton from "../../components/base/BaseAnimatedButton.vue";
import BaseLoadingPanel from "../../components/base/BaseLoadingPanel.vue";
import VTransitionSlideFadeDownY from "../../transitions/VTransitionSlideFadeDownY.vue";
import BaseTextarea from "../../components/base/BaseTextarea.vue";
import {useForm} from "vee-validate";
import yup from "../../validation/index.js";
import {apiReplaceParams, apiRoutes} from "../../router/api-routes.js";
import {useRequest} from "../../composables/api-request.js";
import {useRoute, useRouter} from "vue-router";
import {useToast} from "vue-toastification";

const router = useRouter()
const route = useRoute()
const toast = useToast()
const idParam = computed(() => {
    const id = parseInt(route.params.id, 10)
    if (isNaN(id)) return route.params.id
    return id
})

const paymentMethod = ref(null)

const loading = ref(false)
const canSubmit = ref(true)

const publishStatus = ref(true)
const paymentTypes = [
    {
        value: 'behpardakht',
        name: 'درگاه بانک - به پرداخت',
        image: '/gateways/beh-pardakht.png',
    },
    {
        value: 'idpay',
        name: 'درگاه بانک - آیدی پی',
        image: '/gateways/idpay.png',
    },
    {
        value: 'irankish',
        name: 'درگاه بانک - ایران کیش',
        image: '/gateways/irankish.jpg',
    },
    {
        value: 'parsian',
        name: 'درگاه بانک - تجارت الکترونیک پارسیان',
        image: '/gateways/tap.jpg',
    },
    {
        value: 'sadad',
        name: 'درگاه بانک - سداد',
        image: '/gateways/sadad.jpg',
    },
    {
        value: 'sepehr',
        name: 'درگاه بانک - پرداخت الکترونیک سپهر',
        image: '/gateways/mabna.png',
    },
    {
        value: 'zarinpal',
        name: 'درگاه بانک - زرین پال',
        image: '/gateways/zarinpal.png',
    },
]
const selectedPaymentType = ref(null)

function paymentTypeChange(selected) {
    selectedPaymentType.value = selected
}

const {handleSubmit, errors, isSubmitting} = useForm({
    validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
    if (!canSubmit.value) return
})

onMounted(() => {
    // useRequest(apiReplaceParams(apiRoutes.admin.paymentMethods.show, {payment_method: idParam}), null, {
    //     success: (response) => {
    //         for (let i of paymentTypes) {
    //             if (response.bank_gateway_type === i.value)
    //                 selectedPaymentType.value = i
    //         }
    //
    //         loading.value = false
    //     },
    // })
})
</script>

<style scoped>

</style>
