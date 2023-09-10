<template>
    <base-loading-panel
        :loading="loading"
        type="content"
    >
        <template #content>
            <div class="bg-white mb-3 rounded-lg border p-3">
                جزئیات سفارش -
                <span
                    v-if="order?.id"
                    class="text-teal-600"
                >{{ order?.code }}</span>
            </div>

            <div class="bg-white mb-3 rounded-lg border p-3">
                سفارش دهنده -
                <router-link
                    v-if="order?.user.id"
                    :to="{name: 'admin.user.profile', params: {id: order?.user.id}}"
                    class="text-blue-600 hover:text-opacity-90"
                >{{
                        (order?.user?.first_name || order?.user?.last_name) ? (order?.user?.first_name + ' ' + order?.user?.last_name).trim() : order?.user.username
                    }}</router-link>
            </div>

            <div class="lg:flex lg:flex-wrap lg:gap-3">
                <partial-card class="mb-3 lg:grow">
                    <template #header>
                        تغییر وضعیت پرداخت
                    </template>
                    <template #body>
                        <div class="p-3">
                            <form-order-change-payment-status
                                v-model:selected="paymentStatus"
                            />
                        </div>
                    </template>
                </partial-card>

                <partial-card class="mb-3 lg:grow">
                    <template #header>
                        تغییر وضعیت ارسال
                    </template>
                    <template #body>
                        <div class="p-3">
                            <form-order-change-send-status
                                v-model:selected="sendStatus"
                            />
                        </div>
                    </template>
                </partial-card>
            </div>

            <div class="mb-3">
                <base-accordion
                    :open="false"
                    btn-class="text-white bg-pink-600 hover:bg-pink-500 focus-visible:ring-pink-800"
                    btn-icon-class="text-white"
                    panel-class="bg-white mt-3 rounded-lg !p-4"
                >
                    <template #button>
                        اطلاعات گیرنده
                    </template>
                    <template #panel>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                            <div class="py-1 px-3 border border-slate-300 rounded">
                                <partial-input-label title="نام"/>
                                <span class="text-teal-600 mt-1">مهران مرادی</span>
                            </div>
                            <div class="py-1 px-3 border border-slate-300 rounded">
                                <partial-input-label title="کد ملی"/>
                                <span class="text-teal-600 mt-1">۳۳۶۰۳۶۵۸۸۷</span>
                            </div>
                            <div class="py-1 px-3 border border-slate-300 rounded">
                                <partial-input-label title="شماره تماس"/>
                                <span class="text-teal-600 mt-1">۰۹۹۳۸۳۰۶۱۹۸</span>
                            </div>
                            <div class="py-1 px-3 border border-slate-300 rounded">
                                <partial-input-label title="استان"/>
                                <span class="text-teal-600 mt-1">کرمانشاه</span>
                            </div>
                            <div class="py-1 px-3 border border-slate-300 rounded">
                                <partial-input-label title="شهر"/>
                                <span class="text-teal-600 mt-1">قصر شیرین</span>
                            </div>
                            <div class="py-1 px-3 border border-slate-300 rounded">
                                <partial-input-label title="کدپستی"/>
                                <span class="text-teal-600 mt-1">۶۷۷۴۱۱۶۱۹۵</span>
                            </div>
                            <div class="py-1 px-3 border border-slate-300 rounded sm:col-span-2">
                                <partial-input-label title="آدرس"/>
                                <span class="text-teal-600 mt-1">کرمانشاه قصرشیرین قصرجدید فاز1</span>
                            </div>
                        </div>
                    </template>
                </base-accordion>
            </div>

            <div class="mb-3">
                <base-accordion
                    :open="false"
                    btn-class="text-white bg-fuchsia-600 hover:bg-fuchsia-500 focus-visible:ring-fuchsia-800"
                    btn-icon-class="text-white"
                    panel-class="bg-white mt-3 rounded-lg !p-4"
                >
                    <template #button>
                        جزئیات سفارش
                    </template>
                    <template #panel>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                            <div class="py-1 px-3 border border-slate-300 rounded">
                                <partial-input-label title="شماره فاکتور"/>
                                <span class="text-teal-600 mt-1">172584328151083</span>
                            </div>
                            <div class="py-1 px-3 border border-slate-300 rounded">
                                <partial-input-label title="مبلغ قابل پرداخت"/>
                                <span class="text-teal-600 mt-1">۶۷۹,۰۰۰ تومان</span>
                            </div>
                            <div class="py-1 px-3 border border-slate-300 rounded">
                                <partial-input-label title="تاریخ ثبت سفارش"/>
                                <span class="text-teal-600 mt-1">۱۷ شهریور ۱۴۰۲ در ساعت ۱۹ و ۳۴ دقیقه</span>
                            </div>
                            <div class="py-1 px-3 border border-slate-300 rounded">
                                <partial-input-label title="وضعیت سفارش"/>
                                <div class="mt-1">
                                    <span
                                        class="bg-cyan-600 rounded text-white py-1 px-2 inline-block">در صف بررسی</span>
                                </div>
                            </div>
                            <div class="py-1 px-3 border border-slate-300 rounded">
                                <partial-input-label title="طریقه ارسال"/>
                                <span class="text-teal-600 mt-1">پست</span>
                            </div>
                            <div class="py-1 px-3 border border-slate-300 rounded">
                                <partial-input-label title="هزینه ارسال"/>
                                <span class="text-teal-600 mt-1">۴۴,۰۰۰ تومان</span>
                            </div>
                            <div class="py-1 px-3 border border-slate-300 rounded">
                                <partial-input-label title="تحویل حضوری"/>
                                <span class="text-teal-600 mt-1">خیر</span>
                            </div>
                            <div class="py-1 px-3 border border-slate-300 rounded">
                                <partial-input-label title="عنوان کوپن"/>
                                <span class="text-teal-600 mt-1">-</span>
                            </div>
                            <div class="py-1 px-3 border border-slate-300 rounded">
                                <partial-input-label title="مبلغ کوپن"/>
                                <span class="text-teal-600 mt-1">-</span>
                            </div>
                        </div>
                    </template>
                </base-accordion>
            </div>

            <div class="mb-3">
                <base-accordion
                    :open="false"
                    btn-class="text-white bg-purple-600 hover:bg-purple-500 focus-visible:ring-pink-800"
                    btn-icon-class="text-white"
                    panel-class="bg-white mt-3 rounded-lg !p-4"
                >
                    <template #button>
                        وضعیت مالی
                    </template>
                    <template #panel>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                            <div class="py-1 px-3 border border-slate-300 rounded">
                                <partial-input-label title="وضعیت پرداخت"/>
                                <div class="mt-1">
                                    <span
                                        class="bg-red-600 rounded text-white py-1 px-2 inline-block">پرداخت نشده</span>
                                </div>
                            </div>
                            <div class="py-1 px-3 border border-slate-300 rounded">
                                <partial-input-label title="کد رهگیری"/>
                                <span class="text-teal-600 mt-1">-</span>
                            </div>
                            <div class="py-1 px-3 border border-slate-300 rounded">
                                <partial-input-label title="تاریخ پرداخت"/>
                                <span class="text-teal-600 mt-1">-</span>
                            </div>
                            <div class="py-1 px-3 border border-slate-300 rounded">
                                <partial-input-label title="شیوه پرداخت"/>
                                <span class="text-teal-600 mt-1">پرداخت الکترونیک سداد</span>
                            </div>
                        </div>
                    </template>
                </base-accordion>
            </div>
        </template>
    </base-loading-panel>

    <partial-card>
        <template #header>
            محصولات خریداری شده
        </template>
        <template #body>
            <div
                v-if="!itemsLoading"
                class="p-3"
            >
                <base-button
                    type="submit"
                    class="bg-orange-500 text-white mr-auto px-6 w-full sm:w-auto flex items-center"
                    :disabled="isDownloadFactor"
                    @click="factorDownloadHandler"
                >
                    <VTransitionFade>
                        <loader-circle
                            v-if="isDownloadFactor"
                            main-container-klass="absolute w-full h-full top-0 left-0"
                            big-circle-color="border-transparent"
                        />
                    </VTransitionFade>

                    <ArrowDownTrayIcon class="h-6 w-6 ml-2"/>
                    <span class="mx-auto">دانلود فاکتور (پی دی اف)</span>
                </base-button>
            </div>

            <base-loading-panel :loading="itemsLoading" type="table">
                <template #content>
                    <base-datatable
                        ref="datatable"
                        :enable-search-box="false"
                        :enable-multi-operation="false"
                        :is-slot-mode="true"
                        :is-loading="table.isLoading"
                        :columns="table.columns"
                        :rows="table.rows"
                        :has-checkbox="false"
                        :total="table.totalRecordCount"
                        @do-search="doSearch"
                    >
                        <template v-slot:product="{value}">

                        </template>
                        <template v-slot:unit_price="{value}">

                        </template>
                        <template v-slot:product_count="{value}">

                        </template>
                        <template v-slot:total_price="{value}">

                        </template>
                        <template v-slot:discount="{value}">

                        </template>
                        <template v-slot:discounted_price="{value}">

                        </template>
                    </base-datatable>
                </template>
            </base-loading-panel>
        </template>
    </partial-card>
