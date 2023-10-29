<template>
    <base-loading-panel :loading="loading" type="table">
        <template #content>
            <base-semi-datatable
                :is-loading="table.isLoading"
                :columns="table.columns"
                :rows="table.rows"
                :total="table.total"
                @do-search="doSearch"
            >
                <template #emptyTableRows>
                    <partial-empty-rows
                        image="/empty-statuses/empty-comment.svg"
                        image-class="w-64"
                        message="هیچ دیدگاهی ثبت نشده است"
                    />
                </template>

                <template #product="{value, index}">
                    <div class="flex items-center gap-3">
                        <router-link
                            :to="{name: 'product.detail', params: {id: 1}}"
                            class="inline-block shrink-0"
                        >
                            <base-lazy-image
                                alt="تصویر محصول"
                                :lazy-src="'/src/assets/products/p' + index + '.jpg'"
                                class="!w-24 h-auto hover:scale-95 transition"
                            />
                        </router-link>
                        <router-link
                            :to="{name: 'product.detail', params: {id: 1}}"
                            class="inline-block text-blue-600 hover:text-opacity-90 leading-relaxed w-80"
                        >
                            ماوس آبکی و مسخره‌ای که خیلی قیمت نداره
                        </router-link>
                    </div>
                </template>

                <template #condition="{value}">
                    <partial-badge-condition-comment/>
                </template>

                <template #up_vote="{value}">
                    <span class="border-2 border-emerald-500 text-sm rounded-lg px-2.5">8</span>
                    <span class="text-sm mr-2 text-gray-400">مفید</span>
                </template>

                <template #down_vote="{value}">
                    <span class="border-2 border-rose-500 text-sm rounded-lg px-2.5">1</span>
                    <span class="text-sm mr-2 text-gray-400">نامرتبط</span>
                </template>

                <template #created_at="{value}">
                    <span class="text-sm">۱۷ شهریور ۱۴۰۲ در ساعت ۱۹ و ۳۴ دقیقه</span>
                </template>

                <template #op="{value}">
                    <router-link
                        :to="{name: 'user.comment.detail', params: {id: 12345}}"
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
import {reactive, ref} from "vue";
import BaseSemiDatatable from "../../components/base/BaseSemiDatatable.vue";
import BaseLoadingPanel from "../../components/base/BaseLoadingPanel.vue";
import PartialBadgeConditionComment from "../../components/partials/PartialBadgeConditionComment.vue";
import PartialEmptyRows from "../../components/partials/PartialEmptyRows.vue";
import BaseLazyImage from "../../components/base/BaseLazyImage.vue";

const loading = ref(true)
const table = reactive({
    isLoading: true,
    columns: [
        {
            field: 'product',
            label: 'محصول',
        },
        {
            field: 'condition',
            label: 'وضعیت بررسی',
            columnClasses: 'whitespace-nowrap',
        },
        {
            field: 'up_vote',
            label: '',
            cellClasses: 'text-center',
            columnClasses: 'whitespace-nowrap',
        },
        {
            field: 'down_vote',
            label: '',
            cellClasses: 'text-center',
            columnClasses: 'whitespace-nowrap',
        },
        {
            field: 'created_at',
            label: 'تاریخ ارسال',
            columnClasses: 'whitespace-nowrap',
        },
        {
            field: 'op',
            label: 'عملیات',
            columnClasses: 'whitespace-nowrap',
        },
    ],
    rows: [],
    total: 0,
})

const doSearch = (offset, limit) => {
    table.isLoading = true

    // useRequest(apiRoutes., {
    //     params: {limit, offset, order, sort, text},
    // }, {
    //     success: (response) => {
    //         table.rows = response.data
    //         table.total = response.meta.total
    //
    //         return false
    //     },
    //     error: () => {
    //         table.rows = []
    //         table.total = 0
    //     },
    //     finally: () => {
    loading.value = false
    table.isLoading = false
    //     },
    // })
}

doSearch(0, 12)
</script>

<style scoped>

</style>
