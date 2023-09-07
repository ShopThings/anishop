<template>
    <partial-card>
        <template #header>
            ویرایش کوپن -
            <span
                v-if="coupon?.id"
                class="text-teal-600"
            >{{ coupon?.title }}</span>
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
                                <base-switch
                                    label="قابل استفاده نمودن کوپن"
                                    name="is_published"
                                    :enabled="coupon?.is_published"
                                    sr-text="قابل استفاده نمودن کوپن"
                                    @change="(status) => {publishStatus=status}"
                                />
                            </div>

                            <div class="flex flex-wrap">
                                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                    <base-input
                                        label-title="کد"
                                        placeholder="وارد نمایید"
                                        name="code"
                                        :value="coupon?.code"
                                    >
                                        <template #icon>
                                            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                    <base-input
                                        label-title="عنوان"
                                        placeholder="وارد نمایید"
                                        name="title"
                                        :value="coupon?.title"
                                    >
                                        <template #icon>
                                            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                    <base-input
                                        type="number"
                                        label-title="قیمت تخفیف"
                                        placeholder="وارد نمایید"
                                        name="price"
                                        :value="coupon?.price"
                                    >
                                        <template #icon>
                                            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                            </div>

                            <hr class="my-3">

                            <div class="flex flex-wrap">
                                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                    <base-input
                                        type="number"
                                        label-title="حداقل قیمت اعمال"
                                        placeholder="وارد نمایید"
                                        name="apply_min_price"
                                        :is-optional="true"
                                        :value="coupon?.apply_min_price"
                                    >
                                        <template #icon>
                                            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                    <base-input
                                        type="number"
                                        label-title="حداکثر قیمت اعمال"
                                        placeholder="وارد نمایید"
                                        name="apply_max_price"
                                        :is-optional="true"
                                        :value="coupon?.apply_max_price"
                                    >
                                        <template #icon>
                                            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                    <base-input
                                        type="number"
                                        label-title="تعداد قابل استفاده"
                                        placeholder="وارد نمایید"
                                        name="use_count"
                                        :value="coupon?.use_count"
                                    >
                                        <template #icon>
                                            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                    <base-input
                                        type="number"
                                        label-title="قابل استفاده مجدد پس از(بر حسب روز)"
                                        placeholder="وارد نمایید"
                                        name="reusable_after"
                                        :is-optional="true"
                                        :value="coupon?.reusable_after"
                                    >
                                        <template #icon>
                                            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                    <partial-input-lead text="مقدار وارد شده برای استفاده هر کاربر می‌باشد"/>
                                </div>
                            </div>

                            <hr class="my-3">

                            <div class="flex flex-wrap">
                                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                    <partial-input-label
                                        title="تاریخ شروع"
                                        :is-optional="true"
                                    />
                                    <date-picker
                                        v-model="startDate"
                                        placeholder="انتخاب تاریخ شروع"
                                    />
                                </div>
                                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                                    <partial-input-label
                                        title="تاریخ پایان"
                                        :is-optional="true"
                                    />
                                    <date-picker
                                        v-model="endDate"
                                        placeholder="انتخاب تاریخ پایان"
                                    />
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
                                            main-container-klass="absolute w-full h-full"
                                            big-circle-color="border-transparent"
                                        />
                                    </VTransitionFade>

                                    <template #icon="{klass}">
                                        <CheckIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
                                    </template>

                                    <span class="ml-auto">ویرایش کوپن</span>
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
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import PartialCard from "../../../components/partials/PartialCard.vue";
import BaseSwitch from "../../../components/base/BaseSwitch.vue";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import {CheckIcon, ArrowLeftCircleIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import BaseInput from "../../../components/base/BaseInput.vue";
import PartialInputLabel from "../../../components/partials/PartialInputLabel.vue";
import PartialInputLead from "../../../components/partials/PartialInputLead.vue";
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

const loading = ref(false)
const canSubmit = ref(true)

const coupon = ref(null)

const publishStatus = ref(true)
const startDate = ref(null)
const endDate = ref(null)

const {handleSubmit, errors, isSubmitting} = useForm({
    validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
    if (!canSubmit.value) return
})

// onMounted(() => {
//     useRequest(apiReplaceParams(apiRoutes.admin.coupons.show, {coupon: idParam.value}), null, {
//         success: (response) => {
//             festival.value = response.data
//             startDate.value = response.data.start_date
//             endDate.value = response.data.end_date
//
//             loading.value = false
//         },
//     })
// })
</script>

<style scoped>

</style>
