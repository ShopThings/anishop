import {computed, reactive, ref, toValue} from "vue";
import {defineStore} from "pinia";
import {HomeProductAPI} from "@/service/APIHomePages.js";
import {useRoute} from "vue-router";
import isFunction from "lodash.isfunction";
import isObject from "lodash.isobject";

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

  function setColors(items) {
    colors.value = items
  }

  function setSizes(items) {
    sizes.value = items
  }

  function setBrands(items) {
    brands.value = items
  }

  function setPriceRange(range) {
    priceRange.value = range
  }

  function setDynamicFilters(filters) {
    attributes.value = filters
  }

  function fetchColorsAndSizes(onSuccess = null) {
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
          if (i.name && i.name?.toString() !== '' && colors.findIndex(item => item.name === i.name) === -1) {
            colors.push({
              id: counter,
              name: i.name,
              hex: i.hex,
            })
          }

          if (i.size && i.size?.toString() !== '' && sizes.findIndex(item => item.name === i.size) === -1) {
            sizes.push({
              id: counter,
              size: i.size,
            })
          }

          counter++
        }

        setColors(colors)
        setSizes(sizes)

        if (onSuccess && isFunction(onSuccess)) {
          onSuccess()
        }

        isColorAndSizeLoading.value = false
      },
      error() {
        isColorAndSizeLoading.value = false
        return false
      },
    })
  }

  function fetchBrands(onSuccess = null) {
    return HomeProductAPI.fetchBrandsFilter({
      category: route.query?.category,
      festival: route.query?.festival,
    }, {
      success(response) {
        setBrands(response.data)

        if (onSuccess && isFunction(onSuccess)) {
          onSuccess()
        }
      },
      error() {
        return false
      },
      finally() {
        isBrandsLoading.value = false
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
        }

        setPriceRange(minmaxPrice)

        if (data?.min && data?.max) {
          if (onSuccess && isFunction(onSuccess)) {
            onSuccess(minmaxPrice)
          }
        }
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
    colors.value = []
    sizes.value = []
    brands.value = []
    priceRange.value = [0, 0]
    attributes.value = []
    //
    isBrandsLoading.value = true
    isColorAndSizeLoading.value = true
    isPriceRangeLoading.value = true
    isAttributesLoading.value = true
  }

  function fetchAllFiltersAtOnce(callbacks) {
    return Promise.all([
      fetchColorsAndSizes(),
      fetchBrands(),
      fetchPriceRange(),
      fetchDynamicFilters(),
    ]).then(async (data) => {
      if (callbacks.success && isFunction(callbacks.success)) {
        callbacks.success(data)
      }
    }).catch((err) => {
      if (callbacks.error && isFunction(callbacks.error)) {
        callbacks.error(err)
      }
    }).finally(() => {
      if (callbacks.finally && isFunction(callbacks.finally)) {
        callbacks.finally()
      }
    })
  }

  return {
    colors, getColors, setColors,
    sizes, getSizes, setSizes,
    brands, getBrands, setBrands,
    priceRange, getPriceRange, setPriceRange,
    attributes, getDynamicFilters, setDynamicFilters,
    //
    colorAndSizeLoading, brandsLoading, priceRangeLoading, attributesLoading,
    //
    fetchColorsAndSizes, fetchBrands, fetchPriceRange, fetchDynamicFilters,
    //
    fetchAllFiltersAtOnce,
    //
    $reset,
  }
})

