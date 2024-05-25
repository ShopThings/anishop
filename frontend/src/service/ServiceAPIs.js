import {apiReplaceParams} from "@/router/api-routes.js";
import {useRequest, useRequestWrapper} from "@/composables/api-request.js";
import isObject from "lodash.isobject";

export const GenericAPI = (url, {
  replacement,
  only,
  except,
  urlKeys,
  urlCallbacks,
}) => {
  const api = {}

  const defaultUrlKeys = {
    show: 'show',
    index: 'index',
    store: 'store',
    update: 'update',
    destroy: 'destroy',
    batchDestroy: 'batchDestroy',
  }

  // make default variables
  if (!only || !only?.length) {
    only = Object.values(defaultUrlKeys)
  }
  if (!except || !except?.length) {
    except = []
  }
  if (!urlKeys || !isObject(urlKeys) || !Object.keys(urlKeys).length) {
    urlKeys = defaultUrlKeys
  } else {
    urlKeys = Object.assign(defaultUrlKeys, urlKeys)
  }

  if (only.indexOf(defaultUrlKeys.show) !== -1 && except.indexOf(defaultUrlKeys.show) === -1) {
    api.fetchById = (id, callbacks) => {
      if (isObject(urlCallbacks?.show)) {
        return useRequestWrapper(
          apiReplaceParams(url[urlKeys.show], {[replacement]: id}),
          null,
          urlCallbacks.show,
          callbacks
        )
      } else {
        return useRequest(
          apiReplaceParams(url[urlKeys.show], {[replacement]: id}),
          null,
          callbacks
        )
      }
    }
  }

  if (only.indexOf(defaultUrlKeys.index) !== -1 && except.indexOf(defaultUrlKeys.index) === -1) {
    api.fetchAll = (params, callbacks) => {
      if (isObject(urlCallbacks?.index)) {
        return useRequestWrapper(apiReplaceParams(url[urlKeys.index]), {params}, urlCallbacks.index, callbacks)
      } else {
        return useRequest(apiReplaceParams(url[urlKeys.index]), {params}, callbacks)
      }
    }
  }

  if (only.indexOf(defaultUrlKeys.store) !== -1 && except.indexOf(defaultUrlKeys.store) === -1) {
    api.create = (data, callbacks) => {
      if (isObject(urlCallbacks?.store)) {
        return useRequestWrapper(url[urlKeys.store], {
          method: 'POST',
          data,
        }, urlCallbacks.store, callbacks)
      } else {
        return useRequest(url[urlKeys.store], {
          method: 'POST',
          data,
        }, callbacks)
      }
    }
  }

  if (only.indexOf(defaultUrlKeys.update) !== -1 && except.indexOf(defaultUrlKeys.update) === -1) {
    api.updateById = (id, data, callbacks) => {
      if (isObject(urlCallbacks?.update)) {
        return useRequestWrapper(apiReplaceParams(url[urlKeys.update], {[replacement]: id}), {
          method: 'PUT',
          data,
        }, urlCallbacks.update, callbacks)
      } else {
        return useRequest(apiReplaceParams(url[urlKeys.update], {[replacement]: id}), {
          method: 'PUT',
          data,
        }, callbacks)
      }
    }
  }

  if (only.indexOf(defaultUrlKeys.destroy) !== -1 && except.indexOf(defaultUrlKeys.destroy) === -1) {
    api.deleteById = (id, callbacks) => {
      if (isObject(urlCallbacks?.destroy)) {
        return useRequestWrapper(apiReplaceParams(url[urlKeys.destroy], {[replacement]: id}), {
          method: 'DELETE'
        }, urlCallbacks.destroy, callbacks)
      } else {
        return useRequest(apiReplaceParams(url[urlKeys.destroy], {[replacement]: id}), {
          method: 'DELETE'
        }, callbacks)
      }
    }
  }

  if (only.indexOf(defaultUrlKeys.batchDestroy) !== -1 && except.indexOf(defaultUrlKeys.batchDestroy) === -1) {
    api.deleteByIds = (ids, callbacks) => {
      if (isObject(urlCallbacks?.batchDestroy)) {
        return useRequestWrapper(url[urlKeys.batchDestroy], {
          method: 'DELETE',
          data: {
            ids,
          }
        }, urlCallbacks.batchDestroy, callbacks)
      } else {
        return useRequest(url[urlKeys.batchDestroy], {
          method: 'DELETE',
          data: {
            ids,
          }
        }, callbacks)
      }
    }
  }

  return api
}
