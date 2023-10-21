<template>
    <new-creation-guide-top route-name="admin.user.add">
        <template #text>
            با استفاده از ستون عملیات می‌توانید اقدام به حذف و مشاهده کاربر نمایید
        </template>
        <template #buttonText>
            <PlusIcon class="w-6 h-6 ml-2 group-hover:rotate-90 transition"/>
            افزودن کاربر جدید
        </template>
    </new-creation-guide-top>

    <partial-card ref="tableContainer">
        <template #header>
            لیست کاربران
        </template>

        <template #body>
            <base-loading-panel :loading="loading" type="table">
                <template #content>
                    <base-datatable
                        ref="datatable"
                        :enable-search-box="true"
                        :enable-multi-operation="true"
                        :selection-operations="selectionOperations"
                        :is-slot-mode="true"
                        :is-loading="table.isLoading"
                        :selection-columns="table.selectionColumns"
                        :columns="table.columns"
                        :rows="table.rows"
                        :has-checkbox="true"
                        :total="table.totalRecordCount"
                        :sortable="table.sortable"
                        @do-search="doSearch"
                    >
                        <template v-slot:roles="{value}">
                            <span v-if="value.roles"
                                  v-for="(role) in value.roles"
                                  class="rounded-md text-white bg-fuchsia-700 text-xs py-1 px-2 inline-block m-1 whitespace-nowrap">
                                {{ role }}
                            </span>
                            <span v-else
                                  class="rounded-md text-white bg-black text-xs py-1 px-2 inline-block whitespace-nowrap">فاقد نقش</span>
                        </template>
                        <template v-slot:created_at="{value}">
                            <span v-if="value.created_at" class="text-xs">{{ value.created_at }}</span>
                            <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
                        </template>
                        <template v-slot:verified_at="{value}">
                            <span v-if="value.verified_at" class="text-emerald-500 text-xs flex flex-col">
                                <span
                                    class="text-gray-500 border rounded-full py-1 px-2 bg-white shadow inline-block mb-1 mx-auto">تایید شده در تاریخ</span>
                                {{ value.verified_at }}
                            </span>
                            <span v-else class="rounded-md text-white bg-rose-500 text-xs p-1">تایید نشده</span>
                        </template>
                        <template v-slot:op="{value}">
                            <base-datatable-menu
                                :items="operations"
                                :data="value"
                                :container="getMenuContainer"
                                :removals="!value.is_deletable ? ['delete'] : []"
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
import {PlusIcon, MinusIcon} from "@heroicons/vue/24/outline/index.js"
import BaseDatatable from "../../../components/base/BaseDatatable.vue"
import NewCreationGuideTop from "../../../components/admin/NewCreationGuideTop.vue"
import BaseDatatableMenu from "../../../components/base/datatable/BaseDatatableMenu.vue";
import {apiReplaceParams, apiRoutes} from "../../../router/api-routes.js";
import {useRequest} from "../../../composables/api-request.js";
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import PartialCard from "../../../components/partials/PartialCard.vue";
import {useRouter} from "vue-router";
import {useToast} from "vue-toastification";
import {hideAllPoppers} from "floating-vue";
import {useConfirmToast} from "../../../composables/toast-confirm.js";

const router = useRouter()
const toast = useToast()

const datatable = ref(null)
const tableContainer = ref(null)
const loading = ref(true)
const table = reactive({
    isLoading: false,
    selectionColumns: [
        {
            label: "#",
            field: "id",
            columnStyles: "width: 3%;",
            isKey: true,
            sortable: true,
        },
        {
            label: "موبایل",
            field: "username",
            columnClasses: 'whitespace-nowrap',
            sortable: true,
        },
        {
            label: "نام",
            field: "first_name",
            sortable: true,
        },
        {
            label: "نام خانوادگی",
            field: "last_name",
            sortable: true,
        },
        {
            label: "نقش",
            field: "roles",
        },
        {
            label: "تاریخ عضویت",
            field: "created_at",
            columnClasses: 'whitespace-nowrap',
            sortable: true,
        },
        {
            label: "وضعیت",
            field: "verified_at",
            columnClasses: 'whitespace-nowrap',
            sortable: true,
        },
    ],
    columns: [
        {
            label: "#",
            field: "id",
            columnStyles: "width: 3%;",
            sortable: true,
            isKey: true,
        },
        {
            label: "موبایل",
            field: "username",
            sortable: true,
            columnClasses: 'whitespace-nowrap',
        },
        {
            label: "نام",
            field: "first_name",
            sortable: true,
        },
        {
            label: "نام خانوادگی",
            field: "last_name",
            sortable: true,
        },
        {
            label: "نقش",
            field: "roles",
        },
        {
            label: "تاریخ عضویت",
            field: "created_at",
            columnClasses: 'whitespace-nowrap',
            sortable: true,
        },
        {
            label: "وضعیت",
            field: "verified_at",
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
        id: 'edit',
        link: {
            text: 'مشاهده و ویرایش',
            icon: 'PencilIcon',
        },
        event: {
            click: (data) => {
                router.push({
                    name: 'admin.user.profile',
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
                    useRequest(apiReplaceParams(apiRoutes.admin.users.destroy, {user: data.id}), {
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

const selectionOperations = [
    {
        btn: {
            tooltip: 'حذف موارد انتخاب شده',
            icon: 'TrashIcon',
            class: 'bg-rose-500 border-rose-600',
        },
        event: {
            click: (items) => {
                const ids = []
                for (const item in items) {
                    if (items.hasOwnProperty(item)) {
                        if (items[item].id)
                            ids.push(items[item].id)
                    }
                }

                if (!ids.length) {
                    toast.info('ابتدا آیتم‌های مورد نیاز را انتخاب کنید و سپس دوباره تلاش نمایید.')
                    return
                }

                toast.clear()

                useConfirmToast(() => {
                    useRequest(apiRoutes.admin.users.batchDestroy, {
                        method: 'DELETE',
                        data: {
                            ids,
                        },
                    }, {
                        success: () => {
                            toast.success('عملیات با موفقیت انجام شد.')
                            datatable.value?.refresh()
                            datatable.value?.resetSelection()

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

    useRequest(apiRoutes.admin.users.index, {
        params: {limit, offset, order, sort, text},
    }, {
        success: (response) => {
            table.rows = response.data
            table.totalRecordCount = response.meta.total

            return false
        },
        error: () => {
            table.rows = []
            table.totalRecordCount = 0
        },
        finally: () => {
            loading.value = false
            table.isLoading = false
            table.sortable.order = order
            table.sortable.sort = sort

            if (tableContainer.value && tableContainer.value.card)
                tableContainer.value.card.scrollIntoView({behavior: "smooth"})
        },
    })
}

doSearch(0, 15, 'id', 'desc')
</script>

<style scoped>

</style>
<script setup>
</script>
