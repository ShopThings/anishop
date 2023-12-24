<template>
  <partial-card>
    <template #header>
      ویرایش دسته‌بندی -
      <span
          v-if="category?.id"
          class="text-teal-600"
      >{{ category?.name }}</span>
    </template>
    <template #body>
      <div class="p-3">
        <base-loading-panel
            :loading="loading"
            type="form"
        >
          <template #content>
            <form @submit.prevent="onSubmit">
              <div class="p-2">
                <base-switch
                    label="عدم نمایش دسته‌بندی"
                    on-label="نمایش دسته‌بندی"
                    name="is_published"
                    :enabled="category?.is_published"
                    sr-text="نمایش/عدم نمایش دسته‌بندی"
                    @change="(status) => {publishStatus=status}"
                />
              </div>

              <div class="flex flex-wrap items-end">
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input
                      label-title="نام"
                      placeholder="وارد نمایید"
                      name="name"
                      :value="category?.name"
                  >
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input
                      type="number"
                      label-title="اولویت"
                      placeholder="وارد نمایید"
                      name="priority"
                      :value="category?.priority"
                      :min="0"
                  >
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>

                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <partial-input-label title="دسته‌بندی والد"/>
                  <base-select-searchable
                      :options="categories"
                      options-key="id"
                      options-text="name"
                      name="parent_category"
                      :selected="initialCategory"
                      :is-loading="searchLoading"
                      :is-local-search="false"
                      placeholder="جستجوی دسته‌بندی..."
                      @change="categorySelectionChange"
                      @query="searchCategory"
                  >
                    <template #item="{item}">
                      <div class="flex items-center">
                        <span>{{ item.name }}</span>
                        <span
                            class="py-1 px-2 text-sm bg-blue-500 text-white mr-2"
                        >
                            سطح
                            {{ item.level }}
                        </span>
                      </div>
                    </template>
                  </base-select-searchable>
                  <partial-input-error-message :error-message="errors.category"/>
                </div>
              </div>

              <hr class="my-3">

              <div class="flex flex-wrap">
                <div class="p-2 w-full sm:w-auto sm:grow">
                  <base-switch
                      label="نمایش در منو"
                      name="show_in_menu"
                      :enabled="category?.show_in_menu"
                      sr-text="نمایش/عدم نمایش دسته‌بندی در منو"
                      @change="(status) => {showInMenuStatus=status}"
                  />
                </div>
                <div class="p-2 w-full sm:w-auto sm:grow">
                  <base-switch
                      label="نمایش در منوی صفحه جستجو"
                      name="show_in_search_side_menu"
                      :enabled="category?.show_in_search_side_menu"
                      sr-text="نمایش/عدم نمایش دسته‌بندی در منوی صفحه جستجو"
                      @change="(status) => {showInSearchSideMenuStatus=status}"
                  />
                </div>
                <div class="p-2 w-full sm:w-auto sm:grow">
                  <base-switch
                      label="نمایش در اسلایدر"
                      name="show_in_slider"
                      :enabled="category?.show_in_slider"
                      sr-text="نمایش/عدم نمایش دسته‌بندی در اسلایدر"
                      @change="(status) => {showInSliderStatus=status}"
                  />
                </div>
              </div>

              <div class="px-2 py-3">
                <base-animated-button
                    type="submit"
                    class="bg-emerald-500 text-white mr-auto px-6 w-full sm:w-auto"
                    :disabled="isSubmitting"
                >
                  <VTransitionFade>
                    <loader-circle
                        v-if="isSubmitting"
                        main-container-klass="absolute w-full h-full top-0 left-0"
                        big-circle-color="border-transparent"
                    />
                  </VTransitionFade>

                  <template #icon="{klass}">
                    <CheckIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
                  </template>

                  <span class="ml-auto">ویرایش دسته‌بندی</span>
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
import {computed, onMounted, ref} from "vue";
import {useForm} from "vee-validate";
import yup from "../../../validation/index.js";
import PartialCard from "../../../components/partials/PartialCard.vue";
import PartialInputLabel from "../../../components/partials/PartialInputLabel.vue";
import BaseSwitch from "../../../components/base/BaseSwitch.vue";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import {CheckIcon, ArrowLeftCircleIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import BaseInput from "../../../components/base/BaseInput.vue";
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import {apiRoutes} from "../../../router/api-routes.js";
import {useRequest} from "../../../composables/api-request.js";
import BaseSelectSearchable from "../../../components/base/BaseSelectSearchable.vue";
import PartialInputErrorMessage from "../../../components/partials/PartialInputErrorMessage.vue";
import {useToast} from "vue-toastification";
import {useRoute, useRouter} from "vue-router";

const router = useRouter()
const route = useRoute()
const toast = useToast()
const idParam = computed(() => {
  const id = parseInt(route.params.id, 10)
  if (isNaN(id)) return route.params.id
  return id
})

const loading = ref(false)
const canSubmit = ref(true)

const category = ref(null)

const searchLoading = ref(true)
const categories = ref([])
const selectedCategory = ref(null)

const initialCategory = ref(null)

const publishStatus = ref(true)
const showInMenuStatus = ref(true)
const showInSearchSideMenuStatus = ref(true)
const showInSliderStatus = ref(true)

function categorySelectionChange(selected) {
  selectedCategory.value = selected
}

function searchCategory(query) {
  // searchLoading.value = true
  // useRequest(apiRoutes.admin.categories.index, {
  //     data: {
  //         query,
  //     },
  // }, {
  //     success: (response) => {
  //         categories.value = response.data
  //     },
  //     finally: () => {
  //         searchLoading.value = false
  //     }
  // })
}

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})

onMounted(() => {
  // useRequest(apiReplaceParams(apiRoutes.admin.categories.show, {category: idParam.value}), null, {
  //     success: (response) => {
  //         category.value = response.data
  //         initialCategory.value = {
  //             id: response.data.category.id,
  //             name: response.data.category.name,
  //         }
  //
  //         loading.value = false
  //     },
  // })
})
</script>

<style scoped>

</style>
