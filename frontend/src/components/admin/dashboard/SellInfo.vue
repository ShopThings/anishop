<template>
  <div class="flex flex-col sm:flex-row items-end justify-center gap-6 sm:gap-4">
    <div class="flex flex-col gap-3 w-full">
      <div class="bg-amber-500 rounded-lg w-full">
        <base-loading-panel :loading="allAmountsLoading">
          <template #loader>
            <div class="flex flex-col gap-3 p-5">
              <loader-circle
                big-circle-color="border-transparent"
                container-bg-color=""
                main-container-klass="relative w-full h-8"
                small-circle-color="border-t-white"
              />
              <span class="text-xs text-center text-white/60">در حال بارگذاری مجموع خرید</span>
            </div>
          </template>

          <template #content>
            <div class="flex items-center p-5 gap-3">
              <div class="flex flex-col gap-3.5">
                <div class="flex flex-wrap items-center gap-2">
                  <span class="text-xl text-white">300,000,000</span>
                  <span class="text-sm text-amber-100">تومان</span>
                </div>

                <span class="text-amber-200 text-sm">مجموع خریدهای انجام شده تاکنون</span>
              </div>

              <CurrencyDollarIcon class="size-12 text-white/60 mr-auto"/>
            </div>
          </template>
        </base-loading-panel>
      </div>
    </div>

    <div class="flex flex-col gap-3 w-full">
      <div class="flex flex-wrap gap-1.5 items-center">
        <partial-input-label space="mb-1 sm:ml-1 sm:mb-0" title="نمایش در دوره"/>
        <base-select
          :options="periods"
          :selected="selectedPeriod"
          btn-space-class="py-1.5 px-3"
          options-key="value"
          options-text="text"
          optionsClass="mt-1 right-0"
          @change="periodChangeHandler"
        />
      </div>

      <div class="bg-purple-500 rounded-lg w-full">
        <base-loading-panel :loading="periodAmountsLoading">
          <template #loader>
            <div class="flex flex-col gap-3 p-5">
              <loader-circle
                big-circle-color="border-transparent"
                container-bg-color=""
                main-container-klass="relative w-full h-8"
                small-circle-color="border-t-white"
              />
              <span class="text-xs text-center text-white/60">
                در حال بارگذاری مجموع خرید
                {{ selectedPeriod?.text }}
              </span>
            </div>
          </template>

          <template #content>
            <div class="flex items-center p-5 gap-3">
              <div class="flex flex-col gap-3.5">
                <div class="flex flex-wrap items-center gap-2">
                  <span class="text-xl text-white">300,000,000</span>
                  <span class="text-sm text-purple-100">تومان</span>
                </div>

                <span class="text-purple-200 text-sm">
                  مجموع خریدهای انجام شده
                  {{ selectedPeriod?.text }}
                </span>
              </div>

              <ChartPieIcon class="size-12 text-white/60 mr-auto"/>
            </div>
          </template>
        </base-loading-panel>
      </div>
    </div>
  </div>
</template>

<script setup>
import {ChartPieIcon, CurrencyDollarIcon} from "@heroicons/vue/24/outline/index.js"
import BaseSelect from "@/components/base/BaseSelect.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import {onMounted, ref} from "vue";
import {REPORT_PERIODS} from "@/composables/constants.js";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";

const allAmountsLoading = ref(true)
const periodAmountsLoading = ref(true)

const periods = [
  {
    value: REPORT_PERIODS.TODAY.value,
    text: REPORT_PERIODS.TODAY.text,
  },
  {
    value: REPORT_PERIODS.WEEKLY.value,
    text: REPORT_PERIODS.WEEKLY.text,
  },
  {
    value: REPORT_PERIODS.MONTHLY.value,
    text: REPORT_PERIODS.MONTHLY.text,
  },
  {
    value: REPORT_PERIODS.MONTHS_6.value,
    text: REPORT_PERIODS.MONTHS_6.text,
  },
  {
    value: REPORT_PERIODS.YEARLY.value,
    text: REPORT_PERIODS.YEARLY.text,
  },
]
const selectedPeriod = ref({
  value: REPORT_PERIODS.TODAY.value,
  text: REPORT_PERIODS.TODAY.text,
})

function periodChangeHandler(selected) {
  if (periodAmountsLoading.value) return

  selectedPeriod.value = selected

  // TODO: make an API call to get what we needed...
}

onMounted(() => {
  // TODO: get amount of sells of all time...

  setTimeout(() => {
    periodAmountsLoading.value = false
  }, 10000)
})
</script>
