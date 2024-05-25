import {useRequest} from "@/composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "@/router/api-routes.js";
import {GenericAPI} from "./ServiceAPIs.js";

export const HomeSettingAPI = {
  fetchAll(callbacks) {
    return useRequest(
      apiRoutes.settings,
      null,
      callbacks
    )
  },
}

export const HomeMainPageAPI = {
  fetchMenu(menu, callbacks) {
    return useRequest(apiReplaceParams(apiRoutes.main.menu, {menu}), null, callbacks)
  },

  fetchCategoriesMain(params, callbacks) {
    return useRequest(apiRoutes.main.categories, {params}, callbacks)
  },

  fetchSliderMain(callbacks) {
    return useRequest(apiRoutes.main.sliderMain, null, callbacks)
  },

  fetchSliderChosenCategories(callbacks) {
    return useRequest(apiRoutes.main.sliderChosenCategories, null, callbacks)
  },

  fetchSliderPopularBrands(callbacks) {
    return useRequest(apiRoutes.main.sliderPopularBrands, null, callbacks)
  },

  fetchSliderOffers(callbacks) {
    return useRequest(apiRoutes.main.sliderOffers, null, callbacks)
  },

  fetchSliders(callbacks) {
    return useRequest(apiRoutes.main.sliders, null, callbacks)
  },

  fetchLatestBlogs(callbacks) {
    return useRequest(apiRoutes.main.latestBlogs, null, callbacks)
  },

  createContactUs(data, callbacks) {
    return useRequest(
      apiRoutes.contactUs,
      {
        method: 'POST',
        data,
      },
      callbacks
    )
  },

  createComplaint(data, callbacks) {
    return useRequest(
      apiRoutes.complaints,
      {
        method: 'POST',
        data,
      },
      callbacks
    )
  },

  addToNewsletter(mobile, callbacks) {
    return useRequest(
      apiRoutes.newsletters,
      {
        method: 'POST',
        data: {mobile},
      },
      callbacks
    )
  },

  fetchAllFaqs(callbacks) {
    return useRequest(apiRoutes.faqs, null, callbacks)
  },
}

export const HomePageAPI = {
  fetchById(id, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.pages, {page: id}),
      null,
      callbacks
    )
  },
}

export const HomeBrandAPI = {
  fetchAll(callbacks) {
    return useRequest(apiRoutes.brands, null, callbacks)
  },
}

export const HomeProductAPI = Object.assign(
  GenericAPI(apiRoutes.products, {
    only: ['index', 'show'],
    replacement: 'product',
  }), {
    fetchByIdMinified(id, callbacks) {
      return useRequest(
        apiReplaceParams(apiRoutes.products.minifiedShow, {product: id}),
        null,
        callbacks
      )
    },

    fetchBrandsFilter(params, callbacks) {
      return useRequest(apiRoutes.products.brandsFilter, {params}, callbacks)
    },

    fetchColorsAndSizesFilter(params, callbacks) {
      return useRequest(apiRoutes.products.colorsAndSizesFilter, {params}, callbacks)
    },

    fetchPriceRangeFilter(params, callbacks) {
      return useRequest(apiRoutes.products.priceRangeFilter, {params}, callbacks)
    },

    fetchDynamicFilters(params, callbacks) {
      return useRequest(apiRoutes.products.dynamicFilters, {params}, callbacks)
    },
  }
)

export const HomeFestivalAPI = {
  fetchAll(callbacks) {
    return useRequest(apiRoutes.festivals.index, null, callbacks)
  },
}

export const HomeCommentAPI = {
  fetchAll(productId, params, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.comments.index, {product: productId}),
      {params},
      callbacks
    )
  },

  report(productId, commentId, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.comments.report, {
        product: productId,
        comment: commentId,
      }),
      {method: 'PUT'},
      callbacks
    )
  },

  vote(productId, commentId, data, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.comments.vote, {
        product: productId,
        comment: commentId,
      }),
      {
        method: 'PUT',
        data,
      },
      callbacks
    )
  },
}

export const HomeBlogCommentAPI = {
  fetchAll(blogId, params, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.blogComments.index, {blog: blogId}),
      {params},
      callbacks
    )
  },

  report(blogId, commentId, data, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.blogComments.report, {
        blog: blogId,
        comment: commentId,
      }),
      {
        method: 'PUT',
        data,
      },
      callbacks
    )
  },

  vote(blogId, commentId, data, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.blogComments.vote, {
        blog: blogId,
        comment: commentId,
      }),
      {
        method: 'PUT',
        data,
      },
      callbacks
    )
  },
}

