<template>
  <partial-dialog
    v-model:open="isOpen"
    @close="$emit('close')"
  >
    <template #title>
      <div class="flex items-center">
        <PencilSquareIcon class="h-6 w-6 ml-2"/>
        تغییر نام
      </div>
    </template>

    <template #body>
      <form @submit.prevent="onSubmit">
        <base-input
          class="grow mb-3"
          name="newName"
          :value="item.full_name"
          placeholder="نام جدید را وارد کنید..."
        >
          <template #icon>
            <PencilIcon class="w-6 h-6 text-gray-400"/>
          </template>
        </base-input>
        <div class="text-left">
          <base-button
            type="submit"
            class="relative bg-emerald-500 border-emerald-600 grow rounded-lg text-sm px-6"
            :disabled="!canSubmit"
          >
            <VTransitionFade>
              <loader-circle
                v-if="!canSubmit"
                main-container-klass="absolute w-full h-full top-0 left-0"
                big-circle-color="border-transparent"
              />
            </VTransitionFade>

            تغییر نام
          </base-button>
        </div>
      </form>
    </template>
  </partial-dialog>
</template>

<script setup>
import {computed} from "vue";
import {PencilSquareIcon, PencilIcon} from "@heroicons/vue/24/outline/index.js";
import yup from "@/validation/index.js";
import PartialDialog from "@/components/partials/PartialDialog.vue";
import BaseInput from "../BaseInput.vue";
import BaseButton from "../BaseButton.vue";
import {FilemanagerAPI} from "@/service/APIFilemanager.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";

const props = defineProps({
  open: Boolean,
  item: {
    type: Object,
    required: true,
  },
  path: {
    type: String,
    required: true,
  },
  disk: String,
})

const emit = defineEmits(['update:open', 'success', 'close'])

const isOpen = computed({
  get() {
    return props.open
  },
  set(value) {
    emit('update:open', value)
  },
})

const {canSubmit, onSubmit, errors} = useFormSubmit({
  validationSchema: yup.object().shape({
    newName: yup.string().required('نام جدید فایل/پوشه را وارد نمایید.')
  })
}, (values, actions) => {
  if (values.newName === props.item.full_name) {
    actions.setFieldError('newName', 'نام جدید با نام پیشین یکسان می‌باشد!')
    return
  }

  canSubmit.value = false

  FilemanagerAPI.rename({
    old_name: props.item.full_name,
    new_name: values.newName,
    path: props.path,
    disk: props.disk,
  }, {
    success() {
      emit('success', values.newName, props.item.full_name)

      actions.resetForm()
      isOpen.value = false
    },
    finally() {
      canSubmit.value = true
    },
  })
})
</script>
