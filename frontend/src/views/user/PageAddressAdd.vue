<template>
    <partial-card class="border-0 p-3">
        <template #body>
            <form @submit.prevent="onSubmit">
                <div class="flex flex-wrap">
                    <div class="p-2 w-full sm:w-1/2 lg:w-1/3">
                        <base-input
                            label-title="نام گیرنده"
                            placeholder="وارد نمایید"
                            name="full_name"
                        >
                            <template #icon>
                                <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                            </template>
                        </base-input>
                    </div>
                    <div class="p-2 w-full sm:w-1/2 lg:w-1/3">
                        <base-input
                            label-title="شماره تماس"
                            placeholder="وارد نمایید"
                            name="mobile"
                        >
                            <template #icon>
                                <DevicePhoneMobileIcon class="h-6 w-6 text-gray-400"/>
                            </template>
                        </base-input>
                    </div>
                    <div class="p-2 w-full sm:w-1/2 lg:w-1/3">
                        <partial-input-label title="انتخاب استان"/>
                        <base-select
                            :options="provinces"
                            options-key="id"
                            options-text="name"
                            :is-loading="provinceLoading"
                            :selected="selectedProvince"
                            name="province"
                            @change="(status) => {selectedProvince = status}"
                        />
                        <partial-input-error-message :error-message="errors.province"/>
                    </div>
                    <div class="p-2 w-full sm:w-1/2 lg:w-1/3">
                        <partial-input-label title="انتخاب شهرستان"/>
                        <base-select
                            :options="cities"
                            options-key="id"
                            options-text="name"
                            :is-loading="cityLoading"
                            :selected="selectedCity"
                            name="city"
                            @change="(status) => {selectedCity = status}"
                        />
                        <partial-input-error-message :error-message="errors.city"/>
                    </div>
                    <div class="p-2 w-full sm:w-1/2 lg:w-1/3">
                        <base-input
                            label-title="کدپستی"
                            placeholder="وارد نمایید"
                            name="postal_code"
                        >
                            <template #icon>
                                <HashtagIcon class="h-6 w-6 text-gray-400"/>
                            </template>
                        </base-input>
                    </div>
                    <div class="p-2 w-full">
                        <base-textarea
                            label-title="آدرس محل سکونت"
                            placeholder="آدرس داخل شهرستان را وارد نمایید"
                            name="address"
                        >
                            <template #icon>
                                <ArrowLeftCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
                            </template>
                        </base-textarea>
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

                        <span class="ml-auto">افزودن آدرس</span>
                    </base-animated-button>
                </div>
            </form>
        </template>
    </partial-card>
</template>

<script setup>
import {onMounted, ref} from "vue";
import {useForm} from "vee-validate";
import yup from "../../validation/index.js";
import BaseInput from "../../components/base/BaseInput.vue";
import VTransitionFade from "../../transitions/VTransitionFade.vue";
import BaseAnimatedButton from "../../components/base/BaseAnimatedButton.vue";
import LoaderCircle from "../../components/base/loader/LoaderCircle.vue";
import {
    CheckIcon,
    ArrowLeftCircleIcon,
    DevicePhoneMobileIcon,
    HashtagIcon,
} from "@heroicons/vue/24/outline/index.js";
import PartialInputLabel from "../../components/partials/PartialInputLabel.vue";
import PartialInputErrorMessage from "../../components/partials/PartialInputErrorMessage.vue";
import BaseSelect from "../../components/base/BaseSelect.vue";
import PartialCard from "../../components/partials/PartialCard.vue";
import BaseTextarea from "../../components/base/BaseTextarea.vue";
import {useRequest} from "../../composables/api-request.js";
import {apiRoutes} from "../../router/api-routes.js";

const provinces = ref([])
const cities = ref([])
const provinceLoading = ref(true)
const cityLoading = ref(true)

const selectedProvince = ref(null)
const selectedCity = ref(null)

const canSubmit = ref(true)

const {handleSubmit, errors, isSubmitting} = useForm({
    validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
    if (!canSubmit.value) return
})

onMounted(() => {
    // useRequest(apiRoutes.admin.cityPostPrices.provinces, null, {
    //     success: (response) => {
    //         provinces.value = response.data
    //
    //         provinceLoading.value = false
    //     },
    // })
    //
    // useRequest(apiRoutes.admin.cityPostPrices.cities, null, {
    //     success: (response) => {
    //         cities.value = response.data
    //
    //         cityLoading.value = false
    //     },
    // })
})
</script>

<style scoped>

</style>
