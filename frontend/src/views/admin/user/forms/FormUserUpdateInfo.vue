<template>
  <form @submit.prevent="onSubmit">
    <div class="flex flex-wrap">
      <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
        <base-input
          label-title="نام کاربری"
          placeholder="(معمولا شماره تلفن همراه می‌باشد)"
          name="username"
          :has-edit-mode="false"
          :is-editable="false"
          :value="user?.username"
        >
          <template #icon>
            <UserIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
      </div>
      <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
        <partial-input-label title="نقش کاربر"/>
        <base-select-searchable
          :options="roles"
          options-key="value"
          options-text="name"
          name="roles"
          :multiple="true"
          :selected="initialRoles"
          :has-edit-mode="false"
          @change="roleChange"
        />
        <partial-input-error-message :error-message="errors.roles"/>
      </div>
    </div>

    <hr class="my-3">

    <div class="flex flex-wrap">
      <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
        <base-input
          label-title="نام"
          placeholder="حروف فارسی"
          name="first_name"
          :has-edit-mode="false"
          :value="user?.first_name"
        >
          <template #icon>
            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
      </div>
      <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
        <base-input
          label-title="نام خانوادگی"
          placeholder="حروف فارسی"
          name="last_name"
          :has-edit-mode="false"
          :value="user?.last_name"
        >
          <template #icon>
            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
      </div>
      <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
        <base-input
          label-title="کد ملی"
          placeholder="فقط شامل اعداد"
          name="national_code"
          :has-edit-mode="false"
          :value="user?.national_code"
        >
          <template #icon>
            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
      </div>
      <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
        <base-input
          label-title="شماره شبا"
          is-optional
          placeholder="xxxxxxxxxxxxxxxx"
          name="shaba_number"
          :has-edit-mode="false"
          :value="user?.shaba_number"
        >
          <template #icon>
            <HashtagIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
      </div>
    </div>

    <div class="px-2 py-3">
      <base-animated-button
        type="submit"
        class="bg-emerald-500 text-white mr-auto px-6 w-full sm:w-auto"
        :disabled="!canSubmit"
      >
        <VTransitionFade>
          <loader-circle
            v-if="!canSubmit"
            main-container-klass="absolute w-full h-full top-0 left-0"
            big-circle-color="border-transparent"
          />
        </VTransitionFade>

        <template #icon="{klass}">
          <CheckIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
        </template>

        <span class="ml-auto">ویرایش کاربر</span>
      </base-animated-button>
    </div>
  </form>
</template>

<script setup>
import {ArrowLeftCircleIcon, CheckIcon, HashtagIcon, UserIcon} from "@heroicons/vue/24/outline/index.js";
import BaseSelectSearchable from "@/components/base/BaseSelectSearchable.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import {computed, onMounted, ref} from "vue";
import yup, {transformNumbersToEnglish} from "@/validation/index.js";
import {useRoute} from "vue-router";
import {useToast} from "vue-toastification";
import {RoleAPI} from "@/service/APIRole.js";
import {UserAPI} from "@/service/APIUser.js";
import {getRouteParamByKey} from "@/composables/helper.js";
import {useFormSubmit} from "@/composables/form-submit.js";

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
  initialRoles: {
    type: Object,
    required: true,
  },
})
const emit = defineEmits(['update:user', 'update:initialRoles'])

const user = computed({
  get() {
    return props.user
  },
  set(value) {
    emit('update:user', value)
  },
})

const initialRoles = computed({
  get() {
    return props.initialRoles
  },
  set(value) {
    emit('update:initialRoles', value)
  },
})

const route = useRoute()
const toast = useToast()
const idParam = getRouteParamByKey('id')

const selectedRole = ref(null)
const roles = ref({})

function roleChange(selected) {
  selectedRole.value = selected
}

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    first_name: yup.string().persian('نام باید از حروف فارسی باشد.').required('نام اجباری می‌باشد.'),
    last_name: yup.string().persian('نام خانوادگی باید از حروف فارسی باشد.').required('نام خانوادگی اجباری می‌باشد.'),
    national_code: yup.string()
      .transform(transformNumbersToEnglish)
      .persianNationalCode('کد ملی نامعتبر است.').required('کد ملی اجباری می‌باشد.'),
    shaba_number: yup.string()
      .transform(transformNumbersToEnglish)
      .optional().nullable(),
  }),
  keepValuesOnUnmount: true,
}, (values, actions) => {
  if (!canSubmit.value) return

  // validate extra inputs
  if (!selectedRole.value || selectedRole.value.length === 0) {
    actions.setFieldError('roles', 'انتخاب حداقل یک نقش اجباری می‌باشد.')
    return
  }

  if (Array.isArray(selectedRole.value)) {
    for (let i of selectedRole.value) {
      if (roles.value.map(val => val.value).indexOf(i.value) === -1) {
        actions.setFieldError('roles', 'نقش انتخاب شده نامعتبر می‌باشد.')
        return
      }
    }
    values.roles = selectedRole.value
  } else {
    if (roles.value.map(val => val.value).indexOf(selectedRole.value.value) === -1) {
      actions.setFieldError('roles', 'نقش انتخاب شده نامعتبر می‌باشد.')
      return
    }
    values.roles = [selectedRole.value]
  }
  //

  canSubmit.value = false

  UserAPI.updateById(idParam.value, values, {
    success(response) {
      toast.success('ویرایش اطلاعات با موفقیت انجام شد.')

      user.value = response.data

      const retrievedRoles = []
      for (let o in response.data.roles) {
        if (response.data.roles.hasOwnProperty(o)) {
          retrievedRoles.push({
            name: response.data.roles[o],
            value: o,
          })
        }
      }
      initialRoles.value = retrievedRoles

      return false
    },
    error(error) {
      if (error.errors && Object.keys(error.errors).length >= 1)
        actions.setErrors(error.errors)

      return false
    },
    finally() {
      canSubmit.value = true
    },
  })
})

onMounted(() => {
  RoleAPI.fetchAll({
    success(response) {
      roles.value = response.data
    },
  })
})
</script>
