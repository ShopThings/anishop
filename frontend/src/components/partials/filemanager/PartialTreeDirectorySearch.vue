<template>
  <form @submit.prevent="onSubmit">
    <base-input class="grow mb-3" name="searchDir"
                placeholder="نام پوشه را وارد کنید...">
      <template #icon>
        <MagnifyingGlassCircleIcon class="w-6 h-6 text-gray-400"/>
      </template>
    </base-input>
    <div class="text-left">
      <base-button type="submit"
                   class="bg-primary grow rounded-lg border-primary text-sm mb-3 ml-2 !py-1">
        انجام جستجو
      </base-button>
      <base-button @click="clearFilter"
                   class="bg-gray-200 !text-black grow border border-gray-300 rounded-lg text-sm mb-3 !py-1">
        حذف فیلتر
      </base-button>
    </div>
  </form>
</template>

<script setup>
import {MagnifyingGlassCircleIcon} from '@heroicons/vue/24/outline';
import {useForm} from "vee-validate";
import yup from "../../../validation/index.js";
import BaseInput from "../../base/BaseInput.vue";
import BaseButton from "../../base/BaseButton.vue";

const emit = defineEmits(['search', 'clear-filter'])

const {handleSubmit, resetField, values} = useForm({
  validationSchema: yup.object().shape({
    searchDir: yup.string().required('برای جستجو ابتدا متن جستجو را وارد نمایید.')
  })
})

const onSubmit = handleSubmit(() => {
  emit('search', values.search)
})

function clearFilter() {
  emit('clear-filter', {
    resetField,
    name: 'searchDir',
    value: values.searchDir,
  })
}
</script>

<style scoped>

</style>
