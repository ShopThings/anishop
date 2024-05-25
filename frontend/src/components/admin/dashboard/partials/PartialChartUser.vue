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

          <Line :data="chartData" :options="chartOptions" height="300px"/>
        </div>
      </div>
    </template>
  </base-loading-panel>
</template>

<script setup>
import {onMounted, ref} from "vue";
import {
  CategoryScale,
  Chart as ChartJS,
  Legend,
  LinearScale,
  LineElement,
  PointElement,
  Title,
  Tooltip
} from 'chart.js';
import {Line} from 'vue-chartjs';
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import BaseSelect from "@/components/base/BaseSelect.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import {REPORT_PERIODS} from "@/composables/constants.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {AdminPanelDashboardAPI} from "@/service/APIAdminPanel.js";

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
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

const chartLoading = ref(true)
const chartData = ref({})
const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    title: {
      display: true,
      text: 'نمودار تعداد عضویت کاربران',
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

function getPeriodData(selected = null) {
  periodLoading.value = true

  AdminPanelDashboardAPI.getChartUsers(selectedPeriod.value.value, {
    success(response) {
      chartData.value = {
        labels: response.labels,
        datasets: [
          {
            label: response.dataset_label,
            borderColor: '#f87979',
            data: response.data,
            fill: false,
            tension: 0.4,
          },
        ],
      }

      if (selected) {
        // Assign selected period again to prevent mistaken selecting
        selectedPeriod.value = selected
      }
    },
    finally() {
      periodLoading.value = false
      chartLoading.value = false
    },
  })
}

function periodChangeHandler(selected) {
  if (periodLoading.value) return

  selectedPeriod.value = selected

  getPeriodData(selected)
}

onMounted(() => {
  getPeriodData()
})
</script>
