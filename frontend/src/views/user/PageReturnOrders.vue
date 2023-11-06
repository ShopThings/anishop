<template>
    <base-message
        type="info"
        :has-close="false"
        class="rounded-lg"
    >
        <div class="leading-relaxed">
            سفارشاتی قابلیت مرجوع شدن را دارند که پرداخت آنها کامل انجام شده و تا حداکثر یک هفته از خرید آنها میگذرد.
        </div>
    </base-message>

    <form @submit.prevent="onSubmit">
        <div class="flex flex-col sm:flex-row gap-2 items-end mb-3">
            <div class="w-full sm:w-auto sm:grow">
                <partial-input-label title="انتخاب سفارش جهت مرجوع نمودن"/>
                <base-select-searchable
                    options-text="code"
                    options-key="id"
                    :options="orders"
                    :is-loading="ordersLoading"
                    @change="(selected) => {selectedOrder=selected}"
                >
                    <template #item="{item}">
                        <span class="tracking-widest">{{ item.code }}</span>
                    </template>
                </base-select-searchable>
            </div>

            <base-button
                type="submit"
                class="flex items-center justify-center gap-2 bg-primary group text-sm shrink-0 w-full sm:w-auto"
                :disabled="isSubmitting"
            >
                <VTransitionFade>
                    <loader-circle
                        v-if="isSubmitting"
                        main-container-klass="absolute w-full h-full top-0 left-0"
                        big-circle-color="border-transparent"
                    />
                </VTransitionFade>

                <span class="mx-auto">مرجوع نمودن سفارش</span>
                <ArrowLeftIcon class="w-6 h-6 group-hover:-translate-x-1.5 transition"/>
            </base-button>
        </div>
    </form>

    <base-loading-panel :loading="returnOrdersTableLoading" type="table">
        <template #content>
            <partial-general-title title="سفارشات مرجوع شده"/>

            <base-semi-datatable
                pagination-theme="modern"
                :is-loading="returnOrdersTableSetting.isLoading"
                :columns="returnOrdersTableSetting.columns"
                :rows="returnOrdersTableSetting.rows"
                :total="returnOrdersTableSetting.total"
                @do-search="getReturnOrders"
            >
                <template #status="{value}">
                    <partial-badge-status-return-order/>
                </template>

                <template #requested_at="{value}">
                    <span class="text-sm">{{ value.requested_at }}</span>
                </template>

                <template #op="{value}">
                    <router-link
                        :to="{name: 'user.return_order.detail', params: {code: 12345}}"
                        class="text-blue-600 hover:text-opacity-80 text-sm"
                    >
                        مشاهده جزئیات
                    </router-link>
                </template>
            </base-semi-datatable>
        </template>
    </base-loading-panel>
</template>

<script setup>
import {onMounted, reactive, ref} from "vue";
import {ArrowLeftIcon} from "@heroicons/vue/24/outline/index.js";
import BaseMessage from "../../components/base/BaseMessage.vue";
import BaseSelectSearchable from "../../components/base/BaseSelectSearchable.vue";
import {useRequest} from "../../composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "../../router/api-routes.js";
import {useUserStore} from "../../store/StoreUserAuth.js";
import BaseButton from "../../components/base/BaseButton.vue";
import BaseSemiDatatable from "../../components/base/BaseSemiDatatable.vue";
import PartialBadgeStatusReturnOrder from "../../components/partials/PartialBadgeStatusReturnOrder.vue";
import PartialGeneralTitle from "../../components/partials/PartialGeneralTitle.vue";
import BaseLoadingPanel from "../../components/base/BaseLoadingPanel.vue";
import PartialInputLabel from "../../components/partials/PartialInputLabel.vue";
import {useForm} from "vee-validate";
import yup from "../../validation/index.js";
import VTransitionFade from "../../transitions/VTransitionFade.vue";
import LoaderCircle from "../../components/base/loader/LoaderCircle.vue";

const store = useUserStore()
const user = store.getUser

const orders = ref([])
const ordersLoading = ref(true)
const selectedOrder = ref(null)

const returnOrdersTableLoading = ref(true)
const returnOrdersTableSetting = reactive({
    isLoading: true,
    columns: [
        {
            field: 'code',
            label: 'کد درخواست',
        },
        {
            field: 'order_code',
            label: 'کد سفارش',
        },
        {
            field: 'status',
            label: 'وضعیت',
        },
        {
            field: 'requested_at',
            label: 'تاریخ درخواست',
        },
        {
            field: 'op',
            label: 'عملیات',
        },
    ],
    rows: [],
    total: 0,
})

const getReturnOrders = (offset, limit) => {
    returnOrdersTableSetting.isLoading = true

    // useRequest(apiRoutes., {
    //     params: {limit, offset, order, sort, text},
    // }, {
    //     success: (response) => {
    //         returnOrdersTableSetting.rows = response.data
    //         returnOrdersTableSetting.total = response.meta.total
    //
    //         return false
    //     },
    //     error: () => {
    //         returnOrdersTableSetting.rows = []
    //         returnOrdersTableSetting.total = 0
    //     },
    //     finally: () => {
    returnOrdersTableLoading.value = false
    returnOrdersTableSetting.isLoading = false
    //     },
    // })
}

getReturnOrders(0, 15)

const canSubmit = ref(true)
const {handleSubmit, errors, isSubmitting} = useForm({
    validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
    if (!canSubmit.value) return
})

onMounted(() => {
    // useRequest(apiReplaceParams(apiRoutes.admin.orders.index, {user: user.id}), null, {
    //     success: (response) => {
    //         orders.value = response.data
    //
    //         ordersLoading.value = false
    //     },
    // })
})
</script>

<style scoped>

</style>
