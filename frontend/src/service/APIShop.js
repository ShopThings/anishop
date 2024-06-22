import {GenericAPI} from "./ServiceAPIs.js";
import {apiReplaceParams, apiRoutes} from "@/router/api-routes.js";
import {useRequest} from "@/composables/api-request.js";

export const SendMethodAPI = Object.assign(
  GenericAPI(apiRoutes.admin.sendMethods, {replacement: 'send_method'}),
  {
    // extra functionality goes here
  }
)

export const FestivalAPI = Object.assign(
  GenericAPI(apiRoutes.admin.festivals, {replacement: 'festival'}),
  {
    fetchProducts(festivalId, params, callbacks) {
      return useRequest(
        apiReplaceParams(apiRoutes.admin.festivals.products, {festival: festivalId}),
        {params},
        callbacks
      )
    },

    addProduct(festivalId, data, callbacks) {
      return useRequest(
        apiReplaceParams(apiRoutes.admin.festivals.storeProduct, {festival: festivalId}),
        {
          method: 'POST',
          data,
        },
        callbacks
      )
    },

    addCategory(festivalId, data, callbacks) {
      return useRequest(
        apiReplaceParams(apiRoutes.admin.festivals.storeCategoryProducts, {festival: festivalId}),
        {
          method: 'POST',
          data,
        },
        callbacks
      )
    },

    removeProduct(festivalId, productId, callbacks) {
      return useRequest(
        apiReplaceParams(apiRoutes.admin.festivals.destroyProduct, {
          festival: festivalId,
          product: productId,
        }),
        {method: 'DELETE'},
        callbacks
      )
    },

    removeProducts(festivalId, ids, callbacks) {
      return useRequest(
        apiReplaceParams(apiRoutes.admin.festivals.batchDestroyProduct, {
          festival: festivalId,
        }),
        {
          method: 'DELETE',
          data: {
            ids,
          },
        },
        callbacks
      )
    },

    removeCategoryProducts(festivalId, categoryId, callbacks) {
      return useRequest(
        apiReplaceParams(apiRoutes.admin.festivals.batchDestroyCategory, {
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

export const ProvinceAPI = Object.assign(
  GenericAPI(apiRoutes.admin.provinces, {
    replacement: 'province',
    only: ['show']
  }),
  {
    fetchAll(callbacks) {
      return useRequest(apiRoutes.provinces, null, callbacks)
    },

    fetchCityByProvince(cityId, provinceId, callbacks) {
      return useRequest(
        apiReplaceParams(apiRoutes.admin.cities.show, {province: provinceId, city: cityId}),
        null,
        callbacks
      )
    },

    fetchCities(provinceId, callbacks) {
      return useRequest(
        apiReplaceParams(apiRoutes.cities, {province: provinceId}),
        null,
        callbacks
      )
    },
  }
)
