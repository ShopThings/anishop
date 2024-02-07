import BaseConfirm from "@/components/base/BaseConfirm.vue";
import {POSITION, TYPE, useToast} from "vue-toastification";
import isFunction from "lodash.isfunction";
import BaseLoading from "@/components/base/toast/BaseLoading.vue";
import isObject from "lodash.isobject";

export const useConfirmToast = (onAccept, title, subTitle, showBackdrop) => {
  const noop = () => {
  }
  let onDecline = noop

  if (isFunction(onAccept)) {
    onDecline = noop
  } else if (isObject(onAccept)) {
    if ('decline' in onAccept) {
      onDecline = onAccept.decline
    } else {
      onDecline = noop
    }

    if ('accept' in onAccept) {
      onAccept = onAccept.accept
    } else {
      onAccept = noop
    }
  } else {
    onAccept = noop
    onDecline = noop
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
      accept: onAccept,
      decline: onDecline,
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
