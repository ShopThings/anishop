<template>
  <base-loading-panel
    :loading="chartLoading"
    type="chart"
  >
    <template #content>
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

        <div class="relative">
          <VTransitionFade>
            <loader-circle
              v-if="periodLoading"
              big-circle-color="border-transparent"
              main-container-klass="absolute w-full h-full top-0 left-0"
              spinner-klass="!h-6 !w-6"
            />
          </VTransitionFade>

          <Bar :data="chartData" :options="chartOptions" height="350px"/>
        </div>
      </div>
    </template>
  </base-loading-panel>
</template>

<script setup>
import {BarElement, CategoryScale, Chart as ChartJS, Legend, LinearScale, PointElement, Title, Tooltip} from "chart.js";
import {Bar} from 'vue-chartjs'
import {onMounted, ref} from "vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {REPORT_PERIODS} from "@/composables/constants.js";
import BaseSelect from "@/components/base/BaseSelect.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  BarElement,
  Title,
  Tooltip,
  Legend
)

const periodLoading = ref(false)

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
    value: REPORT_PERIODS.MONTHS_3.value,
    text: REPORT_PERIODS.MONTHS_3.text,
  },
]
const selectedPeriod = ref({
  value: REPORT_PERIODS.TODAY.value,
  text: REPORT_PERIODS.TODAY.text,
})

const chartLoading = ref(true)
const chartData = ref({})
const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    title: {
      display: true,
      text: 'نمودار تعداد سفارشات نسبت به وضعیت (به صورت متغیر)',
      font: {
        family: 'font-iranyekan-regular',
        size: 14,
      },
    },

    legend: {
      display: true,
      rtl: true,
      labels: {
        usePointStyle: true,
        font: {
          family: 'font-iranyekan-regular',
        },
      },
    },

    tooltip: {
      rtl: true,
      titleFont: {
        family: 'font-iranyekan-regular',
      },
      bodyFont: {
        family: 'font-iranyekan-regular',
      },
      padding: 12,
      cornerRadius: 10,
    },
  },
  interaction: {
    intersect: false,
  },
}

function periodChangeHandler(selected) {
  if (periodLoading.value) return

  selectedPeriod.value = selected

  // TODO: make an API call to get what we needed...
}

onMounted(() => {
  setTimeout(() => {
    chartLoading.value = false

    chartData.value = {
      labels: [
        'January', 'February', 'March',
        'April', 'May', 'June',
        'July', 'August', 'September',
        'October', 'November', 'December'
      ],
      datasets: [
        {
          label: 'در حال بررسی (امروز)',
          backgroundColor: '#f87979',
          data: [40, 20, 12, 39, 10, 40, 39, 80, 40, 20, 12, 11]
        }
      ]
    }
  }, 2000)
})
</script>
