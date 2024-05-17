import {useRequest} from "@/composables/api-request.js";
import {apiRoutes} from "@/router/api-routes.js";

export const SearchAPI = {
  products(text, callbacks) {
    return useRequest(apiRoutes.search.product, {
      method: 'GET',
      params: {
        text,
      },
    }, callbacks)
  },

  blogs(text, callbacks) {
    return useRequest(apiRoutes.search.blog, {
      method: 'GET',
      params: {
        text,
      },
    }, callbacks)
  },
}
