import {apiReplaceParams, apiRoutes} from "@/router/api-routes.js";
import {GenericAPI} from "./ServiceAPIs.js";
import {useRequest} from "@/composables/api-request.js";

export const ColorAPI = Object.assign(
  GenericAPI(apiRoutes.admin.colors, {replacement: 'color'}),
  {
    // extra functionality goes here
  }
)

export const BrandAPI = Object.assign(
  GenericAPI(apiRoutes.admin.brands, {replacement: 'brand'}),
  {
    // extra functionality goes here
  }
)

export const CategoryAPI = Object.assign(
  GenericAPI(apiRoutes.admin.categories, {replacement: 'category'}),
  {
    // extra functionality goes here
  }
)

export const CategoryImageAPI = Object.assign(
  GenericAPI(apiRoutes.admin.categoryImages, {
    replacement: 'category_image',
    batchDestroy: false,
  }),
  {
    // extra functionality goes here
  }
)

export const UnitAPI = Object.assign(
  GenericAPI(apiRoutes.admin.units, {replacement: 'unit'}),
  {
    // extra functionality goes here
  }
)

export const ProductAPI = Object.assign(
  GenericAPI(apiRoutes.admin.products, {replacement: 'product'}),
  {
    modifyProducts(productId, data, callbacks) {
      useRequest(
        apiReplaceParams(apiRoutes.admin.products.modifyProducts, {product: productId}),
        {
          method: 'POST',
          data,
        },
        callbacks
      )
    },

    modifyBatchInfo(data, callbacks) {
      useRequest(
        apiRoutes.admin.products.batchEditInfo,
        {
          method: 'PUT',
          data,
        },
        callbacks
      )
    },

    modifyBatchPrice(data, callbacks) {
      useRequest(
        apiRoutes.admin.products.batchEditPrice,
        {
          method: 'PUT',
          data,
        },
        callbacks
      )
    },
  }
)

export const ProductAttributeAPI = Object.assign(
  GenericAPI(apiRoutes.admin.productAttributes, {replacement: 'product_attribute'}),
  {
    // extra functionality goes here
  }
)

export const ProductAttributeValueAPI = Object.assign(
  GenericAPI(apiRoutes.admin.productAttributeValues, {replacement: 'product_attribute_value'}),
  {
    // extra functionality goes here
  }
)

export const ProductAttributeCategoryAPI = Object.assign(
  GenericAPI(apiRoutes.admin.productAttributeCategories, {replacement: 'product_attribute_category'}),
  {
    // extra functionality goes here
  }
)

export const ProductAttributeProductAPI = Object.assign(
  GenericAPI(apiRoutes.admin.productAttributeProducts, {
    only: ['show', 'store'],
    replacement: 'product',
  }),
  {
    // extra functionality goes here
  }
)

export const CommentAPI = {
  fetchById(productId, commentId, callback) {
    useRequest(
      apiReplaceParams(apiRoutes.admin.comments.show, {
        product: productId,
        comment: commentId,
      }),
      null,
      callback
    )
  },

  fetchAll(productId, params, callbacks) {
    useRequest(
      apiReplaceParams(apiRoutes.admin.comments.index, {product: productId}),
      {params},
      callbacks
    )
  },

  updateById(productId, commentId, data, callback) {
    useRequest(
      apiReplaceParams(apiRoutes.admin.comments.update, {
        product: productId,
        comment: commentId,
      }),
      {
        method: 'PUT',
        data,
      },
      callback
    )
  },

  deleteById(productId, commentId, callback) {
    useRequest(
      apiReplaceParams(apiRoutes.admin.comments.destroy, {
        product: productId,
        comment: commentId,
      }),
      {method: 'DELETE'},
      callback
    )
  },

  deleteByIds(productId, ids, callback) {
    useRequest(
      apiReplaceParams(apiRoutes.admin.comments.batchDestroy, {product: productId}),
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
