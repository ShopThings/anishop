import {apiRoutes} from "../router/api-routes.js";
import {GenericAPI} from "./ServiceAPIs.js";

export const PaymentMethodAPI = Object.assign(
    GenericAPI(apiRoutes.admin.paymentMethods, {replacement: 'payment_method'}),
    {
        // extra functionality goes here
    }
)
