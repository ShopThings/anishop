export default {
  user: {
    login: 'api/login',
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
        index: 'api/user/notifications',
        update: 'api/user/notifications',
        check: 'api/user/notifications/new',
      },
    },

    orders: {
      index: 'api/user/orders',
      show: 'api/user/orders/{order}',
      update: 'api/user/orders/{order}',
      unpaidOrderPayments: 'api/user/orders/unpaid-order-payments'
    },

    returnOrders: {
      index: 'api/user/return-orders',
      show: 'api/user/return-orders/{return_order}',
      store: 'api/user/return-orders/{order}',
      update: 'api/user/return-orders/{return_order}',
      destroy: 'api/user/return-orders/{return_order}',
      changeStatus: 'api/user/return-orders/{return_order}/change-status',
      returnableOrders: 'api/user/return-orders/returnable-orders',
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
}
