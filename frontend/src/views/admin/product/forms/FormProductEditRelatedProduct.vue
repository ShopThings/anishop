<template>
    <base-loading-panel
        :loading="loading"
        type="form"
    >
        <template #content>
            <form>
                <partial-card class="mb-3 p-3 relative">
                    <template #body>
                        <loader-dot-orbit
                            v-if="isSubmitting"
                            main-container-klass="absolute w-full h-full top-0 left-0 z-[2]"
                            container-bg-color="bg-blue-50 opacity-40"
                        />

                        <div class="w-full p-2">
                            <partial-input-label title="انتخاب محصول مرتبط"/>
                            <base-select-searchable
                                :options="products"
                                options-key="id"
                                options-text="title"
                                name="products"
                                :multiple="true"
                                :is-loading="productLoading"
                                :is-local-search="false"
                                placeholder="جستجوی محصول..."
                                :selected="selectedProducts"
                                @change="(p) => {selectedProducts = p}"
                                @query="searchProduct"
                            />
                        </div>

                        <div v-if="selectedProducts && selectedProducts.length">
                            <partial-input-label title="محصولات انتخاب شده"/>
                            <div
                                class="mt-3 p-2 py-1 border-2 border-dashed rounded-lg border-indigo-200 mb-3 relative flex flex-wrap"
                            >
                                <div
                                    v-for="(product, idx) in products"
                                    class="rounded bg-blue-100 text-sm text-blue-700 py-1 px-2 flex items-center ml-2 my-1"
                                >
                                    <span class="ml-3">{{ product?.title }}</span>
                                    <base-button-close v-tooltip.top="'حذف از لیست'" @click="removeProduct(idx)"/>
                                </div>
                            </div>
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
                            :allow-prev-step="false"
                            :show-prev-step-button="false"
                            :loading="isSubmitting"
                            @next="handleNextClick(options.next)"
                        />
                    </template>
                </partial-card>
            </form>
        </template>
    </base-loading-panel>
</template>

<script setup>
import {computed, ref} from "vue";
import PartialCard from "../../../../components/partials/PartialCard.vue";
import PartialStepyNextPrevButtons from "../../../../components/partials/PartialStepyNextPrevButtons.vue";
import {useForm} from "vee-validate";
import yup from "../../../../validation/index.js";
import BaseSelectSearchable from "../../../../components/base/BaseSelectSearchable.vue";
import PartialInputLabel from "../../../../components/partials/PartialInputLabel.vue";
import {useRequest} from "../../../../composables/api-request.js";
import {apiRoutes} from "../../../../router/api-routes.js";
import BaseButtonClose from "../../../../components/base/BaseButtonClose.vue";
import isArray from "lodash.isarray";
import LoaderDotOrbit from "../../../../components/base/loader/LoaderDotOrbit.vue";
import {useRoute} from "vue-router";
import {useToast} from "vue-toastification";
import BaseLoadingPanel from "../../../../components/base/BaseLoadingPanel.vue";

defineProps({
    options: {
        type: Object,
        required: true,
    },
})

const route = useRoute()
const toast = useToast()
const idParam = computed(() => {
    const id = parseInt(route.params.id, 10)
    if (isNaN(id)) return route.params.id
    return id
})

const loading = ref(false)
const canSubmit = ref(true)

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

//----------------------
// Product search
//----------------------
const productLoading = ref(true)
const products = ref([])
const selectedProducts = ref(null)

function searchProduct(query) {
    // useRequest(apiReplaceParams(apiRoutes.admin.products.show, {product: idParam.value}), null, {
    //     success: (response) => {
    //         selectedProducts.value = response.data.related_products
    //
    //         loading.value = false
    //     }
    // })
    //
    // useRequest(apiRoutes.admin.products.index, {
    //     data: {
    //         query,
    //     },
    // }, {
    //     success: (response) => {
    //         products.value = response.data
    //     },
    //     finally: () => {
    //         productLoading.value = false
    //     }
    // })
}

function removeProduct(idx) {
    if (isArray(selectedProducts.value))
        selectedProducts.value.splice(idx, 1)
}
</script>

<style scoped>

</style>
<script setup>
</script>
