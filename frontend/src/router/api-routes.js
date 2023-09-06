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
            index: 'api/admin/colors',
            show: 'api/admin/colors/{color}',
            store: 'api/admin/colors',
            update: 'api/admin/colors/{color}',
            destroy: 'api/admin/colors/{color}',
            batchDestroy: 'api/admin/colors/batch',
        },

        brands: {
            index: 'api/admin/brands',
            show: 'api/admin/brands/{brand}',
            store: 'api/admin/brands',
            update: 'api/admin/brands/{brand}',
            destroy: 'api/admin/brands/{brand}',
            batchDestroy: 'api/admin/brands/batch',
        },

        categories: {
            index: 'api/admin/categories',
            show: 'api/admin/categories/{category}',
            store: 'api/admin/categories',
            update: 'api/admin/categories/{category}',
            destroy: 'api/admin/categories/{category}',
            batchDestroy: 'api/admin/categories/batch',
        },

        categoryImages: {
            index: 'api/admin/category-images',
            show: 'api/admin/category-images/{category_image}',
            store: 'api/admin/category-images',
            update: 'api/admin/category-images/{category_image}',
            destroy: 'api/admin/category-images/{category_image}',
        },

        products: {
            index: 'api/admin/products',
            show: 'api/admin/products/{product}',
            store: 'api/admin/products',
            update: 'api/admin/products/{product}',
            destroy: 'api/admin/products/{product}',
            batchDestroy: 'api/admin/products/batch',
        },

        festivals: {
            index: 'api/admin/festivals',
            show: 'api/admin/festivals/{festival}',
            store: 'api/admin/festivals',
            update: 'api/admin/festivals/{festival}',
            destroy: 'api/admin/festivals/{festival}',
            batchDestroy: 'api/admin/festivals/batch',
            //
            products: 'api/admin/festivals/{festival}/products',
            storeProduct: 'api/admin/festivals/{festival}/{product}',
            storeCategoryProducts: 'api/admin/festivals/{festival}/{category}',
            destroyProduct: 'api/admin/festivals/{festival}/{product}',
            batchDestroyProduct: 'api/admin/festivals/{festival}/batch',
        },

        units: {
            index: 'api/admin/units',
            show: 'api/admin/units/{unit}',
            store: 'api/admin/units',
            update: 'api/admin/units/{unit}',
            destroy: 'api/admin/units/{unit}',
            batchDestroy: 'api/admin/units/batch',
        },

        coupons: {
            index: 'api/admin/coupons',
            show: 'api/admin/coupons/{coupon}',
            store: 'api/admin/coupons',
            update: 'api/admin/coupons/{coupon}',
            destroy: 'api/admin/coupons/{coupon}',
            batchDestroy: 'api/admin/coupons/batch',
        },

        blogs: {
            index: 'api/admin/blogs',
            show: 'api/admin/blogs/{blog}',
            store: 'api/admin/blogs',
            update: 'api/admin/blogs/{blog}',
            destroy: 'api/admin/blogs/{blog}',
            batchDestroy: 'api/admin/blogs/batch',
        },

        blogCategories: {
            index: 'api/admin/blog-categories',
            show: 'api/admin/blog-categories/{blog_category}',
            store: 'api/admin/blog-categories',
            update: 'api/admin/blog-categories/{blog_category}',
            destroy: 'api/admin/blog-categories/{blog_category}',
            batchDestroy: 'api/admin/blog-categories/batch',
        },

        staticPages: {
            index: 'api/admin/static-pages',
            show: 'api/admin/static-pages/{static_page}',
            store: 'api/admin/static-pages',
            update: 'api/admin/static-pages/{static_page}',
            destroy: 'api/admin/static-pages/{static_page}',
            batchDestroy: 'api/admin/static-pages/batch',
        },

        contacts: {
            index: 'api/admin/contacts',
            show: 'api/admin/contacts/{contact}',
            update: 'api/admin/contacts/{contact}',
            destroy: 'api/admin/contacts/{contact}',
            batchDestroy: 'api/admin/contacts/batch',
        },

        complaints: {
            index: 'api/admin/complaints',
            show: 'api/admin/complaints/{complaint}',
            update: 'api/admin/complaints/{complaint}',
            destroy: 'api/admin/complaints/{complaint}',
            batchDestroy: 'api/admin/complaints/batch',
        },

        faqs: {
            index: 'api/admin/faqs',
            show: 'api/admin/faqs/{faq}',
            store: 'api/admin/faqs',
            update: 'api/admin/faqs/{faq}',
            destroy: 'api/admin/faqs/{faq}',
            batchDestroy: 'api/admin/faqs/batch',
        },

        newsletters: {
            index: 'api/admin/newsletters',
            show: 'api/admin/newsletters/{newsletter}',
            store: 'api/admin/newsletters',
            destroy: 'api/admin/newsletters/{newsletter}',
            batchDestroy: 'api/admin/newsletters/batch',
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
