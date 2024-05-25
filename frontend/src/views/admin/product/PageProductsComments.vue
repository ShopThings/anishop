<template>
  <partial-card ref="tableContainer">
    <template #header>
      لیست دیدگاه‌های محصول
    </template>

    <template #body>
      <base-loading-panel :loading="loading" type="table">
        <template #content>
          <base-datatable
            ref="datatable"
            :columns="table.columns"
            :enable-multi-operation="false"
            :enable-search-box="true"
            :has-checkbox="true"
            :is-loading="table.isLoading"
            :is-slot-mode="true"
            :rows="table.rows"
            :sortable="table.sortable"
            :total="table.totalRecordCount"
            @do-search="doSearch"
          >
            <template v-slot:product="{value}">
              <div class="flex flex-col gap-3">
                <base-lazy-image
                  :alt="value.product.title"
                  :lazy-src="value.product.image.path"
                  :size="FileSizes.SMALL"
                  :is-local="false"
                  class="!h-28 sm:!h-20 w-auto rounded"
                />
                <router-link
                  :to="{name: 'admin.product.detail', params: {slug: value.product.slug}}"
                  class="text-blue-600 hover:text-opacity-90"
                  target="_blank"
                >
                  {{ value.product.title }}
                </router-link>
              </div>
            </template>

            <template v-slot:sender="{value}">
              <router-link
                :to="{name: 'admin.user.profile', params: {id: value.created_by.id}}"
                class="text-blue-600 hover:text-opacity-90"
              >
                <partial-username-label :user="value.created_by"/>
              </router-link>
            </template>

            <template v-slot:status="{value}">
              <partial-badge-seen-status-comment :status="value.status"/>
            </template>

            <template v-slot:condition="{value}">
              <partial-badge-condition-comment :condition="value.condition"/>
            </template>

            <template v-slot:flag_count="{value}">
              <span class="rounded py-1 px-2 bg-rose-200">{{ value.flag_count }}</span>
            </template>

            <template v-slot:created_at="{value}">
              <span v-if="value.created_at" class="text-xs">{{ value.created_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template v-slot:answered_by="{value}">
              <router-link
                v-if="value.answered_by"
                :to="{name: 'admin.user.profile', params: {id: value.answered_by.id}}"
                class="text-blue-600 hover:text-opacity-80"
              >
                <partial-username-label :user="value.answered_by"/>
              </router-link>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template v-slot:answered_at="{value}">
              <span v-if="value.answered_at" class="text-xs">{{ value.answered_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template v-slot:op="{value}">
              <base-datatable-menu :container="getMenuContainer" :data="value" :items="operations"/>
            </template>
          </base-datatable>
        </template>
      </base-loading-panel>
    </template>
  </partial-card>
</template>

<script setup>
import {computed, reactive, ref} from "vue";
import {useRouter} from "vue-router";
import {useToast} from "vue-toastification";
import {hideAllPoppers} from "floating-vue";
import {useConfirmToast} from "@/composables/toast-helper.js";
import {MinusIcon} from "@heroicons/vue/24/outline/index.js";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseDatatableMenu from "@/components/base/datatable/BaseDatatableMenu.vue";
import BaseDatatable from "@/components/base/BaseDatatable.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {CommentAPI} from "@/service/APIProduct.js";
import PartialBadgeConditionComment from "@/components/partials/PartialBadgeConditionComment.vue";
import PartialBadgeSeenStatusComment from "@/components/partials/PartialBadgeSeenStatusComment.vue";
import PartialUsernameLabel from "@/components/partials/PartialUsernameLabel.vue";
import {FileSizes} from "@/composables/file-list.js";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";

const router = useRouter()
const toast = useToast()

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
      label: "محصول",
      field: "product",
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
      label: "تاریخ ارسال",
      field: "created_at",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "پاسخ توسط",
      field: "answered_by",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "پاسخ در تاریخ",
      field: "answered_at",
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
          name: 'admin.product.comment.detail',
          params: {
            slug: data.product.slug,
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
          CommentAPI.deleteById(data.product.slug, data.id, {
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

  CommentAPI.fetchAllComments({limit, offset, order, sort, text}, {
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