</template>

<script setup>
import {computed, onMounted, reactive, ref} from "vue";
import PartialCard from "../../../components/partials/PartialCard.vue";
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import {apiReplaceParams, apiRoutes} from "../../../router/api-routes.js";
import {useRequest} from "../../../composables/api-request.js";
import {useRoute} from "vue-router";
import {useToast} from "vue-toastification";
import PartialInputLabel from "../../../components/partials/PartialInputLabel.vue";
import BaseAccordion from "../../../components/base/BaseAccordion.vue";
import FormOrderChangePaymentStatus from "./forms/FormOrderChangePaymentStatus.vue";
import FormOrderChangeSendStatus from "./forms/FormOrderChangeSendStatus.vue";
import BaseDatatable from "../../../components/base/BaseDatatable.vue";
import BaseButton from "../../../components/base/BaseButton.vue";
import {ArrowDownTrayIcon} from "@heroicons/vue/24/outline/index.js";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";

const route = useRoute()
const toast = useToast()
const idParam = computed(() => {
    const id = parseInt(route.params.id, 10)
    if (isNaN(id)) return route.params.id
    return id
})

const loading = ref(false)

const order = ref(null)
const paymentStatus = ref(null)
const sendStatus = ref(null)

// onMounted(() => {
//     useRequest(apiReplaceParams(apiRoutes.admin.orders.show, {order: idParam.value}), null, {
//         success: (response) => {
//             order.value = response.data
//             paymentStatus.value = response.data.payment_status
//             sendStatus.value = response.data.send_status
//
//             loading.value = false
//         },
//     })
// })

