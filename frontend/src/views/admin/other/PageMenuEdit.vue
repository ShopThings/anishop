<template>
  <partial-card>
    <template #header>
      ویرایش منو
    </template>
    <template #body>
      <div class="p-3">
        <base-loading-panel
            :loading="loading"
            type="form"
        >
          <template #content>
            <form @submit.prevent="onSubmit">
              <div
                  class="flex flex-wrap items-center justify-between mb-3 bg-indigo-50 rounded border-2 border-indigo-400 p-3">
                <div class="p-2 w-full sm:w-1/2">
                  <base-input
                      :value="menu?.title"
                      name="title"
                      placeholder="عنوان منو"
                  >
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="py-2 px-3 bg-white/70 rounded-full w-full sm:w-auto">
                  <base-switch
                      :enabled="menu?.is_published ?? false"
                      label="نمایش منو"
                      name="is_published"
                      sr-text="نمایش/عدم نمایش منو"
                      @change="(status) => {menu.is_published=status}"
                  />
                </div>
              </div>

              <div class="p-2">
                <nested-menus v-if="menuItems" :menus="menuItems"/>

                <div class="mt-3 mb-1">
                  <base-button
                      class="!text-orange-600 border-orange-400 w-full sm:w-auto flex items-center hover:bg-orange-50 mr-auto"
                      @click="handleNewMenuClick"
                  >
                    <span class="mr-auto text-sm">ساخت زیر منو</span>
                    <PlusIcon class="h-6 w-6 mr-auto sm:mr-2"/>
                  </base-button>
                </div>
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

                  <span class="ml-auto">ویرایش منو</span>
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
        </base-loading-panel>
      </div>
    </template>
  </partial-card>
</template>

<script setup>
import {onMounted, ref} from "vue";
import {useToast} from "vue-toastification";
import yup from "@/validation/index.js";
import PartialCard from "@/components/partials/PartialCard.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {ArrowLeftCircleIcon, CheckIcon, PlusIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import NestedMenus from "./infra/NestedMenus.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import uniqueId from "lodash.uniqueid";
import {getRouteParamByKey} from "@/composables/helper.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {MenuAPI} from "@/service/APIConfig.js";
import isObject from "lodash.isobject";
import BaseInput from "@/components/base/BaseInput.vue";

const toast = useToast()
const idParam = getRouteParamByKey('id')

const loading = ref(true)
const menu = ref(null)
const menuItems = ref([])

function handleNewMenuClick() {
  menuItems.value.push({
    tmp_id: parseInt(uniqueId()),
    id: null,
    parent_id: null,
    title: '',
    link: '',
    can_have_children: true,
    is_published: true,
    children: [],
  })
}

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    title: yup.string().required('عنوان منو را وارد نمایید.'),
  }),
}, (values, actions) => {
  canSubmit.value = false

  let menuPromise = new Promise((resolve, reject) => {
    MenuAPI.updateById(idParam.value, {
      title: values.title,
      is_published: menu.is_published,
    }, {
      success: (response) => {
        menu.value = response.data
        resolve()
      },
      error(error) {
        reject(error)
      },
    })
  })
  let menuItemsPromise = new Promise((resolve, reject) => {
    MenuAPI.modifyMenuItems(idParam.value, {
      menus: menuItems.value,
    }, {
      success(response) {
        setFormFields(response.data)
        resolve()
      },
      error() {
        reject(false)
      },
    })
  })

  Promise.all([
    menuPromise,
    menuItemsPromise,
  ]).then(() => {
    toast.success('ویرایش اطلاعات با موفقیت انجام شد.')
  }).catch((error) => {
    if (isObject(error)) {
      actions.setErrors(error.errors)
    }
  }).finally(() => {
    canSubmit.value = true
  })
})

onMounted(() => {
  MenuAPI.fetchById(idParam.value, {
    success: (response) => {
      menu.value = response.data
    },
  })

  MenuAPI.fetchMenuItems(idParam.value, {
    success(response) {
      setFormFields(response.data)
      loading.value = false
    },
  })
})

function setFormFields(item) {
  menuItems.value = item
}
</script>
