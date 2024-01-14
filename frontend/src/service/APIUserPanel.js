import {useRequest} from "@/composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "@/router/api-routes.js";
import {GenericAPI} from "./ServiceAPIs.js";

export const UserPanelDashboardAPI = {
  fetchLatestOrders(callbacks) {
    useRequest(
      apiRoutes.user.main.latestOrders,
      null,
      callbacks
    )
  },

  fetchLatestReturnOrders(callbacks) {
    useRequest(
      apiRoutes.user.main.latestReturnOrders,
      null,
      callbacks
    )
  },
}

export const UserPanelInfoAPI = {
  updateInfo(data, callbacks) {
    useRequest(
      apiRoutes.user.info.info,
      {
        method: 'PUT',
        data,
      },
      callbacks
    )
  },

  updatePassword(data, callbacks) {
    useRequest(
      apiRoutes.user.info.password,
      {
        method: 'PUT',
        data,
      },
      callbacks
    )
  },
}

export const UserPanelOrderAPI = Object.assign(
  GenericAPI(apiRoutes.user.orders, {
    except: ['index', 'show', 'update'],
    replacement: 'order',
  }),
  {
    // extra functionality goes here
  }
)

export const UserPanelReturnOrderAPI = Object.assign(
  GenericAPI(apiRoutes.user.returnOrders, {
    except: ['store', 'batchDestroy'],
    replacement: 'return_order',
  }),
  {
    fetchReturnableOrders(callbacks) {
      useRequest(apiRoutes.user.returnOrders.returnableOrders, null, callbacks);
    },

    create(orderCode, data, callbacks) {
      useRequest(
        apiReplaceParams(apiRoutes.user.returnOrders.store, {order: orderCode}),
        {
          method: 'POST',
          data,
        },
        callbacks
      )
    },
  }
)

export const UserPanelCommentAPI = Object.assign(
  GenericAPI(apiRoutes.user.comments, {
    except: ['store', 'batchDestroy'],
    replacement: 'comment',
  }),
  {
    create(productId, data, callbacks) {
      useRequest(
        apiReplaceParams(apiRoutes.user.comments.store, {product: productId}),
        {
          method: 'POST',
          data,
        },
        callbacks
      )
    }
  }
)

export const UserPanelBlogCommentAPI = Object.assign(
  GenericAPI(apiRoutes.user.blogComments, {
    except: ['store', 'batchDestroy'],
    replacement: 'comment',
  }),
  {
    create(blogId, data, callbacks) {
      useRequest(
        apiReplaceParams(apiRoutes.user.blogComments.store, {blog: blogId}),
        {
          method: 'POST',
          data,
        },
        callbacks
      )
    }
  }
)

export const UserPanelFavoriteProductAPI = Object.assign(
  GenericAPI(apiRoutes.user.favoriteProducts, {
    only: ['index', 'store', 'destroy'],
    replacement: 'product',
  }), {
    // extra functionality goes here
  }
)

export const UserPanelAddressAPI = Object.assign(
  GenericAPI(apiRoutes.user.addresses, {
    except: ['batchDestroy'],
    replacement: 'address',
  }), {
    // extra functionality goes here
  }
)

export const UserPanelContactAPI = Object.assign(
  GenericAPI(apiRoutes.user.contacts, {
    only: ['index', 'show', 'destroy'],
    replacement: 'contact',
  }), {
    // extra functionality goes here
  }
)
