import isObject from "lodash.isobject";

export const apiRoutes = {
    // admin routes
    admin: {
        login: 'api/admin/login',
        logout: 'api/admin/logout',

        roles: 'api/admin/roles',

        users: {
            index: 'api/admin/users',
            show: 'api/admin/users/{user}',
            store: 'api/admin/users',
            update: 'api/admin/users/{user}',
            destroy: 'api/admin/users/{user}',
            batchDestroy: 'api/admin/users/batch',
        },

        files: {
            show: 'api/admin/files/{file}/{size?}',
            list: 'api/admin/files',
            tree: 'api/admin/files/tree',
            createDir: 'api/admin/files/directory',
            rename: 'api/admin/files/rename',
            move: 'api/admin/files/move',
            copy: 'api/admin/files/copy',
            destroy: 'api/admin/files/{file}',
            batchDestroy: 'api/admin/files/batch',
            upload: 'api/admin/files',
            download: 'api/admin/files/{file}'
        },
    },


    // user routes
    user: {
        logout: 'api/user/logout',
    },

    // general routes
    captcha: '/captcha/api',
    login: 'api/login',
}

export const apiReplaceParams = function (url, params) {
    if (isObject(params)) {
        for (const param in params) {
            if (params.hasOwnProperty(param)) {
                url = url.replace(
                    new RegExp('\{' + param + '\??\}'),
                    params[param]
                )
            }
        }
    }

    url = url.replace(
        new RegExp('\{[^\?]*\?\}'),
        ''
    )

    return url
}
