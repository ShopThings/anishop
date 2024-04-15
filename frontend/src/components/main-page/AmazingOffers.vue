<template>
  <div
      v-if="productsLoading || !products?.length"
      class="flex items-center gap-6 overflow-hidden"
  >
    <loader-card
        v-for="i in 5"
        :key="i"
        class="!w-80 rounded-lg shadow-lg !border-[3px] !border-rose-400"
    />
  </div>
  <template v-else>
    <partial-general-title
        container-class="mb-5 mt-6 p-2"
        line-class="bg-gradient-to-r from-yellow-500 via-orange-500 to-rose-600"
        title="پیشنهاد‌های شگفت‌انگیز"
        title-size="text-xl bg-clip-text text-transparent bg-gradient-to-r from-yellow-500 via-orange-500 to-rose-600"
        type="side"
    />

    <product-carousel :products="products">
      <template #slide="{slide}">
        <div class="w-full h-full">
          <product-card
              :product="slide"
              container-class="rounded-lg shadow-lg border-[3px] border-rose-400"
          />
        </div>
      </template>
    </product-carousel>
  </template>
</template>

<script setup>
import {onMounted, ref} from "vue";
import PartialGeneralTitle from "@/components/partials/PartialGeneralTitle.vue";
import ProductCarousel from "@/components/product/ProductCarousel.vue";
import ProductCard from "@/components/product/ProductCard.vue";
import LoaderCard from "@/components/base/loader/LoaderCard.vue";
import {HomeMainPageAPI} from "@/service/APIHomePages.js";

const emit = defineEmits(['loaded'])

const products = ref(null)
const productsLoading = ref(true)

onMounted(() => {
  HomeMainPageAPI.fetchSliderOffers({
    success(response) {
      products.value = response.data
      productsLoading.value = false
    },
    finally() {
      emit('loaded', !!products.value?.length)
    },
  })
})
</script>
