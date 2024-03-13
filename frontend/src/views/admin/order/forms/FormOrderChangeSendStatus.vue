<template>
  <form @submit.prevent="onSubmit">
    <div class="p-2">
      <partial-input-label title="وضعیت ارسال"/>
      <base-select
          :is-loading="loading"
          :options="statuses"
          :selected="selectedStatus"
          name="send_status"
          options-key="id"
          options-text="title"
          @change="sendStatusChange"
      />
      <partial-input-error-message :error-message="errors.send_status"/>
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

        <span>تغییر وضعیت ارسال</span>
      </base-button>
    </div>
  </form>
</template>

<script setup>
import {onMounted, ref} from "vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseSelect from "@/components/base/BaseSelect.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import {OrderAPI} from "@/service/APIOrder.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {getRouteParamByKey} from "@/composables/helper.js";

const props = defineProps({
  selected: Object,
})
const emit = defineEmits(['update:selected'])

const idParam = getRouteParamByKey('id', null, false)
const loading = ref(true)

const statuses = ref(null)
const selectedStatus = ref(null)

function sendStatusChange(selected) {
  selectedStatus.value = selected
}

const {canSubmit, errors, onSubmit} = useFormSubmit({}, (values, actions) => {
  if (
      !selectedStatus.value ||
      !statuses.value ||
      statuses.value.findIndex((item) => (+item.id === selectedStatus.value.id)) === -1
  ) {
    actions.setFieldError('send_status', 'وضعیت ارسال انتخاب شده نامعتبر است.')
    return
  }

  canSubmit.value = false

  OrderAPI.updateById(idParam.value, {
    send_status: selectedStatus.value,
  }, {
    success() {
      toast.success('وضعیت ارسال تغییر یافت.')

      emit('update:selected', {
        title: selectedStatus.value.title,
        color_hex: selectedStatus.value.color_hex,
      })
    },
    error(error) {
      if (error.errors && Object.keys(error.errors).length >= 1)
        actions.setErrors(error.errors)
    },
    finally() {
      canSubmit.value = true
    },
  })
})

onMounted(() => {
  OrderAPI.fetchSendStatuses({
    success: (response) => {
      statuses.value = response.data
      loading.value = false
    },
  })
})
</script>
