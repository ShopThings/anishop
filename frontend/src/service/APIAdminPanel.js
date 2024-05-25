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

  getTotalSales(callbacks) {
    return useRequest(
      apiRoutes.admin.dashboard.totalSale,
      null,
      callbacks
    )
  },

  getPeriodSales(period, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.admin.dashboard.periodSale, {period}),
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

  getChartOrders(period, status, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.admin.dashboard.chartOrders, {period, status}),
      null,
      callbacks
    )
  },

  getChartReturnOrders(period, status, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.admin.dashboard.chartReturnOrders, {period, status}),
      null,
      callbacks
    )
  },

  getMostSaleProducts(period, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.admin.dashboard.tableMostSaleProducts, {period}),
      null,
      callbacks
    )
  },

  getDashboardCounting(callbacks) {
    return useRequest(
      apiRoutes.admin.dashboard.dashboardCounting,
      null,
      callbacks
    )
  },
}
