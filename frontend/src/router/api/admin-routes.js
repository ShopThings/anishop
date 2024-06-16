export default {
  admin: {
    login: 'api/admin/login',
    logout: 'api/admin/logout',

    roles: 'api/admin/roles',
    permissions: 'api/admin/permissions',

    main: {
      countOfAlerts: 'api/admin/counting/alerts',
      countOfOrders: 'api/admin/counting/orders',
    },

    notification: {
      index: 'api/admin/notifications',
      update: 'api/admin/notifications',
      check: 'api/admin/notifications/new',
    },

    dashboard: {
      totalSale: 'api/admin/sale/total',
      periodSale: 'api/admin/sale/{period}',
      chartUsers: 'api/admin/chart/users/{period}',
      chartOrders: 'api/admin/chart/orders/{period}/{status}',
      chartReturnOrders: 'api/admin/chart/return-orders/{period}/{status}',
      tableMostSaleProducts: 'api/admin/table/most-sale-product/{period}',
      dashboardCounting: 'api/admin/dashboard/counting',
    },

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
      index: 'api/admin/payment-methods',
      show: 'api/admin/payment-methods/{payment_method}',
      store: 'api/admin/payment-methods',
      update: 'api/admin/payment-methods/{payment_method}',
      destroy: 'api/admin/payment-methods/{payment_method}',
      batchDestroy: 'api/admin/payment-methods/batch',
    },

    sendMethods: {
      index: 'api/admin/send-methods',
      show: 'api/admin/send-methods/{send_method}',
      store: 'api/admin/send-methods',
      update: 'api/admin/send-methods/{send_method}',
      destroy: 'api/admin/send-methods/{send_method}',
      batchDestroy: 'api/admin/send-methods/batch',
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
      storeProduct: 'api/admin/festivals/{festival}/product',
      storeCategoryProducts: 'api/admin/festivals/{festival}/category',
      destroyProduct: 'api/admin/festivals/{festival}/product/{product}',
      batchDestroyCategory: 'api/admin/festivals/{festival}/category/{category}',
      batchDestroyProduct: 'api/admin/festivals/{festival}/products',
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
      showVariants: 'api/admin/products/{product}/variants',
      galleyStore: 'api/admin/products/{product}/gallery',
      galleyShow: 'api/admin/products/{product}/gallery',
      relatedProductsStore: 'api/admin/products/{product}/related-products',
      relatedProductsShow: 'api/admin/products/{product}/related-products',
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
      all: 'api/admin/products/comments/all',
      show: 'api/admin/products/{product}/comments/{comment}',
      update: 'api/admin/products/{product}/comments/{comment}',
      destroy: 'api/admin/products/{product}/comments/{comment}',
      batchDestroy: 'api/admin/products/{product}/comments/batch',
    },

    orders: {
      index: 'api/admin/orders/all/{user?}',
      show: 'api/admin/orders/{order}',
      update: 'api/admin/orders/{order}',
      destroy: 'api/admin/orders/{order}',
      //
      updatePayment: 'api/admin/orders/{order}/payment',
      paymentStatuses: 'api/admin/orders/payment-statuses',
      sendStatuses: 'api/admin/orders/send-statuses',
      exportPdf: 'api/admin/orders/export/{order}',
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
      index: 'api/admin/return-orders/all/{user?}',
      show: 'api/admin/return-orders/{return_order}',
      update: 'api/admin/return-orders/{return_order}',
      destroy: 'api/admin/return-orders/{return_order}',
      modifyOrderItem: 'api/admin/return-orders/{return_order}/{return_order_item}/modify-item',
      allStatuses: 'api/admin/return-orders/all-statuses',
      statuses: 'api/admin/return-orders/statuses',
      returnOrderItems: 'api/admin/return-orders/{return_order}/to-stock',
    },

    reports: {
      usersQueryBuilder: 'api/admin/reports/users/query-builder',
      productsQueryBuilder: 'api/admin/reports/products/query-builder',
      ordersQueryBuilder: 'api/admin/reports/orders/query-builder',
      users: 'api/admin/reports/users',
      products: 'api/admin/reports/products',
      orders: 'api/admin/reports/orders',
      usersExport: 'api/admin/reports/users/export',
      productsExport: 'api/admin/reports/products/export',
      ordersExport: 'api/admin/reports/orders/export',
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
      all: 'api/admin/blogs/comments/all',
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
      sliderItems: 'api/admin/sliders/{slider}/slides',
      modifySliderItem: 'api/admin/sliders/{slider}/modify',
    },

    menus: {
      index: 'api/admin/menus',
      show: 'api/admin/menus/{menu}',
      update: 'api/admin/menus/{menu}',
      batchDestroy: 'api/admin/menus/batch',
      menuItems: 'api/admin/menus/{menu}/items',
      modifyMenuItem: 'api/admin/menus/{menu}/modify',
    },

    settings: {
      index: 'api/admin/settings/{group?}',
      update: 'api/admin/settings',
    },

    files: {
      list: 'api/admin/files',
      tree: 'api/admin/files/tree',
      createDir: 'api/admin/files/directory',
      rename: 'api/admin/files/rename',
      move: 'api/admin/files/move',
      copy: 'api/admin/files/copy',
      destroy: 'api/admin/files',
      batchDestroy: 'api/admin/files/batch',
      upload: 'api/admin/files',
      download: 'api/admin/files/{file}'
    },
  },
}
