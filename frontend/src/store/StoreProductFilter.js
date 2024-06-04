import {computed, ref} from "vue";
import {defineStore} from "pinia";
import {HomeProductAPI} from "@/service/APIHomePages.js";
import {useRoute} from "vue-router";
import isFunction from "lodash.isfunction";

export const useProductFilterStore = defineStore('product_search_filter', () => {
  const brands = ref([])
  const colors = ref([])
  const sizes = ref([])
  const priceRange = ref([0, 0])
  const attributes = ref([])
  //
  const isBrandsLoading = ref(true)
  const isColorAndSizeLoading = ref(true)
  const isPriceRangeLoading = ref(true)
  const isAttributesLoading = ref(true)

  const getBrands = computed(() => brands.value)
  const getColors = computed(() => colors.value)
  const getSizes = computed(() => sizes.value)
  const getPriceRange = computed(() => priceRange.value)
  const getDynamicFilters = computed(() => attributes.value)
  //
  const brandsLoading = computed(() => isBrandsLoading.value)
  const colorAndSizeLoading = computed(() => isColorAndSizeLoading.value)
  const priceRangeLoading = computed(() => isPriceRangeLoading.value)
  const attributesLoading = computed(() => isAttributesLoading.value)

  const route = useRoute()

  function setBrands(items) {
    brands.value = items
  }

  function setColors(items) {
    colors.value = items
  }

  function setSizes(items) {
    sizes.value = items
  }

  function setPriceRange(range) {
    priceRange.value = range
  }

  function setDynamicFilters(filters) {
    attributes.value = filters
  }

  function fetchBrands() {
    return HomeProductAPI.fetchBrandsFilter({
      category: route.query?.category,
      festival: route.query?.festival,
    }, {
      success(response) {
        setBrands(response.data)
      },
      error() {
        return false
      },
      finally() {
        isBrandsLoading.value = false
      },
    })
  }

  function fetchColorsAndSizes() {
    return HomeProductAPI.fetchColorsAndSizesFilter({
      category: route.query?.category,
      festival: route.query?.festival,
    }, {
      success(response) {
        let data = response.data

        let colors = []
        let sizes = []

        let counter = 1;
        for (let i of data) {
          if (i.name && i.name?.toString() !== '') {
            colors.push({
              id: counter,
              name: i.name,
              hex: i.hex,
            })
          }

          if (i.size?.toString() !== '') {
            sizes.push({
              id: counter,
              size: i.size,
            })
          }

          counter++
        }

        setColors(colors)
        setSizes(sizes)
      },
      error() {
        return false
      },
      finally() {
        isColorAndSizeLoading.value = false
      },
    })
  }

  function fetchPriceRange(onSuccess = null) {
    return HomeProductAPI.fetchPriceRangeFilter({
      category: route.query?.category,
      festival: route.query?.festival,
    }, {
      success(response) {
        let data = response.data
        let minmaxPrice = [0, 0]

        if (data?.min && data?.max) {
          minmaxPrice = [data.min, data.max]

          if (onSuccess && isFunction(onSuccess)) {
            onSuccess(minmaxPrice)
          }
        }

        setPriceRange(minmaxPrice)
      },
      error() {
        return false
      },
      finally() {
        isPriceRangeLoading.value = false
      },
    })
  }

  function fetchDynamicFilters(onSuccess = null) {
    return HomeProductAPI.fetchDynamicFilters({
      category: route.query?.category,
      festival: route.query?.festival,
    }, {
      success(response) {
        if (onSuccess && isFunction(onSuccess)) {
          onSuccess(response.data)
        }

        setDynamicFilters(response.data)
      },
      error() {
        return false
      },
      finally() {
        isAttributesLoading.value = false
      },
    })
  }

  function $reset() {
    brands.value = []
    colors.value = []
    sizes.value = []
    priceRange.value = [0, 0]
    attributes.value = []
    //
    isBrandsLoading.value = true
    isColorAndSizeLoading.value = true
    isPriceRangeLoading.value = true
    isAttributesLoading.value = true
  }

  fetchBrands()
  fetchColorsAndSizes()
  fetchPriceRange()
  fetchDynamicFilters()

  return {
    brands, getBrands, setBrands,
    colors, getColors, setColors,
    sizes, getSizes, setSizes,
    priceRange, getPriceRange, setPriceRange,
    attributes, getDynamicFilters, setDynamicFilters,
    //
    brandsLoading, colorAndSizeLoading, priceRangeLoading, attributesLoading,
    //
    fetchBrands, fetchColorsAndSizes, fetchPriceRange, fetchDynamicFilters,
    //
    $reset,
  }
})

