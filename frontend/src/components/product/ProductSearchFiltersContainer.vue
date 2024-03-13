<template>
  <div class="flex items-center p-3 gap-3 justify-between">
    <span class="font-iranyekan-bold text-xl">فیلترها</span>
    <button
        class="p-1 text-cyan-600 text-sm hover:text-opacity-80 transition"
        type="button"
        @click="clearAllFilters"
    >
      حذف فیلترها
    </button>
  </div>

  <div
      class="text-emerald-700 px-3 py-1.5 text-xs leading-relaxed bg-emerald-50"
  >
    پس از انتخاب فیلترها، از دکمه
    <span class="text-black">اعمال فیلتر</span>
    در پایین صفحه برای اعمال فیلترها، استفاده نمایید.
  </div>

  <div class="mt-3">
    <product-search-filters v-model:search-params="searchParams"/>
  </div>

  <div
      class="px-3 pb-3 pt-5 bg-white"
      :class="filterBtnClass"
  >
    <base-button
        class="w-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 hover:bg-gradient-to-bl"
        @click="filterHandler"
    >
      اعمال فیلترها
    </base-button>
  </div>
</template>

<script setup>
import {nextTick, ref} from "vue";
import ProductSearchFilters from "@/components/product/ProductSearchFilters.vue";
import BaseButton from "@/components/base/BaseButton.vue";

const props = defineProps({
  searchParams: {
    type: Object,
    default: () => {
      return {}
    },
  },
  filterBtnClass: String,
})
const emit = defineEmits(['filter'])

const searchParams = ref(props.searchParams)

function filterHandler() {
  emit('filter', searchParams.value)
}

function clearAllFilters() {
  searchParams.value = {}
  nextTick(() => {
    emit('filter', searchParams.value)
  })
}
</script>
