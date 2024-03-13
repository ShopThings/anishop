import {computed, ref} from "vue"
import {defineStore} from "pinia"

export const useCreationProductStore = defineStore('product_creation', () => {
  let product = ref(null)

  const getProduct = computed(
    () => product.value
  )
  const getProductId = computed(() => product.value?.id)
  const getProductSlug = computed(() => product.value?.slug)

  function setProduct(createdProduct) {
    product.value = createdProduct
  }

  function $reset() {
    product.value = null
  }

  // refs' become states,
  // computed() become getters
  // and functions become actions
  return {
    product,
    getProduct,
    getProductId,
    getProductSlug,
    setProduct,
    //
    $reset,
  }
})
