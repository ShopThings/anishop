<template>
  <partial-card>
    <template #header>
      ویرایش اسلایدهای اسلایدر
      <span
        v-if="slider?.id"
        class="text-slate-400 text-base"
      >{{ slider?.title }}</span>
    </template>
    <template #body>
      <div class="p-3">
        <base-loading-panel
          :loading="loading"
          type="form"
        >
          <template #content>
            <base-message
              v-if="errors && Object.keys(errors).length"
              type="error"
            >
              <ul class="leading-relaxed flex flex-col gap-3 list-inside list-disc">
                <li
                  v-for="(err, idx) in errors"
                  :key="idx"
                >
                  {{ err }}
                </li>
              </ul>
            </base-message>

            <form @submit.prevent="onSubmit">
              <div class="p-2">
                <draggable
                  v-if="slides && slides.length"
                  :animation="200"
                  :group="{ name: 'slides' }"
                  :list="slides"
                  ghost-class="ghost"
                  handle=".handle"
                  item-key="tmpId"
                  tag="ul"
                >
                  <template #item="{ element, index }">
                    <li class="pt-7 px-2 border-2 border-dashed border-slate-300 rounded-lg mb-3">
                      <div class="my-2 relative">
                        <base-button
                          v-if="!isBlogSidePlace"
                          class="!absolute top-0 left-0 -translate-y-9 -translate-x-1/4 bg-rose-500 !p-1 z-[1]"
                          @click="removeSlideHandler(index)"
                        >
                          <TrashIcon class="h-5 w-5"/>
                        </base-button>

                        <base-button
                          v-tooltip.left="'برای جابجایی بکشید'"
                          :class="[
                              'handle cursor-grab active:cursor-grabbing w-20 !rounded-tl-none !rounded-br-none',
                              '!absolute top-0 right-0 -translate-y-9 translate-x-2 bg-gray-100 !py-1 z-[1]',
                              'border-b-2 border-l-2 !border-t-none !border-r-none',
                          ]"
                        >
                          <Bars2Icon class="h-6 w-6 text-gray-500 mx-auto"/>
                        </base-button>

                        <template v-if="isBlogPlace || isBlogSidePlace">
                          <div class="flex flex-wrap">
                            <div class="p-2 w-full md:w-1/2">
                              <partial-input-label title="انتخاب بلاگ"/>
                              <base-select-searchable
                                :current-page="blogSelectConfig.currentPage.value"
                                :has-pagination="true"
                                :is-loading="blogLoading"
                                :is-local-search="false"
                                :last-page="blogSelectConfig.lastPage.value"
                                :name="'blog' + element.tmpId"
                                :options="blogs"
                                options-key="id"
                                options-text="title"
                                placeholder="جستجوی بلاگ..."
                                :selected="element?.blog"
                                @change="(selected) => {element.blog = selected}"
                                @query="searchBlog"
                                @click-next-page="searchBlogNextPage"
                                @click-prev-page="searchBlogPrevPage"
                              >
                                <template #item="{item}">
                                  <div class="flex items-center gap-3">
                                    <base-lazy-image
                                      :alt="item.title"
                                      :is-local="false"
                                      :lazy-src="item?.image?.full_path"
                                      :size="FileSizes.SMALL"
                                      class="!w-16 !h-auto object-cover rounded"
                                    />

                                    <span class="text-sm">{{ item.title }}</span>
                                  </div>
                                </template>
                              </base-select-searchable>
                              <partial-input-error-message :error-message="errors.blog"/>
                            </div>
                          </div>
                        </template>
                        <template v-else-if="isAmazingOfferPlace">
                          <div class="flex flex-wrap">
                            <div class="p-2 w-full md:w-1/2">
                              <partial-input-label title="انتخاب محصول"/>
                              <base-select-searchable
                                :current-page="productSelectConfig.currentPage.value"
                                :has-pagination="true"
                                :is-loading="productLoading"
                                :is-local-search="false"
                                :last-page="productSelectConfig.lastPage.value"
                                :name="'product' + element.tmpId"
                                :options="products"
                                options-key="id"
                                options-text="title"
                                placeholder="جستجوی محصول..."
                                :selected="element?.product"
                                @change="(selected) => {element.product = selected}"
                                @query="searchProduct"
                                @click-next-page="searchProductNextPage"
                                @click-prev-page="searchProductPrevPage"
                              >
                                <template #item="{item}">
                                  <div class="flex items-center gap-3">
                                    <base-lazy-image
                                      :alt="item.title"
                                      :is-local="false"
                                      :lazy-src="item?.image?.path"
                                      :size="FileSizes.SMALL"
                                      class="!w-16 !h-auto object-cover rounded"
                                    />

                                    <span class="text-sm">{{ item.title }}</span>
                                  </div>
                                </template>
                              </base-select-searchable>
                              <partial-input-error-message :error-message="errors.product"/>
                            </div>
                          </div>
                        </template>
                        <template v-else>
                          <div class="p-2 flex flex-col md:flex-row items-center md:items-end gap-4">
                            <div class="flex flex-col items-center">
                              <partial-input-label title="انتخاب تصویر"/>
                              <base-media-placeholder
                                v-model:selected="element.image"
                                type="image"
                              />
                            </div>
                            <div class="p-2 w-full md:w-2/3">
                              <base-input
                                :name="'link' + element.tmpId"
                                :value="element?.link"
                                label-title="لینک"
                                placeholder="وارد نمایید"
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

                  <span class="ml-auto">ویرایش اسلایدها</span>
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
import {computed, onMounted, ref, watch} from "vue";
import draggable from "vuedraggable";
import PartialCard from "@/components/partials/PartialCard.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {Bars2Icon, CheckIcon, CurrencyDollarIcon, PlusIcon, TrashIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {useToast} from "vue-toastification";
import uniqueId from "lodash.uniqueid";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseMediaPlaceholder from "@/components/base/BaseMediaPlaceholder.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import {SLIDER_ITEM_OPTIONS, SLIDER_PLACES} from "@/composables/constants.js";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import BaseSelectSearchable from "@/components/base/BaseSelectSearchable.vue";
import {getRouteParamByKey} from "@/composables/helper.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {ProductAPI} from "@/service/APIProduct.js";
import {useSelectSearching} from "@/composables/select-searching.js";
import {BlogAPI} from "@/service/APIBlog.js";
import {SliderAPI} from "@/service/APIConfig.js";
import BaseMessage from "@/components/base/BaseMessage.vue";
import {FileSizes} from "@/composables/file-list.js";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import {useRouter} from "vue-router";

