<template>
  <form @submit.prevent="onSubmit">
    <div class="flex flex-wrap">
      <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
        <base-input
          :in-edit-mode="false"
          :is-editable="userStore.hasRole(ROLES.DEVELOPER)"
          :value="user?.username"
          label-title="نام کاربری"
          name="username"
          placeholder="(معمولا شماره تلفن همراه می‌باشد)"
        >
          <template #icon>
            <UserIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
      </div>
      <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
        <partial-input-label title="نقش کاربر"/>
        <base-select-searchable
          :in-edit-mode="false"
          :is-editable="hasEditPermission"
          :multiple="true"
          :options="roles"
          :selected="selectedRoles"
          name="roles"
          options-key="value"
          options-text="name"
          @change="roleChange"
        />
        <partial-input-error-message :error-message="errors.roles"/>
      </div>
    </div>

    <hr class="my-3">

    <div class="flex flex-wrap">
      <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
        <base-input
          :in-edit-mode="false"
          :is-editable="hasEditPermission"
          :value="user?.first_name"
          label-title="نام"
          name="first_name"
          placeholder="حروف فارسی"
        >
          <template #icon>
            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
      </div>
      <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
        <base-input
          :in-edit-mode="false"
          :is-editable="hasEditPermission"
          :value="user?.last_name"
          label-title="نام خانوادگی"
          name="last_name"
          placeholder="حروف فارسی"
        >
          <template #icon>
            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
      </div>
      <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
        <base-input
          :in-edit-mode="false"
          :is-editable="hasEditPermission"
          :value="user?.national_code"
          label-title="کد ملی"
          name="national_code"
          placeholder="فقط شامل اعداد"
        >
          <template #icon>
            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
      </div>
      <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
        <base-input
          :in-edit-mode="false"
          :is-editable="hasEditPermission"
          :value="user?.sheba_number"
          is-optional
          label-title="شماره شبا"
          name="sheba_number"
          placeholder="xxxxxxxxxxxxxxxx"
        >
          <template #icon>
            <HashtagIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
      </div>
    </div>

    <div
      v-if="hasEditPermission"
      class="px-2 py-3"
    >
      <base-animated-button
        :disabled="!canSubmit"
        class="bg-emerald-500 text-white mr-auto px-6 w-full sm:w-auto"
        type="submit"
      >
        <VTransitionFade>
          <loader-circle
            v-if="!canSubmit"
            big-circle-color="border-transparent"
            main-container-klass="absolute w-full h-full top-0 left-0"
          />
        </VTransitionFade>

        <template #icon="{klass}">
          <CheckIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
        </template>

        <span class="ml-auto">ویرایش کاربر</span>
      </base-animated-button>

      <div
        v-if="Object.keys(errors)?.length"
        class="text-left"
      >
        <div
          class="w-full sm:w-auto sm:inline-block text-center text-sm border-2 border-rose-500 bg-rose-50 rounded-full py-1 px-3 mt-2"
        >
          (
          <span>{{ Object.keys(errors)?.length }}</span>
          )
          خطا، لطفا بررسی کنید
        </div>
      </div>
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
import {useToast} from "vue-toastification";
import {RoleAPI} from "@/service/APIRole.js";
import {UserAPI} from "@/service/APIUser.js";
import {getRouteParamByKey} from "@/composables/helper.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {PERMISSION_PLACES, PERMISSIONS, ROLES, useAdminAuthStore} from "@/store/StoreUserAuth.js";

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
})
const emit = defineEmits(['update:user'])

const user = computed({
  get() {
    return props.user
  },
  set(value) {
    emit('update:user', value)
  },
})

const toast = useToast()
const idParam = getRouteParamByKey('id')

const userStore = useAdminAuthStore()
const hasEditPermission = computed(() => {
  return +userStore.getUser.id === +idParam ||
    userStore.hasPermission(PERMISSION_PLACES.USER, PERMISSIONS.UPDATE)
})

const selectedRoles = ref([])
const roles = ref({})

for (let o in props.user?.roles || {}) {
  if (props.user.roles.hasOwnProperty(o)) {
    selectedRoles.value.push({
      name: props.user.roles[o],
      value: o,
    })
  }
}

function roleChange(selected) {
  selectedRoles.value = selected
}

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    first_name: yup.string().persian('نام باید از حروف فارسی باشد.').required('نام را وارد نمایید.'),
    last_name: yup.string().persian('نام خانوادگی باید از حروف فارسی باشد.').required('نام خانوادگی را وارد نمایید.'),
    national_code: yup.string()
      .transform(transformNumbersToEnglish)
      .persianNationalCode('کد ملی نامعتبر است.')
      .required('کد ملی را وارد نمایید.'),
    sheba_number: yup.string()
      .transform(transformNumbersToEnglish)
      .optional().nullable(),
  }),
  keepValuesOnUnmount: true,
}, (values, actions) => {
  if (!userStore.hasPermission(PERMISSION_PLACES.USER, PERMISSIONS.UPDATE)) {
    toast.error('امکان ویرایش وجود ندارد.')
    return
  }

  // validate extra inputs
  if (!selectedRoles.value || selectedRoles.value.length === 0) {
    actions.setFieldError('roles', 'انتخاب حداقل یک نقش اجباری می‌باشد.')
    return
  }

  if (Array.isArray(selectedRoles.value)) {
    for (let i of selectedRoles.value) {
      if (roles.value.map(val => val.value).indexOf(i.value) === -1) {
        actions.setFieldError('roles', 'نقش انتخاب شده نامعتبر می‌باشد.')
        return
      }
    }
    values.roles = selectedRoles.value
  } else {
    if (roles.value.map(val => val.value).indexOf(selectedRoles.value.value) === -1) {
      actions.setFieldError('roles', 'نقش انتخاب شده نامعتبر می‌باشد.')
      return
    }
    values.roles = [selectedRoles.value]
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
      selectedRoles.value = retrievedRoles

      return false
    },
    error(error) {
      if (error?.errors && Object.keys(error.errors).length >= 1) {
        actions.setErrors(error.errors)
      }

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
