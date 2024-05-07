import {useRequest} from "@/composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "@/router/api-routes.js";

export const AdminPanelDashboardAPI = {
  getCountAlerts(callbacks) {
    return useRequest(
      apiRoutes.admin.main.countOfAlerts,
      null,
      callbacks
    )
  },

  getCountOrders(callbacks) {
    return useRequest(
      apiRoutes.admin.main.countOfOrders,
      null,
      callbacks
    )
  },

  //-----------------------------------------------------

  getTotalSells(callbacks) {
    return useRequest(
      apiRoutes.admin.dashboard.totalSell,
      null,
      callbacks
    )
  },

  getPeriodSells(period, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.admin.dashboard.periodSell, {period}),
      null,
      callbacks
    )
  },

  getChartUsers(period, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.admin.dashboard.chartUsers, {period}),
      null,
      callbacks
    )
  },

  getChartOrders(period, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.admin.dashboard.chartOrders, {period}),
      null,
      callbacks
    )
  },

  getChartReturnOrders(period, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.admin.dashboard.chartReturnOrders, {period}),
      null,
      callbacks
    )
  },

  getMostSellProducts(period, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.admin.dashboard.tableMostSellProducts, {period}),
      null,
      callbacks
    )
  },
}
