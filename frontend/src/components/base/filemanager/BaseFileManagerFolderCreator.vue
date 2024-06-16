<template>
  <form @submit.prevent="onSubmit">
    <div class="text-left sm:flex sm:items-start justify-end">
      <base-input class="grow mb-2 sm:max-w-96" name="folder" placeholder="نام پوشه را وارد کنید...">
        <template #icon>
          <FolderPlusIcon class="h-6 w-6 text-gray-400"/>
        </template>
      </base-input>

      <base-button
          :disabled="!canSubmit"
          class="sm:shrink-0 mb-2 sm:mr-2 bg-emerald-500 text-sm group w-full sm:w-auto flex gap-2 items-center mr-auto"
          type="submit"
      >
        <VTransitionFade>
          <loader-circle
              v-if="!canSubmit"
              big-circle-color="border-transparent"
              main-container-klass="absolute w-full h-full top-0 left-0"
          />
        </VTransitionFade>

        <PlusIcon class="h-6 w-6 shrink-0 group-hover:rotate-90 transition"/>
        <span class="m-auto">ایجاد پوشه جدید</span>
      </base-button>
    </div>
  </form>
</template>

<script setup>
import {FolderPlusIcon} from '@heroicons/vue/24/outline';
import BaseInput from "@/components/base/BaseInput.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {PlusIcon} from "@heroicons/vue/24/outline/index.js";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import yup from "@/validation/index.js";
import BaseButton from "@/components/base/BaseButton.vue";
import {FilemanagerAPI} from "@/service/APIFilemanager.js";

const props = defineProps({
  path: {
    type: String,
    default: '/',
    required: true,
  },
  disk: String,
})
const emit = defineEmits(['created'])

const {canSubmit, onSubmit, errors} = useFormSubmit({
  validationSchema: yup.object().shape({
    folder: yup.string()
        .required('لطفا نام پوشه را وارد نمایید')
        .folderName('نام پوشه دارای مقادیر نامعتبر می‌باشد.')
  }),
}, (values, actions) => {
  canSubmit.value = false

  FilemanagerAPI.createDirectory({
    name: values.folder,
    path: props.path,
    disk: props.disk,
  }, {
    success() {
      actions.resetForm()

      emit('created')
    },
    finally() {
      canSubmit.value = true
    },
  })
})
</script>
