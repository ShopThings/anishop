import {useRequest} from "@/composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "@/router/api-routes.js";
import {GenericAPI} from "./ServiceAPIs.js";

export const UserPanelDashboardAPI = {
  getCountOfStuffs(callbacks) {
    return useRequest(
      apiRoutes.user.main.countOfStuffs,
      null,
      callbacks
    )
  },

  fetchLatestOrders(callbacks) {
    return useRequest(
      apiRoutes.user.main.latestOrders,
      null,
      callbacks
    )
  },

  fetchLatestReturnOrders(callbacks) {
    return useRequest(
      apiRoutes.user.main.latestReturnOrders,
      null,
      callbacks
    )
  },
}

export const UserPanelInfoAPI = {
  fetchInfo(callbacks) {
    return useRequest(
      apiRoutes.user.info.info.show,
      null,
      callbacks
    )
  },

  updateInfo(data, callbacks) {
    return useRequest(
      apiRoutes.user.info.info.update,
      {
        method: 'PUT',
        data,
      },
      callbacks
    )
  },

  updatePassword(data, callbacks) {
    return useRequest(
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
    only: ['index', 'show', 'update'],
    replacement: 'order',
  }),
  {
    fetchUnpaidOrderPayments(callbacks) {
      return useRequest(apiRoutes.user.orders.unpaidOrderPayments, null, callbacks)
    },
  }
)

export const UserPanelReturnOrderAPI = Object.assign(
  GenericAPI(apiRoutes.user.returnOrders, {
    except: ['store', 'batchDestroy'],
    replacement: 'return_order',
  }),
  {
    fetchReturnableOrders(callbacks) {
      return useRequest(apiRoutes.user.returnOrders.returnableOrders, null, callbacks);
    },

    create(orderCode, callbacks) {
      return useRequest(
        apiReplaceParams(apiRoutes.user.returnOrders.store, {order: orderCode}),
        {
          method: 'POST',
        },
        callbacks
      )
    },

    changeStatus(requestCode, data, callbacks) {
      return useRequest(
        apiReplaceParams(apiRoutes.user.returnOrders.changeStatus, {return_order: requestCode}),
        {
          method: 'POST',
          data,
        },
        callbacks
      )
    }
  }
)

export const UserPanelCommentAPI = Object.assign(
  GenericAPI(apiRoutes.user.comments, {
    except: ['store', 'batchDestroy'],
    replacement: 'comment',
  }),
  {
    create(productId, data, callbacks) {
      return useRequest(
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
      return useRequest(
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
