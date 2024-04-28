import {GenericAPI} from "./ServiceAPIs.js";
import {apiReplaceParams, apiRoutes} from "@/router/api-routes.js";
import {useRequest} from "@/composables/api-request.js";

export const OrderAPI = Object.assign(
  GenericAPI(apiRoutes.admin.orders, {
    except: ['store', 'batchDestroy'],
    replacement: 'order',
  }),
  {
    fetchUserOrders(userId, callbacks) {
      return useRequest(
        apiReplaceParams(apiRoutes.admin.orders.index, {user: userId}),
        null,
        callbacks
      )
    },

    fetchPaymentStatuses(callbacks) {
      return useRequest(
        apiRoutes.admin.orders.paymentStatuses,
        null,
        callbacks
      )
    },

    fetchSendStatuses(callbacks) {
      return useRequest(
        apiRoutes.admin.orders.sendStatuses,
        null,
        callbacks
      )
    },

    updateOrderPayment(orderId, data, callbacks) {
      return useRequest(
        apiReplaceParams(apiRoutes.admin.orders.updatePayment, {order: orderId}),
        {
          method: 'PUT',
          data,
        },
        callbacks
      )
    },

    exportPdf(orderId, callbacks) {
      return useRequest(
        apiReplaceParams(apiRoutes.admin.orders.exportPdf, {order: orderId}),
        {
          method: 'PUT',
        },
        callbacks
      )
    }
  }
)

export const OrderBadgeAPI = Object.assign(
  GenericAPI(apiRoutes.admin.orderBadges, {replacement: 'order_badge'}),
  {
    // extra functionality goes here
  }
)

export const ReturnOrderAPI = Object.assign(
  GenericAPI(apiRoutes.admin.returnOrders, {
    except: ['store', 'batchDestroy'],
    replacement: 'return_order',
  }),
  {
    fetchUserReturnOrders(userId, callbacks) {
      return useRequest(
        apiReplaceParams(apiRoutes.admin.returnOrders.index, {user: userId}),
        null,
        callbacks
      )
    },

    modifyOrderItem(returnOrderId, returnOrderItemId, data, callbacks) {
      return useRequest(
        apiReplaceParams(apiRoutes.admin.returnOrders.modifyOrderItem, {
          return_order: returnOrderId,
          return_order_item: returnOrderItemId,
        }),
        {
          method: 'PUT',
          data,
        },
        callbacks
      )
    },

    returnOrderItemsToStock(returnOrderId, callbacks) {
      return useRequest(
        apiReplaceParams(apiRoutes.admin.returnOrders.returnOrderItems, {return_order: returnOrderId}),
        {method: 'PUT'},
        callbacks
      )
    },

    fetchStatuses(callbacks) {
      return useRequest(
        apiRoutes.admin.returnOrders.statuses,
        null,
        callbacks
      )
    },
  }
)
