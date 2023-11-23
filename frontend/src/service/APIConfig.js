import {GenericAPI} from "./ServiceAPIs.js";
import {apiReplaceParams, apiRoutes} from "../router/api-routes.js";
import {useRequest} from "../composables/api-request.js";

export const SliderAPI = Object.assign(
    GenericAPI(apiRoutes.admin.sliders, {replacement: 'slider'}),
    {
        modifySliderItems(sliderId, data, callbacks) {
            useRequest(
                apiReplaceParams(apiRoutes.admin.sliders.modifySliderItem, {slider: sliderId}),
                {
                    method: 'POST',
                    data,
                },
                callbacks
            )
        },
    }
)

export const MenuAPI = Object.assign(
    GenericAPI(apiRoutes.admin.menus, {
        except: ['store', 'update', 'destroy'],
        replacement: 'menu',
    }),
    {
        modifyMenuItems(menuId, data, callbacks) {
            useRequest(
                apiReplaceParams(apiRoutes.admin.menus.modifyMenuItem, {menu: menuId}),
                {
                    method: 'POST',
                    data,
                },
                callbacks
            )
        },
    }
)

export const SettingAPI = Object.assign(
    GenericAPI(apiRoutes.admin.settings, {
        only: ['index', 'update'],
        replacement: 'setting',
    }),
    {
        // extra functionality goes here
    }
)
