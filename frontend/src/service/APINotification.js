import {useRequest} from "@/composables/api-request.js";
import {apiRoutes} from "@/router/api-routes.js";

export const NotificationAPI = {
  fetchAll(callbacks) {
    useRequest(
      apiRoutes.admin.notification.index,
      null,
      callbacks
    )
  },

  markAllAsRead(callbacks) {
    useRequest(
      apiRoutes.admin.notification.update,
      {
        method: 'PUT',
      },
      callbacks
    )
  },

  checkNotifications(callbacks) {
    useRequest(
      apiRoutes.admin.notification.check,
      null,
      callbacks
    )
  },
}

export const UserNotificationAPI = {
  fetchAll(callbacks) {
    useRequest(
      apiRoutes.user.info.notification.index,
      null,
      callbacks
    )
  },

  markAllAsRead(callbacks) {
    useRequest(
      apiRoutes.user.info.notification.update,
      {
        method: 'PUT',
      },
      callbacks
    )
  },

  checkNotifications(callbacks) {
    useRequest(
      apiRoutes.user.info.notification.check,
      null,
      callbacks
    )
  },
}