const router = useRouter()
const toast = useToast()
const idParam = getRouteParamByKey('id')

const loading = ref(true)

const slider = ref(null)
const slides = ref([])

//-----------------------------
const isMainSlidersPlace = computed(() => {
  return slider.value?.place_in?.value === SLIDER_PLACES.MAIN_SLIDERS.value
})
const isBlogPlace = computed(() => {
  return slider.value?.place_in?.value === SLIDER_PLACES.MAIN_BLOG.value
})
const isBlogSidePlace = computed(() => {
  return slider.value?.place_in?.value === SLIDER_PLACES.MAIN_BLOG_SIDE.value
})
const isAmazingOfferPlace = computed(() => {
  return slider.value?.place_in?.value === SLIDER_PLACES.AMAZING_OFFER.value
})

//-----------------------------
function removeSlideHandler(idx) {
  if (isBlogSidePlace.value) return

  if (slides.value[idx]) {
    slides.value.splice(idx, 1)
  }
}

function handleNewSlideClick() {
  if (isBlogSidePlace.value) return

  slides.value.push({
    id: null,
    tmpId: parseInt(uniqueId()),
    link: '',
    image: null,
    blog: null,
    product: null,
  })
}

//-----------------------------
// Search in blog
//-----------------------------
const blogs = ref([])
const blogSelectConfig = useSelectSearching({
  searchFn(query) {
    BlogAPI.fetchAll({
      only_published: true,
      limit: blogSelectConfig.limit.value,
      offset: blogSelectConfig.offset(),
      text: query
    }, {
      success(response) {
        blogs.value = response.data
        if (response.meta) {
          blogSelectConfig.lastPage.value = response.meta?.last_page
        }
      },
      finally() {
        blogSelectConfig.isLoading.value = false
      }
    })
  },
})
const searchBlog = blogSelectConfig.search
const blogLoading = blogSelectConfig.isLoading
const searchBlogNextPage = blogSelectConfig.searchNextPage
const searchBlogPrevPage = blogSelectConfig.searchPrevPage

