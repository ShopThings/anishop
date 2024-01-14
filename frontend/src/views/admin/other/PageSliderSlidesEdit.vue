<template>
  <partial-card>
    <template #header>
      ویرایش اسلایدهای اسلایدر
      <span
        v-if="slider?.id"
        class="text-teal-600"
      >{{ slider?.title }}</span>
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
                <draggable
                  v-if="slides && slides.length"
                  item-key="id"
                  tag="ul"
                  :animation="200"
                  :list="slides"
                  :group="{ name: 'slides' }"
                  handle=".handle"
                  ghost-class="ghost"
                >
                  <template #item="{ element, index }">
                    <li class="pt-6 px-2 border-2 border-dashed border-slate-300 rounded-lg mb-3">
                      <div class="mt-2 relative mb-4">
                        <base-button
                          v-if="!isBlogSidePlace"
                          class="!absolute top-0 left-0 -translate-y-8 -translate-x-1/4 bg-rose-500 !p-1 z-[1]"
                          @click="removeSlideHandler(index)"
                        >
                          <TrashIcon class="h-5 w-5"/>
                        </base-button>

                        <base-button
                          v-tooltip.left="'برای جابجایی بکشید'"
                          :class="[
                                'handle cursor-grab active:cursor-grabbing !px-8 sm:!px-10 !rounded-t-none !rounded-br-none',
                                '!absolute top-0 right-0 -translate-y-8 translate-x-2 bg-gray-100 !py-1 z-[1]',
                                'border-b-2 border-l-2 !border-t-none !border-r-none',
                            ]"
                        >
                          <Bars2Icon class="h-6 w-6 text-gray-500"/>
                        </base-button>

                        <template v-if="isBlogPlace || isBlogSidePlace">
                          <div class="flex flex-wrap">
                            <div class="p-2 w-full md:w-1/2">
                              <partial-input-label title="انتخاب بلاگ"/>
                              <base-select-searchable
                                :options="blogs"
                                options-key="id"
                                options-text="title"
                                :name="'blog' + element.id"
                                :is-loading="searchBlogLoading"
                                :is-local-search="false"
                                placeholder="جستجوی بلاگ..."
                                @change="(selected) => {element.blog = selected}"
                                @query="searchBlog"
                              />
                              <partial-input-error-message
                                :error-message="errors.blog"/>
                            </div>
                          </div>
                        </template>
                        <template v-else-if="isAmazingOfferPlace">
                          <div class="flex flex-wrap">
                            <div class="p-2 w-full md:w-1/2">
                              <partial-input-label title="انتخاب محصول"/>
                              <base-select-searchable
                                :options="products"
                                options-key="id"
                                options-text="title"
                                :name="'product' + element.id"
                                :is-loading="searchProductLoading"
                                :is-local-search="false"
                                placeholder="جستجوی محصول..."
                                @change="(selected) => {element.product = selected}"
                                @query="searchProduct"
                              />
                              <partial-input-error-message
                                :error-message="errors.product"/>
                            </div>
                          </div>
                        </template>
                        <template v-else>
                          <div class="p-2 flex flex-col items-center">
                            <partial-input-label
                              title="انتخاب تصویر"
                            />
                            <base-media-placeholder
                              type="image"
                              :selected="element?.image"
                            />
                          </div>

                          <div class="flex flex-wrap">
                            <div class="p-2 w-full md:w-2/3">
                              <base-input
                                label-title="لینک"
                                placeholder="وارد نمایید"
                                :name="'link' + element.id"
                                :value="element?.link"
                                @input="(v) => {element.link = v}"
                              >
                                <template #icon>
                                  <CurrencyDollarIcon class="h-6 w-6 text-gray-400"/>
                                </template>
                              </base-input>
                            </div>
                          </div>
                        </template>
                      </div>
                    </li>
                  </template>
                </draggable>

                <div
                  v-if="!isBlogSidePlace"
                  class="mt-3 mb-1"
                >
                  <base-button
                    class="!text-orange-600 border-orange-400 w-full sm:w-auto flex items-center hover:bg-orange-50 mr-auto"
                    @click="handleNewSlideClick"
                  >
                    <span class="mr-auto text-sm">ساخت اسلاید</span>
                    <PlusIcon class="h-6 w-6 mr-auto sm:mr-2"/>
                  </base-button>
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

                  <span class="ml-auto">ویرایش اسلایدها</span>
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
import {computed, onMounted, reactive, ref} from "vue";
import {useForm} from "vee-validate";
import yup from "@/validation/index.js";
import draggable from "vuedraggable";
import PartialCard from "@/components/partials/PartialCard.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {
  CheckIcon,
  CurrencyDollarIcon,
  PlusIcon,
  Bars2Icon, TrashIcon
} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {useToast} from "vue-toastification";
import {useRoute} from "vue-router";
import uniqueId from "lodash.uniqueid";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseMediaPlaceholder from "@/components/base/BaseMediaPlaceholder.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import {SLIDER_PLACES} from "@/composables/constants.js";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import BaseSelectSearchable from "@/components/base/BaseSelectSearchable.vue";

const route = useRoute()
const toast = useToast()
const idParam = computed(() => {
  const id = parseInt(route.params.id, 10)
  if (isNaN(id)) return route.params.id
  return id
})

const loading = ref(false)
const canSubmit = ref(true)

const slider = ref(null)
const slides = reactive([])

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})

function removeSlideHandler(idx) {
  if (slides[idx])
    slides.splice(idx, 1)
}

function handleNewSlideClick() {
  slides.push({
    id: parseInt(uniqueId()),
    link: '',
    image: null,
    blog: null,
    product: null,
  })
}

//-----------------------------
// Search in blog
//-----------------------------
const isBlogPlace = computed(() => {
  return slider?.slider_place.place_in === SLIDER_PLACES.MAIN_BLOG.value
})
const isBlogSidePlace = computed(() => {
  return slider?.slider_place.place_in === SLIDER_PLACES.MAIN_BLOG_SIDE.value
})

const blogs = ref([])
const searchBlogLoading = ref(true)

function searchBlog(query) {
  // searchBlogLoading.value = true
  // useRequest(apiRoutes.admin.blogs.index, {
  //     data: {
  //         query,
  //     },
  // }, {
  //     success: (response) => {
  //         blogs.value = response.data
  //     },
  //     finally: () => {
  //         searchBlogLoading.value = false
  //     }
  // })
}

//-----------------------------

//-----------------------------
// Search in product
//-----------------------------
const isAmazingOfferPlace = computed(() => {
  return slider?.slider_place.place_in === SLIDER_PLACES.AMAZING_OFFER.value
})

const products = ref([])
const searchProductLoading = ref(true)

function searchProduct(query) {
  // searchProductLoading.value = true
  // useRequest(apiRoutes.admin.products.index, {
  //     data: {
  //         query,
  //     },
  // }, {
  //     success: (response) => {
  //         products.value = response.data
  //     },
  //     finally: () => {
  //         searchProductLoading.value = false
  //     }
  // })
}

//-----------------------------

onMounted(() => {
  // useRequest(apiReplaceParams(apiRoutes.admin.sliders.show, {slider: idParam.value}), null, {
  //     success: (response) => {
  //         slider.value = response.data
  //         slides.value = response.data.items
  //
  //         loading.value = false
  //     },
  // })
})
</script>

<style scoped>
.ghost {
  background: rgb(226 232 240 / .5);
  border-radius: .5rem;
}
</style>
