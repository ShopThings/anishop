<template>
  <base-loading-panel
      :loading="loading"
      type="form"
  >
    <template #content>
      <form>
        <partial-card class="mb-3 p-3 relative">
          <template #body>
            <loader-dot-orbit
                v-if="!canSubmit"
                container-bg-color="bg-blue-50 opacity-40"
                main-container-klass="absolute w-full h-full top-0 left-0 z-[2]"
            />

            <div class="flex items-end">
              <div class="flex flex-wrap">
                <TransitionGroup name="fade-group">
                  <div
                      v-for="(image, idx) in images"
                      :key="idx"
                  >
                    <div class="p-4 flex flex-col relative">
                      <partial-builder-remove-btn
                          v-if="images.length > 1"
                          class="bottom-0 top-auto !-translate-x-0"
                          @click="handleRemoveImage(idx)"
                      />

                      <partial-input-label
                          title="انتخاب تصویر"
                      />
                      <base-media-placeholder
                          :selected="image"
                          type="image"
                      />
                    </div>
                  </div>
                </TransitionGroup>
              </div>

              <div class="shrink-0 p-4">
                <base-button
                    v-tooltip.top-end="'افزودن تصویر جدید'"
                    class="!rounded-full border-2 border-dashed p-4 w-16 h-16 flex items-center justify-center border-orange-400"
                    @click="handleNewImageClick"
                >
                  <PlusIcon class="w-6 h-6 text-gray-500"/>
                </base-button>
              </div>
            </div>
          </template>
        </partial-card>

        <partial-card>
          <template #body>
            <partial-stepy-next-prev-buttons
                :allow-next-step="canSubmit"
                :allow-prev-step="canSubmit"
                :current-step="options.currentStep"
                :current-step-index="options.currentStepIndex"
                :last-step="options.lastStep"
                :loading="!canSubmit"
                :show-prev-step-button="canSubmit"
                @next="handleNextClick(options.next)"
                @prev="options.prev"
            />

            <div
              v-if="Object.keys(errors)?.length"
              class="text-left px-3.5 mb-3"
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
          </template>
        </partial-card>
      </form>
    </template>
  </base-loading-panel>
</template>

<script setup>
import {onMounted, ref} from "vue";
import {PlusIcon} from "@heroicons/vue/24/outline/index.js"
import PartialCard from "@/components/partials/PartialCard.vue";
import PartialStepyNextPrevButtons from "@/components/partials/PartialStepyNextPrevButtons.vue";
import BaseMediaPlaceholder from "@/components/base/BaseMediaPlaceholder.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import PartialBuilderRemoveBtn from "@/components/partials/PartialBuilderRemoveBtn.vue";
import LoaderDotOrbit from "@/components/base/loader/LoaderDotOrbit.vue";
import {useToast} from "vue-toastification";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {ProductAPI} from "@/service/APIProduct.js";
import {getRouteParamByKey} from "@/composables/helper.js";

defineProps({
  options: {
    type: Object,
    required: true,
  },
})
const emit = defineEmits(['add', 'remove'])

const toast = useToast()
const slugParam = getRouteParamByKey('slug', null, false)

const loading = ref(true)

//------------------------
// Product new instance
//------------------------
const images = ref([
  {
    image: null,
  },
])

function handleNewImageClick() {
  images.value.push({
    image: null,
  })

  emit('add', images.value)
}

function handleRemoveImage(idx) {
  images.value.splice(idx, 1)

  emit('remove', images.value)
}

//------------------------

let nextFn = null

function handleNextClick(next) {
  onSubmit()
  nextFn = next
}

const {canSubmit, errors, onSubmit} = useFormSubmit({},
    (values, actions) => {
      const definedImages = []
      for (let i of images.value) {
        if (i && i.image?.full_name) {
          definedImages.push(i.image.full_path)
        }
      }

      if (!definedImages.length) {
        toast.error('انتخاب حداقل یک تصویر برای گالری تصاویر، الزامی می‌باشد.')
        return
      }

      canSubmit.value = false

      ProductAPI.createGallery(slugParam.value, {
        images: definedImages,
      }, {
        success() {
          if (nextFn) nextFn()
        },
        finally() {
          canSubmit.value = true
        },
      })
    }
)

onMounted(() => {
  ProductAPI.fetchGallery(slugParam.value, {
    success: (response) => {
      images.value = response.data
      loading.value = false
    },
  })
})
</script>

<style scoped>
.fade-group-enter-active,
.fade-group-leave-active {
  transition: opacity 0.3s ease;
  transition-delay: .05s;
}

.fade-group-enter-from,
.fade-group-leave-to {
  opacity: 0;
}
</style>
