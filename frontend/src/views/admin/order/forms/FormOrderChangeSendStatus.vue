<template>
  <form @submit.prevent="onSubmit">
    <div class="p-2">
      <partial-input-label title="وضعیت ارسال"/>
      <base-select
        :options="statuses"
        options-key="value"
        options-text="name"
        :is-loading="loading"
        :selected="selectedStatus"
        name="send_status"
        @change="sendStatusChange"
      />
      <partial-input-error-message :error-message="errors.type"/>
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

        <span>تغییر وضعیت ارسال</span>
      </base-button>
    </div>
  </form>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import {useForm} from "vee-validate";
import yup from "@/validation/index.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseSelect from "@/components/base/BaseSelect.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";

const props = defineProps({
  selected: Object,
})
const emit = defineEmits(['update:selected'])

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

function sendStatusChange(selected) {
  selectedStatus.value = selected
}

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})

onMounted(() => {
  // useRequest(apiReplaceParams(apiRoutes.admin.orders.sendStatuses), null, {
  //     success: (response) => {
  //         statuses.value = response.data
  //
  //         loading.value = false
  //     },
  // })
})
</script>
