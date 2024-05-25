<template>
  <new-creation-guide-top route-name="admin.send_method.add">
    <template #text>
      با استفاده از ستون عملیات می‌توانید اقدام به حذف و ویرایش روش‌های ارسال نمایید
    </template>
    <template
      v-if="userStore.hasPermission(PERMISSION_PLACES.PAYMENT_METHOD, PERMISSIONS.CREATE)"
      #buttonText
    >
      <PlusIcon class="w-6 h-6 ml-2 group-hover:rotate-90 transition"/>
      افزودن روش ارسال جدید
    </template>
  </new-creation-guide-top>

  <partial-card ref="tableContainer">
    <template #header>
      لیست روش‌های ارسال
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
            <template v-slot:image="{value}">
              <partial-show-image :item="value.image"/>
            </template>

            <template v-slot:price="{value}">
              <div
                v-if="value.price > 0"
                class="font-iranyekan-bold"
              >
                {{ numberFormat(value.price) }}
                <span class="text-xs text-gray-400">تومان</span>
              </div>
              <MinusIcon v-else class="w-5 h-5 text-rose-500"/>
            </template>

            <template v-slot:determine_price_by_shop_location="{value}">
              <partial-badge-publish
                :publish="!!value.determine_price_by_shop_location"
                publish-text="بله"
                unpublish-text="خیر"
              />
            </template>

            <template v-slot:only_for_shop_location="{value}">
              <partial-badge-publish
                :publish="!!value.only_for_shop_location"
                publish-text="فعال"
                unpublish-text="غیر فعال"
              />
            </template>

            <template v-slot:apply_number_of_shipments_on_price="{value}">
              <partial-badge-publish
                :publish="!!value.apply_number_of_shipments_on_price"
                publish-text="بله"
                unpublish-text="خیر"
              />
            </template>

            <template v-slot:is_published="{value}">
              <base-switch-confirmation
                :id="value.id"
                v-model="value.is_published"
                :api="SendMethodAPI"
                off-label="عدم انتشار"
                on-label="انتشار"
                update-key="is_published"
                @success="() => {datatable?.refresh()}"
              />
            </template>

            <template v-slot:priority="{value}">
              <span class="py-1 px-2 rounded bg-violet-200">{{ value.priority }}</span>
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
import {useRouter} from "vue-router";
import {useToast} from "vue-toastification";
import {computed, reactive, ref} from "vue";
import {hideAllPoppers} from "floating-vue";
import {useConfirmToast} from "@/composables/toast-helper.js";
import {MinusIcon, PlusIcon} from "@heroicons/vue/24/outline/index.js";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseDatatableMenu from "@/components/base/datatable/BaseDatatableMenu.vue";
import BaseDatatable from "@/components/base/BaseDatatable.vue";
import NewCreationGuideTop from "@/components/admin/NewCreationGuideTop.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialShowImage from "@/components/partials/filemanager/PartialShowImage.vue";
import PartialBadgePublish from "@/components/partials/PartialBadgePublish.vue";
import {SendMethodAPI} from "@/service/APIShop.js";
import {numberFormat} from "@/composables/helper.js";
import BaseSwitchConfirmation from "@/components/base/BaseSwitchConfirmation.vue";
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
      sortable: true,
      isKey: true,
    },
    {
      label: "تصویر",
      field: "image",
      sortable: true,
    },
    {
      label: "عنوان",
      field: "title",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "هزینه ارسال",
      field: "price",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "درنظرگیری هزینه ارسال فروشگاه",
      field: "determine_price_by_shop_location",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "اعمال فقط برای محل فروشگاه",
      field: "only_for_shop_location",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "اعمال هزینه ارسال به ازای هر مرسوله",
      field: "apply_number_of_shipments_on_price",
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
      label: "اولویت",
      field: "priority",
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
      label: "تصویر",
      field: "image",
      sortable: true,
    },
    {
      label: "عنوان",
      field: "title",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "هزینه ارسال",
      field: "price",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "درنظرگیری هزینه ارسال فروشگاه",
      field: "determine_price_by_shop_location",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "اعمال فقط برای محل فروشگاه",
      field: "only_for_shop_location",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "اعمال هزینه ارسال به ازای هر مرسوله",
      field: "apply_number_of_shipments_on_price",
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
      label: "اولویت",
      field: "priority",
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
      show: userStore.hasPermission(PERMISSION_PLACES.PAYMENT_METHOD, [PERMISSIONS.UPDATE, PERMISSIONS.DELETE])
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

  if (!row.is_deletable || !userStore.hasPermission(PERMISSION_PLACES.PAYMENT_METHOD, PERMISSIONS.DELETE)) {
    removals.push(['delete'])
  }
  if (!userStore.hasPermission(PERMISSION_PLACES.PAYMENT_METHOD, PERMISSIONS.UPDATE)) {
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
          name: 'admin.send_method.edit',
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

        if (!data.is_deletable) {
          toast.warning('این آیتم قابل حذف نمی‌باشد.')
          return
        }

        useConfirmToast(() => {
          SendMethodAPI.deleteById(data.id, {
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
            if (items[item].id && items[item].is_deletable)
              ids.push(items[item].id)
          }
        }

        if (!ids.length) {
          toast.info('ابتدا آیتم‌های مورد نیاز را انتخاب کنید و سپس دوباره تلاش نمایید.')
          return
        }

        toast.clear()

        useConfirmToast(() => {
          SendMethodAPI.deleteByIds(ids, {
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

  SendMethodAPI.fetchAll({limit, offset, order, sort, text}, {
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
