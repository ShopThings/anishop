<template>
  <base-tab-panel :tabs="tabs" tab-panel-extra-class="border-0">
    <template #productComments>
      <base-loading-panel :loading="productCommentLoading" type="table">
        <template #content>
          <base-semi-datatable
            pagination-theme="modern"
            :is-loading="productCommentTable.isLoading"
            :columns="productCommentTable.columns"
            :rows="productCommentTable.rows"
            :total="productCommentTable.total"
            @do-search="doSearchProductComment"
          >
            <template #emptyTableRows>
              <partial-empty-rows
                image="/empty-statuses/empty-comment.svg"
                image-class="w-64"
                message="هیچ دیدگاهی برای محصولات ثبت نشده است"
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

    <template #blogComments>
      <base-loading-panel :loading="blogCommentLoading" type="table">
        <template #content>
          <base-semi-datatable
            pagination-theme="modern"
            :is-loading="blogCommentTable.isLoading"
            :columns="blogCommentTable.columns"
            :rows="blogCommentTable.rows"
            :total="blogCommentTable.total"
            @do-search="doSearchBlogComment"
          >
            <template #emptyTableRows>
              <partial-empty-rows
                image="/empty-statuses/empty-comment.svg"
                image-class="w-64"
                message="هیچ دیدگاهی برای بلاگ ثبت نشده است"
              />
            </template>

            <template #blog="{value, index}">
              <div class="flex items-center gap-3">
                <router-link
                  :to="{name: 'blog.detail', params: {id: 1}}"
                  class="inline-block shrink-0"
                >
                  <base-lazy-image
                    alt="تصویر بلاگ"
                    :lazy-src="'/src/assets/blogs/b' + index + '.jpg'"
                    class="!w-24 h-auto hover:scale-95 transition"
                  />
                </router-link>
                <router-link
                  :to="{name: 'blog.detail', params: {id: 1}}"
                  class="inline-block text-blue-600 hover:text-opacity-90 leading-relaxed w-80"
                >
                  یه موضوع خیلی مسخره ولی جالب برای شما دوستان
                </router-link>
              </div>
            </template>

            <template #condition="{value}">
              <partial-badge-condition-comment/>
            </template>

            <template #badge="{value}">
              <partial-badge-status-blog-comment/>
            </template>

            <template #created_at="{value}">
              <span class="text-sm">۱۷ شهریور ۱۴۰۲ در ساعت ۱۹ و ۳۴ دقیقه</span>
            </template>

            <template #op="{value}">
              <router-link
                :to="{name: 'user.comment.detail.blog', params: {id: 12345}}"
                class="text-blue-600 hover:text-opacity-80 text-sm"
              >
                مشاهده جزئیات
              </router-link>
            </template>
          </base-semi-datatable>
        </template>
      </base-loading-panel>
    </template>
  </base-tab-panel>

</template>

<script setup>
import {reactive, ref} from "vue";
import BaseSemiDatatable from "@/components/base/BaseSemiDatatable.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialBadgeConditionComment from "@/components/partials/PartialBadgeConditionComment.vue";
import PartialEmptyRows from "@/components/partials/PartialEmptyRows.vue";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import BaseTabPanel from "@/components/base/BaseTabPanel.vue";
import PartialBadgeStatusBlogComment from "@/components/partials/PartialBadgeStatusBlogComment.vue";

const tabs = ref({
  productComments: {
    text: 'دیدگاه شما برای محصولات',
  },
  blogComments: {
    text: 'دیدگاه شما برای بلاگ',
  },
})

const blogCommentLoading = ref(true)
const blogCommentTable = reactive({
  isLoading: true,
  columns: [
    {
      field: 'blog',
      label: 'بلاگ',
    },
    {
      field: 'condition',
      label: 'وضعیت بررسی',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'badge',
      label: 'برچسب',
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

const doSearchBlogComment = (offset, limit) => {
  blogCommentTable.isLoading = true

  // useRequest(apiRoutes., {
  //     params: {limit, offset, order, sort, text},
  // }, {
  //     success: (response) => {
  //         blogCommentTable.rows = response.data
  //         blogCommentTable.total = response.meta.total
  //
  //         return false
  //     },
  //     error: () => {
  //         blogCommentTable.rows = []
  //         blogCommentTable.total = 0
  //     },
  //     finally: () => {
  blogCommentLoading.value = false
  blogCommentTable.isLoading = false
  //     },
  // })
}

doSearchBlogComment(0, 15)

//--------------------------------------------

const productCommentLoading = ref(true)
const productCommentTable = reactive({
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

const doSearchProductComment = (offset, limit) => {
  productCommentTable.isLoading = true

  // useRequest(apiRoutes., {
  //     params: {limit, offset, order, sort, text},
  // }, {
  //     success: (response) => {
  //         productCommentTable.rows = response.data
  //         productCommentTable.total = response.meta.total
  //
  //         return false
  //     },
  //     error: () => {
  //         productCommentTable.rows = []
  //         productCommentTable.total = 0
  //     },
  //     finally: () => {
  productCommentLoading.value = false
  productCommentTable.isLoading = false
  //     },
  // })
}

doSearchProductComment(0, 15)
</script>
