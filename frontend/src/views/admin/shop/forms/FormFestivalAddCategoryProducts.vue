<template>
  <partial-card>
    <template #header>
      افزودن محصولات دسته‌بندی به جشنواره
    </template>

    <template #body>
      <form>
        <div class="w-full p-3">
          <partial-input-label title="انتخاب دسته‌بندی"/>
          <base-select-searchable
              :options="categories"
              options-key="id"
              options-text="name"
              name="category"
              :multiple="true"
              :is-loading="loading"
              :is-local-search="false"
              placeholder="جستجوی دسته‌بندی..."
              @change="categorySelectionChange"
              @query="searchCategory"
          >
            <template #item="{item}">
              {{ item.name }}
            </template>
          </base-select-searchable>
          <partial-input-error-message :error-message="errors.category"/>
        </div>

        <div class="sm:flex sm:flex-wrap sm:flex-row-reverse">
          <div class="px-3 py-3">
            <base-animated-button
                type="submit"
                class="bg-purple-500 text-white px-6 w-full sm:w-auto"
                :class="{'!cursor-not-allowed': isSubmitting}"
                :disabled="isSubmitting"
                @click="handleSubmitOperation('add')"
            >
              <VTransitionFade>
                <loader-circle
                    v-if="isSubmitting && submitOperation === 'add'"
                    main-container-klass="absolute w-full h-full top-0 left-0"
                    big-circle-color="border-transparent"
                />
              </VTransitionFade>

              <template #icon="{klass}">
                <CheckCircleIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
              </template>

              <span class="ml-auto">افزودن محصولات دسته‌بندی</span>
            </base-animated-button>
          </div>
          <div class="px-3 py-3">
            <base-animated-button
                type="submit"
                class="!text-pink-500 border-pink-500 px-6 w-full sm:w-auto hover:bg-pink-50"
                :class="{'!cursor-not-allowed': isSubmitting}"
                :disabled="isSubmitting"
                @click="handleSubmitOperation('remove')"
            >
              <VTransitionFade>
                <loader-circle
                    v-if="isSubmitting && submitOperation === 'remove'"
                    main-container-klass="absolute w-full h-full top-0 left-0"
                    big-circle-color="border-transparent"
                />
              </VTransitionFade>

              <template #icon="{klass}">
                <XCircleIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
              </template>

              <span class="ml-auto">حذف محصولات دسته‌بندی</span>
            </base-animated-button>
          </div>
        </div>
      </form>
    </template>
  </partial-card>
</template>

<script setup>
import {computed, ref} from "vue";
import {useForm} from "vee-validate";
import yup from "../../../../validation/index.js";
import PartialCard from "../../../../components/partials/PartialCard.vue";
import BaseSelectSearchable from "../../../../components/base/BaseSelectSearchable.vue";
import PartialInputErrorMessage from "../../../../components/partials/PartialInputErrorMessage.vue";
import PartialInputLabel from "../../../../components/partials/PartialInputLabel.vue";
import LoaderCircle from "../../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../../transitions/VTransitionFade.vue";
import BaseAnimatedButton from "../../../../components/base/BaseAnimatedButton.vue";
import {CheckCircleIcon, XCircleIcon} from "@heroicons/vue/24/outline/index.js";
import {useRoute} from "vue-router";
import {apiRoutes} from "../../../../router/api-routes.js";
import {useRequest} from "../../../../composables/api-request.js";

const route = useRoute()
const idParam = computed(() => {
  const id = parseInt(route.params.id, 10)
  if (isNaN(id)) return route.params.id
  return id
})

const loading = ref(true)
const canSubmit = ref(true)

const submitOperation = ref(null)

const categories = ref({})
const selectedCategory = ref(null)

function categorySelectionChange(selected) {
  selectedCategory.value = selected
}

function searchCategory(query) {
  // useRequest(apiRoutes.admin.categories.index, {
  //     data: {
  //         query,
  //     },
  // }, {
  //     success: (response) => {
  //         products.value = response.data
  //     },
  //     finally: () => {
  //         loading.value = false
  //     }
  // })
}

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return

  if (submitOperation.value === 'add') {

  } else if (submitOperation.value === 'remove') {

  }
})

function handleSubmitOperation(operation) {
  submitOperation.value = operation
  onSubmit()
}
</script>

<style scoped>

</style>
