<template>
    <new-creation-guide-top route-name="admin.category.add">
        <template #text>
            با استفاده از ستون عملیات می‌توانید اقدام به حذف و ویرایش دسته‌بندی نمایید
        </template>
        <template #buttonText>
            <PlusIcon class="w-6 h-6 ml-2 group-hover:rotate-90 transition"/>
            افزودن دسته‌بندی جدید
        </template>
    </new-creation-guide-top>

    <div class="flex flex-wrap gap-3 mb-3">
        <div class="grow">
            <partial-card-navigation
                :to="{name: 'admin.category_images'}"
                bg-color="bg-gradient-to-r from-cyan-500 to-indigo-500"
            >
                <span class="text-white text-lg grow">تصاویر دسته‌بندی‌ها</span>
                <PhotoIcon class="h-12 w-12 text-white text-opacity-50 mr-3"/>
            </partial-card-navigation>
        </div>
    </div>

    <partial-card ref="tableContainer">
        <template #header>
            لیست دسته‌بندی‌ها
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
                        <template v-slot:parent_name="{value}">

                        </template>
                        <template v-slot:show_in_menu="{value}">

                        </template>
                        <template v-slot:show_in_search_side_menu="{value}">

                        </template>
                        <template v-slot:show_in_slider="{value}">

                        </template>
                        <template v-slot:is_published="{value}">

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
import {PlusIcon, PhotoIcon} from "@heroicons/vue/24/outline/index.js"
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
import PartialCardNavigation from "../../../components/partials/PartialCardNavigation.vue";

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
            label: "نام",
            field: "name",
            sortable: true,
        },
        {
            label: "نام والد",
            field: "parent_name",
            sortable: true,
        },
        {
            label: "اولویت",
            field: "priority",
        },
        {
            label: "نمایش در منو",
            field: "show_in_menu",
        },
        {
            label: "نمایش در منو صفحه جستجو",
            field: "show_in_search_side_menu",
        },
        {
            label: "نمایش در اسلایدر دسته‌بندی‌ها",
            field: "show_in_slider",
        },
        {
            label: "وضعیت نمایش",
            field: "is_published",
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
            label: "نام والد",
            field: "parent_name",
            sortable: true,
        },
        {
            label: "اولویت",
            field: "priority",
        },
        {
            label: "نمایش در منو",
            field: "show_in_menu",
        },
        {
            label: "نمایش در منو صفحه جستجو",
            field: "show_in_search_side_menu",
        },
        {
            label: "نمایش در اسلایدر دسته‌بندی‌ها",
            field: "show_in_slider",
        },
        {
            label: "وضعیت نمایش",
            field: "is_published",
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
                    name: 'admin.category.edit',
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
                    useRequest(apiReplaceParams(apiRoutes.admin.categories.destroy, {category: data.id}), {
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
                    useRequest(apiRoutes.admin.categories.batchDestroy, {
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

    // useRequest(apiRoutes.admin.categories.index, {
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
</script>

<style scoped>

</style>
