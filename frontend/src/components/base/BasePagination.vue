<template>
  <template v-if="theme === 'classic'">
    <ul class="flex justify-center items-stretch rtl:flex-row-reverse whitespace-nowrap no-underline flex-wrap">
      <li>
        <a v-tooltip.top="'صفحه اول'"
           class="h-full cursor-pointer bg-white relative inline-flex items-center rounded-l-md px-2 py-2 text-slate-400 ring-1 ring-inset ring-slate-200 hover:bg-slate-50 focus:z-20 focus:outline-offset-0"
           :class="{'!cursor-not-allowed !bg-slate-100 !text-slate-400': currentPage <= 1}"
           :disabled="currentPage <= 1"
           aria-label="First"
           @click.prevent="currentPage = 1"
        >
          <span aria-hidden="true"><ChevronDoubleLeftIcon class="w-5 h-5"/></span>
          <span class="sr-only">صفحه اول</span>
        </a>
      </li>
      <li>
        <a v-tooltip.top="'صفحه قبل'"
           class="h-full cursor-pointer bg-white relative inline-flex items-center px-4 py-2 text-slate-800 ring-1 ring-inset ring-slate-200 hover:bg-slate-50 focus:z-20 focus:outline-offset-0"
           :class="{'!cursor-not-allowed !bg-slate-100 !text-slate-400': currentPage <= 1}"
           :disabled="currentPage <= 1"
           aria-label="Previous"
           @click.prevent="prevPage"
        >
          <span aria-hidden="true"><ChevronLeftIcon class="w-5 h-5"/></span>
          <span class="sr-only">صفحه قبل</span>
        </a>
      </li>
      <li v-for="n in paging"
          :key="n"
      >
        <a v-tooltip.top="'صفحه ' + n"
           class="h-full cursor-pointer bg-white relative inline-flex items-center px-4 py-2 text-slate-800 ring-1 ring-inset ring-slate-200 hover:bg-slate-50 focus:z-20 focus:outline-offset-0"
           :class="{'!cursor-default !bg-primary !text-white !ring-primary': currentPage === n}"
           :disabled="currentPage === n"
           @click.prevent="movePage(n)"
        >
          {{ n }}
        </a>
      </li>
      <li>
        <a v-tooltip.top="'صفحه بعد'"
           class="h-full cursor-pointer bg-white relative inline-flex items-center px-4 py-2 text-slate-800 ring-1 ring-inset ring-slate-200 hover:bg-slate-50 focus:z-20 focus:outline-offset-0"
           :class="{'!cursor-not-allowed !bg-slate-100 !text-slate-400': currentPage >= maxPage}"
           :disabled="currentPage >= maxPage"
           aria-label="Next"
           @click.prevent="nextPage"
        >
          <span aria-hidden="true"><ChevronRightIcon class="w-5 h-5"/></span>
          <span class="sr-only">صفحه بعد</span>
        </a>
      </li>
      <li>
        <a v-tooltip.top="'صفحه آخر'"
           class="h-full cursor-pointer bg-white relative inline-flex items-center rounded-r-md px-2 py-2 text-slate-400 ring-1 ring-inset ring-slate-200 hover:bg-slate-50 focus:z-20 focus:outline-offset-0"
           :class="{'!cursor-not-allowed !bg-slate-100 !text-slate-400': currentPage >= maxPage}"
           :disabled="currentPage >= maxPage"
           aria-label="Last"
           @click.prevent="currentPage = maxPage"
        >
          <span aria-hidden="true"><ChevronDoubleRightIcon class="w-5 h-5"/></span>
          <span class="sr-only">صفحه آخر</span>
        </a>
      </li>
    </ul>
  </template>

  <template v-else-if="theme === 'modern'">
    <ul
        class="flex justify-center items-start rtl:flex-row-reverse whitespace-nowrap no-underline flex-wrap border-t border-slate-300">
      <li>
        <a v-tooltip.top="'صفحه اول'"
           class="h-full border-t-2 mt-[-1px] border-transparent hover:border-slate-400 transition cursor-pointer relative inline-flex items-center px-3 py-3 text-slate-500 hover:text-slate-900 focus:z-20 focus:outline-offset-0 text-sm"
           :class="{'!cursor-not-allowed !text-slate-300 !border-transparent': currentPage <= 1}"
           :disabled="currentPage <= 1"
           aria-label="First"
           @click.prevent="currentPage = 1"
        >
          صفحه اول
          <span class="sr-only">صفحه اول</span>
        </a>
      </li>
      <li>
        <a v-tooltip.top="'صفحه قبل'"
           class="h-full border-t-2 mt-[-1px] border-transparent hover:border-slate-400 transition cursor-pointer relative inline-flex items-center px-3 py-3 text-slate-500 hover:text-slate-900 focus:z-20 focus:outline-offset-0"
           :class="{'!cursor-not-allowed !text-slate-300 !border-transparent': currentPage <= 1}"
           :disabled="currentPage <= 1"
           aria-label="Previous"
           @click.prevent="prevPage"
        >
          <span aria-hidden="true"><ArrowLongLeftIcon class="w-5 h-5"/></span>
          <span class="sr-only">صفحه قبل</span>
        </a>
      </li>
      <li v-for="n in paging"
          :key="n"
      >
        <a v-tooltip.top="'صفحه ' + n"
           class="h-full border-t-2 mt-[-1px] border-transparent hover:border-slate-400 transition cursor-pointer relative inline-flex min-w-[36px] min-h-[36px] px-3 py-3 text-slate-500 hover:text-slate-900 focus:z-20 focus:outline-offset-0"
           :class="{'!cursor-default !border-primary !text-primary': currentPage === n}"
           :disabled="currentPage === n"
           @click.prevent="movePage(n)"
        >
          <span class="mx-auto">{{ n }}</span>
        </a>
      </li>
      <li>
        <a v-tooltip.top="'صفحه بعد'"
           class="h-full border-t-2 mt-[-1px] border-transparent hover:border-slate-400 transition cursor-pointer relative inline-flex items-center px-3 py-3 text-slate-500 hover:text-slate-900 focus:z-20 focus:outline-offset-0"
           :class="{'!cursor-not-allowed !text-slate-300 !border-transparent': currentPage >= maxPage}"
           :disabled="currentPage >= maxPage"
           aria-label="Next"
           @click.prevent="nextPage"
        >
          <span aria-hidden="true"><ArrowLongRightIcon class="w-5 h-5"/></span>
          <span class="sr-only">صفحه بعد</span>
        </a>
      </li>
      <li>
        <a v-tooltip.top="'صفحه آخر'"
           class="h-full border-t-2 mt-[-1px] border-transparent hover:border-slate-400 transition cursor-pointer relative inline-flex items-center px-3 py-3 text-slate-500 hover:text-slate-900 focus:z-20 focus:outline-offset-0 text-sm"
           :class="{'!cursor-not-allowed !text-slate-300 !border-transparent': currentPage >= maxPage}"
           :disabled="currentPage >= maxPage"
           aria-label="Last"
           @click.prevent="currentPage = maxPage"
        >
          صفحه آخر
          <span class="sr-only">صفحه آخر</span>
        </a>
      </li>
    </ul>
  </template>
