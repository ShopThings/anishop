<template>
  <div class="px-3 py-5 flex gap-3 mb-6 bg-white rounded-lg shadow-sm text-sm">
    <StarIcon class="w-5 h-5 text-orange-600 shrink-0"/>
    <div class="text-orange-600">
      لطفا قبل از مرجوع نمودن سفارش، صفحه
      <router-link
        :to="{name: 'pages', params: {url: 'how-to-return-order'}}"
        class="mx-1.5 underline underline-offset-8 text-black hover:text-opacity-90 transition"
        target="_blank"
      >
        نحوه مرجوع نمودن سفارش
      </router-link>
      را مطالعه نمایید.
    </div>
  </div>

  <base-message
    :has-close="false"
    class="rounded-lg"
    type="info"
  >
    <h3 class="font-iranyekan-bold text-base mb-3">
      نکات قابل توجه
    </h3>
    <ul class="leading-relaxed flex flex-col gap-3 list-inside list-disc">
      <li>
        سفارشاتی قابلیت مرجوع شدن را دارند که
        <span class="underline underline-offset-8">به مشتری تحویل داده شده</span>
        ، پرداخت آن به صورت کامل انجام شده باشد و تا حداکثر یک هفته از خرید آنها میگذرد.
      </li>
      <li>
        پس از ثبت درخواست، برای وارد نمودن علت مرجوع نمودن یا حذف درخواست، وارد مشاهده جزئیات شوید.
      </li>
    </ul>
  </base-message>

  <form @submit.prevent="onSubmit">
    <div class="flex flex-col sm:flex-row gap-2 items-end mb-3">
      <div class="w-full sm:w-[28rem]">
        <partial-input-label title="انتخاب سفارش جهت مرجوع نمودن"/>
        <base-select-searchable
          :is-loading="ordersLoading"
          :options="orders"
          :selected="selectedOrder"
          options-text="code"
          options-key="code"
          @change="(selected) => {selectedOrder = selected}"
        >
          <template #item="{item}">
            <span class="tracking-widest">{{ item.code }}</span>
          </template>
        </base-select-searchable>
        <partial-input-error-message :error-message="errors.order"/>
      </div>

      <base-button
        :disabled="!canSubmit"
        class="flex items-center justify-center gap-2 bg-primary group text-sm shrink-0 w-full sm:w-auto"
        type="submit"
      >
        <VTransitionFade>
          <loader-circle
            v-if="!canSubmit"
            big-circle-color="border-transparent"
            main-container-klass="absolute w-full h-full top-0 left-0"
          />
        </VTransitionFade>

        <span class="mx-auto">مرجوع نمودن سفارش</span>
        <ArrowLeftIcon class="w-6 h-6 group-hover:-translate-x-1.5 transition"/>
      </base-button>
    </div>
  </form>

  <base-loading-panel :loading="returnOrdersTableLoading" type="table">
    <template #content>
      <partial-general-title title="درخواست‌های ثبت شده"/>

      <base-semi-datatable
        :columns="returnOrdersTableSetting.columns"
        :is-loading="returnOrdersTableSetting.isLoading"
        :rows="returnOrdersTableSetting.rows"
        :total="returnOrdersTableSetting.total"
        :sortable="returnOrdersTableSetting.sortable"
        pagination-theme="modern"
        @do-search="getReturnOrders"
      >
        <template #code="{value}">
          <span class="tracking-widest font-iranyekan-bold">{{ value.code }}</span>
        </template>

        <template #order_code="{value}">
          <router-link
            :to="{name: 'user.order.detail', params: {code: value.order_code}}"
            class="text-blue-600 hover:text-opacity-80 text-sm"
            target="_blank"
          >
            <span class="tracking-widest font-iranyekan-bold">{{ value.order_code }}</span>
          </router-link>
        </template>

        <template #status="{value}">
          <partial-badge-status-return-order
            :color-hex="value.status.color_hex"
            :text="value.status.text"
          />
        </template>

        <template #requested_at="{value}">
          <span v-if="value.requested_at" class="text-xs">{{ value.requested_at }}</span>
          <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
        </template>

        <template #op="{value}">
          <router-link
            :to="{name: 'user.return_order.detail', params: {code: value.code}}"
            class="text-blue-600 hover:text-opacity-80 text-sm whitespace-nowrap"
          >
            مشاهده جزئیات
          </router-link>
        </template>
      </base-semi-datatable>
    </template>
  </base-loading-panel>
</template>

<script setup>
import {onMounted, reactive, ref} from "vue";
import {ArrowLeftIcon, MinusIcon, StarIcon} from "@heroicons/vue/24/outline/index.js";
import BaseMessage from "@/components/base/BaseMessage.vue";
import BaseSelectSearchable from "@/components/base/BaseSelectSearchable.vue";
import {useUserAuthStore} from "@/store/StoreUserAuth.js";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseSemiDatatable from "@/components/base/BaseSemiDatatable.vue";
import PartialBadgeStatusReturnOrder from "@/components/partials/PartialBadgeStatusReturnOrder.vue";
import PartialGeneralTitle from "@/components/partials/PartialGeneralTitle.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {UserPanelReturnOrderAPI} from "@/service/APIUserPanel.js";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";

const store = useUserAuthStore()
const user = store.getUser

const orders = ref([])
const ordersLoading = ref(true)
const selectedOrder = ref(null)

const returnOrdersTableLoading = ref(true)
const returnOrdersTableSetting = reactive({
  isLoading: true,
  columns: [
    {
      field: 'code',
      label: 'کد درخواست',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'order_code',
      label: 'کد سفارش',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'status',
      label: 'وضعیت',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'requested_at',
      label: 'تاریخ درخواست',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'op',
      label: 'عملیات',
    },
  ],
  rows: [],
  total: 0,
  sortable: {
    order: "requested_at",
    sort: "desc",
  },
})
const getReturnOrders = (offset, limit, order, sort, text) => {
  returnOrdersTableSetting.isLoading = true

  UserPanelReturnOrderAPI.fetchAll({limit, offset, order, sort, text}, {
    success: (response) => {
      returnOrdersTableSetting.rows = response.data
      returnOrdersTableSetting.total = response.meta.total

      return false
    },
    error: () => {
      returnOrdersTableSetting.rows = []
      returnOrdersTableSetting.total = 0
    },
    finally: () => {
      returnOrdersTableLoading.value = false
      returnOrdersTableSetting.isLoading = false
    },
  })
}

getReturnOrders(0, 15)

//--------------------------------------------
const {canSubmit, errors, onSubmit} = useFormSubmit({}, (values, actions) => {
  if (!selectedOrder.value?.code) {
    actions.setFieldError('order', 'سفارش جهت ثبت درخواست مرجوع را انتخاب نمایید.')
    return
  }

  canSubmit.value = false

  UserPanelReturnOrderAPI.create(selectedOrder.value.code, {
    success() {
      getReturnOrders(0, 15)
    },
    error(error) {
      if (error?.errors && Object.keys(error.errors).length >= 1) {
        actions.setErrors(error.errors)
      }
    },
    finally() {
      canSubmit.value = true
    },
  })
})

onMounted(() => {
  UserPanelReturnOrderAPI.fetchReturnableOrders({
    success: (response) => {
      orders.value = response.data
      ordersLoading.value = false
    },
  })
})
</script>
