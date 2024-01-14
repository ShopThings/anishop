import {GenericAPI} from "./ServiceAPIs.js";
import {apiReplaceParams, apiRoutes} from "@/router/api-routes.js";
import {useRequest} from "@/composables/api-request.js";

export const FestivalAPI = Object.assign(
  GenericAPI(apiRoutes.admin.festivals, {replacement: 'festival'}),
  {
    fetchProducts(festivalId, callbacks) {
      useRequest(
        apiReplaceParams(apiRoutes.admin.festivals.products, {festival: festivalId}),
        null,
        callbacks
      )
    },

    addProduct(festivalId, productId, callbacks) {
      useRequest(
        apiReplaceParams(apiRoutes.admin.festivals.storeProduct, {
          festival: festivalId,
          product: productId,
        }),
        {method: 'POST'},
        callbacks
      )
    },

    addCategory(festivalId, categoryId, callbacks) {
      useRequest(
        apiReplaceParams(apiRoutes.admin.festivals.storeCategoryProducts, {
          festival: festivalId,
          category: categoryId,
        }),
        {method: 'POST'},
        callbacks
      )
    },

    removeProduct(festivalId, productId, callbacks) {
      useRequest(
        apiReplaceParams(apiRoutes.admin.festivals.destroyProduct, {
          festival: festivalId,
          product: productId,
        }),
        {method: 'DELETE'},
        callbacks
      )
    },

    removeCategoryProducts(festivalId, categoryId, callbacks) {
      useRequest(
        apiReplaceParams(apiRoutes.admin.festivals.batchDestroyProduct, {
          festival: festivalId,
          category: categoryId,
        }),
        {method: 'DELETE'},
        callbacks
      )
    },
  }
)

export const CouponAPI = Object.assign(
  GenericAPI(apiRoutes.admin.coupons, {replacement: 'coupon'}),
  {
    // extra functionality goes here
  }
)

export const CityPostPriceAPI = Object.assign(
  GenericAPI(apiRoutes.admin.cityPostPrices, {replacement: 'city_post_price'}),
  {
    // extra functionality goes here
  }
)

export const WeightPostPriceAPI = Object.assign(
  GenericAPI(apiRoutes.admin.weightPostPrices, {replacement: 'weight_post_price'}),
  {
    // extra functionality goes here
  }
)

export const ProvinceAPI = {
  fetchAll(callbacks) {
    useRequest(apiRoutes.admin.provinces, null, callbacks)
  },

  fetchCities(provinceId, callbacks) {
    useRequest(
      apiReplaceParams(apiRoutes.admin.cities, {province: provinceId}),
      null,
      callbacks
    )
  },
}
