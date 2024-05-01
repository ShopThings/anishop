import {GenericAPI} from "./ServiceAPIs.js";
import {apiReplaceParams, apiRoutes} from "@/router/api-routes.js";
import {useRequest} from "@/composables/api-request.js";
import {useHead} from "@unhead/vue";

export const BlogAPI = Object.assign(
  GenericAPI(apiRoutes.admin.blogs, {
    replacement: 'blog',
    urlCallbacks: {
      show: {
        success(response) {
          useHead()
        },
      },
    },
  }),
  {
    // extra functionality goes here
  }
)

export const BlogBadgeAPI = Object.assign(
  GenericAPI(apiRoutes.admin.blogBadges, {replacement: 'blog_badge'}),
  {
    // extra functionality goes here
  }
)

export const BlogCommentAPI = {
  fetchById(blogId, commentId, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.admin.blogComments.show, {
        blog: blogId,
        comment: commentId,
      }),
      null,
      callbacks
    )
  },

  fetchAll(blogId, params, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.admin.blogComments.index, {blog: blogId}),
      {params},
      callbacks
    )
  },

  fetchAllComments(params, callbacks) {
    return useRequest(
      apiRoutes.admin.blogComments.all,
      {params},
      callbacks
    )
  },

  create(blogId, data, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.admin.blogComments.store, {blog: blogId}),
      {
        method: 'POST',
        data,
      },
      callbacks
    )
  },

  updateById(blogId, commentId, data, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.admin.blogComments.update, {
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

  deleteById(blogId, commentId, callbacks) {
    return useRequest(
      apiReplaceParams(apiRoutes.admin.blogComments.destroy, {
        blog: blogId,
        comment: commentId,
      }),
      {method: 'DELETE'},
      callbacks
    )
  },

  deleteByIds(blogId, ids, callback) {
    return useRequest(
      apiReplaceParams(apiRoutes.admin.blogComments.batchDestroy, {blog: blogId}),
      {
        method: 'DELETE',
        data: {
          ids,
        }
      },
      callback
    )
  },
}

export const BlogCategoryAPI = Object.assign(
  GenericAPI(apiRoutes.admin.blogCategories, {replacement: 'blog_category'}),
  {
    // extra functionality goes here
  }
)
