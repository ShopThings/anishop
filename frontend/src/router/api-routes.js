import isObject from "lodash.isobject";

export const apiRoutes = {
    // admin routes
    admin: {
        login: 'api/admin/login',
        logout: 'api/admin/logout',

        roles: 'api/admin/roles',
        permissions: 'api/admin/permissions',

        users: {
            index: 'api/admin/users',
            show: 'api/admin/users/{user}',
            store: 'api/admin/users',
            update: 'api/admin/users/{user}',
            destroy: 'api/admin/users/{user}',
            batchDestroy: 'api/admin/users/batch',
            addresses: 'api/admin/users/{user}/addresses',
            favoriteProducts: 'api/admin/users/{user}/favorite-product',
            purchases: 'api/admin/users/{user}/purchases',
            carts: 'api/admin/users/{user}/carts',
        },

        paymentMethods: {
            index: 'api/admin/payment-method',
            show: 'api/admin/payment-methods/{payment_method}',
            store: 'api/admin/payment-methods/{payment_method}',
            update: 'api/admin/payment-methods/{payment_method}',
            destroy: 'api/admin/payment-methods/{payment_method}',
            batchDestroy: 'api/admin/payment-methods/batch',
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
            batchDestroyProduct: 'api/admin/festivals/{festival}/category/{category}',
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
            modifyProducts: 'api/admin/products/{product}/modify',
            batchEditInfo: 'api/admin/products/batch/info',
            batchEditPrice: 'api/admin/products/batch/price',
        },

        productAttributes: {
            index: 'api/admin/product-attributes',
            show: 'api/admin/product-attributes/{product_attribute}',
            store: 'api/admin/product-attributes',
            update: 'api/admin/product-attributes/{product_attribute}',
            destroy: 'api/admin/product-attributes/{product_attribute}',
            batchDestroy: 'api/admin/product-attributes/batch',
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
            show: 'api/admin/product-attribute-products/{product}',
            store: 'api/admin/product-attribute-products/{product}',
        },

        comments: {
            index: 'api/admin/products/{product}/comments',
            show: 'api/admin/products/{product}/comments/{comment}',
            update: 'api/admin/products/{product}/comments/{comment}',
            destroy: 'api/admin/products/{product}/comments/{comment}',
            batchDestroy: 'api/admin/products/{product}/comments/batch',
        },

        orders: {
            index: 'api/admin/orders/{user?}',
            show: 'api/admin/orders/{order}',
            update: 'api/admin/orders/{order}',
            destroy: 'api/admin/orders/{order}',
            //
            updatePayment: 'api/admin/orders/{order}/payment',
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
            index: 'api/admin/return-orders/{user?}',
            show: 'api/admin/return-orders/{return_order}',
            update: 'api/admin/return-orders/{return_order}',
            destroy: 'api/admin/return-orders/{return_order}',
            modifyOrderItem: 'api/admin/return-orders/{return_order}/{return_order_item}/modify-item',
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
            index: 'api/admin/blogs/{blog}/comments',
            show: 'api/admin/blogs/{blog}/comments/{comment}',
            store: 'api/admin/blogs/{blog}/comments',
            update: 'api/admin/blogs/{blog}/comments/{comment}',
            destroy: 'api/admin/blogs/{blog}/comments/{comment}',
            batchDestroy: 'api/admin/blogs/{blog}/comments/batch',
        },

        blogCategories: {
            index: 'api/admin/blog-categories',
            show: 'api/admin/blog-categories/{blog_category}',
            store: 'api/admin/blog-categories',
            update: 'api/admin/blog-categories/{blog_category}',
            destroy: 'api/admin/blog-categories/{blog_category}',
            batchDestroy: 'api/admin/blog-categories/batch',
        },

        smsLogs: {
            index: 'api/admin/sms-log',
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

        provinces: 'api/admin/provinces',
        cities: 'api/admin/cities/{province}',

        cityPostPrices: {
            index: 'api/admin/city-post-prices',
            show: 'api/admin/city-post-prices/{city_post_price}',
            store: 'api/admin/city-post-prices',
            update: 'api/admin/city-post-prices/{city_post_price}',
            destroy: 'api/admin/city-post-prices/{city_post_price}',
            batchDestroy: 'api/admin/city-post-prices/batch',
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
            modifySliderItem: 'api/admin/sliders/{slider}/modify',
        },

        menus: {
            index: 'api/admin/menus',
            show: 'api/admin/menus/{menu}',
            batchDestroy: 'api/admin/menus/batch',
            modifyMenuItem: 'api/admin/menus/{menu}/modify',
        },

        settings: {
            index: 'api/admin/settings',
            update: 'api/admin/settings/{setting}',
        },

        files: {
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
        login: 'api/user/login',
        logout: 'api/user/logout',

        main: {
            countOfStuffs: 'api/user/count-of-stuffs',
            latestOrders: 'api/user/orders/latest',
            latestReturnOrders: 'api/user/return-orders/latest',
        },

        info: {
            info: 'api/user/info',
            password: 'api/user/password',
            notification: {
                index: 'api/user/notification',
                update: 'api/user/notification/{notification}',
            },
        },

        orders: {
            index: 'api/user/orders',
            show: 'api/user/orders/{order}',
            update: 'api/user/orders/{order}',
            destroy: 'api/user/orders/{order}',
        },

        returnOrders: {
            index: 'api/user/return-orders',
            show: 'api/user/return-orders/{return_order}',
            store: 'api/user/return-orders/{order}',
            update: 'api/user/return-orders/{return_order}',
            destroy: 'api/user/return-orders/{return_order}',
        },

        comments: {
            index: 'api/user/product/comments',
            show: 'api/user/product/comments/{comment}',
            store: 'api/user/product/{product}/comments',
            update: 'api/user/product/comments/{comment}',
            destroy: 'api/user/product/comments/{comment}',
        },

        blogComments: {
            index: 'api/user/blog/comments',
            show: 'api/user/blog/comments/{comment}',
            store: 'api/user/blog/{blog}/comments',
            update: 'api/user/blog/comments/{comment}',
            destroy: 'api/user/blog/comments/{comment}',
        },

        favoriteProducts: {
            index: 'api/user/favorite-products',
            store: 'api/user/favorite-products',
            destroy: 'api/user/favorite-products/{product}',
        },

        addresses: {
            index: 'api/user/addresses',
            show: 'api/user/addresses/{address}',
            store: 'api/user/addresses',
            update: 'api/user/addresses/{address}',
            destroy: 'api/user/addresses/{address}',
        },

        contacts: {
            index: 'api/user/contacts',
            show: 'api/user/contacts/{contact}',
            destroy: 'api/user/contacts/{contact}',
        },
    },

    // general routes
    captcha: '/captcha/api',
    showFile: 'api/files/{file}/{size?}',

    signup: {
        stepMobile: 'api/signup/mobile',
        stepCode: 'api/signup/code',
        stepPass: 'api/signup/new-password',
    },

    recoverPass: {
        stepMobile: 'api/recover-password/mobile',
        stepCode: 'api/recover-password/code',
        stepNewPass: 'api/recover-password/new-password',
    },

    main: {
        sliderMain: 'api/sliders/main',
        sliderChosenCategories: 'api/sliders/categories',
        sliderPopularBrands: 'api/sliders/brands',
        sliderOffers: 'api/sliders/amazing-offers',
        sliders: 'api/sliders',
        latestBlogs: 'api/blogs/latest',
    },

    brands: 'api/brands',

    products: {
        index: 'api/products',
        show: 'api/products/{product}',
        colors: 'api/products/colors',
        sizes: 'api/products/sizes',
        priceRange: 'api/products/price-range',
    },

    comments: {
        index: 'api/products/{product}/comments',
        report: 'api/products/{product}/comments/{comment}/report',
        vote: 'api/products/{product}/comments/{comment}/vote',
    },

    blogs: {
        index: 'api/blogs',
        show: 'api/blogs/{blog}',
        vote: 'api/blogs/{blog}/vote',
        archive: 'api/blogs/archive',
        sliderMain: 'api/blogs/sliders/main',
        sliderMainSide: 'api/blogs/sliders/side-slides',
        popularCategories: 'api/blogs/popular-categories',
        mostViewedPosts: 'api/blogs/most-viewed',
    },

    blogComments: {
        index: 'api/blogs/{blog}/comments',
        report: 'api/blogs/{blog}/comments/{comment}/report',
        vote: 'api/blogs/{blog}/comments/{comment}/vote',
    },

    contactUs: 'api/contact-us',
    complaints: 'api/complaints',
    newsletters: 'api/newsletters',
    faqs: 'api/faqs',
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
