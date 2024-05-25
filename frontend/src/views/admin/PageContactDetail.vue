<template>
  <partial-card>
    <template #header>
      جزئیات تماس -
      <span
          v-if="contact?.id"
          class="text-slate-400 text-base"
      >{{ contact?.title }}</span>
    </template>
    <template #body>
      <div class="p-3">
        <base-loading-panel
            :loading="loading"
            type="content"
        >
          <template #content>
            <ul class="grid grid-cols-1 lg:grid-cols-3 gap-3">
              <li class="sm:flex sm:items-center">
                <span class="ml-2 text-gray-400 text-sm whitespace-nowrap">ارسال شده توسط:</span>
                <div class="mt-1 sm:mt-0 sm:inline-block">
                  <template v-if="contact?.created_by">
                    <router-link
                        :to="{name: 'admin.user.profile', params: {id: contact?.created_by.id}}"
                        class="text-blue-600 hover:text-opacity-80"
                    >
                      <partial-username-label :user="contact?.created_by"/>
                    </router-link>
                    -
                    ({{ contact?.name ?? '-' }})
                  </template>
                  <template v-else>
                    {{ contact?.name ?? '-' }}
                  </template>
                </div>
              </li>
              <li class="sm:flex sm:items-center">
                <span class="ml-2 text-gray-400 text-sm whitespace-nowrap">ارسال شده در تاریخ:</span>
                <div class="mt-1 sm:mt-0 sm:inline-block">
                  {{ contact?.created_at ?? '-' }}
                </div>
              </li>
              <li class="sm:flex sm:items-center">
                <span class="ml-2 text-gray-400 text-sm whitespace-nowrap">موبایل:</span>
                <div class="mt-1 sm:mt-0 sm:inline-block">
                  {{ contact?.mobile ?? '-' }}
                </div>
              </li>
            </ul>

            <div class="flex flex-col gap-3 shadow-md bg-white p-6 mt-3 border border-slate-50 leading-loose">
              <h3
                  v-if="contact"
                  class="font-iranyekan-bold text-lg"
              >
                {{ contact?.title }}
              </h3>

              <div>{{ contact?.message ?? '-' }}</div>
            </div>

            <form
                v-if="contact?.created_by"
                @submit.prevent="onSubmit"
            >
              <div class="p-2">
                <partial-input-label title="پاسخ به کاربر"/>
                <base-editor
                    :value="contact?.answer"
                    name="answer"
                />
              </div>

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

                  <span class="ml-auto">ثبت پاسخ</span>
                </base-animated-button>
              </div>
            </form>
          </template>
        </base-loading-panel>
      </div>
    </template>
  </partial-card>
</template>

<script setup>
import {onMounted, ref} from "vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import {getRouteParamByKey} from "@/composables/helper.js";
import {ContactAPI} from "@/service/APIPage.js";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import {CheckIcon} from "@heroicons/vue/24/outline/index.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseEditor from "@/components/base/BaseEditor.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import yup from "@/validation/index.js";
import PartialUsernameLabel from "@/components/partials/PartialUsernameLabel.vue";

const idParam = getRouteParamByKey('id')

const loading = ref(true)
const contact = ref(null)

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    answer: yup.string().when([], (inputValue, schema) => {
      return !!(contact.value?.created_by)
          ? schema.required('پاسخ خود را وارد نمایید.')
          : schema.optional()
    }),
  }),
}, (values, actions) => {
  if (!values.answer || values?.answer?.trim() === '') {
    return
  }

  canSubmit.value = false

  ContactAPI.updateById(idParam.value, {
    answer: values.answer,
  }, {
    success(response) {
      toast.success('پاسخ شما ثبت شد.')
      tinyMCE.activeEditor.setContent(response.data.description)

      setFormFields(response.data)
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

onMounted(() => {
  ContactAPI.fetchById(idParam.value, {
    success: (response) => {
      setFormFields(response.data)

      // make contact as seen
      ContactAPI.updateById(idParam.value, {
        is_seen: true,
      }, {
        success(response2) {
          setFormFields(response2.data)
          return false
        },
        error() {
          return false
        },
      })

      loading.value = false
    }
  })
})

function setFormFields(item) {
  contact.value = item
}
</script>
