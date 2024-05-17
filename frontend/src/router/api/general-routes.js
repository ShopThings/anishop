export default {
  maintenance: '/api/maintenance',

  captcha: '/captcha/api',
  showFile: '/api/files',

  settings: 'api/settings',

  cart: {
    index: 'api/cart',
    show: 'api/cart/{cart}',
    sessionCarts: 'api/cart/session',
    store: 'api/cart',
    destroy: 'api/cart',
    active: 'api/cart/active',
    addAll: 'api/cart/addAll',
    add: 'api/cart/{code}/add',
  },

  provinces: 'api/provinces',
  cities: 'api/cities/{province}',

  signup: {
    stepMobile: 'api/signup/mobile',
    stepCode: 'api/signup/code',
    stepPass: 'api/signup/new-password',
    resendCode: 'api/signup/resend-code',
  },

  recoverPass: {
    stepMobile: 'api/recover-password/mobile',
    stepCode: 'api/recover-password/code',
    stepNewPass: 'api/recover-password/new-password',
    resendCode: 'api/recover-password/resend-code',
  },

  paymentMethods: {
    index: 'api/payment-methods'
  },

  sendMethods: {
    index: 'api/send-methods',
  },

  coupons: {
    check: 'api/coupons/{code}/check'
  },

  checkout: {
    placeOrder: 'api/place-order',
    payOrder: 'api/pay/{id}',
    sendPrice: 'api/send-price',
    payResult: 'api/pay/{id}/result',
  },

  main: {
    menu: 'api/menus/{menu}',
    categories: 'api/categories/main',
    sliderMain: 'api/sliders/main',
    sliderChosenCategories: 'api/sliders/categories',
    sliderPopularBrands: 'api/sliders/brands',
    sliderOffers: 'api/sliders/amazing-offers',
    sliders: 'api/sliders',
    latestBlogs: 'api/blogs/latest',
  },

  brands: 'api/brands',

  search: {
    product: 'api/search/products',
    blog: 'api/search/blogs',
  },

  products: {
    index: 'api/products',
    show: 'api/products/{product}',
    minifiedShow: 'api/products/{product}/minified',
    brandsFilter: 'api/products/filter/brands',
    colorsAndSizesFilter: 'api/products/filter/colors-and-sizes',
    priceRangeFilter: 'api/products/filter/price-range',
    dynamicFilters: 'api/products/filter/get-dynamic-filters',
  },

  festivals: {
    index: 'api/festivals',
  },

  comments: {
    index: 'api/products/{product}/comments',
    report: 'api/products/{product}/comments/{comment}/report',
    vote: 'api/products/{product}/comments/{comment}/vote',
  },

  blogs: {
    index: 'api/blogs',
    show: 'api/blogs/{blog}',
    minifiedShow: 'api/blogs/{blog}/minified',
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
  },

  pages: 'api/pages/{page}',

  contactUs: 'api/contact-us',
  complaints: 'api/complaints',
  newsletters: 'api/newsletters',
  faqs: 'api/faqs',
}
