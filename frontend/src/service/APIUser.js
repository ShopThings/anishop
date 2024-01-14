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
    useRequest(
      apiReplaceParams(apiRoutes.admin.users.addresses, {user: userId}),
      null,
      callbacks
    )
  }
}

export const UserFavoriteProductAPI = {
  fetchAll(userId, params, callbacks) {
    useRequest(
      apiReplaceParams(apiRoutes.admin.users.favoriteProducts, {user: userId}),
      {params},
      callbacks
    )
  }
}

export const UserPurchaseAPI = {
  fetchAll(userId, params, callbacks) {
    useRequest(
      apiReplaceParams(apiRoutes.admin.users.purchases, {user: userId}),
      {params},
      callbacks
    )
  }
}

export const UserCartAPI = {
  fetchAll(userId, callbacks) {
    useRequest(
      apiReplaceParams(apiRoutes.admin.users.carts, {user: userId}),
      null,
      callbacks
    )
  }
}
