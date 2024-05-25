<template>
  <new-creation-guide-top route-name="admin.coupon.add">
    <template #text>
      با استفاده از ستون عملیات می‌توانید اقدام به حذف و ویرایش کوپن تخفیف نمایید
    </template>
    <template
      v-if="userStore.hasPermission(PERMISSION_PLACES.COUPON, PERMISSIONS.CREATE)"
      #buttonText
    >
      <PlusIcon class="w-6 h-6 ml-2 group-hover:rotate-90 transition"/>
      افزودن کوپن تخفیف جدید
    </template>
  </new-creation-guide-top>

  <partial-card ref="tableContainer">
    <template #header>
      لیست کوپن‌های تخفیف
    </template>

    <template #body>
      <base-loading-panel :loading="loading" type="table">
        <template #content>
          <base-datatable
            ref="datatable"
            :columns="table.columns"
            :enable-multi-operation="true"
            :enable-search-box="true"
            :has-checkbox="true"
            :is-loading="table.isLoading"
            :is-slot-mode="true"
            :rows="table.rows"
            :selection-columns="table.selectionColumns"
            :selection-operations="selectionOperations"
            :sortable="table.sortable"
            :total="table.totalRecordCount"
            @do-search="doSearch"
          >
            <template v-slot:code="{value}">
              <span class="rounded-lg py-1 px-2 tracking-widest text-black bg-teal-200">{{ value.code }}</span>
            </template>

            <template v-slot:price="{value}">
              <span class="text-black ml-1.5">{{ numberFormat(value.price) }}</span>
              <span class="text-xs text-gray-400">تومان</span>
            </template>

            <template v-slot:start_at="{value}">
              <span v-if="value.start_at" class="text-xs">{{ value.start_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template v-slot:end_at="{value}">
              <span v-if="value.end_at" class="text-xs">{{ value.end_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template v-slot:apply_min_price="{value}">
              <template v-if="value.apply_min_price">
                <span class="text-black ml-1.5">{{ numberFormat(value.apply_min_price) }}</span>
                <span class="text-xs text-gray-400">تومان</span>
              </template>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template v-slot:apply_max_price="{value}">
              <template v-if="value.apply_max_price">
                <span class="text-black ml-1.5">{{ numberFormat(value.apply_max_price) }}</span>
                <span class="text-xs text-gray-400">تومان</span>
              </template>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template v-slot:use_count="{value}">
              <span class="text-black text-base px-2.5 rounded-lg border-2 border-teal-300 bg-teal-50">{{
                  value.used_count
                }}</span>
              از
              <span class="text-black text-base px-2.5 rounded-lg border-2 border-teal-300 bg-teal-50">{{
                  value.use_count
                }}</span>
              عدد استفاده شده
            </template>

            <template v-slot:reusable_after="{value}">
              <span class="text-black text-base px-2.5 rounded-lg border-2 border-teal-300 bg-teal-50">{{
                  value.reusable_after
                }}</span>
              روز
            </template>

            <template v-slot:is_published="{value}">
              <partial-badge-publish :publish="!!value.is_published"/>
            </template>

            <template v-slot:created_at="{value}">
              <span v-if="value.created_at" class="text-xs">{{ value.created_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template v-slot:op="{value}">
              <base-datatable-menu
                :container="getMenuContainer"
                :data="value"
                :items="operations"
                :removals="calcRemovals(value)"
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
import {MinusIcon, PlusIcon} from "@heroicons/vue/24/outline/index.js"
import BaseDatatable from "@/components/base/BaseDatatable.vue"
import NewCreationGuideTop from "@/components/admin/NewCreationGuideTop.vue"
import BaseDatatableMenu from "@/components/base/datatable/BaseDatatableMenu.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import {useRouter} from "vue-router";
import {useToast} from "vue-toastification";
import {hideAllPoppers} from "floating-vue";
import {useConfirmToast} from "@/composables/toast-helper.js";
import {CouponAPI} from "@/service/APIShop.js";
import PartialBadgePublish from "@/components/partials/PartialBadgePublish.vue";
import {numberFormat} from "@/composables/helper.js";
import {PERMISSION_PLACES, PERMISSIONS, useAdminAuthStore} from "@/store/StoreUserAuth.js";

const router = useRouter()
const toast = useToast()

const userStore = useAdminAuthStore()

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
      label: "عنوان",
      field: "title",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "کد",
      field: "code",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "قیمت",
      field: "price",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "تاریخ شروع",
      field: "start_at",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "تاریخ پایان",
      field: "end_at",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "حداقل قیمت اعمال",
      field: "apply_min_price",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "حداکثر قیمت اعمال",
      field: "apply_max_price",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "تعداد استفاده شده/کل(بر حسب روز)",
      field: "use_count",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "قابل استفاده پس از(بر حسب روز)",
      field: "reusable_after",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "وضعیت نمایش",
      field: "is_published",
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
      label: "عنوان",
      field: "title",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "کد",
      field: "code",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "قیمت",
      field: "price",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "تاریخ شروع",
      field: "start_at",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "تاریخ پایان",
      field: "end_at",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "حداقل قیمت اعمال",
      field: "apply_min_price",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "حداکثر قیمت اعمال",
      field: "apply_max_price",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "تعداد استفاده شده/کل(بر حسب روز)",
      field: "use_count",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "قابل استفاده پس از(بر حسب روز)",
      field: "reusable_after",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "وضعیت نمایش",
      field: "is_published",
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
      show: userStore.hasPermission(PERMISSION_PLACES.COUPON, [PERMISSIONS.UPDATE, PERMISSIONS.DELETE])
    },
  ],
  rows: [],
  totalRecordCount: 0,
  sortable: {
    order: "id",
    sort: "desc",
  },
})

function calcRemovals(row) {
  let removals = []

  if (!row.is_deletable || !userStore.hasPermission(PERMISSION_PLACES.COUPON, PERMISSIONS.DELETE)) {
    removals.push(['delete'])
  }
  if (!userStore.hasPermission(PERMISSION_PLACES.COUPON, PERMISSIONS.UPDATE)) {
    removals.push(['edit'])
  }

  return removals
}

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
          name: 'admin.coupon.edit',
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

        useConfirmToast(() => {
          CouponAPI.deleteById(data.id, {
            success() {
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
          CouponAPI.deleteByIds(ids, {
            success() {
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

  CouponAPI.fetchAll({limit, offset, order, sort, text}, {
    success(response) {
      table.rows = response.data
      table.totalRecordCount = response.meta.total

      return false
    },
    error() {
      table.rows = []
      table.totalRecordCount = 0
    },
    finally() {
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
