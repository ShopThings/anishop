import {useRequest} from "@/composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "@/router/api-routes.js";
import {GenericAPI} from "./ServiceAPIs.js";

export const HomeMainPageAPI = {
  fetchSliderMain(callbacks) {
    useRequest(apiRoutes.main.sliderMain, null, callbacks)
  },

  fetchSliderChosenCategories(callbacks) {
    useRequest(apiRoutes.main.sliderChosenCategories, null, callbacks)
  },

  fetchSliderPopularBrands(callbacks) {
    useRequest(apiRoutes.main.sliderPopularBrands, null, callbacks)
  },

  fetchSliderOffers(callbacks) {
    useRequest(apiRoutes.main.sliderOffers, null, callbacks)
  },

  fetchSliders(callbacks) {
    useRequest(apiRoutes.main.sliders, null, callbacks)
  },

  fetchLatestBlogs(callbacks) {
    useRequest(apiRoutes.main.latestBlogs, null, callbacks)
  },

  createContactUs(data, callbacks) {
    useRequest(
      apiRoutes.contactUs,
      {
        method: 'POST',
        data,
      },
      callbacks
    )
  },

  createComplaint(data, callbacks) {
    useRequest(
      apiRoutes.complaints,
      {
        method: 'POST',
        data,
      },
      callbacks
    )
  },

  addToNewsletter(mobile, callbacks) {
    useRequest(
      apiRoutes.newsletters,
      {
        method: 'POST',
        data: {mobile},
      },
      callbacks
    )
  },

  fetchAllFaqs(callbacks) {
    useRequest(apiRoutes.faqs, null, callbacks)
  },
}

export const HomeBrandAPI = {
  fetchAll(callbacks) {
    useRequest(apiRoutes.brands, null, callbacks)
  },
}

export const HomeProductAPI = Object.assign(
  GenericAPI(apiRoutes.products, {
    only: ['index', 'show'],
    replacement: 'product',
  }), {
    fetchColorsAndSizes(callbacks) {
      useRequest(apiRoutes.products.colorsAndSizes, null, callbacks)
    },

    getPriceRange(callbacks) {
      useRequest(apiRoutes.products.priceRange, null, callbacks)
    },

    getDynamicFilters(callbacks) {
      useRequest(apiRoutes.products.dynamicFilters, null, callbacks)
    },
  }
)

export const HomeCommentAPI = {
  fetchAll(productId, params, callbacks) {
    useRequest(
      apiReplaceParams(apiRoutes.comments.index, {product: productId}),
      {params},
      callbacks
    )
  },

  report(productId, commentId, data, callbacks) {
    useRequest(
      apiReplaceParams(apiRoutes.comments.report, {
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

  vote(productId, commentId, data, callbacks) {
    useRequest(
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
    useRequest(
      apiReplaceParams(apiRoutes.blogComments.index, {blog: blogId}),
      {params},
      callbacks
    )
  },

  report(blogId, commentId, data, callbacks) {
    useRequest(
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
    useRequest(
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
    useRequest(apiRoutes.blogs.sliderMain, null, callbacks)
  },

  fetchSliderMainSideImages(callbacks) {
    useRequest(apiRoutes.blogs.sliderMainSide, null, callbacks)
  },

  fetchPopularCategories(callbacks) {
    useRequest(apiRoutes.blogs.popularCategories, null, callbacks)
  },

  fetchMostViewedBlogs(callbacks) {
    useRequest(apiRoutes.blogs.mostViewedPosts, null, callbacks)
  },
}

export const HomeBlogAPI = Object.assign(
  GenericAPI(apiRoutes.blogs, {
    only: ['index', 'show'],
    replacement: 'blog',
  }), {
    fetchBlogArchive(callbacks) {
      useRequest(apiRoutes.blogs.archive, null, callbacks)
    },

    vote(blogId, data, callbacks) {
      useRequest(
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
  storeMobile(mobile, callbacks) {
    useRequest(
      apiRoutes.signup.stepMobile,
      {
        method: 'POST',
        data: {username: mobile},
      },
      callbacks
    )
  },

  verifyCode(code, callbacks) {
    useRequest(
      apiRoutes.signup.stepCode,
      {
        method: 'POST',
        data: {code},
      },
      callbacks
    )
  },

  assignPassword(data, callbacks) {
    useRequest(
      apiRoutes.signup.stepPass,
      {
        method: 'POST',
        data,
      },
      callbacks
    )
  },

  resendVerifyCode(callbacks) {
    useRequest(
      apiRoutes.signup.resendCode,
      {method: 'POST'},
      callbacks
    )
  },
}

export const HomeRecoverPasswordAPI = {
  checkMobile(mobile, callbacks) {
    useRequest(
      apiRoutes.recoverPass.stepMobile,
      {
        method: 'POST',
        data: {username: mobile},
      },
      callbacks
    )
  },

  verifyCode(code, callbacks) {
    useRequest(
      apiRoutes.recoverPass.stepCode,
      {
        method: 'POST',
        data: {code},
      },
      callbacks
    )
  },

  assignNewPassword(data, callbacks) {
    useRequest(
      apiRoutes.recoverPass.stepNewPass,
      {
        method: 'POST',
        data,
      },
      callbacks
    )
  },

  resendVerifyCode(callbacks) {
    useRequest(
      apiRoutes.recoverPass.resendCode,
      {method: 'POST'},
      callbacks
    )
  },
}
