<template>
  <form @submit.prevent="onSubmit">
    <partial-input-label title="وارد نمودن توضیحات"/>
    <base-editor
      :value="description"
      name="description"
    />

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

        <span>ثبت توضیحات</span>
      </base-button>
    </div>
  </form>
</template>

<script setup>
import BaseEditor from "@/components/base/BaseEditor.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import {getRouteParamByKey} from "@/composables/helper.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {OrderAPI} from "@/service/APIOrder.js";

defineProps({
  description: {
    type: String,
    required: true,
  },
})
const emit = defineEmits(['success'])

const idParam = getRouteParamByKey('id', null, false)

const {canSubmit, onSubmit} = useFormSubmit({}, (values, actions) => {
  canSubmit.value = false

  OrderAPI.updateById(idParam.value, {
    description: values.value,
  }, {
    success(response) {
      toast.success('توضیحات شما ثبت شد.')

      emit('success', response.data.description)
    },
    error(error) {
      if (error?.errors && Object.keys(error.errors).length >= 1) {
        actions.setErrors(error.errors)
      }
    },
    finally() {
      canSubmit.value = true
    },
  })
})
</script>
