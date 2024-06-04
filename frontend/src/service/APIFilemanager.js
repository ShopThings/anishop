import {useRequest} from "@/composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "@/router/api-routes.js";

export const FilemanagerAPI = {
  fetchList(params, callbacks) {
    return useRequest(apiRoutes.admin.files.list, {params}, callbacks)
  },

  fetchTree(params, callbacks) {
    return useRequest(apiRoutes.admin.files.tree, {params}, callbacks)
  },

  createDirectory(data, callbacks) {
    return useRequest(apiRoutes.admin.files.createDir, {
      method: 'POST',
      data,
    }, callbacks)
  },

  rename(data, callbacks) {
    return useRequest(apiRoutes.admin.files.rename, {
      method: 'POST',
      data,
    }, callbacks)
  },

  move(data, callbacks) {
    return useRequest(apiRoutes.admin.files.move, {
      method: 'POST',
      data,
    }, callbacks)
  },

  copy(data, callbacks) {
    return useRequest(apiRoutes.admin.files.copy, {
      method: 'POST',
      data,
    }, callbacks)
  },

  deleteFile(data, callbacks) {
    return useRequest(
      apiRoutes.admin.files.destroy,
      {
        method: 'DELETE',
        data,
      },
      callbacks)
  },

  deleteFiles(data, callbacks) {
    return useRequest(
      apiRoutes.admin.files.batchDestroy,
      {
        method: 'DELETE',
        data,
      },
      callbacks
    )
  },

  uploadFile(data, callbacks) {
    return useRequest(apiRoutes.admin.files.upload, {
      method: 'POST',
      data,
    }, callbacks)
  },

  downloadFile(fileId, params, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.admin.files.download, {file: fileId}),
      {
        responseType: 'arraybuffer',
        params
      },
      callbacks
    )
  },
}
