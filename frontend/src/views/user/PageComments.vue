<template>
  <base-tab-panel :tabs="tabs" tab-panel-extra-class="border-0">
    <template #productComments>
      <base-loading-panel :loading="productCommentLoading" type="table">
        <template #content>
          <base-semi-datatable
              :columns="productCommentTable.columns"
              :is-loading="productCommentTable.isLoading"
              :rows="productCommentTable.rows"
              :total="productCommentTable.total"
              pagination-theme="modern"
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
                    :to="{name: 'product.detail', params: {slug: value.product.slug}}"
                    class="inline-block shrink-0"
                >
                  <base-lazy-image
                      :alt="value.product.title"
                      :lazy-src="value.product.image.path"
                      :size="FileSizes.SMALL"
                      class="!w-24 h-auto hover:scale-95 transition"
                  />
                </router-link>
                <router-link
                    :to="{name: 'product.detail', params: {slug: value.product.slug}}"
                    class="inline-block text-blue-600 hover:text-opacity-90 leading-relaxed w-80"
                >
                  {{ value.product.title }}
                </router-link>
              </div>
            </template>

            <template #condition="{value}">
              <partial-badge-condition-comment :condition="value.condition"/>
            </template>

            <template #up_vote="{value}">
              <span class="border-2 border-emerald-500 text-sm rounded-lg px-2.5">{{
                  formatPriceLikeNumber(value.up_vote_count)
                }}</span>
              <span class="text-sm mr-2 text-gray-400">مفید</span>
            </template>

            <template #down_vote="{value}">
              <span class="border-2 border-rose-500 text-sm rounded-lg px-2.5">{{
                  formatPriceLikeNumber(value.down_vote_count)
                }}</span>
              <span class="text-sm mr-2 text-gray-400">نامرتبط</span>
            </template>

            <template #created_at="{value}">
              <span v-if="value.created_at" class="text-xs">{{ value.created_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template #op="{value}">
              <router-link
                  :to="{name: 'user.comment.detail', params: {id: value.id}}"
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
              :columns="blogCommentTable.columns"
              :is-loading="blogCommentTable.isLoading"
              :rows="blogCommentTable.rows"
              :total="blogCommentTable.total"
              pagination-theme="modern"
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
                    :to="{name: 'blog.detail', params: {slug: value.blog.slug}}"
                    class="inline-block shrink-0"
                >
                  <base-lazy-image
                      :alt="value.blog.title"
                      :lazy-src="value.blog.image.path"
                      :size="FileSizes.SMALL"
                      class="!w-24 h-auto hover:scale-95 transition"
                  />
                </router-link>
                <router-link
                    :to="{name: 'blog.detail', params: {slug: value.blog.slug}}"
                    class="inline-block text-blue-600 hover:text-opacity-90 leading-relaxed w-80"
                >
                  {{ value.blog.title }}
                </router-link>
              </div>
            </template>

            <template #condition="{value}">
              <partial-badge-condition-comment :condition="value.condition"/>
            </template>

            <template #badge="{value}">
              <partial-badge-status-blog-comment
                  :color-hex="value.badge.color_hex"
                  :text="value.badge.title"
              />
            </template>

            <template #created_at="{value}">
              <span v-if="value.created_at" class="text-xs">{{ value.created_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template #op="{value}">
              <router-link
                  :to="{name: 'user.comment.detail.blog', params: {id: value.id}}"
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
import {UserPanelBlogCommentAPI, UserPanelCommentAPI} from "@/service/APIUserPanel.js";
import {MinusIcon} from "@heroicons/vue/24/outline/index.js";
import {FileSizes} from "@/composables/file-list.js";
import {formatPriceLikeNumber} from "@/composables/helper.js";

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
  sortable: {
    order: "created_at",
    sort: "desc",
  },
})

const doSearchBlogComment = (offset, limit, order, sort, text) => {
  blogCommentTable.isLoading = true

  UserPanelBlogCommentAPI.fetchAll({limit, offset, order, sort, text}, {
    success: (response) => {
      blogCommentTable.rows = response.data
      blogCommentTable.total = response.meta.total

      return false
    },
    error: () => {
      blogCommentTable.rows = []
      blogCommentTable.total = 0
    },
    finally: () => {
      blogCommentLoading.value = false
      blogCommentTable.isLoading = false
    },
  })
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
  sortable: {
    order: "created_at",
    sort: "desc",
  },
})

const doSearchProductComment = (offset, limit, order, sort, text) => {
  productCommentTable.isLoading = true

  UserPanelCommentAPI.fetchAll({limit, offset, order, sort, text}, {
    success: (response) => {
      productCommentTable.rows = response.data
      productCommentTable.total = response.meta.total

      return false
    },
    error: () => {
      productCommentTable.rows = []
      productCommentTable.total = 0
    },
    finally: () => {
      productCommentLoading.value = false
      productCommentTable.isLoading = false
    },
  })
}

doSearchProductComment(0, 15)
</script>
