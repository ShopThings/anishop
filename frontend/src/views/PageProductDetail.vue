<template>
  <div class="p-3">
    <product-detail
      :slider-top-spacing="110"
      @product-loaded="productLoadedHandler"
    />
  </div>

  <app-newsletter/>
</template>

<script setup>
import ProductDetail from "@/components/product/ProductDetail.vue";
import AppNewsletter from "@/components/AppNewsletter.vue";
import {useHomeSettingNoTimerStore} from "@/store/StoreSettings.js";
import {apiRoutes} from "@/router/api-routes.js";
import {FileSizes} from "@/composables/file-list.js";
import {reactive, ref} from "vue";
import {useHead} from "@unhead/vue";

const settingStore = useHomeSettingNoTimerStore()

function getAvailableLeastPriceProductVariant(product) {
  let niceOne = {
    available: false,
    price: null,
    oldPrice: null,
  }

  if (!product.is_available) return niceOne

  product.items.forEach(variant => {
    if (
      !variant.show_coming_soon &&
      !variant.show_call_for_more &&
      variant.is_available &&
      variant.stock_count > 0
    ) {
      if (niceOne.available) {
        if (!niceOne.price || variant.buyable_price < niceOne.price) {
          niceOne.price = variant.buyable_price
          niceOne.oldPrice = variant.price
        }
      } else {
        niceOne.price = variant.buyable_price
        niceOne.oldPrice = variant.price
      }
    } else {
      if (!niceOne.available && (!niceOne.price || variant.buyable_price < niceOne.price)) {
        niceOne.price = variant.buyable_price
        niceOne.oldPrice = variant.price
      }
    }
  })

  return niceOne
}

const localDescription = ref(settingStore.getDescription)
const localKeywords = ref(settingStore.getKeywords)
const properties = ref('')
const product = reactive({})
const usefulVariant = reactive({
  available: false,
  price: null,
  oldPrice: null,
})

//-------------------------------
// Add Torob meta tags
//-------------------------------
useHead({
  meta: [
    {name: 'product_id', content: product?.id},
    {name: 'product_name', content: product?.title},
    {name: 'availability', content: usefulVariant?.available ? 'instock' : 'outofstock'},
    {name: 'product_price', content: usefulVariant?.price},
    {
      name: 'product_old_price',
      content: usefulVariant?.price < usefulVariant?.oldPrice ? usefulVariant?.oldPrice : null,
    },
  ],
})
//-------------------------------

useHead({
  meta: [
    {property: 'og:title', content: product?.title},
    {
      property: 'og:image',
      content: product?.image
        ? import.meta.env.VITE_API_BASE_URL + apiRoutes.showFile + '?file=' + product.image.path + '&size=' + FileSizes.ORIGINAL
        : null,
    },

    {name: 'twitter:title', content: product?.title},
    {
      name: 'twitter:image:src',
      content: product?.image
        ? import.meta.env.VITE_API_BASE_URL + apiRoutes.showFile + '?file=' + product.image.path + '&size=' + FileSizes.ORIGINAL
        : null,
    },

    {itemprop: 'name', content: product?.title},
    {
      itemprop: 'image',
      content: product?.image
        ? import.meta.env.VITE_API_BASE_URL + apiRoutes.showFile + '?file=' + product.image.path + '&size=' + FileSizes.ORIGINAL
        : null,
    }
  ],
})

useSeoMeta({
  title: product?.title,
  description: properties.value.trim() !== '' ? [localDescription, properties] : localDescription,
  keywords: product?.keywords?.length
    ? [
      Array.isArray(localKeywords) ? localKeywords.join(', ') : localKeywords,
      product.keywords.join(', '),
    ]
    : Array.isArray(localKeywords) ? localKeywords.join(', ') : localKeywords,
})

function productLoadedHandler(loadedProduct) {
  localDescription.value = settingStore.getDescription
  localKeywords.value = settingStore.getKeywords

  properties.value = ''

  if (loadedProduct.quick_properties?.length) {
    loadedProduct.quick_properties.forEach(property => {
      properties.value += property.title
      properties.value += Array.isArray(property.tags) ? property.tags.join(', ') : property.tags
      properties.value += '\r\n';
    })
  }

  Object.assign(usefulVariant, getAvailableLeastPriceProductVariant(loadedProduct))
  Object.assign(product, loadedProduct)
}
</script>
