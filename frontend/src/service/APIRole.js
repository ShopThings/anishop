import {apiRoutes} from "@/router/api-routes.js";
import {useRequest} from "@/composables/api-request.js";

export const RoleAPI = {
  fetchAll(callbacks) {
    useRequest(apiRoutes.admin.roles, null, callbacks)
  },
}

export const PermissionAPI = {
  fetchAll(callbacks) {
    useRequest(apiRoutes.admin.permissions, null, callbacks)
  },
}
