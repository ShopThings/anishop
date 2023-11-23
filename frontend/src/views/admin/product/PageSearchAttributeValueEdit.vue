<template>
    <base-loading-panel
        :loading="loading"
        type="form"
    >
        <template #content>
            <partial-card>
                <template #header>
                    ویرایش تخصیص مقدار به ویژگی با عنوان
                    <span
                        v-if="attribute?.id"
                        class="text-teal-600"
                    >{{ attribute?.title }}</span>
                </template>
                <template #body>
                    <div class="p-3">
                        <form @submit.prevent="onSubmit">
                            <div class="flex flex-wrap items-end justify-between">
                                <div class="w-full p-2 sm:w-1/2">
                                    <base-input
                                        label-title="عنوان"
                                        placeholder="وارد نمایید"
                                        name="title"
                                        :value="attributeValue?.title"
                                    >
                                        <template #icon>
                                            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                                        </template>
                                    </base-input>
                                </div>
                                <div class="w-full p-2 sm:w-1/2">
                                    <base-input
                                        type="number"
                                        :min="0"
                                        label-title="اولویت"
                                        placeholder="وارد نمایید"
                                        name="priority"
                                        :value="attributeValue?.priority"
                                    >
                                        <template #icon>
                                            <HashtagIcon class="h-6 w-6 text-gray-400"/>
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

                                    <span class="ml-auto">ویرایش مقدار</span>
                                </base-animated-button>
                            </div>
                        </form>
                    </div>
                </template>
            </partial-card>
        </template>
    </base-loading-panel>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import {useForm} from "vee-validate";
import yup from "../../../validation/index.js";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckIcon, HashtagIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import PartialCard from "../../../components/partials/PartialCard.vue";
import BaseInput from "../../../components/base/BaseInput.vue";
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import {useRequest} from "../../../composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "../../../router/api-routes.js";
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
const valParam = computed(() => {
    const val = parseInt(route.params.val, 10)
    if (isNaN(val)) return route.params.val
    return val
})

const loading = ref(false)
const canSubmit = ref(true)

const attribute = ref(null)
const attributeValue = ref(null)

const {handleSubmit, errors, isSubmitting} = useForm({
    validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
    if (!canSubmit.value) return
})

onMounted(() => {
    // useRequest(apiReplaceParams(apiRoutes.admin.productAttributes, {product_attribute: idParam.value}), null, {
    //     success: (response) => {
    //         attribute.value = response.data
    //     },
    // })

    // useRequest(apiReplaceParams(apiRoutes.admin.productAttributeValues, {product_attribute_value: valParam.value}), null, {
    //     success: (response) => {
    //         attributeValue.value = response.data
    //
    //         loading.value = false
    //     },
    // })
})
</script>

<style scoped>

</style>
