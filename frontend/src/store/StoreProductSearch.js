import {computed, ref} from "vue"
import {defineStore} from "pinia"

export const useProductSearchStore = defineStore('product_search_result', () => {
  let brands = ref(null)
  let categories = ref(null)
  let products = ref(null)

  const getBrands = computed(
    () => brands.value
  )
  const getCategories = computed(
    () => categories.value
  )
  const getProducts = computed(
    () => products.value
  )

  function setBrands(brands) {
    brands.value = brands
  }

  function setCategories(categories) {
    categories.value = categories
  }

  function setProducts(products) {
    products.value = products
  }

  function $reset() {
    brands.value = null
    categories.value = null
    products.value = null
  }

  return {
    brands, getBrands, setBrands,
    categories, getCategories, setCategories,
    products, getProducts, setProducts,
    //
    $reset,
  }
})
