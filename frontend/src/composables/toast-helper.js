import BaseConfirm from "../components/base/BaseConfirm.vue";
import {POSITION, TYPE, useToast} from "vue-toastification";
import isFunction from "lodash.isfunction";
import BaseLoading from "@/components/base/toast/BaseLoading.vue";

export const useConfirmToast = (onAccept, title, subTitle, showBackdrop) => {
  onAccept = isFunction(onAccept) ? onAccept : () => {
  }

  const toast = useToast()

  return toast({
    component: BaseConfirm,
    props: {
      title,
      subTitle,
      showBackdrop: showBackdrop ?? true,
    },
    listeners: {
      accept: onAccept
    },
  }, {
    timeout: false,
    type: TYPE.DEFAULT,
    position: POSITION.TOP_CENTER,
    closeOnClick: false,
    closeButton: false,
    toastClassName: 'confirmation-toast',
  })
}

export const useLoadingToast = (title, isLoading, showBackdrop) => {
  const toast = useToast()

  return toast({
    component: BaseLoading,
    props: {
      title,
      isLoading,
      showBackdrop: showBackdrop ?? false
    },
  }, {
    timeout: false,
    type: TYPE.DEFAULT,
    position: POSITION.BOTTOM_LEFT,
    closeOnClick: false,
    closeButton: false,
    toastClassName: 'loading-toast',
  })
}
