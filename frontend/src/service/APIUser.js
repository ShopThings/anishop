import {apiReplaceParams, apiRoutes} from "@/router/api-routes.js";
import {GenericAPI} from "./ServiceAPIs.js";
import {useRequest} from "@/composables/api-request.js";

export const UserAPI = Object.assign(
  GenericAPI(apiRoutes.admin.users, {replacement: 'user'}),
  {
    // extra functionality goes here
  }
)

export const UserAddressAPI = {
  fetchAll(userId, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.admin.userAddresses.index, {user: userId}),
      null,
      callbacks
    )
  },

  create(userId, data, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.admin.userAddresses.store, {user: userId}),
      {
        method: 'POST',
        data,
      },
      callbacks
    )
  },

  updateById(userId, addressId, data, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.admin.userAddresses.update, {user: userId, address: addressId}),
      {
        method: 'PUT',
        data,
      },
      callbacks
    )
  },

  deleteById(userId, addressId, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.admin.userAddresses.destroy, {user: userId, address: addressId}),
      {
        method: 'DELETE',
      },
      callbacks
    )
  },
}

export const UserFavoriteProductAPI = {
  fetchAll(userId, params, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.admin.users.favoriteProducts, {user: userId}),
      {params},
      callbacks
    )
  },
}

export const UserPurchaseAPI = {
  fetchAll(userId, params, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.admin.users.purchases, {user: userId}),
      {params},
      callbacks
    )
  },
}

export const UserCartAPI = {
  fetchAll(userId, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.admin.users.carts, {user: userId}),
      null,
      callbacks
    )
  },
}