export const useProductFilterParamStore = defineStore('product_search_filter_param', () => {
  const route = useRoute()
  const searchParams = ref({})

  const getSearchParams = computed(() => searchParams.value)

  const getBrands = computed(() => searchParams.value?.brands)
  const getPriceRange = computed(() => searchParams.value?.price_range)
  const getOnlyAvailable = computed(() => searchParams.value?.only_available)
  const getIsSpecial = computed(() => searchParams.value?.is_special)
  const getDynamicFilters = computed(() => searchParams.value?.dynamic_filters)

  const getOrder = computed(() => searchParams.value?.order)
  const getFestival = computed(() => searchParams.value?.festival)
  const getCategory = computed(() => searchParams.value?.category)

  function setBrands(value = null, removeFalseValues = true) {
    if (value) {
      value = Array.isArray(value) ? value : [value]
    }

    searchParams.value.brands = value

    clearSearchParams(removeFalseValues)
  }

  function removeBrands() {
    delete searchParams.value.brands
  }

  function setPriceRange(minPrice, maxPrice, removeFalseValues = true) {
    minPrice = parseInt(minPrice, 10)
    maxPrice = parseInt(maxPrice, 10)

    if (!isNaN(minPrice) && !isNaN(maxPrice)) {
      searchParams.value.price_range = [minPrice, maxPrice]
    }

    clearSearchParams(removeFalseValues)
  }

  function removePriceRange() {
    delete searchParams.value.price_range
  }

  function setOnlyAvailable(value, removeFalseValues = true) {
    searchParams.value.only_available = value?.toString() === 'true'

    if (!searchParams.value?.only_available) {
      delete searchParams.value.only_available
    }

    clearSearchParams(removeFalseValues)
  }

  function removeOnlyAvailable() {
    delete searchParams.value.only_available
  }

  function setIsSpecial(value, removeFalseValues = true) {
    searchParams.value.is_special = value?.toString() === 'true'

    if (!searchParams.value?.is_special) {
      delete searchParams.value.is_special
    }

    clearSearchParams(removeFalseValues)
  }

  function removeIsSpecial() {
    delete searchParams.value.is_special
  }

  function setDynamicFilters(value, removeFalseValues = true) {
    searchParams.value.dynamic_filters = value

    clearSearchParams(removeFalseValues)
  }

  function removeDynamicFilters() {
    delete searchParams.value.dynamic_filters
  }

  function setOrder(value, removeFalseValues = true) {
    searchParams.value.order = value

    clearSearchParams(removeFalseValues)
  }

  function removeOrder() {
    delete searchParams.value.order
  }

  function setFestival(value, removeFalseValues = true) {
    searchParams.value.festival = value

    clearSearchParams(removeFalseValues)
  }

  function removeFestival() {
    delete searchParams.value.festival
  }

  function setCategory(value, removeFalseValues = true) {
    searchParams.value.category = value

    clearSearchParams(removeFalseValues)
  }

  function removeCategory() {
    delete searchParams.value.category
  }

  function readFiltersFromRoute(removeFalseValues = true) {
    if (route.query?.min_price && route.query?.max_price) {
      setPriceRange(route.query.min_price, route.query.max_price)
    }
    setOnlyAvailable(route.query?.only_available)
    setIsSpecial(route.query?.is_special)
    setDynamicFilters(route.query?.dynamic_filters)
    setCategory(route.query?.category)
    setFestival(route.query?.festival)
    setOrder(route.query?.order)

    // handle brands array or a single brand
    if (route.query?.brands && Array.isArray(route.query.brands) && route.query.brands.length) {
      setBrands(route.query.brands)
    } else if (route.query?.brand) {
      setBrands(route.query.brand)
    }

    clearSearchParams(removeFalseValues)
  }

  function getRouteQueryObject() {
    let queryObj = {
      query: {
        brands: getBrands.value,
        only_available: getOnlyAvailable.value,
        is_special: getIsSpecial.value,
        dynamic_filters: getDynamicFilters.value,
        order: getOrder.value,
      }
    }

    let priceRange = getPriceRange.value
    if (priceRange && priceRange[0] && priceRange[1]) {
      queryObj.query.min_price = priceRange[0]
      queryObj.query.max_price = priceRange[1]
    }

    return queryObj
  }

  function resetSearchParams(except = []) {
    let exceptions = {}

    if (except.length) {
      except.forEach(item => {
        if (searchParams.value[item]) {
          exceptions[item] = searchParams.value[item]
        }
      })
    }

    $reset()

    if (Object.keys(exceptions).length) {
      for (let o in exceptions) {
        if (exceptions.hasOwnProperty(o)) {
          searchParams.value[o] = exceptions[o]
        }
      }
    }
  }

  function clearSearchParams(removeFalseValues = true) {
    for (let o in searchParams.value) {
      if (searchParams.value.hasOwnProperty(o)) {
        if (
          searchParams.value[o] === null ||
          searchParams.value[o] === undefined ||
          (
            Array.isArray(searchParams.value[o]) &&
            !searchParams.value[o].map(item => item !== null && item !== undefined).length
          ) ||
          (
            removeFalseValues &&
            searchParams.value[o] === false
          )
        ) {
          delete searchParams.value[o]
        }
      }
    }
  }

  function $reset() {
    searchParams.value = {}
  }

  return {
    getSearchParams, searchParams,
    getBrands, setBrands, removeBrands,
    getPriceRange, setPriceRange, removePriceRange,
    getOnlyAvailable, setOnlyAvailable, removeOnlyAvailable,
    getIsSpecial, setIsSpecial, removeIsSpecial,
    getDynamicFilters, setDynamicFilters, removeDynamicFilters,
    getOrder, setOrder, removeOrder,
    getFestival, setFestival, removeFestival,
    getCategory, setCategory, removeCategory,
    //
    readFiltersFromRoute, getRouteQueryObject,
    resetSearchParams, clearSearchParams,
    //
    $reset,
  }
})
