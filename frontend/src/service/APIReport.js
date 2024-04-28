import {useRequest} from "@/composables/api-request.js";
import {apiRoutes} from "@/router/api-routes.js";

export const ReportAPI = {
  getUsersQB(callbacks) {
    return useRequest(apiRoutes.admin.reports.usersQueryBuilder, null, callbacks)
  },

  getProductsQB(callbacks) {
    return useRequest(apiRoutes.admin.reports.productsQueryBuilder, null, callbacks)
  },

  getOrdersQB(callbacks) {
    return useRequest(apiRoutes.admin.reports.ordersQueryBuilder, null, callbacks)
  },

  fetchUsers(data, callbacks) {
    return useRequest(
      apiRoutes.admin.reports.users,
      {
        method: 'POST',
        data,
      },
      callbacks
    )
  },

  fetchProducts(data, callbacks) {
    return useRequest(
      apiRoutes.admin.reports.products,
      {
        method: 'POST',
        data,
      },
      callbacks
    )
  },

  fetchOrders(data, callbacks) {
    return useRequest(
      apiRoutes.admin.reports.orders,
      {
        method: 'POST',
        data,
      },
      callbacks
    )
  },

  exportUsers(data, callbacks) {
    return useRequest(
      apiRoutes.admin.reports.usersExport,
      {
        method: 'POST',
        data,
      },
      callbacks
    )
  },

  exportProducts(data, callbacks) {
    return useRequest(
      apiRoutes.admin.reports.productsExport,
      {
        method: 'POST',
        data,
      },
      callbacks
    )
  },

  exportOrders(data, callbacks) {
    return useRequest(
      apiRoutes.admin.reports.ordersExport,
      {
        method: 'POST',
        data,
      },
      callbacks
    )
  },
}
