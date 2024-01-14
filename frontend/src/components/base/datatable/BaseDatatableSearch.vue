<template>
  <form @submit.prevent="onSubmit" class="px-3 pt-3">
    <div class="sm:flex sm:items-start">
      <div class="grow sm:ml-3 mb-3 relative">
        <base-input
          :klass="showRemoveFilterButtonOnInput ? 'pl-10' : ''"
          name="search"
          placeholder="عبارت جستجو را وارد کنید..."
          @input="checkFilterButtonAppearance"
          @focus="checkFilterButtonAppearance"
        >
          <template #icon>
            <MagnifyingGlassCircleIcon class="w-6 h-6 text-gray-400"/>
          </template>
        </base-input>

        <button
          v-if="showRemoveFilterButtonOnInput && showInputRemoveFilterButton"
          v-tooltip.bottom="'حذف فیلتر'"
          type="button"
          class="absolute top-0 translate-y-1 left-0 p-2 group"
          @click="clearFilter"
        >
          <XMarkIcon class="w-6 h-6 text-gray-400 group-hover:text-gray-500 transition"/>
        </button>
      </div>
      <div class="flex shrink-0">
        <div class="flex grow">
          <base-button
            type="submit"
            :class="[
                            showRemoveFilterButtonOnInput ? 'rounded-lg' : 'rounded-r-lg rounded-l-none',
                        ]"
            class="bg-primary grow border-primary text-sm mb-3"
          >
            انجام جستجو
          </base-button>
          <base-button
            v-if="!showRemoveFilterButtonOnInput"
            @click="clearFilter"
            class="bg-gray-200 !text-black grow border border-gray-300 rounded-l-lg rounded-r-none text-sm mb-3"
          >
            حذف فیلتر
          </base-button>
        </div>
        <base-animated-button
          v-if="showRefreshButton"
          @click="emit('refresh')"
          v-tooltip.top="'بارگذاری مجدد'"
          class="bg-gray-200 !text-black border border-gray-300 rounded-lg text-sm mb-3 mr-3 shrink-0"
        >
          <template #icon="{klass}">
            <ArrowPathIcon class="h-6 w-6 my-[.12rem]" :class="klass"/>
          </template>
        </base-animated-button>
      </div>
    </div>
  </form>
</template>

<script setup>
import {MagnifyingGlassCircleIcon, ArrowPathIcon, XMarkIcon} from '@heroicons/vue/24/outline';
import {useForm} from "vee-validate";
import yup from "@/validation/index.js";
import BaseInput from "../BaseInput.vue";
import BaseButton from "../BaseButton.vue";
import BaseAnimatedButton from "../BaseAnimatedButton.vue";
import {ref} from "vue";

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
