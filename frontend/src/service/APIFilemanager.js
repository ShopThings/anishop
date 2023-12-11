import {useRequest} from "../composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "../router/api-routes.js";

export const FilemanagerAPI = {
    showFile(fileId, size, callbacks) {
        useRequest(
            apiReplaceParams(apiRoutes.showFile, {file: fileId, size}),
            null,
            callbacks
        )
    },

    fetchList(callbacks) {
        useRequest(apiRoutes.admin.files.list, null, callbacks)
    },

    fetchTree(callbacks) {
        useRequest(apiRoutes.admin.files.tree, null, callbacks)
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

    deleteFileById(fileId, callbacks) {
        useRequest(
            apiReplaceParams(apiRoutes.admin.files.destroy, {file: fileId}),
            {method: 'DELETE'},
            callbacks)
    },

    deleteFileByIds(fileId, ids, callbacks) {
        useRequest(
            apiRoutes.admin.files.batchDestroy,
            {
                method: 'DELETE',
                data: {
                    ids,
                },
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

    downloadFile(fileId, callbacks) {
        useRequest(
            apiReplaceParams(apiRoutes.admin.files.download, {file: fileId}),
            null,
            callbacks
        )
    },
}
