<template>
  <form @submit.prevent="onSubmit">
    <div class="mb-3">
      <base-switch
        v-if="userStore.hasAnyRole([ROLES.DEVELOPER, ROLES.SUPER_ADMIN])"
        :enabled="!user?.is_deletable"
        disabled-color="bg-pink-300"
        enabled-color="bg-pink-600"
        label="غیر قابل حذف نمودن کاربر توسط سایر اعضاء"
        name="is_deletable"
        sr-text="غیر قابل حذف نمودن کاربر توسط سایر اعضاء"
        @change="(status) => {deletableStatus=status}"
      />
    </div>

    <template v-if="userStore.hasPermission(PERMISSION_PLACES.USER, PERMISSIONS.BAN)">
      <base-switch
        :enabled="!user?.is_banned"
        label="عدم اجازه فعالیت کاربر"
        name="is_banned"
        on-label="اجازه فعالیت کاربر"
        sr-text="اجازه یا جلوگیری از فعالیت کاربر"
        @change="(status) => {banStatus=status}"
      />

      <VTransitionSlideFadeDownY>
        <div
          v-if="!banStatus"
          class="mt-3"
        >
          <base-textarea
            :in-edit-mode="!user?.ban_desc"
            :value="user?.ban_desc"
            label-title="علت عدم اجازه فعالیت به کاربر"
            name="ban_desc"
            placeholder="توضیحات خود را وارد کنید..."
          >
            <template #icon>
              <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
            </template>
          </base-textarea>
        </div>
      </VTransitionSlideFadeDownY>
    </template>

    <div class="px-2 py-3">
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

        <span class="ml-auto">ویرایش وضعیت‌ها</span>
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
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {CheckIcon, InformationCircleIcon} from "@heroicons/vue/24/outline/index.js";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import VTransitionSlideFadeDownY from "@/transitions/VTransitionSlideFadeDownY.vue";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import {computed, ref} from "vue";
import yup from "@/validation/index.js";
import {useRequest} from "@/composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "@/router/api-routes.js";
import {useRoute} from "vue-router";
import {useToast} from "vue-toastification";
import {PERMISSION_PLACES, PERMISSIONS, ROLES, useAdminAuthStore} from "@/store/StoreUserAuth.js";
import {useFormSubmit} from "@/composables/form-submit.js";

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

const userStore = useAdminAuthStore()

const route = useRoute()
const toast = useToast()
const idParam = computed(() => {
  return route.params.id
})

const banStatus = ref(!user.value?.is_banned ?? false)
const deletableStatus = ref(!user.value?.is_deletable ?? false)

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    ban_desc: yup.string().when('is_banned', {
      is: false,
      then: (schema) => {
        if (userStore.hasPermission(PERMISSION_PLACES.USER, PERMISSIONS.BAN)) {
          return schema.required('وارد نمودن توضیحات عدم اجازه فعالیت ضروری می‌باشد.')
        }
        return schema
      },
      otherwise: (schema) => schema.optional().nullable(),
    }),
  }),
  keepValuesOnUnmount: true,
}, (values, actions) => {
  canSubmit.value = false

  if (banStatus.value) {
    delete values['ban_desc']
  }
  if (!userStore.hasAnyRole([ROLES.DEVELOPER, ROLES.SUPER_ADMIN])) {
    delete values['is_deletable']
  }

  useRequest(apiReplaceParams(apiRoutes.admin.users.update, {
    user: idParam.value,
  }), {
    method: 'PUT',
    data: {
      ban_desc: values.ban_desc,
      // is MUST inverse values for backend
      is_banned: !banStatus.value,
      is_deletable: !deletableStatus.value,
    },
  }, {
    success(response) {
      toast.success('ویرایش اطلاعات با موفقیت انجام شد.')
      user.value = response.data
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
</script>
