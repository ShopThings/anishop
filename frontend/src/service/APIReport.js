import {useRequest} from "../composables/api-request.js";
import {apiRoutes} from "../router/api-routes.js";

export const ReportAPI = {
    getUsersQB(callbacks) {
        useRequest(apiRoutes.admin.reports.usersQueryBuilder, null, callbacks)
    },

    getProductsQB(callbacks) {
        useRequest(apiRoutes.admin.reports.productsQueryBuilder, null, callbacks)
    },

    getOrdersQB(callbacks) {
        useRequest(apiRoutes.admin.reports.ordersQueryBuilder, null, callbacks)
    },

    fetchUsers(callbacks) {
        useRequest(apiRoutes.admin.reports.users, null, callbacks)
    },

    fetchProducts(callbacks) {
        useRequest(apiRoutes.admin.reports.products, null, callbacks)
    },

    fetchOrders(callbacks) {
        useRequest(apiRoutes.admin.reports.orders, null, callbacks)
    },
}