export const HomeBlogMainPage = {
  fetchSliderMain(callbacks) {
    return useRequest(apiRoutes.blogs.sliderMain, null, callbacks)
  },

  fetchSliderMainSideImages(callbacks) {
    return useRequest(apiRoutes.blogs.sliderMainSide, null, callbacks)
  },

  fetchPopularCategories(callbacks) {
    return useRequest(apiRoutes.blogs.popularCategories, null, callbacks)
  },

  fetchMostViewedBlogs(callbacks) {
    return useRequest(apiRoutes.blogs.mostViewedPosts, null, callbacks)
  },
}

export const HomeBlogAPI = Object.assign(
  GenericAPI(apiRoutes.blogs, {
    only: ['index', 'show'],
    replacement: 'blog',
  }), {
    fetchByIdMinified(id, callbacks) {
      return useRequest(
        apiReplaceParams(apiRoutes.blogs.minifiedShow, {blog: id}),
        null,
        callbacks
      )
    },

    fetchBlogArchive(callbacks) {
      return useRequest(apiRoutes.blogs.archive, null, callbacks)
    },

    vote(blogId, data, callbacks) {
      return useRequest(
        apiReplaceParams(apiRoutes.blogs.vote, {blog: blogId}),
        {
          method: 'POST',
          data,
        },
        callbacks
      )
    },
  }
)

export const HomeSignupAPI = {
  storeMobile(data, callbacks) {
    return useRequest(
      apiRoutes.signup.stepMobile,
      {
        method: 'POST',
        data,
      },
      callbacks
    )
  },

  verifyCode(data, callbacks) {
    return useRequest(
      apiRoutes.signup.stepCode,
      {
        method: 'POST',
        data,
      },
      callbacks
    )
  },

  assignPassword(data, callbacks) {
    return useRequest(
      apiRoutes.signup.stepPass,
      {
        method: 'POST',
        data,
      },
      callbacks
    )
  },

  resendVerifyCode(callbacks) {
    return useRequest(
      apiRoutes.signup.resendCode,
      {method: 'POST'},
      callbacks
    )
  },
}

export const HomeRecoverPasswordAPI = {
  checkMobile(data, callbacks) {
    return useRequest(
      apiRoutes.recoverPass.stepMobile,
      {
        method: 'POST',
        data,
      },
      callbacks
    )
  },

  verifyCode(data, callbacks) {
    return useRequest(
      apiRoutes.recoverPass.stepCode,
      {
        method: 'POST',
        data,
      },
      callbacks
    )
  },

  assignNewPassword(data, callbacks) {
    return useRequest(
      apiRoutes.recoverPass.stepNewPass,
      {
        method: 'POST',
        data,
      },
      callbacks
    )
  },

  resendVerifyCode(callbacks) {
    return useRequest(
      apiRoutes.recoverPass.resendCode,
      {method: 'POST'},
      callbacks
    )
  },
}

export const HomePaymentMethodAPI = {
  fetchAll(callbacks) {
    return useRequest(
      apiRoutes.paymentMethods.index,
      null,
      callbacks
    )
  }
}

export const HomeSendMethodAPI = {
  fetchAll(callbacks) {
    return useRequest(
      apiRoutes.sendMethods.index,
      null,
      callbacks
    )
  }
}

export const HomeCouponAPI = {
  checkCoupon(code, data, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.coupons.check, {code}),
      {
        method: 'POST',
        data,
      },
      callbacks
    )
  }
}

export const HomeCheckoutAPI = {
  placeOrder(data, callbacks) {
    return useRequest(
      apiRoutes.checkout.placeOrder,
      {
        method: 'POST',
        data,
      },
      callbacks
    )
  },

  payOrder(id, orderCode, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.checkout.payOrder, {id}),
      {
        method: 'POST',
        data: {
          order_code: orderCode,
        },
      },
      callbacks
    )
  },

  payOrderResult(id, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.checkout.payResult, {id}),
      {method: 'POST'},
      callbacks
    )
  },

  calculateSendPrice(data, callbacks) {
    return useRequest(
      apiRoutes.checkout.sendPrice,
      {
        method: 'POST',
        data,
      },
      callbacks
    )
  }
}
