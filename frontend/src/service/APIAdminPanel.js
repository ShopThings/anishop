import {useRequest} from "@/composables/api-request.js";
import {apiRoutes} from "@/router/api-routes.js";

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
}
