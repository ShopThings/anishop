<template>
  <base-paginator
    v-model:total="totalFavoriteProducts"
    :path="getPath"
    :per-page="10"
    container-class="flex flex-col gap-4"
    item-container-class="px-3"
    pagination-theme="modern"
  >
    <template #empty>
      <partial-empty-rows
        image="/images/empty-statuses/empty-fav-product.svg"
        image-class="w-72"
        message="هیچ محصولی به لیست علاقه‌مندی‌ها اضافه نشده است"
      />
    </template>

    <template #item="{item}">
      <div class="py-3 px-4 bg-white rounded-lg relative">
        <partial-builder-remove-btn
          v-tooltip.right="'حذف از علاقه‌مندی‌ها'"
          @click="handleRemoveFavProduct(item)"
        />

        <div class="flex flex-col sm:flex-row gap-3 items-center">
          <div class="shrink-0">
            <base-lazy-image
              :alt="item.product.title"
              :is-local="false"
              :lazy-src="item.product.image.path"
              class="!h-28 sm:!h-20 w-auto rounded"
            />
          </div>
          <div class="grow text-sm">
            {{ item.product.title }}
          </div>
          <div class="text-sm shrink-0">
            <router-link
              :to="{name: 'product.detail', params: {slug: item.product.slug}}"
              class="flex items-center gap-2 text-blue-600 hover:text-opacity-90 group"
            >
              <span class="mx-auto">مشاهده محصول</span>
              <ArrowLongLeftIcon class="w-6 h-6 group-hover:-translate-x-1.5 transition"/>
            </router-link>
          </div>
        </div>
      </div>
    </template>

    <template #loading>
      <loader-list-single/>
    </template>
  </base-paginator>
</template>

<script setup>
import {ref} from "vue";
import PartialEmptyRows from "@/components/partials/PartialEmptyRows.vue";
import {ArrowLongLeftIcon} from "@heroicons/vue/24/outline/index.js";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import PartialBuilderRemoveBtn from "@/components/partials/PartialBuilderRemoveBtn.vue";
import BasePaginator from "@/components/base/BasePaginator.vue";
import LoaderListSingle from "@/components/base/loader/LoaderListSingle.vue";
import {apiRoutes} from "@/router/api-routes.js";
import {UserPanelFavoriteProductAPI} from "@/service/APIUserPanel.js";
import {useToast} from "vue-toastification";
import {useConfirmToast} from "@/composables/toast-helper.js";

const toast = useToast()

const getPath = apiRoutes.user.favoriteProducts.index
const totalFavoriteProducts = ref(0)

function handleRemoveFavProduct(item) {
  useConfirmToast(() => {
    UserPanelFavoriteProductAPI.deleteById(item.id, {
      success() {
        toast.success('محصول از لیست علاقه‌مندی‌ها حذف شد.')
      },
    })
  }, 'حذف محصول از لیست علاقه‌مندی‌ها')
}
</script>
