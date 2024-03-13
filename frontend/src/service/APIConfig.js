import {GenericAPI} from "./ServiceAPIs.js";
import {apiReplaceParams, apiRoutes} from "@/router/api-routes.js";
import {useRequest} from "@/composables/api-request.js";

export const SliderAPI = Object.assign(
  GenericAPI(apiRoutes.admin.sliders, {replacement: 'slider'}),
  {
    fetchSliderItems(sliderId, callbacks) {
      useRequest(
        apiReplaceParams(apiRoutes.admin.sliders.sliderItems, {slider: sliderId}),
        null,
        callbacks
      )
    },

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
    except: ['store', 'destroy'],
    replacement: 'menu',
  }),
  {
    fetchMenuItems(menuId, callbacks) {
      useRequest(
        apiReplaceParams(apiRoutes.admin.menus.menuItems, {menu: menuId}),
        null,
        callbacks
      )
    },

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

export const SettingAPI = {
  updateSetting(data, callbacks) {
    useRequest(
      apiRoutes.admin.settings.update,
      {
        method: 'PUT',
        data,
      },
      callbacks
    )
  },
  fetchAll(groupName, callbacks) {
    useRequest(
      apiReplaceParams(apiRoutes.admin.settings.index, {group: groupName}),
      null,
      callbacks
    )
  },
}

