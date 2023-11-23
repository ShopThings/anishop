<template>
    <partial-card>
        <template #header>
            ویرایش هزینه ارسال برحسب وزن
        </template>
        <template #body>
            <div class="p-3">
                <base-loading-panel
                    :loading="loading"
                    type="form"
                >
                    <template #content>
                        <form @submit.prevent="onSubmit">
                            <div class="flex flex-wrap">
                                <div class="p-2 w-full sm:w-1/2 lg:w-1/3">
                                    <base-input
                                        label-title="حداقل وزن مرسوله"
                                        placeholder="وارد نمایید"
                                        name="min_weight"
                                        :value="postPrice?.min_weight"
                                    >
                                        <template #icon>
                                            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                                <div class="p-2 w-full sm:w-1/2 lg:w-1/3">
                                    <base-input
                                        label-title="حداکثر وزن مرسوله"
                                        placeholder="وارد نمایید"
                                        name="max_weight"
                                        :value="postPrice?.max_weight"
                                    >
                                        <template #icon>
                                            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                                <div class="p-2 w-full sm:w-1/2 lg:w-1/3">
                                    <base-input
                                        label-title="هزینه ارسال"
                                        placeholder="وارد نمایید"
                                        name="post_price"
                                        :value="postPrice?.post_price"
                                    >
                                        <template #icon>
                                            <CurrencyDollarIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
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

                                    <span class="ml-auto">ویرایش هزینه ارسال</span>
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
import {CheckIcon, CurrencyDollarIcon, ArrowLeftCircleIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import BaseInput from "../../../components/base/BaseInput.vue";
import {useRequest} from "../../../composables/api-request.js";
import {apiRoutes} from "../../../router/api-routes.js";
import PartialInputErrorMessage from "../../../components/partials/PartialInputErrorMessage.vue";
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import {useToast} from "vue-toastification";
import {useRoute} from "vue-router";

const route = useRoute()
const toast = useToast()
const idParam = computed(() => {
    const id = parseInt(route.params.id, 10)
    if (isNaN(id)) return route.params.id
    return id
})

const loading = ref(false)
const canSubmit = ref(true)

const postPrice = ref(null)

const {handleSubmit, errors, isSubmitting} = useForm({
    validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
    if (!canSubmit.value) return
})

onMounted(() => {
    // useRequest(apiReplaceParams(apiRoutes.admin.weightPostPrices.show, {weight_post_price: idParam.value}), null, {
    //     success: (response) => {
    //         postPrice.value = response.data
    //
    //         loading.value = false
    //     },
    // })
})
</script>

<style scoped>

</style>
