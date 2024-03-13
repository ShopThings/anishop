import {useRequest} from "@/composables/api-request.js";

export const NotificationAPI = {
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
