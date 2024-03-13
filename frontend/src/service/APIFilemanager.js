import {useRequest} from "@/composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "@/router/api-routes.js";

export const FilemanagerAPI = {
  fetchList(params, callbacks) {
    useRequest(apiRoutes.admin.files.list, {params}, callbacks)
  },

  fetchTree(params, callbacks) {
    useRequest(apiRoutes.admin.files.tree, {params}, callbacks)
  },

  createDirectory(data, callbacks) {
    useRequest(apiRoutes.admin.files.createDir, {
      method: 'POST',
      data,
    }, callbacks)
  },

  rename(data, callbacks) {
    useRequest(apiRoutes.admin.files.rename, {
      method: 'POST',
      data,
    }, callbacks)
  },

  move(data, callbacks) {
    useRequest(apiRoutes.admin.files.move, {
      method: 'POST',
      data,
    }, callbacks)
  },

  copy(data, callbacks) {
    useRequest(apiRoutes.admin.files.copy, {
      method: 'POST',
      data,
    }, callbacks)
  },

  deleteFile(data, callbacks) {
    useRequest(
      apiRoutes.admin.files.destroy,
      {
        method: 'DELETE',
        data,
      },
      callbacks)
  },

  deleteFiles(data, callbacks) {
    useRequest(
      apiRoutes.admin.files.batchDestroy,
      {
        method: 'DELETE',
        data,
      },
      callbacks
    )
  },

  uploadFile(data, callbacks) {
    useRequest(apiRoutes.admin.files.upload, {
      method: 'POST',
      data,
    }, callbacks)
  },

  downloadFile(fileId, params, callbacks) {
    useRequest(
      apiReplaceParams(apiRoutes.admin.files.download, {file: fileId}),
      {params},
      callbacks
    )
  },
}
