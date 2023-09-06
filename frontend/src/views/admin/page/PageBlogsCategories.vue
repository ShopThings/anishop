<template>
    <new-creation-guide-top route-name="admin.blog.category.add">
        <template #text>
            با استفاده از ستون عملیات می‌توانید اقدام به حذف و ویرایش دسته‌بندی بلاگ نمایید
        </template>
        <template #buttonText>
            <PlusIcon class="w-6 h-6 ml-2 group-hover:rotate-90 transition"/>
            افزودن دسته‌بندی بلاگ جدید
        </template>
    </new-creation-guide-top>

    <partial-card ref="tableContainer">
        <template #header>
            لیست دسته‌بندی‌های بلاگ
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
                        <template v-slot:name="{value}">

                        </template>
                        <template v-slot:keywords="{value}">

                        </template>
                        <template v-slot:show_in_menu="{value}">

                        </template>
                        <template v-slot:show_in_side_menu="{value}">

                        </template>
                        <template v-slot:is_published="{value}">

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
import {useRequest} from "../../../composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "../../../router/api-routes.js";
import {useRouter} from "vue-router";
import {useToast} from "vue-toastification";
import {computed, reactive, ref} from "vue";
import {hideAllPoppers} from "floating-vue";
import {useConfirmToast} from "../../../composables/toast-confirm.js";
import {MinusIcon, PlusIcon} from "@heroicons/vue/24/outline/index.js";
import PartialCard from "../../../components/partials/PartialCard.vue";
import BaseDatatableMenu from "../../../components/base/datatable/BaseDatatableMenu.vue";
import BaseDatatable from "../../../components/base/BaseDatatable.vue";
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import NewCreationGuideTop from "../../../components/admin/NewCreationGuideTop.vue";

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
            sortable: true,
            isKey: true,
        },
        {
            label: "نام",
            field: "name",
            sortable: true,
        },
        {
            label: "کلمات کلیدی",
            field: "keywords",
            sortable: true,
        },
        {
            label: "نمایش در منوی اصلی",
            field: "show_in_menu",
            columnClasses: 'whitespace-nowrap',
            sortable: true,
        },
        {
            label: "نمایش در منوی کناری",
            field: "show_in_side_menu",
            columnClasses: 'whitespace-nowrap',
            sortable: true,
        },
        {
            label: "وضعیت نمایش",
            field: "is_published",
            columnClasses: 'whitespace-nowrap',
            sortable: true,
        },
        {
            label: "تاریخ ایجاد",
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
            label: "نام",
            field: "name",
            sortable: true,
        },
        {
            label: "کلمات کلیدی",
            field: "keywords",
            sortable: true,
        },
        {
            label: "نمایش در منوی اصلی",
            field: "show_in_menu",
            columnClasses: 'whitespace-nowrap',
            sortable: true,
        },
        {
            label: "نمایش در منوی کناری",
            field: "show_in_side_menu",
            columnClasses: 'whitespace-nowrap',
            sortable: true,
        },
        {
            label: "وضعیت نمایش",
            field: "is_published",
            columnClasses: 'whitespace-nowrap',
            sortable: true,
        },
        {
            label: "تاریخ ایجاد",
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
        id: 'edit',
        link: {
            text: 'ویرایش',
            icon: 'PencilIcon',
        },
        event: {
            click: (data) => {
                router.push({
                    name: 'admin.blog.category.edit',
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
                    useRequest(apiReplaceParams(apiRoutes.admin.blogCategories.destroy, {blog_category: data.id}), {
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
                    useRequest(apiRoutes.admin.blogCategories.batchDestroy, {
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
    text = text || ''

    // useRequest(apiRoutes.admin.blogCategories.index, {
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
</script>

<style scoped>

</style>
