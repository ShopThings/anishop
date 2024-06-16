<template>
  <form class="px-3 pt-3" @submit.prevent="onSubmit">
    <div class="flex flex-col sm:flex-row sm:items-start gap-3 mb-3">
      <div class="w-full sm:w-96 relative">
        <base-input
            :klass="showRemoveFilterButtonOnInput ? 'pl-10' : ''"
            name="search"
            placeholder="عبارت جستجو را وارد کنید..."
            :has-clear-button="false"
            @focus="checkFilterButtonAppearance"
            @input="checkFilterButtonAppearance"
        >
          <template #icon>
            <MagnifyingGlassCircleIcon class="w-6 h-6 text-gray-400"/>
          </template>
        </base-input>

        <button
            v-if="showRemoveFilterButtonOnInput && showInputRemoveFilterButton"
            v-tooltip.bottom="'حذف فیلتر'"
            class="absolute top-0 translate-y-1 left-0 p-2 group"
            type="button"
            @click="clearFilter"
        >
          <XMarkIcon class="w-6 h-6 text-gray-400 group-hover:text-gray-500 transition"/>
        </button>
      </div>
      <div class="flex shrink-0">
        <div class="flex grow">
          <base-button
              v-tooltip.top="'انجام جستجو'"
              :class="[
                showRemoveFilterButtonOnInput ? 'rounded-lg' : 'rounded-r-lg rounded-l-none',
            ]"
              class="grow bg-primary border-blue-200 text-sm"
              type="submit"
          >
            <div class="flex items-center justify-center gap-2 sm:py-[3px]">
              <MagnifyingGlassIcon class="size-5 sm:size-6 shrink-0"/>
              <span class="sm:hidden">انجام جستجو</span>
            </div>
          </base-button>
          <base-button
              v-if="!showRemoveFilterButtonOnInput"
              v-tooltip.top="'حذف فیلتر'"
              class="bg-white !text-black grow border border-r-0 rounded-l-lg rounded-r-none text-sm hover:bg-slate-100"
              @click="clearFilter"
          >
            <div class="flex items-center justify-center gap-2 sm:py-[3px]">
              <div class="relative">
                <span
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-8 h-[2px] -rotate-45 bg-rose-500"></span>
                <FunnelIcon class="size-5 sm:size-6 shrink-0"/>
              </div>
              <span class="sm:hidden">حذف فیلتر</span>
            </div>
          </base-button>
        </div>
        <base-animated-button
            v-if="showRefreshButton"
            v-tooltip.top="'بارگذاری مجدد'"
            class="bg-white !text-black border border-slate-300 !rounded-full text-sm mr-3 shrink-0 hover:bg-slate-100"
            @click="emit('refresh')"
        >
          <template #icon="{klass}">
            <ArrowPathIcon :class="klass" class="h-6 w-6 my-[.12rem]"/>
          </template>
        </base-animated-button>
      </div>
    </div>
  </form>
</template>

<script setup>
import {ref} from "vue";
import {
  ArrowPathIcon,
  FunnelIcon,
  MagnifyingGlassCircleIcon,
  MagnifyingGlassIcon,
  XMarkIcon,
} from '@heroicons/vue/24/outline';
import {useForm} from "vee-validate";
import yup from "@/validation/index.js";
import BaseInput from "../BaseInput.vue";
import BaseButton from "../BaseButton.vue";
import BaseAnimatedButton from "../BaseAnimatedButton.vue";

defineProps({
  showRemoveFilterButtonOnInput: Boolean,
  showRefreshButton: {
    type: Boolean,
    default: true,
  },
})
const emit = defineEmits(['search', 'clear-filter', 'refresh'])

const showInputRemoveFilterButton = ref(false)

function checkFilterButtonAppearance(text) {
  showInputRemoveFilterButton.value = text !== '';
}

const {handleSubmit, resetField, values} = useForm({
  validationSchema: yup.object().shape({
    search: yup.string().required('برای جستجو ابتدا متن جستجو را وارد نمایید.')
  })
})

const onSubmit = handleSubmit(() => {
  emit('search', values.search)
})

function clearFilter() {
  showInputRemoveFilterButton.value = false

  emit('clear-filter', {
    resetField,
    name: 'search',
    value: values.search,
  })
}
</script>