//-----------------------------------
// Factor downloading
//-----------------------------------
const isDownloadFactor = ref(false)

function factorDownloadHandler() {
    if (!isDownloadFactor.value) return
}

//-----------------------------------

//-----------------------------------
// Table stuffs
//-----------------------------------
const datatable = ref(null)
const tableContainer = ref(null)
const itemsLoading = ref(false)
const table = reactive({
    isLoading: false,
    columns: [
        {
            label: "#",
            field: "id",
            columnStyles: "width: 3%;",
            isKey: true,
        },
        {
            label: "مشخصات محصول",
            field: "product",
            columnClasses: 'whitespace-nowrap',
        },
        {
            label: "فی (به تومان)",
            field: "unit_price",
            columnClasses: 'whitespace-nowrap',
        },
        {
            label: "تعداد",
            field: "product_count",
            columnClasses: 'whitespace-nowrap',
        },
        {
            label: "مبلغ بدون تخفیف(به تومان)",
            field: "total_price",
            columnClasses: 'whitespace-nowrap',
        },
        {
            label: "مبلغ تخفیف(به تومان)",
            field: "discount",
            columnClasses: 'whitespace-nowrap',
        },
        {
            label: "مبلغ نهایی(به تومان)",
            field: "discounted_price",
            columnClasses: 'whitespace-nowrap',
        },
    ],
    rows: [],
    totalRecordCount: 0,
    sortable: {
        order: "id",
        sort: "desc",
    },
})

const doSearch = (offset, limit, order, sort, text) => {
    table.isLoading = true
    text = text || ''

    // useRequest(apiRoutes.admin.orders.index, {
    //     params: {limit, offset, order, sort, text},
    // }, {
    //     success: (response) => {
    //         table.rows = response.data
    //         table.totalRecordCount = response.meta.total
    //
    //         return false
    //     },
    //     error: () => {
    //         table.rows = []
    //         table.totalRecordCount = 0
    //     },
    //     finally: () => {
    itemsLoading.value = false
    table.isLoading = false
    //         table.sortable.order = order
    //         table.sortable.sort = sort
    //
    //         if (tableContainer.value && tableContainer.value.card)
    //             tableContainer.value.card.scrollIntoView({behavior: "smooth"})
    //     },
    // })
}

doSearch(0, 15, 'id', 'desc')
//-----------------------------------
</script>

<style scoped>

</style>
<script setup>
</script>
