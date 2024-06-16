import isObject from "lodash.isobject";
import adminRoutes from "./api/admin-routes.js"
import userRoutes from "./api/user-routes.js"
import generalRoutes from "./api/general-routes.js"

export const apiRoutes = Object.assign(adminRoutes, userRoutes, generalRoutes)

export const apiReplaceParams = function (url, params) {
  if (isObject(params)) {
    for (const param in params) {
      if (params.hasOwnProperty(param) && params[param] !== null) {
        url = url.replace(
          new RegExp('\{' + param + '\??\}', 'g'),
          params[param]
        )
      }
    }
  }

  url = url.replace(
    new RegExp('\{[^}]*\?\}'),
    ''
  )

  return url
}
