<template>
    <partial-card>
        <template #header>
            ویرایش ویژگی جستجوی
            <span
                v-if="product?.id"
                class="text-teal-600"
            >{{ product?.title }}</span>
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
                                <div
                                    v-for="(attr) in productAttributes"
                                    class="w-full p-2 sm:w-1/2 xl:w-1/3"
                                >
                                    <partial-input-label :title="attr.title"/>
                                    <base-select
                                        :options="attr.attr_values"
                                        options-key="id"
                                        options-text="attribute_value"
                                        :selected="attr.product_attr_values[attr.id]"
                                        :name="'attr' + attr.id"
                                        @change="(t) => {attr.product_attr_values[attr.id] = t}"
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
                                            main-container-klass="absolute w-full h-full top-0 left-0"
                                            big-circle-color="border-transparent"
                                        />
                                    </VTransitionFade>

                                    <template #icon="{klass}">
                                        <CheckIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
                                    </template>

                                    <span class="ml-auto">ویرایش ویژگی‌های محصول</span>
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
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import {CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import PartialCard from "../../../components/partials/PartialCard.vue";
import {apiReplaceParams, apiRoutes} from "../../../router/api-routes.js";
import {useRequest} from "../../../composables/api-request.js";
import {useToast} from "vue-toastification";
import {useRoute, useRouter} from "vue-router";
import BaseSelect from "../../../components/base/BaseSelect.vue";
import PartialInputLabel from "../../../components/partials/PartialInputLabel.vue";

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

const product = ref(null)
const productAttributes = ref(null)

const {handleSubmit, errors, isSubmitting} = useForm({
    validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
    if (!canSubmit.value) return
})

onMounted(() => {
    // useRequest(apiReplaceParams(apiRoutes.admin.products.show, {product: idParam.value}), null, {
    //     success: (response) => {
    //         product.value = response.data
    //     },
    // })
    //
    // useRequest(apiReplaceParams(apiRoutes.admin.productAttributes.showProductMain, {product: idParam.value}), null, {
    //     success: (response) => {
    //         productAttributes.value = response.data
    //
    //         loading.value = false
    //     },
    // })
})
</script>

<style scoped>

</style>
