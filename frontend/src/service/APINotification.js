import {useRequest} from "../composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "../router/api-routes.js";

export const NotificationAPI = {
    fetchAll(callbacks) {
        useRequest(
            apiRoutes.user.info.notification.index,
            null,
            callbacks
        )
    },

    updateById(id, data, callbacks) {
        useRequest(
            apiReplaceParams(apiRoutes.user.info.notification.update, {notification: id}),
            {
                method: 'PUT',
                data,
            },
            callbacks
        )
    },
}