//-----------------------------
// Product search
//-----------------------------
const products = ref([])
const productSelectConfig = useSelectSearching({
  searchFn(query) {
    ProductAPI.fetchAll({
      only_published: true,
      limit: productSelectConfig.limit.value,
      offset: productSelectConfig.offset(),
      text: query
    }, {
      success(response) {
        products.value = response.data
        if (response.meta) {
          productSelectConfig.lastPage.value = response.meta?.last_page
        }
      },
      finally() {
        productSelectConfig.isLoading.value = false
      }
    })
  },
})
const searchProduct = productSelectConfig.search
const productLoading = productSelectConfig.isLoading
const searchProductNextPage = productSelectConfig.searchNextPage
const searchProductPrevPage = productSelectConfig.searchPrevPage

//-----------------------------
const hasItemsBlogSelected = computed(() => {
  let counter = 0
  for (let i of slides.value) {
    if (i.blog?.id) {
      counter++
    }
  }

  return counter === slides.value.length
})
const hasItemsProductSelected = computed(() => {
  let counter = 0
  for (let i of slides.value) {
    if (i.product?.id) {
      counter++
    }
  }

  return counter === slides.value.length
})

const {canSubmit, errors, onSubmit} = useFormSubmit({}, (values, actions) => {
  if (isBlogSidePlace.value && slides.value.length !== 2) {
    toast.error('بایستی ۲ اسلاید وارد نمایید!')
    return
  }

  if (isBlogPlace.value && !hasItemsBlogSelected.value) {
    toast.error('بلاگ برای آیتم انتخاب نشده است.')
    return
  }

  if (isAmazingOfferPlace.value && !hasItemsProductSelected.value) {
    toast.error('محصول برای آیتم انتخاب نشده است.')
    return
  }

  canSubmit.value = false

  SliderAPI.modifySliderItems(idParam.value, {
    slides: slides.value,
  }, {
    success(response) {
      toast.success('اسلایدها با موفقیت بروزرسانی شدند.')

      setFormFields(response.data)
    },
    error(error) {
      actions.setErrors(error.errors)
    },
    finally() {
      canSubmit.value = true
    },
  })
})

//-----------------------------
watch([slider, slides], () => {
  if (!slides.value.length && isBlogSidePlace.value) {
    for (let i = 0; i < 2; ++i) {
      slides.value.push({
        id: null,
        tmpId: parseInt(uniqueId()),
        link: '',
        image: null,
        blog: null,
        product: null,
      })
    }
  }
})

onMounted(() => {
  SliderAPI.fetchById(idParam.value, {
    success(response) {
      slider.value = response.data

      if (isMainSlidersPlace.value) {
        router.push({name: 'admin.sliders'})
        return
      }

      SliderAPI.fetchSliderItems(idParam.value, {
        success: (response) => {
          setFormFields(response.data)

          if (isBlogPlace || isBlogSidePlace) {
            searchBlog()
          }
          if (isAmazingOfferPlace) {
            searchProduct()
          }

          loading.value = false
        },
      })
    },
  })
})

function setFormFields(item) {
  if (!item?.length) return

  slides.value = []

  for (let i of item) {
    if (i?.id) {
      let options = i.options
      slides.value.push({
        id: i.id,
        tmpId: parseInt(uniqueId()),
        link: options[SLIDER_ITEM_OPTIONS.LINK.value] ?? '',
        image: options[SLIDER_ITEM_OPTIONS.IMAGE.value] ?? null,
        blog: options[SLIDER_ITEM_OPTIONS.BLOG_ID.value] ?? null,
        product: options[SLIDER_ITEM_OPTIONS.PRODUCT_ID.value] ?? null,
      })
    }
  }
}
</script>

<style scoped>
.ghost {
  background: rgb(226 232 240 / .5);
  border-radius: .5rem;
}
</style>
