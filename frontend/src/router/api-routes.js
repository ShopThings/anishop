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
            index: 'api/admin/users/{user}/addresses',
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

        products: {
            index: 'api/admin/products',
            show: 'api/admin/products/{product}',
            store: 'api/admin/products',
            update: 'api/admin/products/{product}',
            destroy: 'api/admin/products/{product}',
            batchDestroy: 'api/admin/products/batch',
            showMain: 'api/admin/products/{product}/main-info',
        },

        productAttributes: {
            index: 'api/admin/product-attributes',
            show: 'api/admin/product-attributes/{product_attribute}',
            store: 'api/admin/product-attributes',
            update: 'api/admin/product-attributes/{product_attribute}',
            destroy: 'api/admin/product-attributes/{product_attribute}',
            batchDestroy: 'api/admin/product-attributes/batch',
            showProductMain: 'api/admin/product-attributes/{product}/main-info',
        },

        productAttributeValues: {
            index: 'api/admin/product-attribute-values',
            show: 'api/admin/product-attribute-values/{product_attribute_value}',
            store: 'api/admin/product-attribute-values',
            update: 'api/admin/product-attribute-values/{product_attribute_value}',
            destroy: 'api/admin/product-attribute-values/{product_attribute_value}',
            batchDestroy: 'api/admin/product-attribute-values/batch',
        },

        productAttributeCategories: {
            index: 'api/admin/product-attribute-categories',
            show: 'api/admin/product-attribute-categories/{product_attribute_category}',
            store: 'api/admin/product-attribute-categories',
            update: 'api/admin/product-attribute-categories/{product_attribute_category}',
            destroy: 'api/admin/product-attribute-categories/{product_attribute_category}',
            batchDestroy: 'api/admin/product-attribute-categories/batch',
        },

        productAttributeProducts: {
            update: 'api/admin/product-attribute-products/{product_attribute_product}',
        },

        comments: {
            index: 'api/admin/comments',
            show: 'api/admin/comments/{comment}',
            store: 'api/admin/comments',
            update: 'api/admin/comments/{comment}',
            destroy: 'api/admin/comments/{comment}',
            batchDestroy: 'api/admin/comments/batch',
        },

        orders: {
            index: 'api/admin/orders/{user?}',
            show: 'api/admin/orders/{order}',
            update: 'api/admin/orders/{order}',
            destroy: 'api/admin/orders/{order}',
            paymentStatuses: 'api/admin/orders/payment-statuses',
            sendStatuses: 'api/admin/orders/send-statuses',
        },

        orderBadges: {
            index: 'api/admin/order-badges',
            show: 'api/admin/order-badges/{order_badge}',
            store: 'api/admin/order-badges',
            update: 'api/admin/order-badges/{order_badge}',
            destroy: 'api/admin/order-badges/{order_badge}',
            batchDestroy: 'api/admin/order-badges/batch',
        },

        returnOrders: {
            index: 'api/admin/return-orders',
            show: 'api/admin/return-orders/{return_order}',
            update: 'api/admin/return-orders/{return_order}',
            destroy: 'api/admin/return-orders/{return_order}',
        },

        reports: {
            usersQueryBuilder: 'api/admin/users/query-builder',
            productsQueryBuilder: 'api/admin/products/query-builder',
            ordersQueryBuilder: 'api/admin/orders/query-builder',
            users: 'api/admin/reports/users',
            products: 'api/admin/reports/products',
            orders: 'api/admin/reports/orders',
        },

        blogBadges: {
            index: 'api/admin/blog-badges',
            show: 'api/admin/blog-badges/{blog_badge}',
            store: 'api/admin/blog-badges',
            update: 'api/admin/blog-badges/{blog_badge}',
            destroy: 'api/admin/blog-badges/{blog_badge}',
            batchDestroy: 'api/admin/blog-badges/batch',
        },

        blogs: {
            index: 'api/admin/blogs',
            show: 'api/admin/blogs/{blog}',
            store: 'api/admin/blogs',
            update: 'api/admin/blogs/{blog}',
            destroy: 'api/admin/blogs/{blog}',
            batchDestroy: 'api/admin/blogs/batch',
        },

        blogComments: {
            index: 'api/admin/blog-comments',
            show: 'api/admin/blog-comments/{blog_comment}',
            store: 'api/admin/blog-comments',
            update: 'api/admin/blog-comments/{blog_comment}',
            destroy: 'api/admin/blog-comments/{blog_comment}',
            batchDestroy: 'api/admin/blog-comments/batch',
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

        cityPostPrices: {
            index: 'api/admin/city-post-prices',
            show: 'api/admin/city-post-prices/{city_post_price}',
            store: 'api/admin/city-post-prices',
            update: 'api/admin/city-post-prices/{city_post_price}',
            destroy: 'api/admin/city-post-prices/{city_post_price}',
            batchDestroy: 'api/admin/city-post-prices/batch',
            provinces: 'api/admin/provinces',
            cities: 'api/admin/cities',
        },

        weightPostPrices: {
            index: 'api/admin/weight-post-prices',
            show: 'api/admin/weight-post-prices/{weight_post_price}',
            store: 'api/admin/weight-post-prices',
            update: 'api/admin/weight-post-prices/{weight_post_price}',
            destroy: 'api/admin/weight-post-prices/{weight_post_price}',
            batchDestroy: 'api/admin/weight-post-prices/batch',
        },

        sliders: {
            index: 'api/admin/sliders',
            show: 'api/admin/sliders/{slider}',
            store: 'api/admin/sliders',
            update: 'api/admin/sliders/{slider}',
            destroy: 'api/admin/sliders/{slider}',
            batchDestroy: 'api/admin/sliders/batch',
            places: 'api/admin/sliders-place',
        },

        menus: {
            index: 'api/admin/menus',
            show: 'api/admin/menus/{menu}',
            store: 'api/admin/menus',
            update: 'api/admin/menus/{menu}',
            destroy: 'api/admin/menus/{menu}',
            batchDestroy: 'api/admin/menus/batch',
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
