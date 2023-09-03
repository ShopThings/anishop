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

        addresses: {
            index: 'api/admin/addresses',
        },

        orders: {
            index: 'api/admin/orders/{user?}',
            show: 'api/admin/orders/{order}',
            update: 'api/admin/orders/{order}',
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

        paymentMethods: {
            index: 'api/admin/payment-method',
            show: 'api/admin/payment-method/{payment_method}',
            update: 'api/admin/payment-method/{payment_method}',
        },

        colors: {
            index: 'api/admin/color',
            show: 'api/admin/color/{color}',
            store: 'api/admin/colors',
            update: 'api/admin/color/{color}',
            destroy: 'api/admin/colors/{color}',
            batchDestroy: 'api/admin/colors/batch',
        },

        brands: {
            index: 'api/admin/brand',
            show: 'api/admin/brand/{brand}',
            store: 'api/admin/brands',
            update: 'api/admin/brand/{brand}',
            destroy: 'api/admin/brands/{brand}',
            batchDestroy: 'api/admin/brands/batch',
        },

        categories: {
            index: 'api/admin/category',
            show: 'api/admin/category/{category}',
            store: 'api/admin/category',
            update: 'api/admin/category/{category}',
            destroy: 'api/admin/category/{category}',
            batchDestroy: 'api/admin/category/batch',
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
