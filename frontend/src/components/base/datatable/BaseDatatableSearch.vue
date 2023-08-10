<template>
    <form @submit.prevent="onSubmit" class="px-3 pt-3">
        <div class="sm:flex sm:items-start">
            <base-input class="grow sm:ml-3 mb-3" name="search"
                        placeholder="عبارت جستجو را وارد کنید...">
                <template #icon>
                    <MagnifyingGlassCircleIcon class="w-6 h-6 text-gray-400"/>
                </template>
            </base-input>
            <div class="flex shrink-0">
                <div class="flex grow">
                    <base-button type="submit"
                                 class="bg-primary grow rounded-r-lg rounded-l-none border-primary text-sm mb-3">
                        انجام جستجو
                    </base-button>
                    <base-button @click="clearFilter"
                                 class="bg-gray-200 !text-black grow border border-gray-300 rounded-l-lg rounded-r-none text-sm mb-3">
                        حذف فیلتر
                    </base-button>
                </div>
                <base-animated-button @click="emit('refresh')"
                                      v-tooltip.top="'بارگذاری مجدد'"
                                      class="bg-gray-200 !text-black border border-gray-300 rounded-lg text-sm mb-3 mr-3 shrink-0">
                    <template #icon="{klass}">
                        <ArrowPathIcon class="h-6 w-6 my-[.12rem]" :class="klass"/>
                    </template>
                </base-animated-button>
            </div>
        </div>
    </form>
</template>

<script setup>
import {MagnifyingGlassCircleIcon, ArrowPathIcon} from '@heroicons/vue/24/outline';
import {useForm} from "vee-validate";
import yup from "../../../validation/index.js";
import BaseInput from "../BaseInput.vue";
import BaseButton from "../BaseButton.vue";
import BaseAnimatedButton from "../BaseAnimatedButton.vue";

const emit = defineEmits(['search', 'clear-filter', 'refresh'])

const {handleSubmit, resetField, values} = useForm({
    validationSchema: yup.object().shape({
        search: yup.string().required('برای جستجو ابتدا متن جستجو را وارد نمایید.')
    })
})

const onSubmit = handleSubmit(() => {
    emit('search', values.search)
})

function clearFilter() {
    emit('clear-filter', {
        resetField,
        name: 'search',
        value: values.search,
    })
}
</script>

<style scoped>

</style>
