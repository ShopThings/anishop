<template>
    <partial-card ref="tableContainer">
        <template #header>
          لیست دیدگاه‌های بلاگ -
          <span
              v-if="blog?.id"
              class="text-teal-600"
          >{{ blog?.title }}</span>
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
                        <template v-slot:sender="{value}">

                        </template>
                        <template v-slot:status="{value}">

                        </template>
                        <template v-slot:condition="{value}">

                        </template>
                        <template v-slot:is_published="{value}">

                        </template>
                        <template v-slot:created_at="{value}">
                            <span v-if="value.created_at" class="text-xs">{{ value.created_at }}</span>
                            <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
                        </template>
                        <template v-slot:op="{value}">
                            <base-datatable-menu :items="operations" :data="value" :container="getMenuContainer"/>
                        </template>
                    </base-datatable>
                </template>
            </base-loading-panel>
        </template>
    </partial-card>
</template>

<script setup>
import {useRequest} from "../../../composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "../../../router/api-routes.js";
import {useRouter} from "vue-router";
import {useToast} from "vue-toastification";
import {computed, onMounted, reactive, ref} from "vue";
import {hideAllPoppers} from "floating-vue";
import {useConfirmToast} from "../../../composables/toast-confirm.js";
import {MinusIcon} from "@heroicons/vue/24/outline/index.js";
import PartialCard from "../../../components/partials/PartialCard.vue";
import BaseDatatableMenu from "../../../components/base/datatable/BaseDatatableMenu.vue";
import BaseDatatable from "../../../components/base/BaseDatatable.vue";
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";

const router = useRouter()
const toast = useToast()
const blogId = computed(() => {
    const id = parseInt(route.params.id, 10)
    if (isNaN(id)) return route.params.id
    return id
})

const blog = ref(null)

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
            sortable: true,
            isKey: true,
        },
        {
            label: "ارسال توسط",
            field: "sender",
        },
        {
            label: "وضعیت",
            field: "status",
            sortable: true,
        },
        {
            label: "وضعیت تایید",
            field: "condition",
            sortable: true,
        },
        {
            label: "تعداد گزارش",
            field: "flag_count",
            sortable: true,
        },
        {
            label: "وضعیت نمایش",
            field: "is_published",
            sortable: true,
        },
        {
            label: "تاریخ ارسال",
            field: "created_at",
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
            label: "ارسال توسط",
            field: "sender",
        },
        {
            label: "وضعیت",
            field: "status",
            sortable: true,
        },
        {
            label: "وضعیت تایید",
            field: "condition",
            sortable: true,
        },
        {
            label: "تعداد گزارش",
            field: "flag_count",
            sortable: true,
        },
        {
            label: "وضعیت نمایش",
            field: "is_published",
            sortable: true,
        },
        {
            label: "تاریخ ارسال",
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
        link: {
            text: 'مشاهده جزئیات',
            icon: 'EyeIcon',
        },
        event: {
            click: (data) => {
                router.push({
                    name: 'admin.blog.comment.detail',
                    params: {
                        id: blogId.value,
                        detail: data.id,
                    }
                })
            },
        },
    },
    {
        link: {
            text: 'حذف',
            icon: 'TrashIcon',
            class: 'text-rose-500',
        },
        event: {
            click: (data) => {
                hideAllPoppers()
                toast.clear()

                useConfirmToast(() => {
                    useRequest(apiReplaceParams(apiRoutes.admin.blogComments.destroy, {
                        blog: blogId.value,
                        comment: data.id
                    }), {
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
                    useRequest(apiReplaceParams(apiRoutes.admin.blogComments.batchDestroy, {blog: blogId.value}), {
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

    // useRequest(apiReplaceParams(apiRoutes.admin.blogComments.index, {blog: blogId.value}), {
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
    //     table.sortable.order = order
    //     table.sortable.sort = sort
    //
    //     if (tableContainer.value && tableContainer.value.card)
    //         tableContainer.value.card.scrollIntoView({behavior: "smooth"})
    // },
    // })
}

doSearch(0, 15, 'id', 'desc')

// onMounted(() => {
//     useRequest(apiReplaceParams(apiRoutes.admin.blogs.show, {blog: blogId.value}), null, {
//         success: (response) => {
//             blog.value = response.data
//         },
//     })
// })
</script>

<style scoped>

</style>
