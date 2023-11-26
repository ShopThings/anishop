import {GenericAPI} from "./ServiceAPIs.js";
import {apiReplaceParams, apiRoutes} from "../router/api-routes.js";
import {useRequest} from "../composables/api-request.js";

export const BlogAPI = Object.assign(
    GenericAPI(apiRoutes.admin.blogs, {replacement: 'blog'}),
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
    fetchById(blogId, commentId, callback) {
        useRequest(
            apiReplaceParams(apiRoutes.admin.blogComments.show, {
                blog: blogId,
                comment: commentId,
            }),
            null,
            callback
        )
    },

    fetchAll(blogId, params, callbacks) {
        useRequest(
            apiReplaceParams(apiRoutes.admin.blogComments.index, {blog: blogId}),
            {params},
            callbacks
        )
    },

    updateById(blogId, commentId, data, callback) {
        useRequest(
            apiReplaceParams(apiRoutes.admin.blogComments.update, {
                blog: blogId,
                comment: commentId,
            }),
            {
                method: 'PUT',
                data,
            },
            callback
        )
    },

    deleteById(blogId, commentId, callback) {
        useRequest(
            apiReplaceParams(apiRoutes.admin.blogComments.destroy, {
                blog: blogId,
                comment: commentId,
            }),
            {method: 'DELETE'},
            callback
        )
    },

    deleteByIds(blogId, ids, callback) {
        useRequest(
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
