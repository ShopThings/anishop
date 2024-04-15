<template>
  <form @submit.prevent="onSubmit">
    <div class="p-2">
      <partial-input-label title="وضعیت ارجاع"/>
      <base-select
          :is-loading="loading"
          :options="statuses"
          :selected="selectedStatus"
          name="return_status"
          options-key="value"
          options-text="text"
          @change="paymentStatusChange"
      />
      <partial-input-error-message :error-message="errors.status"/>
    </div>
    <div class="p-2">
      <base-textarea
        :in-edit-mode="!(!!yourDescription)"
          :value="yourDescription"
          label-title="علت تغییر وضعیت جهت نمایش به کاربر"
          name="description"
          placeholder="توضیحات خود را وارد نمایید"
      />
    </div>

    <div class="px-2 py-3 text-left">
      <base-button
          :disabled="!canSubmit"
          class="bg-primary text-white mr-auto px-6 w-full sm:w-auto"
          type="submit"
      >
        <VTransitionFade>
          <loader-circle
              v-if="!canSubmit"
              big-circle-color="border-transparent"
              main-container-klass="absolute w-full h-full top-0 left-0"
          />
        </VTransitionFade>

        <span>تغییر وضعیت مرجوعی</span>
      </base-button>
    </div>
  </form>
</template>

<script setup>
import {computed, onMounted, ref, watch} from "vue";
import yup from "@/validation/index.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseSelect from "@/components/base/BaseSelect.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {ReturnOrderAPI} from "@/service/APIOrder.js";
import {getRouteParamByKey} from "@/composables/helper.js";
import {useToast} from "vue-toastification";

const props = defineProps({
  selected: Object,
  description: String,
})
const emit = defineEmits(['updated'])

const toast = useToast()
const idParam = getRouteParamByKey('id', null, false)
const loading = ref(true)

const statuses = ref([])
const selectedStatus = ref(props.selected)
const yourDescription = computed(() => {
  return props.not_accepted_description
})

watch(() => props.selected, () => {
  selectedStatus.value = props.selected
})

function paymentStatusChange(selected) {
  selectedStatus.value = selected
}

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    description: yup.string().optional(),
  }),
}, (values, actions) => {
  if (!selectedStatus.value || !selectedStatus.value.value) {
    actions.setFieldError('status', 'وضعیت مرجوع را انتخاب کنید.')
    return
  }

  canSubmit.value = false

  ReturnOrderAPI.updateById(idParam.value, {
    not_accepted_description: values.description,
    status: selectedStatus.value.value,
  }, {
    success() {
      toast.success('وضعیت با موفقیت تغییر یافت.')
      emit('updated', selectedStatus.value, values.description)
    },
    error(error) {
      if (error?.description) {
        actions.setFieldError('description', error.description)
      }
      if (error?.status) {
        actions.setFieldError('status', error.status)
      }
    },
    finally() {
      canSubmit.value = true
    },
  })
})

onMounted(() => {
  ReturnOrderAPI.fetchStatuses({
    success: (response) => {
      statuses.value = []
      let res = response.data
      for (let i in res) {
        if (res.hasOwnProperty(i)) {
          statuses.value.push({
            text: res[i],
            value: i,
          })
        }
      }

      loading.value = false
    },
  })
})
</script>