</template>

<script setup>
import {
  ChevronDoubleLeftIcon,
  ChevronDoubleRightIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  ArrowLongLeftIcon,
  ArrowLongRightIcon,
} from "@heroicons/vue/24/outline/index.js";
import {computed} from "vue";

const props = defineProps({
  theme: {
    type: String,
    default: 'classic',
    validator: (value) => {
      return ['classic', 'modern'].indexOf(value) !== -1
    },
  },
  currentPage: {
    type: [Number, String],
    required: true,
  },
  paging: {
    type: Array,
    required: true,
  },
  maxPage: {
    type: [Number, String],
    required: true,
  },
  prevPage: {
    type: Function,
    required: true,
  },
  movePage: {
    type: Function,
    required: true,
  },
  nextPage: {
    type: Function,
    required: true,
  },
})
const emit = defineEmits(['update:currentPage', 'update:paging', 'update:maxPage'])

const currentPage = computed({
  get() {
    return props.currentPage
  },
  set(value) {
    emit('update:currentPage', value)
  }
})
const paging = computed({
  get() {
    return props.paging
  },
  set(value) {
    emit('update:paging', value)
  }
})
const maxPage = computed({
  get() {
    return props.maxPage
  },
  set(value) {
    emit('update:maxPage', value)
  }
})
</script>

<style scoped>

</style>