export const useProductFilterParamStore = defineStore('product_search_filter_param', () => {
  const route = useRoute()
  const filterStore = useProductFilterStore()

  const brands = ref({})
  const colors = ref({})
  const sizes = ref({})
  const priceRange = ref([])
  const onlyAvailable = ref(false)
  const isSpecial = ref(false)
  const dynamicFilters = ref({})

  const order = ref(null)
  const festival = ref(null)
  const category = ref(null)

  const getColors = computed(() => {
    if (isObject(colors.value)) {
      return Object.values(colors.value).map(item => item?.name)
    } else {
      removeColors()
    }

    return []
  })

  const getSizes = computed(() => {
    if (isObject(sizes.value)) {
      return Object.values(sizes.value).map(item => item?.size)
    } else {
      removeSizes()
    }

    return []
  })

  const getBrands = computed(() => {
    let allowedBrands = []

    if (isObject(brands.value)) {
      for (let b in brands.value) {
        if (brands.value.hasOwnProperty(b) && !!brands.value[b]) {
          allowedBrands.push(b)
        }
      }
    }

    if (!allowedBrands.length) {
      removeBrands()
    }

    return allowedBrands
  })

  const getPriceRange = computed(() => {
    if (
      priceRange.value && priceRange.value[0] && priceRange.value[1] &&
      +priceRange.value[0] < +priceRange.value[1] &&
      filterStore.getPriceRange && filterStore.getPriceRange[0] && filterStore.getPriceRange[1] &&
      +priceRange.value[0] > +filterStore.getPriceRange[0] &&
      +priceRange.value[1] < +filterStore.getPriceRange[1]
    ) {
      return [priceRange.value[0], priceRange.value[1]]
    } else {
      removePriceRange()
    }

    return null
  })
  const getMinPrice = computed(() => {
    return getPriceRange && getPriceRange[0] ? getPriceRange[0] : null
  })
  const getMaxPrice = computed(() => {
    return getPriceRange && getPriceRange[1] ? getPriceRange[1] : null
  })

  const getOnlyAvailable = computed(() => {
    if (!onlyAvailable.value) {
      removeOnlyAvailable()
    }

    return onlyAvailable.value
  })

  const getIsSpecial = computed(() => {
    if (!isSpecial.value) {
      removeIsSpecial()
    }

    return isSpecial.value
  })

  const getDynamicFilters = computed(() => {
    try {
      if (
        !dynamicFilters.value ||
        !isObject(dynamicFilters.value)
      ) {
        removeDynamicFilters()
      } else if (Object.keys(dynamicFilters.value).length) {
        return JSON.stringify(clearSearchParamsRecursively(dynamicFilters.value))
      }
    } catch (e) {
      // Do nothing for now
    }

    return null
  })

  const getOrder = computed(() => order.value)
  const getFestival = computed(() => festival.value)
  const getCategory = computed(() => category.value)

  const routeKeys = reactive({
    min_price: getMinPrice,
    max_price: getMaxPrice,
    colors: getColors,
    sizes: getSizes,
    brands: getBrands,
    only_available: getOnlyAvailable,
    is_special: getIsSpecial,
    dynamic_filters: getDynamicFilters,
    order: getOrder,
    festival: getFestival,
    category: getCategory,
  })

  function clearSearchParamsRecursively(params) {
    if (!isObject(params)) return params

    for (let o in params) {
      if (params.hasOwnProperty(o)) {
        if (isObject(params[o]) && !Array.isArray(params[o])) {
          if (Object.keys(params[o]).length) {
            params[o] = clearSearchParamsRecursively(params[o])
          } else {
            delete params[o]
          }
        } else {
          if (
            params[o] === null ||
            params[o] === undefined ||
            (
              Array.isArray(params[o]) &&
              !params[o].map(item => item !== null && item !== undefined).length
            ) ||
            (
              params[o] === false
            )
          ) {
            delete params[o]
          } else if (Array.isArray(params[o])) {
            params[o] = params[o].filter(item => item !== null && item !== undefined)
          }
        }
      }
    }

    return params
  }

  function setColors(value) {
    if (isObject(value)) {
      colors.value = value
    }
  }

  function removeColors() {
    colors.value = {}
  }

  function setSizes(value) {
    if (isObject(value)) {
      sizes.value = value
    }
  }

  function removeSizes() {
    sizes.value = {}
  }

  function setBrands(value) {
    if (isObject(value)) {
      brands.value = value
    }
  }

  function removeBrands() {
    brands.value = {}
  }

  function setPriceRange(minPrice, maxPrice) {
    minPrice = parseInt(minPrice, 10)
    maxPrice = parseInt(maxPrice, 10)

    if (!isNaN(minPrice) && !isNaN(maxPrice)) {
      priceRange.value = [minPrice, maxPrice]
    }
  }

  function removePriceRange() {
    priceRange.value = []
  }

  function setOnlyAvailable(value) {
    onlyAvailable.value = value?.toString() === 'true'
  }

  function removeOnlyAvailable() {
    onlyAvailable.value = false
  }

  function setIsSpecial(value) {
    isSpecial.value = value?.toString() === 'true'
  }

  function removeIsSpecial() {
    isSpecial.value = false
  }

  function setDynamicFilters(filters) {
    if (isObject(filters)) {
      dynamicFilters.value = filters
    }
  }

  function removeDynamicFilters() {
    dynamicFilters.value = {}
  }

  function setOrder(value) {
    order.value = value
  }

  function removeOrder() {
    order.value = null
  }

  function setFestival(value) {
    festival.value = value
  }

  function removeFestival() {
    festival.value = null
  }

  function setCategory(value) {
    category.value = value
  }

  function removeCategory() {
    category.value = null
  }

  function readFiltersFromRoute() {
    let params = route.query

    // handle assigning color(s)
    removeColors()
    if (
      (params.colors && Array.isArray(params.colors) && params.colors.length) ||
      params.color
    ) {
      filterStore.getColors?.forEach(item => {
        if (
          params?.colors?.includes(item.name) ||
          params?.color === item.name
        ) {
          colors.value[item.id] = item
        }
      })
    }

    // handle assigning size(s)
    removeSizes()
    if (
      (params.sizes && Array.isArray(params.sizes) && params.sizes.length) ||
      params.size
    ) {
      filterStore.getSizes?.forEach(item => {
        if (
          params?.sizes?.includes(item.size) ||
          params?.size === item.size
        ) {
          colors.value[item.id] = item
        }
      })
    }

    // handle assigning brand(s)
    removeBrands()
    if (
      (params.brands && Array.isArray(params.brands) && params.brands.length) ||
      params.brand
    ) {
      filterStore.getBrands?.forEach(item => {
        let parsedId = parseInt(item.id, 10);

        if (!isNaN(parsedId)) {
          if (params.brands && Array.isArray(params.brands) && params.brands.length) {
            params.brands.forEach(brand => {
              let parsedBrand = parseInt(brand, 10);
              if (!isNaN(parsedBrand) && parsedId === parsedBrand) {
                brands.value[item.id] = true
              }
            })
          } else {
            let parsedBrand = parseInt(params.brand, 10);
            if (!isNaN(parsedBrand) && parsedId === parsedBrand) {
              brands.value[item.id] = true
            }
          }
        }
      })
    }

    if (params.min_price && params.max_price) {
      setPriceRange(params.min_price, params.max_price)
    }
    setOnlyAvailable(params.only_available)
    setIsSpecial(params.is_special)

    if (params.dynamic_filters) {
      try {
        setDynamicFilters(JSON.parse(params.dynamic_filters))
      } catch (e) {
        removeDynamicFilters()
      }
    }

    setOrder(params.order)

    setCategory(params.category)
    setFestival(params.festival)
  }

  function getRouteQueryObject() {
    let queryObj = {};

    let priceRange = getPriceRange.value;
    if (priceRange && priceRange[0] && priceRange[1]) {
      queryObj.min_price = getMinPrice.value;
      queryObj.max_price = getMaxPrice.value;
    }

    queryObj = {
      ...queryObj,
      colors: getColors.value,
      sizes: getSizes.value,
      brands: getBrands.value,
      only_available: getOnlyAvailable.value,
      is_special: getIsSpecial.value,
      dynamic_filters: getDynamicFilters.value,
      order: getOrder.value,
      festival: getFestival.value,
      category: getCategory.value,
    }

    return clearSearchParamsRecursively(queryObj);
  }

  function resetSearchParams(except = []) {
    let exceptions = {}

    if (except.length) {
      except.forEach(item => {
        if (routeKeys[item]) {
          exceptions[item] = routeKeys[item]
        }
      })
    }

    $reset()

    if (Object.keys(exceptions).length) {
      for (let o in exceptions) {
        if (exceptions.hasOwnProperty(o)) {
          let v = toValue(exceptions[o])

          switch (o) {
            case 'min_price':
              setPriceRange(v, getMaxPrice.value)
              break;
            case 'max_price':
              setPriceRange(getMinPrice.value, v)
              break;
            case 'colors':
              setColors(v)
              break;
            case 'sizes':
              setSizes(v)
              break;
            case 'brands':
              setBrands(v)
              break;
            case 'only_available':
              setOnlyAvailable(v)
              break;
            case 'is_special':
              setIsSpecial(v)
              break;
            case 'dynamic_filters':
              setDynamicFilters(v)
              break;
            case 'order':
              setOrder(v)
              break;
            case 'festival':
              setFestival(v)
              break;
            case 'category':
              setCategory(v)
              break;
          }
        }
      }
    }
  }

  function $reset() {
    removeColors()
    removeSizes()
    removeBrands()
    removePriceRange()
    removeOnlyAvailable()
    removeIsSpecial()
    removeDynamicFilters()
    removeOrder()
    removeFestival()
    removeCategory()
  }

  return {
    brands, getColors, setColors, removeColors,
    colors, getSizes, setSizes, removeSizes,
    sizes, getBrands, setBrands, removeBrands,
    priceRange, getPriceRange, setPriceRange, removePriceRange,
    onlyAvailable, getOnlyAvailable, setOnlyAvailable, removeOnlyAvailable,
    isSpecial, getIsSpecial, setIsSpecial, removeIsSpecial,
    dynamicFilters, getDynamicFilters, setDynamicFilters, removeDynamicFilters,
    order, getOrder, setOrder, removeOrder,
    festival, getFestival, setFestival, removeFestival,
    category, getCategory, setCategory, removeCategory,
    //
    readFiltersFromRoute, getRouteQueryObject,
    resetSearchParams, clearSearchParams: clearSearchParamsRecursively,
    routeKeys,
    //
    $reset,
  }
})
