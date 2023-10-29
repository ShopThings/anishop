<template>
    <partial-card ref="tableContainer">
        <template #header>
            لیست سفارشات
        </template>

        <template #body>
            <base-loading-panel :loading="loading" type="table">
                <template #content>
                    <base-datatable
                        ref="datatable"
                        :enable-search-box="true"
                        :enable-multi-operation="false"
                        :is-slot-mode="true"
                        :is-loading="table.isLoading"
                        :columns="table.columns"
                        :rows="table.rows"
                        :has-checkbox="false"
                        :total="table.totalRecordCount"
                        :sortable="table.sortable"
                        @do-search="doSearch"
                    >
                        <template v-slot:user="{value}">

                        </template>
                        <template v-slot:receiver_info="{value}">
                            <base-button
                                class="text-white bg-black text-sm !py-1"
                                @click="showReceiverDetails(value)"
                            >
                                مشاهده
                            </base-button>
                        </template>
                        <template v-slot:payment_status="{value}">

                        </template>
                        <template v-slot:send_status="{value}">

                        </template>
                        <template v-slot:created_at="{value}">
                            <span v-if="value.created_at" class="text-xs">{{ value.created_at }}</span>
                            <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
                        </template>
                        <template v-slot:op="{value}">
                            <base-datatable-menu
                                :items="operations"
                                :data="value"
                                :container="getMenuContainer"
                                :removals="!store.hasAnyRole([ROLES.DEVELOPER, ROLES.SUPER_ADMIN]) ? ['delete'] : []"
                            />
                        </template>
                    </base-datatable>
                </template>
            </base-loading-panel>
        </template>
    </partial-card>
</template>

<script setup>
import {computed, reactive, ref} from "vue"
import {MinusIcon} from "@heroicons/vue/24/outline/index.js"
import BaseDatatable from "../../../components/base/BaseDatatable.vue"
import BaseDatatableMenu from "../../../components/base/datatable/BaseDatatableMenu.vue";
import {apiReplaceParams, apiRoutes} from "../../../router/api-routes.js";
import {useRequest} from "../../../composables/api-request.js";
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import PartialCard from "../../../components/partials/PartialCard.vue";
import {useRouter} from "vue-router";
import {useToast} from "vue-toastification";
import {hideAllPoppers} from "floating-vue";
import {useConfirmToast} from "../../../composables/toast-confirm.js";
import {useAdminStore, ROLES} from "../../../store/StoreUserAuth.js";
import BaseButton from "../../../components/base/BaseButton.vue";

const router = useRouter()
const toast = useToast()

const store = useAdminStore()

const datatable = ref(null)
const tableContainer = ref(null)
const loading = ref(true)
const table = reactive({
    isLoading: false,
    columns: [
        {
            label: "#",
            field: "id",
            columnStyles: "width: 3%;",
            sortable: true,
            isKey: true,
        },
        {
            label: "کد سفارش",
            field: "code",
            columnClasses: 'whitespace-nowrap',
            sortable: true,
        },
        {
            label: "سفارش دهنده",
            field: "user",
            columnClasses: 'whitespace-nowrap',
            sortable: true,
        },
        {
            label: "اطلاعات گیرنده",
            field: "receiver_info",
        },
        {
            label: "وضعیت سفارش",
            field: "payment_status",
        },
        {
            label: "وضعیت ارسال",
            field: "send_status",
        },
        {
            label: "تاریخ سفارش",
            field: "created_at",
            columnClasses: 'whitespace-nowrap',
            sortable: true,
        },
        {
            label: 'عملیات',
            field: 'op',
            width: '7%',
        },
    ],
    rows: [],
    totalRecordCount: 0,
    sortable: {
        order: "id",
        sort: "desc",
    },
})

const getMenuContainer = computed(() => {
    return datatable.value?.tableContainer ?? 'body'
})

const operations = [
    {
        id: 'detail',
        link: {
            text: 'مشاهده جزئیات',
            icon: 'EyeIcon',
        },
        event: {
            click: (data) => {
                router.push({
                    name: 'admin.order.detail',
                    params: {
                        id: data.id,
                    }
                })
            },
        },
    },
    {
        id: 'delete',
        link: {
            text: 'حذف',
            icon: 'TrashIcon',
            class: 'text-rose-500',
        },
        event: {
            click: (data) => {
                hideAllPoppers()
                toast.clear()

                if (!data.is_deletable)
                    toast.warning('این آیتم قابل حذف نمی‌باشد.')

                useConfirmToast(() => {
                    useRequest(apiReplaceParams(apiRoutes.admin.orders.destroy, {order: data.id}), {
                        method: 'DELETE',
                    }, {
                        success: () => {
                            toast.success('عملیات با موفقیت انجام شد.')
                            datatable.value?.refresh()
                            datatable.value?.resetSelectionItem(data)

                            return false
                        },
                    })
                })
            },
        },
    },
]

const doSearch = (offset, limit, order, sort, text) => {
    table.isLoading = true

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
    loading.value = false
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

function showReceiverDetails(value) {
    // show details of receiver detail
    // ...
}
</script>

<style scoped>

</style>
