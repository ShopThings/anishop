<template>
  <form @submit.prevent="onSubmit">
    <div class="p-2">
      <partial-input-label title="وضعیت ارجاع"/>
      <base-select
        :options="statuses"
        options-key="value"
        options-text="name"
        :is-loading="loading"
        :selected="selectedStatus"
        name="return_status"
        @change="paymentStatusChange"
      />
      <partial-input-error-message :error-message="errors.type"/>
    </div>
    <div class="p-2">
      <base-textarea
        name="description"
        label-title="علت تغییر وضعیت جهت نمایش به کاربر"
        placeholder="توضیحات خود را وارد نمایید"
        :value="yourDescription"
        :has-edit-mode="!(!!yourDescription)"
      />
    </div>

    <div class="px-2 py-3 text-left">
      <base-button
        type="submit"
        class="bg-primary text-white mr-auto px-6 w-full sm:w-auto"
        :disabled="isSubmitting"
      >
        <VTransitionFade>
          <loader-circle
            v-if="isSubmitting"
            main-container-klass="absolute w-full h-full top-0 left-0"
            big-circle-color="border-transparent"
          />
        </VTransitionFade>

        <span>تغییر وضعیت مرجوعی</span>
      </base-button>
    </div>
  </form>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import {useForm} from "vee-validate";
import yup from "../../../../validation/index.js";
import {apiReplaceParams, apiRoutes} from "../../../../router/api-routes.js";
import {useRequest} from "../../../../composables/api-request.js";
import LoaderCircle from "../../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../../transitions/VTransitionFade.vue";
import BaseButton from "../../../../components/base/BaseButton.vue";
import BaseSelect from "../../../../components/base/BaseSelect.vue";
import PartialInputErrorMessage from "../../../../components/partials/PartialInputErrorMessage.vue";
import PartialInputLabel from "../../../../components/partials/PartialInputLabel.vue";
import BaseTextarea from "../../../../components/base/BaseTextarea.vue";

const props = defineProps({
  selected: Object,
  description: String,
})
const emit = defineEmits(['update:selected', 'update:description'])

const loading = ref(true)
const canSubmit = ref(true)

const statuses = ref([])
const selectedStatus = computed({
  get() {
    return props.selected
  },
  set(value) {
    emit('update:selected', value)
  },
})
const yourDescription = computed({
  get() {
    return props.description
  },
  set(value) {
    emit('update:description', value)
  },
})

function paymentStatusChange(selected) {
  selectedStatus.value = selected
}

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})

onMounted(() => {
  // useRequest(apiReplaceParams(apiRoutes.admin.orders.paymentStatuses), null, {
  //     success: (response) => {
  //         statuses.value = response.data
  //
  //         loading.value = false
  //     },
  // })
})
</script>
