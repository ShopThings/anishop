<template>
  <form
      class="relative"
      @submit.prevent="onSubmit"
  >
    <loader-dot-orbit
        v-if="isFetching"
        container-bg-color="bg-blue-50 opacity-40"
        loading-text="در حال بارگذاری تنظیمات"
        main-container-klass="absolute w-full h-full top-0 left-0 z-[2]"
    />

    <div class="flex mb-3 items-end">
      <div class="grow flex flex-col gap-3">
        <div
            v-for="(item, idx) in socialItems"
            :key="item.id"
            class="flex flex-wrap relative border rounded-md border-violet-500"
        >
          <partial-builder-remove-btn
              v-if="socialItems.length > 1"
              @click="removeSocialHandler(idx)"
          />

          <div class="p-2 w-full sm:w-1/3 md:w-1/4">
            <partial-input-label title="نوع شبکه اجتماعی"/>
            <base-select
                :name="'type_' + item.id"
                :options="socials"
                :selected="item.type"
                options-key="value"
                options-text="text"
                @change="(selected) => {item.type = selected}"
            >
              <template #item="{item}">
                <div class="grid grid-cols-[30px,1fr] items-center gap-3">
                  <div class="shrink-0 mx-auto" v-html="item.icon"></div>
                  <span>{{ item.text }}</span>
                </div>
              </template>
            </base-select>
          </div>
          <div class="p-2 w-full sm:w-2/3 md:w-3/4">
            <base-input
                v-tooltip.right="'افزودن شبکه اجتماعی جدید'"
                :name="'url_' + item.id"
                :value="item.link"
                label-title="آدرس شبکه اجتماعی"
                placeholder="وارد نمایید"
                @input="(val) => {item.link = val}"
            >
              <template #icon>
                <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
              </template>
            </base-input>
          </div>
        </div>
      </div>
      <div class="p-2">
        <button
            class="rounded-full p-3 flex justify-center items-center bg-emerald-400 text-white group"
            type="button"
            @click="newSocialHandler"
        >
          <PlusIcon class="w-6 h-6 group-hover:rotate-90 transition"/>
        </button>
      </div>
    </div>

    <div class="px-2 py-3">
      <base-animated-button
          :disabled="!canSubmit || isFetching"
          class="bg-emerald-500 text-white mr-auto px-6 w-full sm:w-auto"
          type="submit"
      >
        <VTransitionFade>
          <loader-circle
              v-if="!canSubmit || isFetching"
              big-circle-color="border-transparent"
              main-container-klass="absolute w-full h-full top-0 left-0"
          />
        </VTransitionFade>

        <template #icon="{klass}">
          <CheckIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
        </template>

        <span class="ml-auto">ذخیره اطلاعات</span>
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
import {ref} from "vue";
import {SETTING_KEYS, SOCIAL_NETWORKS} from "@/composables/constants.js";
import yup from "@/validation/index.js";
import {ArrowLeftCircleIcon, CheckIcon, PlusIcon} from "@heroicons/vue/24/outline/index.js";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import BaseSelect from "@/components/base/BaseSelect.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import PartialBuilderRemoveBtn from "@/components/partials/PartialBuilderRemoveBtn.vue";
import LoaderDotOrbit from "@/components/base/loader/LoaderDotOrbit.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {SettingAPI} from "@/service/APIConfig.js";
import {watchImmediate} from "@vueuse/core";
import {findItemByKey} from "@/composables/helper.js";

const props = defineProps({
  setting: {
    type: Object,
    required: true,
  },
  isFetching: {
    type: Boolean,
    default: false,
  },
})
const emit = defineEmits(['updated'])

//---------------------------------
// Social Accounts Stuffs
//---------------------------------
const socials = []
for (let s in SOCIAL_NETWORKS) {
  if (SOCIAL_NETWORKS.hasOwnProperty(s)) {
    socials.push({
      value: SOCIAL_NETWORKS[s].value,
      text: SOCIAL_NETWORKS[s].text,
      icon: SOCIAL_NETWORKS[s].icon,
    })
  }
}
const socialItems = ref([])

watchImmediate(() => props.setting, () => {
  socialItems.value = findItemByKey(props.setting, 'name', SETTING_KEYS.SOCIALS)?.value
  if (!socialItems.value?.length) {
    socialItems.value = [
      {
        id: 1,
        type: null,
        link: '',
      },
    ]
  }
})

function newSocialHandler() {
  socialItems.value.push({
    id: (socialItems.value[socialItems.value.length - 1]?.id || 0) + 1,
    type: null,
    link: '',
  })
}

function removeSocialHandler(idx) {
  if (socialItems.value[idx])
    socialItems.value.splice(idx, 1)
}

//---------------------------------
const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({}),
}, (values, actions) => {
  if (props.isFetching) return

  canSubmit.value = false

  const updateArr = {
    [SETTING_KEYS.SOCIALS]: getDefinedItems(),
  }

  SettingAPI.updateSetting(updateArr, {
    success() {
      emit('updated', updateArr)
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

function getDefinedItems() {
  const items = []

  for (let i of socialItems.value) {
    if (i.type && i.link?.trim() !== '') {
      items.push(i)
    }
  }

  return items
}
</script>
